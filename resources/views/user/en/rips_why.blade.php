@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="fullpage">
        <div class="section rips_why_con01">
            <div class="sub_inner">
                <h4 class="effects">Why Do You Need It?</h4>
                <p class="effects delay_1">An action-driven export platform connecting sales, inventory and orders</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 왜 RIPS인가? -->
        <div class="section rips_why_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">WHY RIPS</span>
                    <h3 class="effect delay_1">Why do you need <b>RIPS</b>?</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/icon/global.svg') }}" alt="">
                        <h5><b>Korea-Vietnam</b></br>Connection</h5>
                        <p>Connecting K-Products <br/> <b>to Vietnamese retailers</b></p>
                    </li>
                    <li class="effect delay_3">
                        <img src="{{ asset('user_assets/img/sub/icon/star.svg') }}" alt="">
                        <h5><b>Popular Product </b></br>Sourcing</h5>
                        <p>Popular products like Buldak Ramen<br/> <b>at the best prices</b></p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/icon/certificate.svg') }}" alt="">
                        <h5><b>400+</b><br/>Product Licenses</h5>
                        <p>Korean products with<br/><b>official customs clearance &amp; distribution</b> </p>
                    </li>
                    <li class="effect delay_5">
                        <img src="{{ asset('user_assets/img/sub/icon/flask.svg') }}" alt="">
                        <h5 style="letter-spacing: -2px;">New Korean Products <br/><b>Test</b> Sales</h5>
                        <p>Proven quality and sales for<br/> stable <b>revenue generation</b></p>
                    </li>
                </ul>

            </div>
        </div>

        <!-- footer -->
        <section class="section fp-auto-height">
            @include('user.en.footer._footer')
        </section>

    </main>


    <script>
         // fullpage
        $('#fullpage').fullpage({
            scrollingSpeed:1000,
            autoScrolling:true,
        });
        $(window).on('resize', function() {
            $.fn.fullpage.reBuild();
        });

        $(window).on('load', function(){
            setTimeout(function(){
                $('.rips_why_con01').addClass('on')
            },100);
        });



    </script>
</body>
@include('user.en.footer._gtm')
</html>