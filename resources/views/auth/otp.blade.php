<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="col-md-4 mx-auto">
        <div class="card p-4 shadow">
            <h4 class="text-center">Masukkan Kode OTP</h4>

            <form method="POST" action="{{ route('otp.verify') }}">
                @csrf

                <input type="text"
                       name="otp"
                       maxlength="6"
                       required
                       class="form-control text-center mt-3"
                       placeholder="XXXXXX">

                @error('otp')
                    <div class="text-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror

                <button type="submit"
                        class="btn btn-primary mt-3 w-100">
                    Verifikasi
                </button>
            </form>

        </div>
    </div>
</div>

</body>
</html>
