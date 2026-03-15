<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
	<meta name="color-scheme" content="light only"/>
	<meta name="prefers-color-scheme" content="light only"/>
	<meta name="supported-color-schemes" content="light"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no, address=no, email=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- 구글 애널리틱스 -->
	<script type="text/javascript" async="" src="https://www.googletagmanager.com/gtag/js?id=G-VGP9WKV8Z3&amp;cx=c&amp;gtm=4e5bi1h1"></script>
	<script>
		window.dataLayer = window.dataLayer || []; 

		function gtag(){
			dataLayer.push(arguments);
		}
		gtag('js', new Date()); gtag('config', 'G-VGP9WKV8Z3');
	</script>
	
	<!-- Google Tag Manager -->
	<script>
		(function(w,d,s,l,i){
			w[l]=w[l]||[];
			w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'
		});
	
		var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;
			j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
			f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-WLWDPV4');
	</script>
		
	<!-- 네이버 애널리틱스 -->
	<script type="text/javascript" src="//wcs.pstatic.net/wcslog.js"></script>
	<script type="text/javascript">
		if(!wcs_add) var wcs_add = {};
		
		wcs_add["wa"] = "19a46f46412ac1";
		
		if(window.wcs) {
		  wcs_do();
		}
	</script>
	
	<!-- 네이버 프리미엄 광고관리 -->
	<script type="text/javascript">
		if(!wcs_add) var wcs_add = {};
		wcs_add["wa"] = "s_282a346d548b";
		
		if(window.wcs) {
		  wcs_do();
		}
	</script>
	
	<!-- 채널톡 -->
	<script>
	(function(){
		var w=window;
		if(w.ChannelIO){
			return w.console.error("ChannelIO script included twice.");
		}
		var ch=function(){
			ch.c(arguments);
		};

		ch.q=[];
		ch.c=function(args){ch.q.push(args);};
		w.ChannelIO=ch;
		function l(){

			if(w.ChannelIOInitialized){return;}

			w.ChannelIOInitialized=true;
	
			var s=document.createElement("script");
	
			s.type="text/javascript";
			s.async=true;
			s.src="https://cdn.channel.io/plugin/ch-plugin-web.js";
			var x=document.getElementsByTagName("script")[0];
			if(x.parentNode){
				x.parentNode.insertBefore(s,x);
			}
		}

		if(document.readyState==="complete"){
			l();
		}else{
			w.addEventListener("DOMContentLoaded",l);
			w.addEventListener("load",l);
		}
	})();
	
	ChannelIO('boot', {
		"pluginKey": "ea54a9b9-da17-4a90-a3b5-e74a2ea2573a"
	});
	</script>


	<title>{{ $meta['title'] }}</title>
	<link rel="canonical" href="{{ $meta['canonical'] }}">
    <link rel="icon" href="{{ asset('user_assets/img/favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('user_assets/img/appleicon.png') }}">
	
	<meta name="Keywords" content="{{ $meta['keywords'] }}">
	<meta name="description" content="{{ $meta['description'] }}">
	<meta property="og:title" content="{{ $meta['og:title'] }}"/>
    <meta property="og:image" content="https://solarteq.co.kr/og_image.png"/>
    <meta property="og:description" content="{{ $meta['og:description'] }}"/>
    <meta property="twitter:title" content="{{ $meta['twitter:title'] }}"/>
    <meta property="twitter:image" content="https://solarteq.co.kr/og_image.png"/>
    <meta property="twitter:description" content="{{ $meta['twitter:description'] }}"/>
    <meta property="twitter:card" content="{{ $meta['twitter:card'] }}"/>


    <!-- css -->
    <link rel="stylesheet" href="{{ asset('user_assets/css/font.css') }}" />
    <link rel="stylesheet" href="{{ asset('user_assets/css/reset.css') }}" />
    <link rel="stylesheet" href="{{ asset('user_assets/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('user_assets/css/fullpage.css') }}" />
    <link rel="stylesheet" href="{{ asset('user_assets/css/style_ko.css') }}" />
    <link rel="stylesheet" href="{{ asset('user_assets/css/sub_ko.css') }}" />

    <!-- js -->
    <script src="{{ asset('user_assets/js/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('user_assets/js/lenis.min.js') }}"></script>
    <script src="{{ asset('user_assets/js/aos.js') }}"></script>
    <script src="{{ asset('user_assets/js/scrolloverflow.min.js') }}"></script>
    <script src="{{ asset('user_assets/js/fullpage.js') }}"></script>
    <script src="{{ asset('user_assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('user_assets/js/common.js') }}"></script>
</head> 