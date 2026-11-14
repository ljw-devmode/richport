@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
        <div class="section about_con01">
            <div class="sub_inner">
                <h4 class="effects">리치포트 소개</h4>
                <p class="effects delay_1">리치포트는 한국을 대표 상품들을 엄선하여,<br/>베트남 시장에 빠르게 공급하는 글로벌 유통 파트너</p>
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
                        <img class="pc_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_PC.png') }}" alt="">
                        <img class="fold_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_F.png') }}" alt="">
                        <img class="mo_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_M.png') }}" alt="">
                    </div>
                    <div class="txt_box">
                        <p class="effect delay_4"><b>안녕하십니까, 리치포트 대표 정성원 입니다.</b></p>
                        <p class="effect delay_4">리치포트는 대한민국을 대표하는 우수한 품질의 상품들을 엄선하여 베트남 시장에 가장 안전하고 빠르게 공급하는 글로벌 유통 파트너입니다.</p>
                        <p class="effect delay_4">저희는 단순히 상품을 수입하고 판매하는 것을 넘어, 한국의 트렌디하고 건강한 라이프스타일을 베트남 소비자들에게 온전히 전달하는 '신뢰의 항구(Rich-Port)'가 되고자 합니다.</p>
                        <p class="effect delay_4">철저한 정품 보장, 투명한 유통 과정으로 양국의 파트너사들과 함께 지속 가능한 성장을 이루어 가겠습니다.</p>
                        <p class="effect delay_4">리치포트와 함께 더 넓은 비즈니스의 기회를 만나보시기 바랍니다.<br/><b>감사합니다.</b></p>
                        <div class="ceo effect delay_5">
                            <strong>리치포트 임직원일동</strong>
                            <span>RICHPORT CREW</span>
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