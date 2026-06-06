<?php

namespace App\Http\Controllers\backend;

use App\Models\User;
use App\Services\TelegramActionAlertService;
use App\Services\LoginLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private const OTP_EXPIRY_MINUTES = 5;

    public function __construct(
        private readonly LoginLogService $loginLogService,
        private readonly TelegramActionAlertService $telegramActionAlertService
    )
    {
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Please enter your username or email address.',
            'password.required' => 'Please enter your password.',
        ]);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (! Auth::attempt([$field => $credentials['login'], 'password' => $credentials['password']])) {
            $this->loginLogService->record(
                $request,
                User::where($field, $credentials['login'])->first(),
                'failed'
            );

            throw ValidationException::withMessages([
                'login' => 'The provided login details are incorrect.',
            ]);
        }

        $request->session()->regenerate();

        $user = $request->user();

        if (! $user->is_active) {
            $this->loginLogService->record($request, $user, 'disabled');

            $this->logoutCurrentSession($request);

            return redirect()->route('login')
                ->withErrors(['login' => 'This account has been disabled.']);
        }

        if (! $user->two_factor_enabled) {
            $this->clearOtp($user);

            $user->forceFill(['last_login_at' => now()])->save();
            $this->loginLogService->record($request, $user, 'success');
            $request->session()->put('two_factor_verified', true);
            $request->session()->forget('two_factor_otp_sent');
            $this->recordActivity('login', 'Authentication', "{$user->username} logged into the system");

            return redirect()->route('admin.dashboard');
        }

        // Password verification succeeded; dashboard access still requires OTP verification.
        $this->clearOtp($user);
        $request->session()->put('two_factor_verified', false);
        $request->session()->forget('two_factor_otp_sent');

        return redirect()->route('two-factor.show');
    }

    public function showTwoFactor(Request $request)
    {
        $user = $request->user();

        if (! $user->two_factor_enabled || $request->session()->get('two_factor_verified')) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.two-factor', [
            'otpSent' => $request->session()->get('two_factor_otp_sent', false),
        ]);
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ], [
            'otp.required' => 'Please enter the OTP sent to your email address.',
            'otp.digits' => 'The OTP must be exactly 6 digits.',
        ]);

        $user = $request->user();

        if (! $this->otpIsValid($user, $request->input('otp'))) {
            $this->clearOtp($user);
            $this->loginLogService->record($request, $user, 'otp_failed');
            $this->logoutCurrentSession($request);

            return redirect()->route('login')
                ->withErrors(['login' => 'The OTP is invalid or expired. Please log in again.']);
        }

        $user->forceFill([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
            'last_login_at' => now(),
        ])->save();

        $this->loginLogService->record($request, $user, 'success');
        $request->session()->put('two_factor_verified', true);
        $request->session()->forget('two_factor_otp_sent');
        $this->recordActivity('login', 'Authentication', "{$user->username} logged into the system");

        return redirect()->route('admin.dashboard');
    }

    public function resendTwoFactor(Request $request)
    {
        if (! $request->user()->two_factor_enabled) {
            return redirect()->route('admin.dashboard');
        }

        $this->sendOtp($request->user());
        $this->telegramActionAlertService->send('LMS OTP Sent', [
            'User' => $request->user()->username.' ('.$request->user()->email.')',
            'IP' => $request->ip() ?? 'N/A',
            'Browser' => $request->userAgent() ?? 'N/A',
            'Expires In' => self::OTP_EXPIRY_MINUTES.' minutes',
            'Time' => now()->format('Y-m-d H:i:s'),
        ]);
        $request->session()->put('two_factor_otp_sent', true);

        return redirect()->route('two-factor.show')
            ->with('success', 'កូដ OTP ត្រូវបានផ្ញើទៅអ៊ីមែលរបស់អ្នក។ សូមពិនិត្យអ៊ីមែលរបស់អ្នក។');
    }

    public function profile(Request $request)
    {
        return view('admin.profile', [
            'user' => $request->user()->loadMissing('roles'),
        ]);
    }

    public function updateTwoFactor(Request $request)
    {
        $validated = $request->validate([
            'two_factor_enabled' => ['required', 'boolean'],
        ], [
            'two_factor_enabled.required' => 'Please choose whether two-factor authentication is enabled.',
            'two_factor_enabled.boolean' => 'Invalid two-factor authentication setting.',
        ]);

        $enabled = (bool) $validated['two_factor_enabled'];

        $request->user()->forceFill([
            'two_factor_enabled' => $enabled,
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ])->save();

        $this->recordActivity('update', 'Security', 'User updated two-factor authentication settings.');

        $request->session()->put('two_factor_verified', true);
        $request->session()->forget('two_factor_otp_sent');

        return back()->with('success', $enabled
            ? 'Two-factor authentication has been enabled.'
            : 'Two-factor authentication has been disabled.');
    }

    public function updateAvatar(Request $request)
    {
        $validated = $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'avatar.required' => 'Please choose an image.',
            'avatar.image' => 'The uploaded file must be an image.',
            'avatar.mimes' => 'Profile image must be JPG, PNG, or WEBP.',
            'avatar.max' => 'Profile image may not be larger than 2MB.',
        ]);

        $user = $request->user();

        if ($user->avatar && str_starts_with($user->avatar, 'avatars/')) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $validated['avatar']->store('avatars', 'public');

        $user->forceFill(['avatar' => $path])->save();

        $this->recordActivity('upload', 'Profile', 'User updated profile image.');

        return back()->with('success', 'Profile image updated successfully.');
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $this->recordActivity('logout', 'Authentication', "{$request->user()->username} logged out of the system");
        }

        if ($request->user()) {
            $this->clearOtp($request->user());
        }

        $this->logoutCurrentSession($request);

        return redirect()->route('login');
    }

    private function sendOtp(User $user): void
    {
        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            'two_factor_code' => Hash::make($otp),
            'two_factor_expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES),
        ])->save();

        Mail::send('emails.otp', [
            'user' => $user,
            'otp' => $otp,
            'expires' => self::OTP_EXPIRY_MINUTES,
        ], function ($message) use ($user): void {
            $message->to($user->email)
                ->subject('Your LMS verification code');
        });
    }

    private function otpIsValid(User $user, string $otp): bool
    {
        return $user->two_factor_enabled
            && filled($user->two_factor_code)
            && filled($user->two_factor_expires_at)
            && now()->lessThanOrEqualTo($user->two_factor_expires_at)
            && Hash::check($otp, $user->two_factor_code);
    }

    private function clearOtp(User $user): void
    {
        $user->forceFill([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ])->save();
    }

    private function recordActivity(string $action, string $module, string $description): void
    {
        if (function_exists('activity_log')) {
            \activity_log($action, $module, $description);
        }
    }

    private function logoutCurrentSession(Request $request): void
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}


