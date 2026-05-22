<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (! Auth::attempt([$field => $credentials['login'], 'password' => $credentials['password']])) {
            throw ValidationException::withMessages([
                'login' => 'ព័ត៌មានចូលប្រព័ន្ធមិនត្រឹមត្រូវ។',
            ]);
        }

        $request->session()->regenerate();

        $user = $request->user();

        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'login' => 'គណនីនេះត្រូវបានបិទ។',
            ]);
        }

        $this->sendOtp($user);
        $request->session()->put('two_factor_verified', false);

        return redirect()->route('two-factor.show')
            ->with('success', 'លេខកូដ OTP ត្រូវបានផ្ញើទៅអ៊ីមែលរបស់អ្នក។');
    }

    public function showTwoFactor()
    {
        return view('auth.two-factor');
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = $request->user();

        if (
            ! $user->two_factor_code ||
            ! $user->two_factor_expires_at ||
            now()->greaterThan($user->two_factor_expires_at) ||
            ! Hash::check($request->input('otp'), $user->two_factor_code)
        ) {
            throw ValidationException::withMessages([
                'otp' => 'លេខកូដ OTP មិនត្រឹមត្រូវ ឬផុតកំណត់។',
            ]);
        }

        $user->forceFill([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
            'last_login_at' => now(),
        ])->save();

        $request->session()->put('two_factor_verified', true);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function resendTwoFactor(Request $request)
    {
        $this->sendOtp($request->user());

        return back()->with('success', 'លេខកូដ OTP ថ្មីត្រូវបានផ្ញើទៅអ៊ីមែលរបស់អ្នក។');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function sendOtp(User $user): void
    {
        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            'two_factor_code' => Hash::make($otp),
            'two_factor_expires_at' => now()->addMinutes(10),
        ])->save();

        Mail::send('emails.otp', [
            'user' => $user,
            'otp' => $otp,
            'expires' => 10
        ], function ($message) use ($user): void {

            $message->to($user->email)
                ->subject(' OTP Code');
        });
    }
}
