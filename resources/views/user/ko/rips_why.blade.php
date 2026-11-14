@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
        <div class="section rips_why_con01">
            <div class="sub_inner">
                <h4 class="effects">왜 필요한가?</h4>
                <p class="effects delay_1">판매·재고·발주를 연결하는 실행형 수출 플랫폼</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 왜 RIPS인가? -->
        <div class="section rips_why_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">WHY RIPS</span>
                    <h3 class="effect delay_1">왜 <b>RIPS</b>가 필요한가?</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/icon/global.svg') }}" alt="">
                        <h5><b>한국-베트남</b></br>연결</h5>
                        <p>K-Product를 <br/> <b>베트남 리테일러에게 연결</b></p>
                    </li>
                    <li class="effect delay_3">
                        <img src="{{ asset('user_assets/img/sub/icon/star.svg') }}" alt="">
                        <h5><b>인기상품 </b></br>소싱</h5>
                        <p>불닭볶음면 등 인기 상품을<br/> <b>최적가 공급</b></p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/icon/certificate.svg') }}" alt="">
                        <h5><b>400개</b> 이상의<br/>상품 인허가</h5>
                        <p>각종 한국 상품의<br/><b>정식 통관&유통</b> </p>
                    </li>
                    <li class="effect delay_5">
                        <img src="{{ asset('user_assets/img/sub/icon/flask.svg') }}" alt="">
                        <h5 style="letter-spacing: -2px;">한국 신상품 <br/><b>테스트</b> 판매</h5>
                        <p>품질과 판매가 보장된<br/> 안정적인 <b>수익 창출</b></p>
                    </li>
                </ul>

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
                $('.rips_why_con01').addClass('on')
            },100);
        });



    </script>
</body>
@include('user.ko.footer._gtm')
</html>