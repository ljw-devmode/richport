@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
        <div class="section rips_about_con01">
            <div class="sub_inner">
                <h4 class="effects">주요특징</h4>
                <p class="effects delay_1">수출의 판단을 데이터로 바꿉니다.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section rips_about_con02">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">WHY RIPS</span>
                    <h3 class="effect delay_1">현지 데이터를 수출 기회로 바꾸는 RIPS</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con02_icon_1.svg') }}" alt="">
                        <h5><b>폐기 감소</b></h5>
                        <p>상품 과재고 방지로<br/> 손실 최소화</p>
                    </li>
                    <li class="effect delay_3">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con02_icon_2.svg') }}" alt="">
                        <h5><b>매출기회 확대</b></h5>
                        <p>안정적인 발주로<br/> 결품 방지</p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con02_icon_3.svg') }}" alt="">
                        <h5><b>적정 발주</b></h5>
                        <p>AI 기반의<br/> 정확한 판매 예측</p>
                    </li>
                    <li class="effect delay_5">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con02_icon_4.svg') }}" alt="">
                        <h5 style="letter-spacing: -2px;"><b>신제품 제안</b></h5>
                        <p>한국의 인기 신상품을<br/> 빠르게 공급</p>
                    </li>
                </ul>

            </div>
        </div>        

        <!-- portfolio -->
        <div class="section portfolio_con">
            <div class="port_slide">
                <div class="tit_box">
                    <h3>주요 서비스</h3>
                    <button class="arrow prev"></button>
                    <button class="arrow next"></button>
                </div>
                <ul class="swiper-wrapper">
                    <li class="swiper-slide">
                        <img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/rips/rips 상품.png') }}" alt="">
                        <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/rips/rips 상품.png') }}" alt="">
                        <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/rips/rips 상품.png') }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit"><b>주요 페이지</b><h4>상품관리</h4></div>
                                    <div class="info">
                                        <dl>
                                            <dt>재고</dt>
                                            <dd>상품별 잔여소진 예상일 산출</dd>
                                        </dl>
                                        <dl>
                                            <dt>발주일</dt>
                                            <dd>재고소진일과 입고 소요기간 고려 발주일 추천</dd>
                                        </dl>
                                        <dl>
                                            <dt>발주수량</dt>
                                            <dd>과재고,결품 방지를 위한 발주 수량 추천</dd>
                                        </dl>
                                        <dl>
                                            <dt>입고 소요기간</dt>
                                            <dd>발주 후 생산~물류~입고까지 기간 예측</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="swiper-slide">
                        <img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/rips/rips 발주관리.png') }}" alt="">
                        <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/rips/rips 발주관리.png') }}" alt="">
                        <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/rips/rips 발주관리.png') }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit"><b>주요 페이지</b><h4>발주 관리</h4></div>
                                    <div class="info">
                                        <dl>
                                            <dt>발주 상태</dt>
                                            <dd>생산~물류~통관~입고까지 상태 안내</dd>
                                        </dl>
                                        <dl>
                                            <dt>발주 정보</dt>
                                            <dd>상태, 업체, 상품 정보 제공</dd>
                                        </dl>
                                        <dl>
                                            <dt>발주자 정보</dt>
                                            <dd>발주자 책임 관리</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="swiper-slide">
                        <img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/rips/rips 설문조사.png') }}" alt="">
                        <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/rips/rips 설문조사.png') }}" alt="">
                        <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/rips/rips 설문조사.png') }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit"><b>주요 페이지</b><h4>설문조사</h4></div>
                                    <div class="info">
                                        <dl>
                                            <dt>테스트 배드</dt>
                                            <dd>신상품 판매 테스트 진행</dd>
                                        </dl>
                                        <dl>
                                            <dt>설문조사</dt>
                                            <dd>고객 의견 수렴 및 분석</dd>
                                        </dl>
                                        <dl>
                                            <dt>구매자 분석</dt>
                                            <dd>연령, 성별, 지역별 분석</dd>
                                        </dl>
                                        <dl>
                                            <dt>리포트</dt>
                                            <dd>상품 판매 성과 리포트</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>                                       
                </ul>
            </div>
        </div>


        <div class="section rips_about_con05">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">STEP-BY-STEP PROCESS</span>
                    <h3 class="effect delay_1">RIPS 도입 절차</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/apply.svg') }}" alt=""></i>RIPS<br/>이용 신청</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/account.svg') }}" alt=""></i>업체 확인,<br/>계정 발급</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/mapping.svg') }}" alt=""></i>지점 및<br/> 상품 맵핑</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/sync.svg') }}" alt=""></i>판매 및<br/>재고 연동</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/launch.svg') }}" alt=""></i>RIPS<br/>서비스 이용</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(2)">RIPS 이용 신청</button>
                    <p>✱ 이용신청 접수 후 담당자가 연락드립니다.</p>
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
                $('.rips_about_con01').addClass('on')
            },100);
        });

        // portfolio_con
        var port_slide = new Swiper(".port_slide", {
            speed:1000,
            loop:true,
            navigation: {
                nextEl: ".port_slide .next",
                prevEl: ".port_slide .prev",
            },
        });

    </script>
</body>
@include('user.ko.footer._gtm')
</html>