<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP</title>
    <!-- Purple Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <style>
        body {
            background: #f4f4f4;
        }
        .otp-box {
            width: 420px;
            margin: auto;
            margin-top: 120px;
        }
        .otp-input {
            width: 45px;
            height: 45px;
            text-align: center;
            font-size: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card otp-box shadow-lg">
            <div class="card-body text-center">
                <h3 class="mb-2">
                    Verifikasi OTP
                </h3>
                <p class="text-muted mb-4">
                    Masukkan kode OTP yang dikirim ke email
                </p>
                <form action="{{ route('otp.verify') }}" method="POST">
                    @csrf

                    <!-- INPUT OTP -->
                    <div class="d-flex justify-content-center mb-4">
                        <input type="text" maxlength="1" class="otp-input otp-input-field">
                        <input type="text" maxlength="1" class="otp-input otp-input-field">
                        <input type="text" maxlength="1" class="otp-input otp-input-field">
                        <input type="text" maxlength="1" class="otp-input otp-input-field">
                        <input type="text" maxlength="1" class="otp-input otp-input-field">
                        <input type="text" maxlength="1" class="otp-input otp-input-field">
                    </div>

                    <!-- Hidden OTP -->
                    <input type="hidden" name="otp" id="otp">

                    <!-- ERROR -->
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @error('otp')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror

                    <!-- BUTTON -->
                    <button type="submit" class="btn btn-gradient-primary w-100">
                        Verifikasi OTP
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const inputs = document.querySelectorAll('.otp-input-field');
        const otpField = document.getElementById('otp');

        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                updateOTP();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === "Backspace" && !input.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        function updateOTP() {
            let otp = '';
            inputs.forEach(input => {
                otp += input.value;
            });
            otpField.value = otp;
        }
    </script>
</body>
</html>