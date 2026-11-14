@include('user.vn.header._head')
<body>
	@include('user.vn.header._header')
    <main id="fullpage">
        <!-- 솔라테크 소개 -->
        <div class="section crew_con01">
            <div class="sub_inner">
                <h4 class="effects">Đội ngũ</h4>
                <p class="effects delay_1">Những người am hiểu thị trường và những người làm chủ công nghệ cùng nhau tìm lời giải cho xuất khẩu.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section crew_con02">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">CREW</span>
                    <h3 class="effect delay_1">Đội ngũ RICHPORT</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <div class="txt_box">
                            <i>01</i>
                            <p><b>Jung Sung-won</b> CEO</p>
                            <span>Chuyên gia phân phối và quản trị kinh doanh tại Việt Nam</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_1.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <div class="txt_box">
                            <i>02</i>
                            <p><b>Lee Jae-woo</b> CTO</p>
                            <span>Phụ trách phát triển dịch vụ RICHPORT</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_2.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <div class="txt_box">
                            <i>03</i>
                            <p><b>Choi Yeon-wook</b> Trưởng nhóm</p>
                            <span>Chuyên gia phân phối tại Việt Nam</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_3.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <div class="txt_box">
                            <i>04</i>
                            <p><b>Nguyễn Thị Thu Trang</b> Trưởng nhóm</p>
                            <span>Chuyên gia phân phối tại Việt Nam và phiên dịch Hàn - Việt</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_4.png') }}" alt=""></div>
                    </li>
                </ul>
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
                $('.crew_con01').addClass('on')
            },100);
        });        
    </script>
</body>
@include('user.vn.footer._gtm')
</html>