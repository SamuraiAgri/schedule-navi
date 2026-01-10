<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- 基本SEOメタタグ -->
    <meta name="description" content="Schedule-Navi - 30秒で作れるシンプルな日程調整サービス。飲み会から会議まで、会員登録不要で無料でご利用いただけます。">
    <meta name="keywords" content="日程調整,スケジュール調整,出欠確認,飲み会,会議,イベント,無料">
    <meta name="author" content="Schedule-Navi">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#3B82F6">

    <!-- OGP (Open Graph Protocol) -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Schedule-Navi">
    <meta property="og:locale" content="ja_JP">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@schedule_navi">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <title inertia>{{ config('app.name', 'Schedule-Navi') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- DNS Prefetch for AdSense -->
    <link rel="dns-prefetch" href="//pagead2.googlesyndication.com">

    <!-- Google Analytics -->
    @if(config('services.google.analytics_id') && config('app.env') === 'production')
        <script async
            src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google.analytics_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', '{{ config('services.google.analytics_id') }}');
        </script>
    @endif

    <!-- Google AdSense -->
    @if(config('services.google.adsense_id') && config('app.env') === 'production')
        <script async
            src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ config('services.google.adsense_id') }}"
            crossorigin="anonymous"></script>
    @endif

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>

<body class="font-sans antialiased bg-gray-50">
    @inertia
</body>

</html>