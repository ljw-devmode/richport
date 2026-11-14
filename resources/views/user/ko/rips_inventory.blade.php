@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
        <div class="section rips_inventory_con01">
            <div class="sub_inner">
                <h4 class="effects">안전재고 예측</h4>
                <p class="effects delay_1">판매 데이터를 분석해 품절과 과재고를 방지합니다.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section rips_inventory_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">PROCESS</span>
                    <h3 class="effect delay_1">재고소진 예측 프로세스</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/product_add.svg') }}" alt=""></i>카테고리 및<br/>상품 등록</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/sales_data.svg') }}" alt=""></i>일별, 상품별<br/>판매 데이터 연동</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/stock.svg') }}" alt=""></i>일별, 상품별<br/>잔여 재고 연동</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/ai_forecast.svg') }}" alt=""></i>AI 일별, 상품별<br/>판매량 예측</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/calendar_clock.svg') }}" alt=""></i>재고 소진<br/>예상일 산출</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(2)">RIPS 이용 신청</button>
                    <p>✱ RIPS를 통해 결품과 과재고를 줄여보세요.</p>
                </div>
            </div>
        </div>        

        <!-- footer -->
        <section class="section fp-auto-height">
            @include('user.ko.footer._footer')
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
@include('user.ko.footer._gtm')
</html>