@include('user.vn.header._head')
<body>
	@include('user.vn.header._header')
    <main id="fullpage">
        <div class="section rips_inventory_con01">
            <div class="sub_inner">
                <h4 class="effects">Dự báo tồn kho an toàn</h4>
                <p class="effects delay_1">Phân tích dữ liệu bán hàng để ngăn tình trạng hết hàng và tồn kho dư thừa.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section rips_inventory_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">PROCESS</span>
                    <h3 class="effect delay_1">Quy trình dự báo hết hàng</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/product_add.svg') }}" alt=""></i>Đăng ký danh mục<br/>và sản phẩm</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/sales_data.svg') }}" alt=""></i>Kết nối dữ liệu bán hàng<br/>theo ngày, theo sản phẩm</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/stock.svg') }}" alt=""></i>Kết nối tồn kho còn lại<br/>theo ngày, theo sản phẩm</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/ai_forecast.svg') }}" alt=""></i>AI dự báo doanh số<br/>theo ngày, theo sản phẩm</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/calendar_clock.svg') }}" alt=""></i>Tính toán ngày<br/>dự kiến hết hàng</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(2)">Đăng ký sử dụng RIPS</button>
                    <p>✱ Giảm tình trạng hết hàng và tồn kho dư thừa với RIPS.</p>
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