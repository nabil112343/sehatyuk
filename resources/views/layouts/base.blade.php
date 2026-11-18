<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SehatYuk')</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    @stack('styles')
</head>
<body>
<div class="app">
    @yield('sidebar')
    <main class="main">@yield('content')</main>
</div>
<div id="toast" class="toast"></div>
<script src="{{ asset('js/api.js') }}"></script>
@stack('scripts')
</body>
</html>
