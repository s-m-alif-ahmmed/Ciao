<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ $data->page_title }}</title>

    {{-- BOOTSTRAP CSS --}}
    <link id="style" href="{{ asset('backend/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
</head>
<body>

<div class="container">
    <div class="my-5">
        {!! $data->page_content !!}
    </div>
</div>

{{-- BOOTSTRAP JS --}}
<script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

{{-- JQUERY JS --}}
<script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>

</body>
</html>
