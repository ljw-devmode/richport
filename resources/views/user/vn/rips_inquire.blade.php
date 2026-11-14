@include('user.vn.header._head')
<body>
	@include('user.vn.header._header')
    <main id="fullpage">
        <div class="section about_con01">
            <div class="sub_inner">
                <h4 class="effects">Giới thiệu RICHPORT</h4>
                <p class="effects delay_1">RICHPORT tuyển chọn những sản phẩm tiêu biểu của Hàn Quốc,<br/>đối tác phân phối toàn cầu cung ứng nhanh chóng cho thị trường Việt Nam</p>
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
                        <img class="pc_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_PC.png') }}" alt="">
                        <img class="fold_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_F.png') }}" alt="">
                        <img class="mo_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_M.png') }}" alt="">
                    </div>
                    <div class="txt_box">
                        <p class="effect delay_4"><b>Xin chào, tôi là Jung Sung-won, CEO của RICHPORT.</b></p>
                        <p class="effect delay_4">RICHPORT là đối tác phân phối toàn cầu, tuyển chọn những sản phẩm chất lượng cao tiêu biểu của Hàn Quốc và cung ứng cho thị trường Việt Nam một cách an toàn và nhanh chóng nhất.</p>
                        <p class="effect delay_4">Không chỉ dừng lại ở việc nhập khẩu và bán sản phẩm, chúng tôi mong muốn trở thành 'bến cảng của niềm tin (Rich-Port)', mang trọn vẹn phong cách sống hiện đại và lành mạnh của Hàn Quốc đến người tiêu dùng Việt Nam.</p>
                        <p class="effect delay_4">Với cam kết hàng chính hãng và quy trình phân phối minh bạch, chúng tôi sẽ cùng các đối tác của hai nước hướng tới tăng trưởng bền vững.</p>
                        <p class="effect delay_4">Hãy cùng RICHPORT khám phá những cơ hội kinh doanh rộng mở hơn.<br/><b>Xin chân thành cảm ơn.</b></p>
                        <div class="ceo effect delay_5">
                            <strong>Toàn thể đội ngũ RICHPORT</strong>
                            <span>RICHPORT CREW</span>
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