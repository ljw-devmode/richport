@include('user.vn.header._head')
<body>
	@include('user.vn.header._header')
    <main id="fullpage">
        <div class="section rips_order_con01">
            <div class="sub_inner">
                <h4 class="effects">Dự báo đặt hàng</h4>
                <p class="effects delay_1">Dự báo nhu cầu dựa trên dữ liệu bán hàng và đề xuất số lượng đặt hàng tối ưu.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section rips_order_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">PROCESS</span>
                    <h3 class="effect delay_1">Quy trình dự báo đặt hàng</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/depletion.svg') }}" alt=""></i>Dự báo ngày hết hàng<br/>dựa trên dự báo bán hàng</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/truck.svg') }}" alt=""></i>Dự báo thời gian<br/>sản xuất và vận chuyển</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/customs.svg') }}" alt=""></i>Dự báo thời gian<br/>thông quan</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/hourglass.svg') }}" alt=""></i><br/>Dự báo tổng thời gian</br>từ đặt hàng đến nhập kho</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/calendar_check.svg') }}" alt=""></i>Dự báo ngày và<br/>số lượng đặt hàng tối ưu</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(2)">Đăng ký sử dụng RIPS</button>
                    <p>✱ Duy trì tồn kho an toàn với RIPS.</p>
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
                $('.rips_inventory_con01').addClass('on')
            },100);
        });



    </script>
</body>
@include('user.vn.footer._gtm')
</html>