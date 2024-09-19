<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="img/logo/logo.png" rel="icon">
    <title>{{ $title }}</title>
    <link href="{{ asset('dist/img/logo/pusri.webp') }}" rel="shortcut icon">
    <link href="{{ asset('dist/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dist/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dist/css/ruang-admin.min.css') }}" rel="stylesheet">
</head>

<body class="bg-gradient-login">
    {{-- Register Content --}}
    <div class="container-login">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 d-none d-md-flex justify-content-center align-items-center">
                <!-- Image section, hidden on small screens -->
                <img src="{{ asset('dist/img/logo/p2p.jpeg') }}" alt="Logo" class="img-fluid">
            </div>
            <div class="col-md-6 justify-content-center align-items-center">
                {{ $slot }}
            </div>
        </div>
    </div>
    {{-- Register Content --}}
    <script src="{{ asset('dist/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('dist/js/ruang-admin.min.js') }}"></script>
</body>

</html>
