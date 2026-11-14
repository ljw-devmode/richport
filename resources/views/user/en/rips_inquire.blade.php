@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="fullpage">
        <div class="section about_con01">
            <div class="sub_inner">
                <h4 class="effects">About RICHPORT</h4>
                <p class="effects delay_1">A global distribution partner that carefully selects Korea’s leading products<br/>and supplies them quickly to the Vietnamese market</p>
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
                        <img class="pc_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_PC.png') }}" alt="">
                        <img class="fold_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_F.png') }}" alt="">
                        <img class="mo_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_M.png') }}" alt="">
                    </div>
                    <div class="txt_box">
                        <p class="effect delay_4"><b>Hello, I am Jung Sung-won, CEO of RICHPORT.</b></p>
                        <p class="effect delay_4">RICHPORT is a global distribution partner that carefully selects high-quality products representing Korea and supplies them to the Vietnamese market safely and quickly.</p>
                        <p class="effect delay_4">Going beyond simply importing and selling products, we aim to be a 'Port of Trust (Rich-Port)' that fully delivers Korea's trendy and healthy lifestyle to Vietnamese consumers.</p>
                        <p class="effect delay_4">With guaranteed authentic products and a transparent distribution process, we will achieve sustainable growth together with our partners in both countries.</p>
                        <p class="effect delay_4">Discover broader business opportunities with RICHPORT.<br/><b>Thank you.</b></p>
                        <div class="ceo effect delay_5">
                            <strong>All of us at RICHPORT</strong>
                            <span>RICHPORT CREW</span>
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