@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="fullpage">
        <div class="section rips_order_con01">
            <div class="sub_inner">
                <h4 class="effects">Order Prediction</h4>
                <p class="effects delay_1">We forecast demand based on sales data and recommend optimal order quantities.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section rips_order_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">PROCESS</span>
                    <h3 class="effect delay_1">Order Prediction Process</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/depletion.svg') }}" alt=""></i>Stock Depletion Date<br/>from Sales Forecast</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/truck.svg') }}" alt=""></i>Production &amp;<br/>Logistics Time Forecast</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/customs.svg') }}" alt=""></i>Customs Clearance<br/>Time Forecast</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/hourglass.svg') }}" alt=""></i><br/>Total Lead Time</br>from Order to Receipt</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/calendar_check.svg') }}" alt=""></i>Optimal Order Date<br/>&amp; Quantity Forecast</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(2)">Apply for RIPS</button>
                    <p>✱ Maintain safety stock with RIPS.</p>
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