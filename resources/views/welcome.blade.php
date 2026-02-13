<!DOCTYPE html>
<html>
<head>
    <title>Koleksi Buku</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body class="text-center">

    <div style="margin-top:150px;">
        <h1>Aplikasi Koleksi Buku</h1>
        <p>Silakan login untuk mengakses data buku dan kategori</p>

        <a href="{{ route('login') }}" class="btn btn-primary m-2">Login</a>
        <a href="{{ route('register') }}" class="btn btn-success m-2">Register</a>
    </div>

</body>
</html>
