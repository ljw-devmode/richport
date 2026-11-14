@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
        <!-- 솔라테크 소개 -->
        <div class="section crew_con01">
            <div class="sub_inner">
                <h4 class="effects">크루 소개</h4>
                <p class="effects delay_1">현장을 아는 사람과 기술을 아는 사람이 함께 수출의 답을 만듭니다.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section crew_con02">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">CREW</span>
                    <h3 class="effect delay_1">리치포트 크루</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <div class="txt_box">
                            <i>01</i>
                            <p><b>정성원</b> CEO</p>
                            <span>베트남 현지 유통 및 경영 전문가</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_1.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <div class="txt_box">
                            <i>02</i>
                            <p><b>이재우</b> CTO</p>
                            <span>리치포트 서비스 개발 총괄</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_2.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <div class="txt_box">
                            <i>03</i>
                            <p><b>최연욱</b> 팀장</p>
                            <span>베트남 현지 유통 전문가</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_3.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <div class="txt_box">
                            <i>04</i>
                            <p><b>Nguyen Thi Thu Trang</b> 팀장</p>
                            <span>베트남 현지 유통 및 한-베 통역 전문가</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_4.png') }}" alt=""></div>
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
                $('.crew_con01').addClass('on')
            },100);
        });        
    </script>
</body>
@include('user.ko.footer._gtm')
</html>