<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
	<meta name="color-scheme" content="light only"/>
	<meta name="prefers-color-scheme" content="light only"/>
	<meta name="supported-color-schemes" content="light"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no, address=no, email=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- 구글 애널리틱스 -->
	<script>
	</script>
	
	<!-- Google Tag Manager -->
	<script>
	</script>

	<title>{{ $meta['title'] }}</title>
	<link rel="canonical" href="{{ $meta['canonical'] }}">
    <link rel="icon" href="{{ asset('user_assets/img/favicon.ico') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('user_assets/img/appleicon.png') }}">
	
	<meta name="Keywords" content="{{ $meta['keywords'] }}">
	<meta name="description" content="{{ $meta['description'] }}">
	<meta property="og:title" content="{{ $meta['og:title'] }}"/>
    <meta property="og:image" content="https://richport.kr/og_image.png"/>
    <meta property="og:description" content="{{ $meta['og:description'] }}"/>
    <meta property="twitter:title" content="{{ $meta['twitter:title'] }}"/>
    <meta property="twitter:image" content="https://richport.kr/og_image.png"/>
    <meta property="twitter:description" content="{{ $meta['twitter:description'] }}"/>
    <meta property="twitter:card" content="{{ $meta['twitter:card'] }}"/>

    <!-- css -->
    <link rel="stylesheet" href="{{ asset('user_assets/css/font.css') }}" />
    <link rel="stylesheet" href="{{ asset('user_assets/css/reset.css') }}" />
    <link rel="stylesheet" href="{{ asset('user_assets/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('user_assets/css/fullpage.css') }}" />
    <link rel="stylesheet" href="{{ asset('user_assets/css/style_ko.css') }}" />
    <link rel="stylesheet" href="{{ asset('user_assets/css/sub_ko.css') }}" />
    <!-- 영어/베트남어 보정 (글자 길이) -->
    <link rel="stylesheet" href="{{ asset('user_assets/css/lang_en_vn.css') }}?v={{ filemtime(public_path('user_assets/css/lang_en_vn.css')) }}" />

    <!-- js -->
    <script src="{{ asset('user_assets/js/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('user_assets/js/lenis.min.js') }}"></script>
    <script src="{{ asset('user_assets/js/aos.js') }}"></script>
    <script src="{{ asset('user_assets/js/scrolloverflow.min.js') }}"></script>
    <script src="{{ asset('user_assets/js/fullpage.js') }}"></script>
    <script src="{{ asset('user_assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('user_assets/js/common.js') }}"></script>
</head> 