@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
		<!-- 건물지원사업 -->		
        <div class="section house_con01">
            <div class="sub_inner">
                <p class="effects">우리집을 스마트한</p>
				<p class="effects delay_1"><span><i>태양광 발전소</i></span>로 바꾸는</p>				
                <p class="effects delay_2">주택지원사업 핵심요약!!! </p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 사업설명 -->
        <div class="section house_con02">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">정부의 주택지원사업으로</span>
                    <h3 class="effect delay_1">태양광 설치 비용을 <br/> <b>최대 250만원 까지 </b>지원</h3>
                </div>
                <ul>
                    <!--
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con08_1.svg') }}" alt="">
                        <h5>100% 부담</h5>
                        <p>태양광 발전소 <br/> <b>설치 비용 부담 ↑</b></p>
                    </li>
                    <li class="noneborder effect delay_3">
                        <span><img class="rotate" src="{{ asset('user_assets/img/sub/business/solar_con06_arrow.svg') }}" alt=""></span>
                    </li>
                    -->
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con08_4.svg') }}" alt="">
                        <h5>50% 부담</h5>
                        <p>설치비<b> 부담↓ </b><br/>전기료<b> 부담↓</b></p>
                    </li>
                    <li class="noneborder effect delay_5">
                        <span><img src="{{ asset('user_assets/img/icon/plus.svg') }}" alt=""></span>
                    </li>					
                    <li class="effect delay_6">
                        <img src="{{ asset('user_assets/img/sub/business/fin_con02_3.svg') }}" alt="">
                        <h5>50% 보조금</h5>
                        <p>최대 250만원 <br/><b>정부 지원금</b></p>
                    </li>
                </ul>
            </div>
        </div>


        <!-- 혜택 -->
        <div class="section house_con03">
            <div class="box">
                <div class="left_txt">
                    <div class="txt_box">
                        <span class="effect"><i>우리집을 태양광 발전소로 바꾸는</i></span>
                        <h3 class="effect delay_1">주택지원사업<b> 장점</b></h3>
                    </div>
                </div>
                <div class="right_box">
                    <ul>
                        <li class="effect">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_1.svg') }}" alt="">지원 한도</i>
                            <p><b>250</b>만원</p>
                            <span>설치비의 최대 50% 까지</span>
                        </li>
                        <li class="effect delay_1">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_4.svg') }}" alt="">발전 용량</i>
                            <p><b>3</b>kW</p>
                            <span>일평균 3.5시간 발전</span>
                        </li>
                        <li class="effect delay_2">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_3.svg') }}" alt="">전기료 절감</i>
                            <p><b>약 7</b>만원</p>
                            <span>월평균 6~8만원 절감</span>
                        </li>
                        <li class="effect delay_3">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_2.svg') }}" alt="">투자비 회수</i>
                            <p><b>3</b>년</p>
                            <span>17년간 수익 발생</span>
                        </li>
                    </ul>
                    <p class="txt effect delay_4">※ 발전 시간은 날씨, 계절에 따라 달라질 수 있습니다.<br/>※ 전기 사용량과 발전량에 따라 절감액은 가감될 수 있습니다.</p>
                </div>
            </div>
        </div>


        <!-- 절차 -->
        <div class="section house_con04">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">상담 부터 지원금 신청, 시공까지~</span>
                    <h3 class="effect delay_1">주택지원사업 지원 절차</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_1.svg') }}" alt=""></i>솔라테크 <br/>상담 신청</p>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_2.svg') }}" alt=""></i>현장 답사 및 <br/>발전량 산정</p>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_2.svg') }}" alt=""></i>주택지원사업 <br/>지원금 신청</p>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_4.svg') }}" alt=""></i>솔라테크와 계약 <br/>및 태양광 설치</p>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/fin_con04_1.svg') }}" alt=""></i>태양광 발전 및 <br/>전기료 절감</p>
                    </li>                                        
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(4)">상담 신청하기</button>
                </div>
            </div>
        </div>


        <!-- 평가항목 -->
        <div class="section house_con05">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">주택지원사업 사전 체크</span>
                    <h3 class="effect delay_1">신청 자격 및 필수 요건</h3>
                </div>
                <div class="graph_box">
                    <div class="tit effect delay_2">사전 체크 <br/>필수!!!</div>
                    <div class="box step_1 effect delay_3">
                        <i>01</i>
                        <h5>신청 자격</h5>
                        <p>단독·공동 주택 <b>실 소유자</b>, <br/><b>소유자 명의</b> 계약</p>
                    </div>
                    <div class="box step_2 effect delay_4">
                        <i>02</i>
                        <h5>시공 요건</h5>
                        <p><b>공고 이후</b> 시공업체 계약, <br/><b>기한 내 설치</b> 및 확인</p>
                    </div>
                    <div class="box step_3 effect delay_5">
                        <i>03</i>
                        <h5>불가 사항</h5>
                        <p>유사 사업 중복 참여, <br/>무허가·공공·분쟁 건물 등</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- 왜 솔라테크 -->
        <div class="section house_con06">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">왜 솔라테크 일까요?</span>
                    <h3 class="effect delay_1">주택지원사업은 <b>솔라테크</b></h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con06_1.svg') }}" alt="">
                        <h5>원스톱 서비스</h5>
                        <p>인허가·시공·사후관리, <br/><b>지원사업 서류 작성</b></p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con06_3.svg') }}" alt="">
                        <h5>자체 구조물 제작</h5>
                        <p><b>태양광 설비비 절감, </b></br>빠른 착공, 빠른 완공</b></p>
                    </li>
                    <li class="effect delay_5">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con06_2.svg') }}" alt="">
                        <h5>인정받은 기술력</h5>
                        <p>포스코,CJ 등이 인정, </br>에너지공단 <b>참여기업</b></p>
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
                        <img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/business/house_con01_pc.jpg') }}" alt="">
                        <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/business/house_con01_fo.jpg') }}" alt="">
                        <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/business/house_con01_mo.jpg') }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit"><h5>주택 태양광</h5><strong>3kW</strong></div>
                                    <div class="info">
                                        <dl>
                                            <dt>지역</dt>
                                            <dd>인천시 중구</dd>
                                        </dl>
                                        <dl>
                                            <dt>형태</dt>
                                            <dd>지붕형</dd>
                                        </dl>
                                        <dl>
                                            <dt>준공일</dt>
                                            <dd>2024.08.11</dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="bot">
                                    <div class="btn">
										<!--
										<a href="https://blog.naver.com/solarteq/224090223289" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_blog.svg') }}" alt="">시공사례</a>
	                                    <a href="" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_youtube.svg') }}" alt="">준공영상</a>
                                    	-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="swiper-slide">
                        <img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/business/house_con02_pc.jpg') }}" alt="">
                        <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/business/house_con02_fo.jpg') }}" alt="">
                        <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/business/house_con02_mo.jpg') }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit"><h5>주택 태양광</h5><strong>3kW</strong></div>
                                    <div class="info">
                                        <dl>
                                            <dt>지역</dt>
                                            <dd>경기도 포천시</dd>
                                        </dl>
                                        <dl>
                                            <dt>형태</dt>
                                            <dd>지붕형</dd>
                                        </dl>
                                        <dl>
                                            <dt>준공일</dt>
                                            <dd>2024.08.18</dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="bot">
                                    <div class="btn">
										<!--
	                                    <a href="https://blog.naver.com/solarteq/224051103215" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_blog.svg') }}" alt="">시공사례</a>
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



        <!-- 포트폴리오 -->
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
                            <img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/business/house_con01_pc.jpg') }}" alt="">
                            <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/business/house_con01_fo.jpg') }}" alt="">
                            <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/business/house_con01_mo.jpg') }}" alt="">
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
        <div class="section house_con07">
            <div class="sub_inner">
                <p class="effects delay_1">주택지원사업은 <span><i>예산 소진시 마감!!</i></span></p>
                <p class="effects delay_2">공고 후 준비는 늦습니다</p>
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
                $('.house_con01').addClass('on')
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