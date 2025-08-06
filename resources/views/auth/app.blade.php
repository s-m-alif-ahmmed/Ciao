<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    {{--    Styles  --}}
    @include('backend.partials.styles')

</head>
<body class="login-img">

    <!-- Main -->
    <main>

        <!-- MAIN -->
        @yield('content')

    </main>
    <!-- Main -->

<!-- Javascript Links -->
@include('backend.partials.scripts')

</body>
</html>
