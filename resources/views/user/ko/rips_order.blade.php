@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
        <div class="section rips_order_con01">
            <div class="sub_inner">
                <h4 class="effects">발주 예측</h4>
                <p class="effects delay_1">판매데이터를 기반으로 수요를 예측하고 최적 발주량을 제안합니다.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section rips_order_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">PROCESS</span>
                    <h3 class="effect delay_1">발주 예측 프로세스</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/depletion.svg') }}" alt=""></i>판매 예측 기반<br/>재고 소진일 예측</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/truck.svg') }}" alt=""></i>생산 및<br/>물류시간 예측</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/customs.svg') }}" alt=""></i>통관 소요시간<br/>예측</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/hourglass.svg') }}" alt=""></i><br/>발주~입고까지</br>총 소요시간 예측</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/calendar_check.svg') }}" alt=""></i>최적 발주일 및<br/>발주 수량 예측</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(2)">RIPS 이용 신청</button>
                    <p>✱ RIPS를 통해 안전재고를 유지하세요.</p>
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