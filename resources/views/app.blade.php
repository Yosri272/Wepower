@php
    $generaleSetting = App\Models\GeneraleSetting::first();

    $title = $generaleSetting?->title ?? 'Razin Commerce';
    $favicon = $generaleSetting?->favicon ?? asset('assets/favicon.png');
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">
    <meta name="app-url" content="{{ url('/') }}">
    <!-- description -->
    <meta name="description" content="ecommerce website">

    <title>{{ $title }}</title>
    <link rel="shortcut icon" href="{{ $favicon }}" type="image/x-icon">

    @vite('resources/css/app.css')
</head>

<body>
    <div id="app"></div>

    @vite('resources/js/app.js')
</body>

</html>
