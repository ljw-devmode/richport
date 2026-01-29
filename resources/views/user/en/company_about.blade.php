@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="fullpage">
        <!-- 솔라테크 소개 -->
        <div class="section about_con01">
            <div class="sub_inner">
                <img class="effects" src="{{ asset('user_assets/img/main/main_logo.svg') }}" alt="">
                <p class="effects delay_1">We connect the power of the sun with smart technology to realize sustainable energy and production innovation.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 인사말 -->
        <div class="section about_con02">
            <div class="sub_inner2">
                <div class="sub_tit">
                    <span class="effect">CREW GREETING</span>
                    <h3 class="effect delay_1">Greetings from employees</h3>
                </div>
                <div class="ceo_box">
                    <div class="img_box effect delay_3">
                        <img class="pc_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_PC.png') }}" alt="">
                        <img class="fold_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_F.png') }}" alt="">
                        <img class="mo_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_M.png') }}" alt="">
                    </div>
                    <div class="txt_box">
                        <p class="effect delay_4"><b>Hello, we are SOLARTEQ, a company creating value for a sustainable tomorrow.</b></p>
                        <p class="effect delay_4">Today, reducing carbon emissions and building a transparent and safe environment have become key measures of corporate competitiveness.</p>
                        <p class="effect delay_4">SOLARTEQ’s solar technologies bring eco-friendly energy to factories, while our smart factory solutions maximize operational efficiency and enable waste-free growth.</p>
                        <p class="effect delay_4">From energy generation to the smartest energy consumption, SOLARTEQ will lead the journey in transforming your business into a future-ready company that values the environment and advances through technology.</p>
                        <p class="effect delay_4"><b>Thank you.</b></p>
                        <div class="ceo effect delay_5">
                            <strong>Sincerely, SOLARTEQ</strong>
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
                    <h3 class="effect delay_1">CONTACT</h3>
                    <div class="tab_box effect delay_2">
                        <a href="javascript:;" class="active">Gwangmyeong HQ</a>
                        <a href="javascript:;">Hwaseong Factory</a>
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
                                <h5>Gwangmyeong HQ</h5>
                                <div>
                                    <b>Address</b>
                                    <p>9F, U-Planet T-Tower, 21 Yangji-ro, Gwangmyeong-si, Gyeonggi-do <a href="javascript:;" onclick="copyText('9F, U-Planet T-Tower, 21 Yangji-ro, Gwangmyeong-si, Gyeonggi-do')">Copy</a></p>
                                </div>
                                <div>
                                    <b>Phone</b>
                                    <p>02-6912-4900 <a href="tel:02-6912-4900">Call Now</a></p>
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
                                <h5>Hwaseong Factory</h5>
                                <div>
                                    <b>Address</b>
                                    <p>985-16 Beodeul-ro, Ujeong-eup, Hwaseong-si, Gyeonggi-do <a href="javascript:;" onclick="copyText('985-16 Beodeul-ro, Ujeong-eup, Hwaseong-si, Gyeonggi-do')">Copy</a></p>
                                </div>
                                <div>
                                    <b>Phone</b>
                                    <p>031-358-4743 <a href="tel:031-358-4743">Call Now</a></p>
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
                    <a href="/download/ci" class="down_btn effect delay_2"><img src="{{ asset('user_assets/img/icon/down.svg') }}" alt="">Download CI Package</a>
                </div>
                <div class="ci_box">
                    <div class="left_box">
                        <img class="effect delay_3" src="{{ asset('user_assets/img/sub/company/about_ci_icon.svg') }}" alt="">
                        <p class="effect delay_4">The SOLARTEQ logo symbolizes infinity, representing limitless solar energy.</p>
                    </div>
                    <div class="right_box">
                        <dl class="effect delay_5">
                            <dt>A. Stand-alone Text Type</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_A.svg') }}" alt=""></dd>
                        </dl>
                        <dl class="effect delay_5">
                            <dt>B. Vertical Combination</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_B.svg') }}" alt=""></dd>
                        </dl>
                        <dl class="effect delay_5">
                            <dt>C. Horizontal Combination</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_C.svg') }}" alt=""></dd>
                        </dl>
                        <dl class="effect delay_6">
                            <dt>D. Stand-alone Text Type</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_1.svg') }}" alt=""></dd>
                        </dl>
                        <dl class="effect delay_6">
                            <dt>E. Vertical Combination</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_2.svg') }}" alt=""></dd>
                        </dl>
                        <dl class="effect delay_6">
                            <dt>F. Horizontal Combination</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_3.svg') }}" alt=""></dd>
                        </dl>

                    </div>
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
                $('.about_con01').addClass('on')
            },100);

            setTimeout(function(){
                $('.tab_content > div').eq(1).removeClass('active')
            },400);

            $('.tab_box a').on('click',function(){
                let liN = $(this).index();
                $(this).addClass('active').siblings().removeClass('active');
                $('.tab_content > div').eq(liN).addClass('active').siblings().removeClass('active');
            });

            setTimeout(function() {
                // 클래스 이름으로 요소를 선택하고 내용 변경
                const element = document.querySelectorAll('.roughmap_lebel_text');
                if (element[1]) {
                    element[1].textContent = 'SOLARTEQ';
                } else {
                    console.log('NOT FOUND.');
                }
                
                if (element[0]) {
                    element[0].textContent = 'SOLARTEQ';
                } else {
                    console.log('NOT FOUND.');
                }
    	    }, 500); // 0.5초 후에 실행
        });

        const copyText = (text)=>{
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text);
                alert('Address copied.');
            } else {
                const textarea = document.createElement("textarea");
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand("copy");
                document.body.removeChild(textarea);
                alert('Address copied.');
            }
        }
    </script>
</body>
@include('user.en.footer._gtm')
</html>