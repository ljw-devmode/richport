@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="wrap">
    
    	<div class="policy_box" data-aos="fade-up">
            <div class="w1700">
            	<a href="javascript:window.history.back()" class="back"><img src="{{ asset('user_assets/img/icon/x.svg') }}" alt=""></a>
                @include('user.en.header._policy_text')
            </div>
        </div>
        @include('user.en.footer._footer')
    </main>
    <script>
	    $(window).on('load', function(){
	        
	    });
    </script>
</body>
@include('user.en.footer._gtm')
</html>