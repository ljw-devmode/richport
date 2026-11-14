@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
        <div class="section about_con01">
            <div class="sub_inner">
                <h4 class="effects">리치포트 소개</h4>
                <p class="effects delay_1">현지 데이터와 한국 브랜드를 연결합니다.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section about_con02">
            <div class="sub_inner2">
                <div class="sub_tit">
                    <span class="effect">GREETING</span>
                    <h3 class="effect delay_1">대표자 인사말</h3>
                </div>
                <div class="ceo_box">
                    <div class="img_box effect delay_3">
                        <img class="pc_img" src="{{ asset('user_assets/img/sub/company/about_ceo.png') }}" alt="">
                        <img class="fold_img" src="{{ asset('user_assets/img/sub/company/about_ceo.png') }}" alt="">
                        <img class="mo_img" src="{{ asset('user_assets/img/sub/company/about_ceo.png') }}" alt="">
                    </div>
                    <div class="txt_box">
                        <p class="effect delay_4">안녕하세요.</p>
                        <p class="effect delay_4">리치포트는 베트남 현지 판매·재고 데이터를 기반으로 한국 브랜드의 동남아 진출을 지원하는 수출 운영 파트너입니다.</p>
                        <p class="effect delay_4">현지 유통사의 결품과 과재고를 줄이고, 한국 브랜드사에는 반복발주로 이어지는 성장 기회를 만들겠습니다.</p>
                        <p class="effect delay_4">리치포트는 데이터와 실행력을 바탕으로 한국과 베트남을 연결하겠습니다.</p>
                        <p class="effect delay_4">감사합니다.</p>
                        <div class="ceo effect delay_5">
                            <strong>대표이사 정 성 원</strong>
                        </div>
                    </div>
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
                $('.about_con01').addClass('on')
            },100);

            setTimeout(function(){
                $('.tab_content > div').eq(1).removeClass('active')
            },400);

            $('.tab_box a').on('click',function(){
                let liN = $(this).index();
                $(this).addClass('active').siblings().removeClass('active');
                $('.tab_content > div').eq(liN).addClass('active').siblings().removeClass('active');
            })
        });

    </script>
</body>
@include('user.ko.footer._gtm')
</html>