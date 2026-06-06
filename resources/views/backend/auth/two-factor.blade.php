<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ផ្ទៀងផ្ទាត់ OTP | LMS</title>

    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.min.css') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #ffffff;
            color: #111827;
            font-family: 'Khmer OS Battambang', 'Noto Sans Khmer', Arial, sans-serif;
        }

        .two-factor-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 16px;
            background: #ffffff;
        }

        .choice-panel {
            width: min(100%, 850px);
            min-height: 690px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 64px 28px 70px;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 10px 0 18px rgba(15, 23, 42, 0.08);
        }

        .choice-title {
            margin: 0 0 12px;
            font-size: 42px;
            line-height: 1.35;
            font-weight: 800;
            text-align: center;
        }

        .choice-subtitle {
            margin: 0 0 116px;
            color: #f0a000;
            font-size: 25px;
            line-height: 1.45;
            font-weight: 700;
            text-align: center;
        }

        .method-form {
            width: min(100%, 250px);
            margin: 0;
        }

        .method-card {
            width: 100%;
            min-height: 188px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            padding: 26px 18px;
            border: 4px solid #3b00bf;
            background: #ffffff;
            color: #111827;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .method-card:hover,
        .method-card:focus {
            transform: translateY(-2px);
            box-shadow: 0 16px 34px rgba(59, 0, 191, 0.16);
            outline: none;
        }

        .method-icon {
            position: relative;
            width: 72px;
            height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #4a96ff;
            font-size: 46px;
        }

        .method-icon .fa-comment-dots {
            position: absolute;
            right: 1px;
            bottom: 2px;
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #4a96ff;
            border-radius: 50%;
            background: #ffffff;
            font-size: 15px;
        }

        .method-label {
            font-size: 26px;
            line-height: 1.35;
            font-weight: 800;
        }

        .choice-footer {
            margin-top: 112px;
            font-size: 22px;
            line-height: 1.5;
            font-weight: 800;
            text-align: center;
        }

        .verify-card {
            width: min(100%, 430px);
            border: 3px solid #3b00bf;
            padding: 28px 34px 32px;
            background: #ffffff;
        }

        .verify-logo {
            display: block;
            width: 58px;
            height: 58px;
            margin: 0 auto 18px;
            border-radius: 50%;
            object-fit: contain;
        }

        .verify-title {
            margin: 0 0 6px;
            font-size: 25px;
            line-height: 1.35;
            font-weight: 800;
            text-align: center;
        }

        .verify-subtitle {
            margin: 0 0 30px;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.55;
            text-align: center;
        }

        .verify-email {
            display: block;
            color: #6b7280;
            font-weight: 700;
        }

        .otp-label,
        .timer-label {
            display: block;
            margin-bottom: 10px;
            color: #111827;
            font-size: 14px;
            font-weight: 800;
        }

        .otp-boxes {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 9px;
            margin-bottom: 28px;
        }

        .otp-box {
            width: 100%;
            height: 34px;
            border: 1px solid #d8dde6;
            border-radius: 7px;
            text-align: center;
            font-size: 18px;
            font-weight: 800;
        }

        .otp-box:focus {
            border-color: #3b00bf;
            box-shadow: 0 0 0 0.15rem rgba(59, 0, 191, 0.12);
            outline: 0;
        }

        .timer-row {
            min-height: 74px;
        }

        .timer-value {
            display: block;
            margin-top: 16px;
            color: #00a86b;
            font-size: 13px;
            font-weight: 800;
            text-align: center;
        }

        .verify-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .verify-actions .btn {
            height: 34px;
            border-radius: 7px;
            padding: 0 10px;
            font-size: 13px;
            font-weight: 800;
        }

        .btn-otp {
            background: #e88700;
            border-color: #e88700;
            color: #ffffff;
        }

        .btn-otp:hover,
        .btn-otp:focus {
            background: #d17800;
            border-color: #d17800;
            color: #ffffff;
        }

        .toast-message {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 20;
            width: min(340px, calc(100vw - 32px));
            min-height: 54px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 13px 38px 13px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.14);
            font-size: 13px;
            line-height: 1.45;
            font-weight: 700;
        }

        .toast-message .fa-check-circle {
            color: #22c55e;
            margin-top: 2px;
        }

        .toast-close {
            position: absolute;
            top: 8px;
            right: 11px;
            border: 0;
            background: transparent;
            color: #9ca3af;
            cursor: pointer;
        }

        .inline-alert {
            width: min(100%, 420px);
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        @media (max-width: 576px) {
            .choice-panel {
                min-height: 620px;
                padding: 42px 18px 50px;
            }

            .choice-title {
                font-size: 32px;
            }

            .choice-subtitle {
                margin-bottom: 80px;
                font-size: 19px;
            }

            .choice-footer {
                margin-top: 80px;
                font-size: 17px;
            }

            .verify-card {
                padding: 24px 20px 28px;
            }

            .verify-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    @php
        $user = auth()->user();
        $email = $user?->email ?? '';
        $expiresAt = $user?->two_factor_expires_at?->timestamp;
    @endphp

    @if (session('success'))
        <div class="toast-message" id="otpToast">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="toast-close" aria-label="Close"
                onclick="document.getElementById('otpToast').remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <main class="two-factor-page">
        @if (! $otpSent)
            <section class="choice-panel">
                <h1 class="choice-title">មជ្ឈមណ្ឌលប្រឡងជាតិ</h1>
                <p class="choice-subtitle">សូមជ្រើសរើសប្រភពនៃការផ្ទៀងផ្ទាត់</p>

                @if ($errors->any())
                    <div class="alert alert-danger inline-alert">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('two-factor.resend') }}" method="POST" class="method-form">
                    @csrf
                    <button type="submit" class="method-card" aria-label="ផ្ញើ OTP តាមអ៊ីមែល">
                        <span class="method-icon" aria-hidden="true">
                            <i class="far fa-envelope"></i>
                            <i class="far fa-comment-dots"></i>
                        </span>
                        <span class="method-label">អ៊ីមែល</span>
                    </button>
                </form>

                <div class="choice-footer">
                    © រក្សាសិទ្ធិគ្រប់យ៉ាងដោយ<br>
                    មជ្ឈមណ្ឌលប្រឡងជាតិ
                </div>
            </section>
        @else
            <section class="verify-card">
                <img class="verify-logo" src="{{ asset('backend/dist/img/spilogo.png') }}" alt="LMS">
                <h1 class="verify-title">មជ្ឈមណ្ឌលប្រឡងជាតិ</h1>
                <p class="verify-subtitle">
                    លេខកូដ OTP ត្រូវបានផ្ញើទៅអ៊ីមែល
                    <span class="verify-email">{{ $email }}</span>
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('two-factor.verify') }}" method="POST" id="otpForm">
                    @csrf
                    <label class="otp-label">លេខកូដ OTP*</label>
                    <input type="hidden" name="otp" id="otpValue" value="{{ old('otp') }}">

                    <div class="otp-boxes" data-old-otp="{{ old('otp') }}">
                        @for ($i = 0; $i < 6; $i++)
                            <input class="otp-box" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*"
                                autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
                                aria-label="OTP digit {{ $i + 1 }}" {{ $i === 0 ? 'autofocus' : '' }}>
                        @endfor
                    </div>

                    <div class="timer-row">
                        <span class="timer-label">ពេលវេលានៅសល់</span>
                        <span class="timer-value" id="otpTimer" data-expires="{{ $expiresAt }}">--:--</span>
                    </div>

                    <div class="verify-actions">
                        <button type="submit" formaction="{{ route('logout') }}" class="btn btn-default">
                            <i class="fas fa-sign-out-alt mr-1"></i>
                            ចាកចេញ
                        </button>

                        <button type="submit" class="btn btn-otp">
                            <i class="fas fa-shield-alt mr-1"></i>
                            ផ្ទៀងផ្ទាត់ OTP
                        </button>
                    </div>
                </form>
            </section>
        @endif
    </main>

    <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/dist/js/adminlte.min.js') }}"></script>
    <script>
        (function () {
            const boxes = Array.from(document.querySelectorAll('.otp-box'));
            const otpValue = document.getElementById('otpValue');
            const boxWrap = document.querySelector('.otp-boxes');

            if (boxes.length && otpValue && boxWrap) {
                const syncOtp = () => {
                    otpValue.value = boxes.map((box) => box.value).join('');
                };

                const fillBoxes = (value) => {
                    boxes.forEach((box) => {
                        box.value = '';
                    });

                    value.replace(/\D/g, '').slice(0, 6).split('').forEach((digit, index) => {
                        boxes[index].value = digit;
                    });

                    syncOtp();
                };

                fillBoxes(boxWrap.dataset.oldOtp || '');

                boxes.forEach((box, index) => {
                    box.addEventListener('input', (event) => {
                        const value = event.target.value.replace(/\D/g, '');
                        event.target.value = value.slice(-1);
                        syncOtp();

                        if (value && boxes[index + 1]) {
                            boxes[index + 1].focus();
                        }
                    });

                    box.addEventListener('keydown', (event) => {
                        if (event.key === 'Backspace' && !box.value && boxes[index - 1]) {
                            boxes[index - 1].focus();
                        }
                    });

                    box.addEventListener('paste', (event) => {
                        event.preventDefault();
                        fillBoxes((event.clipboardData || window.clipboardData).getData('text'));
                        const next = boxes.find((item) => !item.value) || boxes[boxes.length - 1];
                        next.focus();
                    });
                });
            }

            const timer = document.getElementById('otpTimer');

            if (timer && timer.dataset.expires) {
                const expires = Number(timer.dataset.expires) * 1000;

                const updateTimer = () => {
                    const seconds = Math.max(0, Math.floor((expires - Date.now()) / 1000));
                    const minutes = String(Math.floor(seconds / 60)).padStart(2, '0');
                    const rest = String(seconds % 60).padStart(2, '0');
                    timer.textContent = `${minutes}:${rest}`;
                };

                updateTimer();
                setInterval(updateTimer, 1000);
            }
        })();
    </script>
</body>

</html>
