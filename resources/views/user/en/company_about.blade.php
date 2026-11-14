@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="fullpage">
        <div class="section about_con01">
            <div class="sub_inner">
                <h4 class="effects">About RICHPORT</h4>
                <p class="effects delay_1">Connecting local data with Korean brands.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section about_con02">
            <div class="sub_inner2">
                <div class="sub_tit">
                    <span class="effect">GREETING</span>
                    <h3 class="effect delay_1">CEO's Message</h3>
                </div>
                <div class="ceo_box">
                    <div class="img_box effect delay_3">
                        <img class="pc_img" src="{{ asset('user_assets/img/sub/company/about_ceo.png') }}" alt="">
                        <img class="fold_img" src="{{ asset('user_assets/img/sub/company/about_ceo.png') }}" alt="">
                        <img class="mo_img" src="{{ asset('user_assets/img/sub/company/about_ceo.png') }}" alt="">
                    </div>
                    <div class="txt_box">
                        <p class="effect delay_4">Hello.</p>
                        <p class="effect delay_4">RICHPORT is an export operations partner that helps Korean brands expand into Southeast Asia, based on local sales and inventory data from Vietnam.</p>
                        <p class="effect delay_4">We will reduce stockouts and overstock for local retailers, and create growth opportunities for Korean brands that lead to repeat orders.</p>
                        <p class="effect delay_4">Built on data and execution, RICHPORT will connect Korea and Vietnam.</p>
                        <p class="effect delay_4">Thank you.</p>
                        <div class="ceo effect delay_5">
                            <strong>CEO Jung Sung-won</strong>
                        </div>
                    </div>
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
                $('.about_con01').addClass('on')
            },100);

            setTimeout(function(){
                $('.tab_content > div').eq(1).removeClass('active')
            },400);

            $('.tab_box a').on('click',function(){
                let liN = $(this).index();
                $(this).addClass('active').siblings().removeClass('active');
                $('.tab_content > div').eq(liN).addClass('active').siblings().removeClass('active');
            })
        });

    </script>
</body>
@include('user.en.footer._gtm')
</html>