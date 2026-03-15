@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
		<!-- 건물지원사업 -->		
        <div class="section building_con01">
            <div class="sub_inner">
                <h4 class="effects">태양광 건물지원사업</h4>
                <p class="effects delay_1">정부 보조금으로 태양광을</p>
                <p class="effects delay_2"><span><i>반값으로 설치</i></span>하는 방법을 알고 계신가요? </p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 사업설명 -->
        <div class="section building_con02">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">정부는 건물지원사업으로</span>
                    <h3 class="effect delay_1">설치 비용의 <b>50%를</b> 지원</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con08_1.svg') }}" alt="">
                        <h5>부담 감소</h5>
                        <p>정부 보조금으로<br/> 투자비용 <b>50% 감소</b></p>
                    </li>
                    <li class="effect delay_3">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con08_2.svg') }}" alt="">
                        <h5>1,000kW 까지</h5>
                        <p>일반건물 <b>200kW</b>, <br/> RE100·산단기업 <b>1,000kW</b></p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con08_4.svg') }}" alt="">
                        <h5>전기료 절감</h5>
                        <p>태양광으로 생상된<br/>전력을 <b>자가소비</b></p>
                    </li>
                    <li class="effect delay_5">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con08_3.svg') }}" alt="">
                        <h5>ESG 달성</h5>
                        <p>신재생에너지 사용으로<br/> <b>지속가능한 경영 가능</b></p>
                    </li>
                </ul>
            </div>
        </div>


        <!-- 혜택 -->
        <div class="section building_con03">
            <div class="box">
                <div class="left_txt">
                    <div class="txt_box">
                        <span class="effect">매월 <i>약 4,800만원</i>의 수익</span>
                        <h3 class="effect delay_1">빠른 회수, <b>추가 수익</b></h3>
                    </div>
                </div>
                <div class="right_box">
                    <ul>
                        <li class="effect">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_1.svg') }}" alt="">총 발전 수익</i>
                            <p><b>9.7억</b>원</p>
                            <span>20년간 총 예상 수익</span>
                        </li>
                        <li class="effect delay_1">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_2.svg') }}" alt="">투자금 회수</i>
                            <p><b>3</b>년</p>
                            <span>투자금 약 1.1억원</span>
                        </li>
                        <li class="effect delay_2">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_3.svg') }}" alt="">안정적인 수익</i>
                            <p><b>8.5억</b>원</p>
                            <span>17년간 수익 발생</span>
                        </li>
                        <li class="effect delay_3">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_4.svg') }}" alt="">발전소 운영</i>
                            <p><b>5</b>년</p>
                            <span>운영관리팀의 완벽한 관리</span>
                        </li>
                    </ul>
                    <p class="txt effect delay_4">※ RE100, 산단기업 200kW를 기준으로 작성되었습니다.<br/>※ 발전 용량에 따라 회수기간 및 수익은 달라질 수 있습니다.</p>
                </div>
            </div>
        </div>


        <!-- 절차 -->
        <div class="section building_con04">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">매년 초 조기마감으로 지금부터 준비 필수!!!</span>
                    <h3 class="effect delay_1">건물지원사업 진행 절차</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_2.svg') }}" alt=""></i>사업 신청<br/>및 접수</p>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/building_con04_1.svg') }}" alt=""></i>서류검토<br/> &nbsp;</p>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_3.svg') }}" alt=""></i>지원 대상<br/>평가 및 선정</p>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_2.svg') }}" alt=""></i>사업계획서<br/>제출</p>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_1.svg') }}" alt=""></i>사업승인<br/> &nbsp;</p>
                    </li>
                    <li class="effect delay_7">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_4.svg') }}" alt=""></i>설비 공사<br/>및 설치 확인</p>
                    </li>
                    <li class="effect delay_8">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/building_con04_2.svg') }}" alt=""></i>보조금<br/>지급</p>
                    </li>                                        
                </ul>
                <div class="btn_box effect delay_9">
                    <button onclick="openInquire(4)">상담 신청하기</button>
                </div>
            </div>
        </div>


        <!-- 평가항목 -->
        <div class="section building_con05">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">무엇부터 준비해야 할까요?</span>
                    <h3 class="effect delay_1">주요 평가 항목 체크</h3>
                </div>
                <div class="graph_box">
                    <div class="tit effect delay_2">9가지<br/>주요 항목</div>
                    <div class="box step_1 effect delay_3">
                        <i>01</i>
                        <h5>가점 항목</h5>
                        <p>RE100 기업 <b>3점, </b><br/>사업단지 기업 <b>3점, </b><br/>사회복지 시설 <b>3점</b></p>
                    </div>
                    <div class="box step_2 effect delay_4">
                        <i>02</i>
                        <h5>계량 항목</h5>
                        <p>30일 이내 착공 <b>3점, </b><br/>90일 이내 완공 <b>3점, </b><br/>사전 허가 <b>3점</b></p>
                    </div>
                    <div class="box step_3 effect delay_5">
                        <i>03</i>
                        <h5>비 계량 항목</h5>
                        <p>설치계획 <b>20점, </b><br/>참여기업 수행능력 <b>10점, </b><br/>사후관리 <b>10점</b></p>
                    </div>
                </div>
            </div>
        </div>


        <!-- 왜 솔라테크 -->
        <div class="section building_con06">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">왜 솔라테크 일까요?</span>
                    <h3 class="effect delay_1">건물지원사업은 <b>솔라테크</b></h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con06_1.svg') }}" alt="">
                        <h5>원스톱 서비스</h5>
                        <p><b>인허가·시공·사후관리</b>까지, </br>자체 구조물로 <b>빠른 착공·완공</b></p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con06_3.svg') }}" alt="">
                        <h5>최적화된 설치 계획</h5>
                        <p>3D 시뮬레이션, 음영분석 등</br> <b>과학적 설계</b></p>
                    </li>
                    <li class="effect delay_5">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con06_2.svg') }}" alt="">
                        <h5>인정받은 기술력</h5>
                        <p>포스코,CJ 등이 인정, </br>에너지공단 <b>참여기업</b></p>
                    </li>                    
                </ul>
            </div>
        </div>
        



        <!-- 포트폴리오 없을때 -->
        @if ($data->isEmpty() || count($data) < 2)
        <div class="section portfolio_con">
            <div class="port_slide">
                <div class="tit_box">
                    <h3>포트폴리오</h3>
                    <button class="arrow prev"></button>
                    <button class="arrow next"></button>
                </div>
                <ul class="swiper-wrapper">
                    <li class="swiper-slide">
                        <img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/business/gov_building3_pc.jpg') }}" alt="">
                        <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/business/gov_building3_fo.jpg') }}" alt="">
                        <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/business/gov_building3_mo.jpg') }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit"><h5>대산산업</h5><strong>100kW</strong></div>
                                    <div class="info">
                                        <dl>
                                            <dt>지역</dt>
                                            <dd>경기도 부천시</dd>
                                        </dl>
                                        <dl>
                                            <dt>형태</dt>
                                            <dd>주차장형</dd>
                                        </dl>
                                        <dl>
                                            <dt>준공일</dt>
                                            <dd>2025.09.09</dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="bot">
                                    <div class="btn">
                                    <!--
                                    <a href="" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_blog.svg') }}" alt="">시공사례</a>
                                    <a href="" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_youtube.svg') }}" alt="">준공영상</a>
                                    -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="swiper-slide">
                        <img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/business/gov_building1_pc.jpg') }}" alt="">
                        <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/business/gov_building1_fo.jpg') }}" alt="">
                        <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/business/gov_building1_mo.jpg') }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit"><h5>동아에스티</h5><strong>100kW</strong></div>
                                    <div class="info">
                                        <dl>
                                            <dt>지역</dt>
                                            <dd>인천시 연수구</dd>
                                        </dl>
                                        <dl>
                                            <dt>형태</dt>
                                            <dd>지붕형</dd>
                                        </dl>
                                        <dl>
                                            <dt>준공일</dt>
                                            <dd>2025.09.11</dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="bot">
                                    <div class="btn">
                                    <!--
                                    <a href="" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_blog.svg') }}" alt="">시공사례</a>
                                    <a href="" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_youtube.svg') }}" alt="">준공영상</a>
                                    -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="swiper-slide">
                        <img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/business/gov_building2_pc.jpg') }}" alt="">
                        <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/business/gov_building2_fo.jpg') }}" alt="">
                        <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/business/gov_building2_mo.jpg') }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit"><h5>동원로엑스</h5><strong>100kW</strong></div>
                                    <div class="info">
                                        <dl>
                                            <dt>지역</dt>
                                            <dd>인천시 서구</dd>
                                        </dl>
                                        <dl>
                                            <dt>형태</dt>
                                            <dd>지붕형</dd>
                                        </dl>
                                        <dl>
                                            <dt>준공일</dt>
                                            <dd>2025.12</dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="bot">
                                    <div class="btn">
                                    <!--
                                    <a href="" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_blog.svg') }}" alt="">시공사례</a>
                                    <a href="" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_youtube.svg') }}" alt="">준공영상</a>
                                    -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>                    
                </ul>
            </div>
        </div>
        @endif



        <!-- 포트폴리오 있을때 -->
        @if (!$data->isEmpty() && count($data) >= 2)
        <div class="section portfolio_con">
            <div class="port_slide">
                <div class="tit_box">
                    <h3>포트폴리오</h3>
                    <button class="arrow prev"></button>
                    <button class="arrow next"></button>
                </div>
                <ul class="swiper-wrapper">
                    @foreach ($data as $index => $item)
                        <li class="swiper-slide">
	                        <img class="bg_img pc_img" src="{{ Storage::disk('public')->url($item->pc_up_name) }}" alt="">
	                        <img class="bg_img fold_img" src="{{ Storage::disk('public')->url($item->fo_up_name) }}" alt="">
	                        <img class="bg_img mo_img" src="{{ Storage::disk('public')->url($item->mo_up_name) }}" alt="">
                            <div class="inner">
                                <div class="txt_box in_scroll">
                                    <div class="top">
                                        <div class="tit"><h5>{{$item->station_name}}</h5><strong>{{$item->volume}}kW</strong></div>
                                        <div class="info">
                                            <dl>
                                                <dt>지역</dt>
                                                <dd>{{ $item->regions->name_kr ?? '' }} {{ $item->cities->name_kr ?? '' }}</dd>
                                            </dl>
                                            <dl>
                                                <dt>형태</dt>
                                                <dd>{{ $item->formTypes->subject ?? '' }}</dd>
                                            </dl>
                                            <dl>
                                                <dt>준공일</dt>
                                                <dd>{{ $item->completion_date }}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="bot">
                                        <div class="btn">
                                        @if($item->blog)
                                        <a href="{{ $item->blog }}" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_blog.svg') }}" alt="">시공사례</a>
                                        @endif
                                        @if($item->url)
                                        <a href="" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_youtube.svg') }}" alt="">준공영상</a>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach                 
                </ul>
            </div>
        </div>
        @endif


        <!-- 상담신청 -->
        <div class="section building_con07">
            <div class="sub_inner">
                <p class="effects delay_1">건물지원사업은 <span><i>공고 직후 마감!!!</i></span></p>
                <p class="effects delay_2">솔라테크와 바로 상담하세요</p>
            </div>
            <div class="btn_box effects delay_3">
                <button onclick="openInquire(4)">상담 신청하기</button>
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
            normalScrollElements: '.in_scroll'
        });
        $(window).on('resize', function() {
            $.fn.fullpage.reBuild();
        });

        $(window).on('load', function(){
            setTimeout(function(){
                $('.building_con01').addClass('on')
            },100)
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

        function openInquire(val) {

            $('input[name="types_idx"]').prop('checked', false);
            $('input[name="types_idx"][value="' + val + '"]').prop('checked', true);

            modalOpen('inquiry-modal');
        }
    </script>

</body>
@include('user.ko.footer._gtm')
</html>