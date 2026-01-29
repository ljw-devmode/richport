@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
		<!-- 건물지원사업 -->		
        <div class="section carbon_con01">
            <div class="sub_inner">
                <p class="effects">정부가 태양광 설비 자금을 </p>				
                <p class="effects delay_1"><span><i>최대 100억원까지</i></span> 지원하는 </p>
                <p class="effects delay_2">탄소중립설비지원 알아보기!!</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 사업설명 -->
        <div class="section carbon_con02">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">정부가 탄소중립을 목적으로</span>
                    <h3 class="effect delay_1">태양광 설비자금을 <br/><b>최대 100억원까지 </b>지원</h3>
                </div>
                <ul>
                    <!--
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con08_1.svg') }}" alt="">
                        <h5>100% 부담</h5>
                        <p>태양광 설비 <br/> <b>비용 부담 ↑</b></p>
                    </li>
                    <li class="noneborder effect delay_3">
                        <span><img class="rotate" src="{{ asset('user_assets/img/sub/business/solar_con06_arrow.svg') }}" alt=""></span>
                    </li>
                    -->
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con08_4.svg') }}" alt="">
                        <h5>70% 지원</h5>
                        <p>업체별 <b>100억원</b> <br/>사업장별 <b>60억원</b> 까지</p>
                    </li>
                    <li class="noneborder effect delay_5">
                        <span><img src="{{ asset('user_assets/img/icon/plus.svg') }}" alt=""></span>
                    </li>					
                    <li class="effect delay_6">
                        <img src="{{ asset('user_assets/img/sub/business/fin_con02_3.svg') }}" alt="">
                        <h5>30% 부담</h5>
                        <p>정부 지원금으로 <br/><b>비용 부담 ↓</b></p>
                    </li>
                </ul>
            </div>
        </div>


        <!-- 혜택 -->
        <div class="section carbon_con03">
            <div class="box">
                <div class="left_txt">
                    <div class="txt_box">
                        <span class="effect"><i>온실가스 배출 저감을 위한</i></span>
                        <h3 class="effect delay_1"><b>탄소중립설비</b> 지원사업</h3>
                    </div>
                </div>
                <div class="right_box">
                    <ul>
                        <li class="effect">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_1.svg') }}" alt="">지원 한도</i>
                            <p><b>100억</b>원</p>
                            <span>업체별 100억, <br/>사업장별 60억까지</span>
                        </li>
                        <li class="effect delay_1">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_3.svg') }}" alt="">태양광 생산 전력</i>
                            <p><b>자가소비</b></p>
                            <span>자가소비만 가능, <br/>전력판매 불가</span>
                        </li>
                        <li class="effect delay_2">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_2.svg') }}" alt="">지원 비율</i>
                            <p><b>70</b>%</p>
                            <span>중소 70%, 중견 50%, <br/>대기업 30%까지</span>
                        </li>
                        <li class="effect delay_3">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_4.svg') }}" alt="">동일 사업 지원 기간</i>
                            <p>최대&nbsp;<b>2</b>년</p>
                            <span>전년도 사업 완료 후 <br/>차년도 사업 신청</span>
                        </li>
                    </ul>
                    <p class="txt effect delay_4">※ 온실가스 배출권 할당대상 업체만 지원 가능합니다.<br/>※ 설비 구입·설계·설치공사·감리·시운전 자금으로 사용할 수 있습니다.</p>
                </div>
            </div>
        </div>


        <!-- 절차 -->
        <div class="section carbon_con04">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">탄소 중립 실현을 위한</span>
                    <h3 class="effect delay_1">탄소중립설비지원 절차</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_1.svg') }}" alt=""></i>신청서 제출, <br/>사전 검토</p>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_2.svg') }}" alt=""></i>평가·선정, <br/>협약 체결</p>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_3.svg') }}" alt=""></i>계약·착수 신고, <br/>보조금 신청</p>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_2.svg') }}" alt=""></i>착수 검토, <br/>보조금 지급</p>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_4.svg') }}" alt=""></i>설치 완료, <br/>보조금 신청</p>
                    </li>
                    <li class="effect delay_7">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/building_con04_2.svg') }}" alt=""></i>준공 확인, <br/>보조금 지급·정산</p>
                    </li>
                    <li class="effect delay_8">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/fin_con04_1.svg') }}" alt=""></i>사후 관리, <br/>현황 보고</p>
                    </li>                                        
                </ul>
                <div class="btn_box effect delay_9">
                    <button onclick="openInquire(4)">상담 신청하기</button>
                </div>
            </div>
        </div>


        <!-- 평가항목 -->
        <div class="section carbon_con05">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">탄소중립설비지원은 최고점순</span>
                    <h3 class="effect delay_1">주요 평가 항목</h3>
                </div>
                <div class="graph_box">
                    <div class="tit effect delay_2">7가지 <br/>핵심항목</div>
                    <div class="box step_1 effect delay_3">
                        <i>01</i>
                        <h5>사업 계획</h5>
                        <p>사업계획 <b>15점</b>, <br/>감축량 산출 <b>10점</b>, <br/>사후 관리 <b>5점</b></p>
                    </div>
                    <div class="box step_2 effect delay_4">
                        <i>02</i>
                        <h5>사업 효과</h5>
                        <p>온실가스 감축량 <br/>+ 감축효과 <b>50점</b></p>
                    </div>
                    <div class="box step_3 effect delay_5">
                        <i>03</i>
                        <h5>정책 효과</h5>
                        <p>기업 업종 <b>10점</b>, <br/>감축 설비 <b>10점</b></p>
                    </div>
                </div>
            </div>
        </div>


        <!-- 왜 솔라테크 -->
        <div class="section carbon_con06">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">왜 솔라테크 일까요?</span>
                    <h3 class="effect delay_1">탄소중립설비지원은 <br/><b>솔라테크</b></h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con06_1.svg') }}" alt="">
                        <h5>사전 준비</h5>
                        <p>사업계획 수립, 서류 작성 등 <br/><b>지원사업 완벽 준비</b></p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con06_3.svg') }}" alt="">
                        <h5>과학적 설계</h5>
                        <p><b>3D 시뮬레이션, 음영분석 </b>등으로 </br>온실가스 감축량·률 산정</p>
                    </li>
                    <li class="effect delay_5">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con06_2.svg') }}" alt="">
                        <h5>사후 관리</h5>
                        <p>태양광 전문가로 구성된 </br><b>운영관리팀</b></p>
                    </li>                    
                </ul>
            </div>
        </div>
        


        <!-- 포트폴리오 -->
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
                        <img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/business/carbon_con02_pc.jpg') }}" alt="">
                        <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/business/carbon_con02_fo.jpg') }}" alt="">
                        <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/business/carbon_con02_mo.jpg') }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit"><h5>천일제지</h5><strong>1,480kW</strong></div>
                                    <div class="info">
                                        <dl>
                                            <dt>지역</dt>
                                            <dd>전라북도 전주시</dd>
                                        </dl>
                                        <dl>
                                            <dt>형태</dt>
                                            <dd>지붕형</dd>
                                        </dl>
                                        <dl>
                                            <dt>준공일</dt>
                                            <dd>2024.01.15</dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="bot">
                                    <div class="btn">
	                                    <a href="https://blog.naver.com/solarteq/223340834233" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_blog.svg') }}" alt="">시공사례</a>
	                                    <a href="https://www.youtube.com/watch?v=fDBHnB9jV48" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_youtube.svg') }}" alt="">준공영상</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="swiper-slide">
						<img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/business/carbon_con01_pc.jpg') }}" alt="">
                        <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/business/carbon_con01_fo.jpg') }}" alt="">
                        <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/business/carbon_con01_mo.jpg') }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit"><h5>대동타일</h5><strong>1,600kW</strong></div>
                                    <div class="info">
                                        <dl>
                                            <dt>지역</dt>
                                            <dd>세종시 전의면</dd>
                                        </dl>
                                        <dl>
                                            <dt>형태</dt>
                                            <dd>지붕형</dd>
                                        </dl>
                                        <dl>
                                            <dt>준공일</dt>
                                            <dd>2022.12.14</dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="bot">
                                    <div class="btn">
	                                    <a href="https://blog.naver.com/solarteq/222962457004" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_blog.svg') }}" alt="">시공사례</a>
	                                    <a href="https://www.youtube.com/watch?v=VKiyCvmej4g" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_youtube.svg') }}" alt="">준공영상</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>                   
                </ul>
            </div>
        </div>
        @endif


        @if ($data->isEmpty() || count($data) >= 2)         
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
        <div class="section carbon_con07">
            <div class="sub_inner">
                <p class="effects delay_1">탄소중립설비지원은 <span><i>최고점순!!</i></span></p>
                <p class="effects delay_2">공고 전 사업계획, 감축량 산정 필수</p>
                <p class="effects delay_3">솔라테크와 바로 상담하세요</p>				
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
                $('.carbon_con01').addClass('on')
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