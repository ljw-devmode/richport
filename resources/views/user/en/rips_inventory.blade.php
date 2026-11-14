@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="fullpage">
        <div class="section rips_inventory_con01">
            <div class="sub_inner">
                <h4 class="effects">Safety Stock Prediction</h4>
                <p class="effects delay_1">We analyze sales data to prevent stockouts and overstock.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section rips_inventory_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">PROCESS</span>
                    <h3 class="effect delay_1">Stock Depletion Prediction Process</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/product_add.svg') }}" alt=""></i>Register Categories<br/>&amp; Products</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/sales_data.svg') }}" alt=""></i>Sync Daily Sales Data<br/>by Product</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/stock.svg') }}" alt=""></i>Sync Daily Remaining<br/>Stock by Product</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/ai_forecast.svg') }}" alt=""></i>AI Daily Sales Forecast<br/>by Product</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/calendar_clock.svg') }}" alt=""></i>Estimate Stock<br/>Depletion Date</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(2)">Apply for RIPS</button>
                    <p>✱ Reduce stockouts and overstock with RIPS.</p>
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
                $('.rips_inventory_con01').addClass('on')
            },100);
        });



    </script>
</body>
@include('user.en.footer._gtm')
</html>