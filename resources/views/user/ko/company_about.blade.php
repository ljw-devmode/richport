@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
        <!-- 솔라테크 소개 -->
        <div class="section about_con01">
            <div class="sub_inner">
                <img class="effects" src="{{ asset('user_assets/img/main/main_logo.svg') }}" alt="">
                <p class="effects delay_1">태양의 힘을 스마트한 기술로 연결하여,<br/>지속 가능한 에너지와 생산 혁신을 실현합니다.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 인사말 -->
        <div class="section about_con02">
            <div class="sub_inner2">
                <div class="sub_tit">
                    <span class="effect">CREW GREETING</span>
                    <h3 class="effect delay_1">임직원 인사말</h3>
                </div>
                <div class="ceo_box">
                    <div class="img_box effect delay_3">
                        <img class="pc_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_PC.png') }}" alt="">
                        <img class="fold_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_F.png') }}" alt="">
                        <img class="mo_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_M.png') }}" alt="">
                    </div>
                    <div class="txt_box">
                        <p class="effect delay_4"><b>안녕하십니까, 지속 가능한 내일의 가치를 만드는 기업 SOLARTEQ입니다.</b></p>
                        <p class="effect delay_4">오늘날, 탄소를 줄이고, 투명하고 안전한 환경을 구축하는 것은 기업의 경쟁력을 좌우하는 척도가 되었습니다.</p>
                        <p class="effect delay_4">솔라테크의 태양광 기술은 공장에 친환경 에너지라는 생명력을 불어넣고, 솔라테크의 스마트 팩토리 기술은 제조 현장의 효율을 극대화하여 낭비 없는 성장을 실현합니다.</p>
                        <p class="effect delay_4">에너지를 생산하는 것부터 가장 똑똑하게 소비하는 과정까지, 솔라테크는 여러분의 기업이 환경을 생각하고 기술로 앞서가는 미래형 기업으로 거듭나는 든든한 여정에 앞장서겠습니다.</p>
                        <p class="effect delay_4"><b>감사합니다.</b></p>
                        <div class="ceo effect delay_5">
                            <strong>솔라테크 임직원일동</strong>
                            <span>SOLARTEQ CREW</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 찾아오시는 길 -->
        <div class="section about_con03">
            <div class="sub_inner2">
                 <div class="sub_tit">
                    <span class="effect">CONTACT</span>
                    <h3 class="effect delay_1">찾아오시는 길</h3>
                    <div class="tab_box effect delay_2">
                        <a href="javascript:;" class="active">광명 HQ</a>
                        <a href="javascript:;">화성 공장</a>
                    </div>
                </div>
                <div class="tab_content">
                    <div class="active">
                        <div class="map_box">
                            <div class="map effect delay_3">
                                <!-- * 카카오맵 - 지도퍼가기 -->
                                <!-- 1. 지도 노드 -->
                                <div id="daumRoughmapContainer1764209080710" class="root_daum_roughmap root_daum_roughmap_landing"></div>

                                <!--
                                    2. 설치 스크립트
                                    * 지도 퍼가기 서비스를 2개 이상 넣을 경우, 설치 스크립트는 하나만 삽입합니다.
                                -->
                                    <script charset="UTF-8" class="daum_roughmap_loader_script" src="https://ssl.daumcdn.net/dmaps/map_js_init/roughmapLoader.js"></script>

                                <!-- 3. 실행 스크립트 -->
                                <script charset="UTF-8">
                                    new daum.roughmap.Lander({
                                        "timestamp" : "1764209080710",
                                        "key" : "da9rfptst44",
                                    }).render();
                                </script>
                            </div>
                            <div class="info effect delay_4">
                                <h5>광명 HQ</h5>
                                <div>
                                    <b>주소</b>
                                    <p>경기 광명시 양지로 21 티타워 9층 <a href="javascript:;" onclick="copyText('경기 광명시 양지로 21 유플래닛티타워 9층')">복사하기</a></p>
                                </div>
                                <div>
                                    <b>전화</b>
                                    <p>02-6912-4900 <a href="tel:02-6912-4900">전화걸기</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="active">
                        <div class="map_box">
                            <div class="map effect delay_3">
                                <!-- * 카카오맵 - 지도퍼가기 -->
                                <!-- 1. 지도 노드 -->
                                <div id="daumRoughmapContainer1764209367611" class="root_daum_roughmap root_daum_roughmap_landing"></div>

                                <!--
                                    2. 설치 스크립트
                                    * 지도 퍼가기 서비스를 2개 이상 넣을 경우, 설치 스크립트는 하나만 삽입합니다.
                                -->

                                <!-- 3. 실행 스크립트 -->
                                <script charset="UTF-8">
                                    new daum.roughmap.Lander({
                                        "timestamp" : "1764209367611",
                                        "key" : "cvkq4gycx6p",
                                        "mapWidth" : "640",
                                        "mapHeight" : "360"
                                    }).render();
                                </script>
                                
                            </div>
                            <div class="info effect delay_4">
                                <h5>화성 공장</h5>
                                <div>
                                    <b>주소</b>
                                    <p>경기도 화성시 우정읍 버들로 985-16 <a href="javascript:;" onclick="copyText('경기도 화성시 우정읍 버들로 985-16')">복사하기</a></p>
                                </div>
                                <div>
                                    <b>전화</b>
                                    <p>031-358-4743 <a href="tel:031-358-4743">전화걸기</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- CI -->
        <div class="section about_con04">
            <div class="sub_inner2">
                <div class="sub_tit">
                    <span class="effect">INTRODUCING OUR CI</span>
                    <h3 class="effect delay_1">CI</h3>
                    <a href="/download/ci" class="down_btn effect delay_2"><img src="{{ asset('user_assets/img/icon/down.svg') }}" alt="">CI 패키지 다운로드</a>
                </div>
                <div class="ci_box">
                    <div class="left_box">
                        <img class="effect delay_3" src="{{ asset('user_assets/img/sub/company/about_ci_icon.svg') }}" alt="">
                        <p class="effect delay_4">무한한 태양에너지를 상징하는<br/>무한대를 심볼화한 솔라테크 로고</p>
                    </div>
                    <div class="right_box">
                        <dl class="effect delay_5">
                            <dt>A. 독립 텍스트형</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_A.svg') }}" alt=""></dd>
                        </dl>
                        <dl class="effect delay_5">
                            <dt>B. 세로 조합형</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_B.svg') }}" alt=""></dd>
                        </dl>
                        <dl class="effect delay_5">
                            <dt>C. 가로 조합형</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_C.svg') }}" alt=""></dd>
                        </dl>
                        <dl class="effect delay_6">
                            <dt>가. 독립 텍스트형</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_1.svg') }}" alt=""></dd>
                        </dl>
                        <dl class="effect delay_6">
                            <dt>나. 세로 조합형</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_2.svg') }}" alt=""></dd>
                        </dl>
                        <dl class="effect delay_6">
                            <dt>다. 가로 조합형</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_3.svg') }}" alt=""></dd>
                        </dl>

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

        const copyText = (text)=>{
            navigator.clipboard.writeText(text);
            alert('주소가 복사되었습니다.')
        }
    </script>
</body>
@include('user.ko.footer._gtm')
</html>