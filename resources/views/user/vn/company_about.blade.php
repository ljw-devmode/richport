@include('user.vn.header._head')
<body>
	@include('user.vn.header._header')
    <main id="fullpage">
        <div class="section about_con01">
            <div class="sub_inner">
                <h4 class="effects">Giới thiệu RICHPORT</h4>
                <p class="effects delay_1">Kết nối dữ liệu địa phương với thương hiệu Hàn Quốc.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section about_con02">
            <div class="sub_inner2">
                <div class="sub_tit">
                    <span class="effect">GREETING</span>
                    <h3 class="effect delay_1">Thông điệp từ CEO</h3>
                </div>
                <div class="ceo_box">
                    <div class="img_box effect delay_3">
                        <img class="pc_img" src="{{ asset('user_assets/img/sub/company/about_ceo.png') }}" alt="">
                        <img class="fold_img" src="{{ asset('user_assets/img/sub/company/about_ceo.png') }}" alt="">
                        <img class="mo_img" src="{{ asset('user_assets/img/sub/company/about_ceo.png') }}" alt="">
                    </div>
                    <div class="txt_box">
                        <p class="effect delay_4">Xin chào quý vị.</p>
                        <p class="effect delay_4">RICHPORT là đối tác vận hành xuất khẩu, hỗ trợ các thương hiệu Hàn Quốc thâm nhập thị trường Đông Nam Á dựa trên dữ liệu bán hàng và tồn kho thực tế tại Việt Nam.</p>
                        <p class="effect delay_4">Chúng tôi giúp các nhà bán lẻ tại Việt Nam giảm tình trạng hết hàng và tồn kho dư thừa, đồng thời tạo cơ hội tăng trưởng với các đơn hàng lặp lại cho thương hiệu Hàn Quốc.</p>
                        <p class="effect delay_4">RICHPORT sẽ kết nối Hàn Quốc và Việt Nam bằng dữ liệu và năng lực thực thi.</p>
                        <p class="effect delay_4">Xin chân thành cảm ơn.</p>
                        <div class="ceo effect delay_5">
                            <strong>CEO Jung Sung-won</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <section class="section fp-auto-height">
            @include('user.vn.footer._footer')
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
@include('user.vn.footer._gtm')
</html>