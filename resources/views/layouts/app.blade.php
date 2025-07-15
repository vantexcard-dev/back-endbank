<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="//code.tidio.co/yl9c8wxcbftkebl9bfjkl3bilpoaackb.js" async></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <title>Finteezy - Fintech Startup & Digital Wallet</title>
    <!-- favicon  -->
    <link rel="shortcut icon" href="assets/images/fav_icon.svg" type="image/x-icon">
    <!-- faremwork of css -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-lib/bootstrap.min.css')}}">
    <!-- style sheet of css -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <!-- Responsive sheet of css -->
    <link rel="stylesheet" href="{{asset('assets/css/responsive.css')}}">
    <!-- fonts awsome icon link  -->
    <link rel="stylesheet" href="{{asset('assets/font-awesome-lib/icon/font-awesome.min.css')}}">
    <!-- slick-slider link css -->
    <link rel="stylesheet" href="{{asset('assets/css/slick.min.css')}}">
    <!-- animation of css -->
    <link rel="stylesheet" href="{{asset('assets/css/aos.css')}}">
    <!-- Scripts -->
</head>

