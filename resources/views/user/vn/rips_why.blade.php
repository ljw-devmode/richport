@include('user.vn.header._head')
<body>
	@include('user.vn.header._header')
    <main id="fullpage">
        <div class="section rips_why_con01">
            <div class="sub_inner">
                <h4 class="effects">Vì sao cần thiết?</h4>
                <p class="effects delay_1">Nền tảng xuất khẩu thực thi, kết nối bán hàng · tồn kho · đặt hàng</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 왜 RIPS인가? -->
        <div class="section rips_why_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">WHY RIPS</span>
                    <h3 class="effect delay_1">Vì sao cần <b>RIPS</b>?</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/icon/global.svg') }}" alt="">
                        <h5>Kết nối</br><b>Hàn Quốc - Việt Nam</b></h5>
                        <p>Đưa K-Product <br/> <b>đến các nhà bán lẻ Việt Nam</b></p>
                    </li>
                    <li class="effect delay_3">
                        <img src="{{ asset('user_assets/img/sub/icon/star.svg') }}" alt="">
                        <h5>Tìm nguồn</br><b>sản phẩm bán chạy</b></h5>
                        <p>Cung cấp sản phẩm hot như mì Buldak<br/> <b>với giá tốt nhất</b></p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/icon/certificate.svg') }}" alt="">
                        <h5>Cấp phép cho<br/><b>hơn 400</b> sản phẩm</h5>
                        <p>Đảm bảo <b>thông quan & phân phối</b><br/><b>chính ngạch</b> cho hàng Hàn Quốc</p>
                    </li>
                    <li class="effect delay_5">
                        <img src="{{ asset('user_assets/img/sub/icon/flask.svg') }}" alt="">
                        <h5 style="letter-spacing: -2px;"><b>Bán thử</b> sản phẩm <br/>mới Hàn Quốc</h5>
                        <p>Chất lượng và doanh số được đảm bảo,<br/> <b>tạo lợi nhuận</b> ổn định</p>
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
                $('.rips_why_con01').addClass('on')
            },100);
        });



    </script>
</body>
@include('user.vn.footer._gtm')
</html>