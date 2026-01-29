@include('user.cn.header._head')
<body>
	@include('user.cn.header._header')
    <main id="fullpage">
        <!-- 솔라테크 소개 -->
        <div class="section about_con01">
            <div class="sub_inner">
                <img class="effects" src="{{ asset('user_assets/img/main/main_logo.svg') }}" alt="">
                <p class="effects delay_1">通过智能技术连接太阳能，实现可持续能源与生产革新。</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 인사말 -->
        <div class="section about_con02">
            <div class="sub_inner2">
                <div class="sub_tit">
                    <span class="effect">CREW GREETING</span>
                    <h3 class="effect delay_1">员工致辞</h3>
                </div>
                <div class="ceo_box">
                    <div class="img_box effect delay_3">
                        <img class="pc_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_PC.png') }}" alt="">
                        <img class="fold_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_F.png') }}" alt="">
                        <img class="mo_img" src="{{ asset('user_assets/img/sub/company/회사소개_솔라테크소개_M.png') }}" alt="">
                    </div>
                    <div class="txt_box">
                        <p class="effect delay_4"><b>您好，我们是致力于创造可持续未来价值的企业 SOLARTEQ。</b></p>
                        <p class="effect delay_4">如今，降低碳排放并构建透明、安全的环境，已成为衡量企业竞争力的重要标准。</p>
                        <p class="effect delay_4">SOLARTEQ 的太阳能技术为工厂注入环保能源活力，其智能工厂技术最大化生产效率，实现无浪费增长。</p>
                        <p class="effect delay_4">从能源生产到高效智能的能源使用，SOLARTEQ 将引领企业迈向兼顾环境与技术领先的未来型企业。</p>
                        <p class="effect delay_4"><b>谢谢。</b></p>
                        <div class="ceo effect delay_5">
                            <strong>SOLARTEQ 全体员工敬上</strong>
                            <span>SOLARTEQ 团队</span>
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
                    <h3 class="effect delay_1">来访路线</h3>
                    <div class="tab_box effect delay_2">
                        <a href="javascript:;" class="active">光明总部</a>
                        <a href="javascript:;">华城工厂</a>
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
                                <h5>光明总部</h5>
                                <div>
                                    <b>地址</b>
                                    <p>京畿道光明市阳地路21号 T-Tower 9层<a href="javascript:;" onclick="copyText('京畿道光明市阳地路21号 T-Tower 9层')">复制</a></p>
                                </div>
                                <div>
                                    <b>电话</b>
                                    <p>02-6912-4900 <a href="tel:02-6912-4900">拨打电话</a></p>
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
                                <h5>华城工厂</h5>
                                <div>
                                    <b>地址</b>
                                    <p>京畿道华城市牛亭邑垂柳路985-16号 <a href="javascript:;" onclick="copyText('京畿道华城市牛亭邑垂柳路985-16号')">复制</a></p>
                                </div>
                                <div>
                                    <b>电话</b>
                                    <p>031-358-4743 <a href="tel:031-358-4743">拨打电话</a></p>
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
                    <span class="effect">CI 介绍</span>
                    <h3 class="effect delay_1">CI</h3>
                    <a href="/download/ci" class="down_btn effect delay_2"><img src="{{ asset('user_assets/img/icon/down.svg') }}" alt="">下载 CI 套件</a>
                </div>
                <div class="ci_box">
                    <div class="left_box">
                        <img class="effect delay_3" src="{{ asset('user_assets/img/sub/company/about_ci_icon.svg') }}" alt="">
                        <p class="effect delay_4">SOLARTEQ 标志以无限符号象征无穷的太阳能。</p>
                    </div>
                    <div class="right_box">
                        <dl class="effect delay_5">
                            <dt>A. 独立文字型</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_A.svg') }}" alt=""></dd>
                        </dl>
                        <dl class="effect delay_5">
                            <dt>B. 纵向组合型</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_B.svg') }}" alt=""></dd>
                        </dl>
                        <dl class="effect delay_5">
                            <dt>C. 横向组合型</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_C.svg') }}" alt=""></dd>
                        </dl>
                        <dl class="effect delay_6">
                            <dt>D. 独立文字型</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_1.svg') }}" alt=""></dd>
                        </dl>
                        <dl class="effect delay_6">
                            <dt>E. 纵向组合型</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_2.svg') }}" alt=""></dd>
                        </dl>
                        <dl class="effect delay_6">
                            <dt>F. 横向组合型</dt>
                            <dd><img src="{{ asset('user_assets/img/sub/company/about_ci_3.svg') }}" alt=""></dd>
                        </dl>

                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <section class="section fp-auto-height">
            @include('user.cn.footer._footer')
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
            alert('地址已复制。')
        }
    </script>
</body>
@include('user.cn.footer._gtm')
</html>