<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Inline style to set the HTML background color based on our theme in app.css --}}
    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    @routes
    @vite(['resources/js/admin.ts'])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>