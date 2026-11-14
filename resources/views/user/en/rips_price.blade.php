@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="fullpage">
        <div class="section rips_price_con01">
            <div class="sub_inner">
                <h4 class="effects">Pricing</h4>
                <p class="effects delay_1">Pricing description</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section rips_price_con02">
            <div class="sub_inner2">
                <div class="sub_tit">
                    <span class="effect">About Our Pricing</span>
                    <h3 class="effect delay_1">Overview</h3>
                </div>
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
                $('.rips_price_con01').addClass('on')
            },100);
        });



    </script>
</body>
@include('user.en.footer._gtm')
</html>