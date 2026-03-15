@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
        <!-- 지붕임대 태양광 -->
        <div class="section rps_con01">
            <div class="sub_inner">
                <h4 class="effects">RPS 태양광 발전사업</h4>
                <p class="effects delay_1">생산한 전기를 직접 소비하지 않고</p>
                <p class="effects delay_2">판매만 하는 형태의 <span><i>수익형 발전 사업</i></span> 입니다</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 태양광 발전 사업 -->
        <div class="section rps_con02">
            <div class="sub_inner">
                <div class="tit">
                    <h3 class="effect">RPS (전력판매)</h3>
                    <p class="effect delay_1"><b>전력과 인증서 판매</b>를 통한 수익 창출 구조</p>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <div class="txt">
                            <i>01</i>
                            <p>신재생에너지 <b>전력 판매</b><span>SMP 판매</span></p>
                        </div>
                        <div class="path">
                            <div class="box"><span>발전</span>태양광 발전소</div>
                            <div class="box"><span>판매처</span>한국전력</div>
                        </div>
                    </li>
                    <li class="effect delay_3">
                        <div class="txt">
                            <i>02</i>
                            <p>신재생에너지 <b>공급 인증서 판매</b><span>REC 판매</span></p>
                        </div>
                        <div class="path">
                            <div class="box"><span>발전</span>REC 인증서</div>
                            <div class="box"><span>판매처</span>발전사업자</div>
                        </div>
                    </li>
                </ul>
                <div class="result_box effect delay_4">
                    <span>수익 실현</span>
                </div>
            </div>
        </div>

        <!-- 지속가능한 경영을 위한 선택 -->
        <div class="section rps_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">SUSTAINABLE MANAGEMENT</span>
                    <h3 class="effect delay_1">지속가능한 <br class="mo_ver"/>경영을 위한 선택</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/business/rps_con03_icon_1.svg') }}" alt="">
                        <h5><b>수익성</b></h5>
                        <p>SMP + REC<br/> <b>20년간 안정된 이중 수익 구조</b></p>
                    </li>
                    <li class="effect delay_3">
                        <img src="{{ asset('user_assets/img/sub/business/rps_con03_icon_2.svg') }}" alt="">
                        <h5><b>안정성</b></h5>
                        <p><b>RPS 제도 기반의 </b><br/>발전사업, 다양한 지원 정책</p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/business/rps_con03_icon_3.svg') }}" alt="">
                        <h5><b>ESG</b></h5>
                        <p>지속가능경영, 온실가스 감축, <br/><b>친환경 에너지 전환</b></p>
                    </li>
                    <li class="effect delay_5">
                        <img src="{{ asset('user_assets/img/sub/business/rps_con03_icon_4.svg') }}" alt="">
                        <h5><b>성장성</b></h5>
                        <p>재생에너지 확대<br/> <b>2030년까지 30% 이상</b></p>
                    </li>
                </ul>
                <div class="txt effect delay_6"><b>수익 안정과 성장</b>을 동시에 실현하는<br/>차세대 에너지 투자 솔루션</div>
                <div class="info effect delay_7">
                    *RPS는 신에너지 및 재생에너지 개발, 이용, 보급 촉진법을 기준으로 진행하는 사업입니다.<br/>
                    *지역 일자리 창출 / 세수 증가 등 지역 경제에서 긍정적 효과가 있습니다.
                </div>
            </div>
        </div>

        <!-- 건물/토지 계산 -->
        <div class="section rps_con04">
            <div class="calculator_box">
                <div class="img_box">
                    <div class="img_slide">
                        <ul class="swiper-wrapper">
                            <li class="swiper-slide">
                                <img class="pc_img" src="{{ asset('user_assets/img/sub/business/사업소개_RPS_수익계산기_PC.png') }}" alt="">
                                <img class="fold_img" src="{{ asset('user_assets/img/sub/business/사업소개_RPS_수익계산기_F.png') }}" alt="">
                                <img class="mo_img" src="{{ asset('user_assets/img/sub/business/사업소개_RPS_수익계산기_M.png') }}" alt="">
                                <span>동창알엔에스 - 1,533kW</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="right_box type02">
                    <div class="cal_box">
                        <div class="top">
                            <h4>내 발전소 발전수익은</h4>
                            <p>평수를 입력하고 '수익 계산하기' 를 눌러보세요</p>
                            <div class="input_area">
                                <div class="radio_box">
                                    <input type="radio" id="cal_1" name="cal" checked><label for="cal_1">건물</label>
                                    <input type="radio" id="cal_2" name="cal"><label for="cal_2">토지</label>
                                </div>
                                <div class="input_box">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                    <b>평</b>
                                </div>
                            </div>
                            <button onclick="calculatorBtnRps(this)"><span>수익 계산하기</span><i><img src="{{ asset('user_assets/img/icon/loading.svg') }}" alt=""></i></button>
                        </div>
                        <div class="bot">
                            <h5>20년 총 예상 수익은</h5>
                            <b><span class="count">0</span>만원</b>
                            <p>• 연간 발전량 : <strong><span class="count">0</span> kWh/년</strong></p>
                            <p>• 한국전력(SMP) : <strong><span class="count">0</span> 원/년</strong></p>
                            <p>• 발전사업자(REC) : <strong><span class="count">0</span> 원/년</strong></p>
                            <p class="txt">※ 태양광의 설치면적은 다를 수 있으며, 연간 수익은 발전 용량에 따라 달라질 수 있습니다.<br/>※ 한국전력/발전사업가 단가는 상업 운영 시작일 기준으로 수익은 달라질 수 있습니다.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 왜 선택해야할까요 -->
        <div class="section rps_con05">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">WHY SOLARTEQ</span>
                    <h3 class="effect delay_1">왜 솔라테크를<br class="mo_ver"/> 선택해야 할까요?</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <h4><b>많은</b> 시공사례</h4>
                        <p>전국 <b>600건 이상</b>, 784MW 이상,<br/> 탄소 감축 230만그루 이상 실적</p>
                    </li>
                    <li class="effect delay_3">
                        <h4><b>검증된</b> 기술력</h4>
                        <p>9건의 특허로 <b>인정받은 기술력</b>과<br/> 포스코의 포스맥 구조물</p>
                    </li>
                    <li class="effect delay_4">
                        <h4><b>신뢰받는</b> 기업</h4>
                        <p>포스코, CJ가 선택한<br/><b> 믿을 수 있는 파트너</b></p>
                    </li>
                </ul>
            </div>
        </div>

        <!-- portfolio -->
        @if (!$data->isEmpty())
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
	                                    <div class="tit"><h5>{{$item->station_name}}</h5><strong>{{number_format($item->volume)}}kW</strong></div>
	                                    <div class="info">
	                                        <dl>
	                                            <dt>지역</dt>
	                                            <dd>{{ $item->regions->name_kr ?? '' }} {{ $item->cities->name_kr ?? '' }}</dd>
	                                        </dl>
	                                        <dl>
	                                            <dt>형태</dt>
	                                            <dd>{{ $item->formTypes->subject ?? '' }}</dd>
	                                        </dl>
	                                        @if($item->completion_date)
	                                        <dl>
	                                            <dt>준공일</dt>
	                                            <dd>{{ $item->completion_date ?? '' }}</dd>
	                                        </dl>
	                                        @endif
	                                    </div>
	                                </div>
	                                <div class="bot">
	                                    <div class="btn">
	                                    	@if($item->blog)
	                                        <a href="{{ $item->blog }}" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_blog.svg') }}" alt="">시공사례</a>
	                                        @endif
											@if($item->url)	                                        
											<a href="{{ $item->url }}" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_youtube.svg') }}" alt="">준공영상</a>
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

        <!-- 절차 -->
        <div class="section rps_con06">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">STEP-BY-STEP PROCESS</span>
                    <h3 class="effect delay_1">RPS(전력판매) <br class="mo_ver"/>진행 절차</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_1.svg') }}" alt=""></i>상담 및<br/> 수익성 검토</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/rps_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_2.svg') }}" alt=""></i>지자체 허가,<br/> 한국전력 계약</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/rps_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_3.svg') }}" alt=""></i>꼼꼼한 설계<br/>&nbsp;</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/rps_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_4.svg') }}" alt=""></i>튼튼하고<br/> 안전한 시공</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/rps_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_5.svg') }}" alt=""></i>전력판매로<br/> 수익 실현</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(3)">상담 신청하기</button>
                </div>
            </div>
        </div>

        <!-- QnA -->
        <div class="section qna_con rps_qna">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">Q&A</span>
                    <h3 class="effect delay_1">무엇이 궁금하신가요?</h3>
                </div>
                <div class="qna_list effect delay_2">
                    <dl>
                        <dt onclick="toggleQna(this)">투자비용은 정말 0원인가요?</dt>
                        <dd><div class="answer">태양광 발전소 설치를 위한 투자비용은 모두 솔라테크에서 부담합니다.</div></dd>
                    </dl>
                    <dl>
                        <dt onclick="toggleQna(this)">지붕 보수비용은 얼마인가요?</dt>
                        <dd><div class="answer">태양광 발전소 설치 전 지붕에 대한 보수, 방수 시공을 먼저 진행하며,<br/>보수, 방수 시송의 비용은 솔라테크에서 부담합니다.</div></dd>
                    </dl>
                    <dl>
                        <dt onclick="toggleQna(this)">전기료 단가는 20년간 고정인가요?</dt>
                        <dd><div class="answer">20년간 고정된 RE100 리스 구독료로 태양광 발전소에서 생산된 전기를 공급받으실 수 있습니다.</div></dd>
                    </dl>
                </div>
                <div class="btn_box effect delay_3">
                    <a href="/business/qna">더보기</a>
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
            normalScrollElements: '.in_scroll'
        });
        $(window).on('resize', function() {
            $.fn.fullpage.reBuild();
        });

        $(window).on('load', function(){
            setTimeout(function(){
                $('.rps_con01').addClass('on')
            },100)
        });

         // solar_con04
        var calSlide = new Swiper(".rps_con04 .calculator_box .img_slide", {
            effect:'fade',
            speed:1000,
            observeParents:true,
            observeSlideChildren:true,
            loop:true,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,  
            },
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

        const calculatorBtnRps = (btn) => {
            const $rightBox = $(btn).closest('.right_box');
            const $calBox   = $rightBox.find('.cal_box');
            const $input    = $calBox.find('.input_box input');
            const $radio    = $calBox.find('input[name="cal"]:checked');

            // 1) 입력값 (콤마 제거 후 숫자 변환)
            let val   = $input.val().replace(/,/g, '').trim();
            let pyung = parseFloat(val);

            if (isNaN(pyung) || pyung <= 0) {
                alert('평수를 올바르게 입력해주세요.');
                $input.focus();
                return;
            }

            // 2) 기본값: (입력평 / 3, 소숫점 2자리 반올림)
            let base = Math.round((pyung / 3) * 100) / 100; // 소수 둘째 자리 반올림

            // 3) 건물/토지 구분
            const isBuilding = ($radio.attr('id') === 'cal_1'); // cal_1: 건물, cal_2: 토지

            // 공통: kW 계산시 사용하는 배수
            const kwFactor = 3.5 * 365;

            // 3-1) 한국전력(SMP) 단가 (건물/토지 동일)
            const smpPerUnit = kwFactor * 119.13;

            // 3-2) 발전사업자(REC) 단가
            const recPerUnit = isBuilding
                ? kwFactor * 107.704   // 건물
                : kwFactor * 71.803;   // 토지

            // 4) 각 항목 계산 (최종은 정수로 반올림)
            // 2. 발전용량 : (입력평 / 3)
            const capacity = Math.round(base * kwFactor);                 // kW / 년

            // 3. 한국전력 : (입력평 / 3) * 3.5 * 365 * 119.13
            const smp = Math.round(base * smpPerUnit);

            // 4. 발전사업자 : (입력평 / 3) * 3.5 * 365 * (토지: 71.803, 건물: 107.704)
            const rec = Math.round(base * recPerUnit);

            // 1. 총 수익 : 3번 + 4번
            const totalWon = (smp + rec) * 20;
            total = Math.round(totalWon / 10000);

            // 5) .count 요소에 값 세팅 (순서대로)
            // 0: 총 수익, 1: 연간 발전량, 2: SMP, 3: REC
            const $counts = $calBox.find('.count');
            $counts.eq(0).data('num', total).attr('data-num', total);
            $counts.eq(1).data('num', capacity).attr('data-num', capacity);
            $counts.eq(2).data('num', smp).attr('data-num', smp);
            $counts.eq(3).data('num', rec).attr('data-num', rec);

            // 6) 기존 애니메이션 연출 유지
            $(btn).addClass('active');
            $rightBox.removeClass('active');
            $(btn).parents('.top').siblings('.bot').hide();

            setTimeout(function () {
                $rightBox.addClass('active');
                $(btn).parents('.top').siblings('.bot').fadeIn();
                $(btn).removeClass('active');

                // 숫자 애니메이션
                $counts.each(function () {
                    let num = $(this).data('num');
                    animateNumber($(this), num, 5000);
                });
            }, 1500);
        };

        function openInquire(val) {

            $('input[name="types_idx"]').prop('checked', false);
            $('input[name="types_idx"][value="' + val + '"]').prop('checked', true);

            modalOpen('inquiry-modal');
        }
    </script>
</body>
@include('user.ko.footer._gtm')
</html>