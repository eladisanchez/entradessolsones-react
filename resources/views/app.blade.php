<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#007fb6" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if (request()->routeIs('home'))
        <link rel="preload" as="image" href="/assets/img/home-bg.webp" />
    @endif
    @viteReactRefresh
    @vite('resources/js/app.jsx')
    @vite('resources/scss/app.scss')
    @inertiaHead
</head>

<body>
    @inertia
</body>

</html>
