@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
		<!-- 건물지원사업 -->		
        <div class="section finance_con01">
            <div class="sub_inner">
                <p class="effects">정부가 태양광 설비 비용의</p>
                <p class="effects delay_1"><span><i>최대 80%를 저금리 융자</i></span>로 </p>
                <p class="effects delay_2">지원하는 것을 알고 계신가요?</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 사업설명 -->
        <div class="section finance_con02">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">정부의 금융지원사업으로</span>
                    <h3 class="effect delay_1">설비비 부담을 <b>80% </b>감소</h3>
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
                        <h5>80% 지원</h5>
                        <p><b>저 금리 융자,</b><br/> 장기 분할 상환</p>
                    </li>
                    <li class="noneborder effect delay_5">
                        <span><img src="{{ asset('user_assets/img/icon/plus.svg') }}" alt=""></span>
                    </li>					
                    <li class="effect delay_6">
                        <img src="{{ asset('user_assets/img/sub/business/fin_con02_3.svg') }}" alt="">
                        <h5>20% 부담</h5>
                        <p>금융지원사업으로 <br/><b>비용 부담 ↓</b></p>
                    </li>
                </ul>
            </div>
        </div>


        <!-- 혜택 -->
        <div class="section finance_con03">
            <div class="box">
                <div class="left_txt">
                    <div class="txt_box">
                        <span class="effect"><i>큰 금액, 낮은 이자, 장기 상환</i></span>
                        <h3 class="effect delay_1">금융지원사업<b> 주요 혜택</b></h3>
                    </div>
                </div>
                <div class="right_box">
                    <ul>
                        <li class="effect">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_1.svg') }}" alt="">지원 한도</i>
                            <p><b>300억</b>원</p>
                            <span>최소 3,000만원 부터</span>
                        </li>
                        <li class="effect delay_1">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_4.svg') }}" alt="">지원 비율</i>
                            <p><b>80</b>%</p>
                            <span>중소기업 80% 이내, <br/>중견기업 65% 이내</span>
                        </li>
                        <li class="effect delay_2">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_3.svg') }}" alt="">낮은 금리</i>
                            <p><b>1.75</b>%</p>
                            <span>2025년 기준, <br/>분기별 변동금리</span>
                        </li>
                        <li class="effect delay_3">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_2.svg') }}" alt="">상환 기간</i>
                            <p><b>15</b>년</p>
                            <span>5년 거치, <br/>10년 분할 상환</span>
                        </li>
                    </ul>
                    <p class="txt effect delay_4">※ 지원금은 부가가치세, 토지 구입비, 인허가 비용 등에 사용할 수 없습니다.<br/>※ 이자율은 매분기 신재생에너지센터 누리집에서 확인할 수 있습니다.</p>
                </div>
            </div>
        </div>


        <!-- 절차 -->
        <div class="section finance_con04">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">선착순 신청, 조기마감으로 사전 준비 필수!!!</span>
                    <h3 class="effect delay_1">금융지원사업 지원 절차</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_1.svg') }}" alt=""></i>솔라테크와<br/>사전 계약</p>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_2.svg') }}" alt=""></i>사업 신청 <br/>및 접수</p>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_3.svg') }}" alt=""></i>기술 및 <br/>사업성 평가</p>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_2.svg') }}" alt=""></i>추천서 <br/>발급</p>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/building_con04_2.svg') }}" alt=""></i>금융 기관 <br/>대출 심사</p>
                    </li>
                    <li class="effect delay_7">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_4.svg') }}" alt=""></i>자금 인출 <br/>및 시공</p>
                    </li>
                    <li class="effect delay_8">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/fin_con04_1.svg') }}" alt=""></i>준공 및 <br/>운영</p>
                    </li>                                        
                </ul>
                <div class="btn_box effect delay_9">
                    <button onclick="openInquire(4)">상담 신청하기</button>
                </div>
            </div>
        </div>


        <!-- 평가항목 -->
        <div class="section finance_con05">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">금융지원사업 사전 체크</span>
                    <h3 class="effect delay_1">신청 자격 및 필수 요건</h3>
                </div>
                <div class="graph_box">
                    <div class="tit effect delay_2">사전 체크 <br/>필수!!!</div>
                    <div class="box step_1 effect delay_3">
                        <i>01</i>
                        <h5>신청 자격</h5>
                        <p><b>중소·중견</b>·대기업, <br/>공공기관</b></p>
                    </div>
                    <div class="box step_2 effect delay_4">
                        <i>02</i>
                        <h5>필수 요건</h5>
                        <p><b>시공업체 사전 계약</b> 및 <br/> <b>공사 개시일</b> 이후 신청 가능</b></p>
                    </div>
                    <div class="box step_3 effect delay_5">
                        <i>03</i>
                        <h5>불가 사항</h5>
                        <p>유사 사업 중복 참여, <br/> 참여 제한기업 등</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- 왜 솔라테크 -->
        <div class="section finance_con06">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">왜 솔라테크 일까요?</span>
                    <h3 class="effect delay_1">금융지원사업은 <b>솔라테크</b></h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con06_1.svg') }}" alt="">
                        <h5>원스톱 서비스</h5>
                        <p>인허가·시공·사후관리 및 <br/><b>지원사업 서류 작성</b></p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con06_3.svg') }}" alt="">
                        <h5>자체 구조물 제작</h5>
                        <p><b>태양광 설비비 절감, </b></br> 빠른 착공, 빠른 완공</b></p>
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
                        <img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/business/fin_ref02_pc.jpg') }}" alt="">
                        <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/business/fin_ref02_fo.jpg') }}" alt="">
                        <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/business/fin_ref02_mo.jpg') }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit"><h5>신화인텍</h5><strong>300kW</strong></div>
                                    <div class="info">
                                        <dl>
                                            <dt>지역</dt>
                                            <dd>울산시 북구</dd>
                                        </dl>
                                        <dl>
                                            <dt>형태</dt>
                                            <dd>지붕형</dd>
                                        </dl>
                                        <dl>
                                            <dt>준공일</dt>
                                            <dd>2025.08.22</dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="bot">
                                    <div class="btn">
	                                    <a href="https://blog.naver.com/solarteq/224090223289" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_blog.svg') }}" alt="">시공사례</a>
										<!--
	                                    <a href="" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_youtube.svg') }}" alt="">준공영상</a>
                                    	-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="swiper-slide">
                        <img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/business/fin_ref01_pc.jpg') }}" alt="">
                        <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/business/fin_ref01_fo.jpg') }}" alt="">
                        <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/business/fin_ref01_mo.jpg') }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit"><h5>세일 MTC</h5><strong>384kW</strong></div>
                                    <div class="info">
                                        <dl>
                                            <dt>지역</dt>
                                            <dd>울산시 북구</dd>
                                        </dl>
                                        <dl>
                                            <dt>형태</dt>
                                            <dd>지붕형</dd>
                                        </dl>
                                        <dl>
                                            <dt>준공일</dt>
                                            <dd>2025.08.25</dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="bot">
                                    <div class="btn">
	                                    <a href="https://blog.naver.com/solarteq/224051103215" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_blog.svg') }}" alt="">시공사례</a>
										<!--
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
        <div class="section finance_con07">
            <div class="sub_inner">
                <p class="effects delay_1">건물지원사업은 <span><i>선착순·조기마감</i></span></p>
                <p class="effects delay_2">지금부터 준비하지 않으면 늦습니다</p>
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
                $('.finance_con01').addClass('on')
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