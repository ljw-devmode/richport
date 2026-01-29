@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
        <!-- 지붕임대 태양광 -->
        <div class="section solar_con01">
            <div class="sub_inner">
                <h4 class="effects">지붕임대 태양광</h4>
                <p class="effects delay_1">내 건물 지붕이 비어있다면 솔라테크를 통해</p>
                <p class="effects delay_2"><span><i>20년간 안정적인</i></span> 수익을 얻을 수 있습니다</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section solar_con02">
            <div class="sub_inner">
                <p class="effect">지붕임대 태양광 사업은<br/>비어있는 지붕에 태양광 발전소를 설치하고</p>
                <p class="effect"><span><i>20년간 임대료를 지급</i></span><br class="mo_ver"/> 하는 사업입니다</p>
            </div>
        </div>

        <!-- 혜택 -->
        <div class="section solar_con03">
            <div class="box">
                <div class="left_txt">
                    <div class="txt_box">
                        <i class="effect">PRIVILEGE</i>
                        <h3 class="effect delay_1"><span>최고의</span> <b>혜택을</b> 제공합니다</h3>
                    </div>
                </div>
                <div class="right_box">
                    <ul>
                        <li class="effect">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_1.svg') }}" alt="">투자비용</i>
                            <p><b>0</b>원</p>
                            <span>초기 투자 없이 시작</span>
                        </li>
                        <li class="effect delay_1">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_2.svg') }}" alt="">안정된 수익</i>
                            <p><b>20</b>년</p>
                            <span>장기 보장 계약</span>
                        </li>
                        <li class="effect delay_2">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_3.svg') }}" alt="">100kW 기준</i>
                            <p><b>8천</b>만원</p>
                            <span>20년 총 예상 수익</span>
                        </li>
                        <li class="effect delay_3">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_4.svg') }}" alt="">지붕관리/수리</i>
                            <p><b>무상</b></p>
                            <span>유지보수 걱정 없음</span>
                        </li>
                    </ul>
                    <p class="txt effect delay_4">※ 수익은 20년 총액을 기준으로 표시하였습니다.<br/>※ 발전 용량에 따라 수익은 달라질 수 있습니다.</p>
                </div>
            </div>
        </div>

        <!-- 내 건물 지붕은 -->
        <div class="section solar_con04">
            <div class="calculator_box">
                <div class="img_box">
                    <div class="img_slide">
                        <ul class="swiper-wrapper">
                            <li class="swiper-slide">
                                <img class="pc_img" src="{{ asset('user_assets/img/sub/business/지붕임대_수익계산기_PC.png') }}" alt="">
                                <img class="fold_img" src="{{ asset('user_assets/img/sub/business/지붕임대_수익계산기_F.png') }}" alt="">
                                <img class="mo_img" src="{{ asset('user_assets/img/sub/business/지붕임대_수익계산기_M.png') }}" alt="">
                                <span>태신인팩 - 300kW</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="right_box type02">
                    <div class="cal_box">
                        <div class="top">
                            <h4>건물 지붕 임대 수익은</h4>
                            <p>평수를 입력하고 '수익 계산하기' 를 눌러보세요</p>
                            <div class="input_box">
                                <input type="text">
                                <b>평</b>
                            </div>
                            <button onclick="calculatorBtnSolar(this)"><span>계산하기</span><i><img src="{{ asset('user_assets/img/icon/loading.svg') }}" alt=""></i></button>
                        </div>
                        <div class="bot">
                            <h5>20년 총 예상 수익은</h5>
                            <b><span class="count" data-num="23000000">0</span>만원</b>
                            <p>• 발전량 : <strong><span class="count" data-num="500">0</span> kW</strong></p>
                            <p>• 임대료 : <strong><span class="count" data-num="2000">0</span>만원/년</strong></p>
                            <p class="txt">※ 지붕 면적과 태양광의 설치 면적은 다를 수 있으며, 연간 수익은 발전 용량에 따라 달라질 수 있습니다.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- why solarteq -->
        <div class="section solar_con05">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">WHY SOLARTEQ</span>
                    <h3 class="effect delay_1">솔라테크는<br class="mo_ver"/> 믿을 수 있습니다</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <div class="txt_box">
                            <i>01</i>
                            <p><b>높은</b> 임대료</p>
                            <span>업계 최고 수준의 임대료를 드립니다</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/sub/business/solar_con05_1.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <div class="txt_box">
                            <i>02</i>
                            <p><b>안정된</b> 기업</p>
                            <span>태양광 공장을 보유한 규모있는 기업</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/sub/business/solar_con05_2.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <div class="txt_box">
                            <i>03</i>
                            <p><b>신뢰</b>받는 기업</p>
                            <span>포스코, CJ 등 대기업과 함께하는 솔라테크</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/sub/business/solar_con05_3.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <div class="txt_box">
                            <i>04</i>
                            <p><b>인증</b>된 기술력</p>
                            <span>특허 7건, 디자인 6건으로 인정받은 기업</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/sub/business/solar_con05_4.png') }}" alt=""></div>
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
	                                    <div class="tit"><h5>{{$item->station_name}}</h5><strong>{{$item->price}}</strong><span>(20년 총 수익)</span></div>
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

        <!-- procedure -->
        <div class="section solar_con06">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">STEP-BY-STEP PROCESS</span>
                    <h3 class="effect delay_1">지붕임대 태양광 <br class="mo_ver"/>진행 절차</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/solar_con06_icon_1.svg') }}" alt=""></i>상담 접수</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/solar_con06_arrow.svg') }}" alt="">24시간 이내</div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/solar_con06_icon_2.svg') }}" alt=""></i>안정성, 수익성<br/> 검토 후 계약</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/solar_con06_arrow.svg') }}" alt="">일주일 이내</div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/solar_con06_icon_3.svg') }}" alt=""></i>튼튼하고<br/> 안전한 시공</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/solar_con06_arrow.svg') }}" alt="">약 3개월</div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/solar_con06_icon_4.svg') }}" alt=""></i>20년간<br/> 임대료 지급</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_6">
                    <button onclick="openInquire(1)">상담 신청하기</button>
                    <p>✱ 각 단계별 소요시간은 지붕크기에 따라 달라질 수 있습니다.</p>
                </div>
            </div>
        </div>

        <!-- q&a -->
        <div class="section qna_con solar_qna">
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
                        <dt onclick="toggleQna(this)">임대료 선지급이 가능한가요?</dt>
                        <dd><div class="answer">임대료는 설비용량을 기준으로 산정됩니다.<br/>최초 임대료는 상업운전개시일로부터 해당 년도 말까지 일할 계산하여 납부합니다.<br/>정기 임대료는 매년 1월 1년치를 선납합니다.</div></dd>
                    </dl>
                    <dl>
                        <dt onclick="toggleQna(this)">임대료 산정 기준과 지급 시기는 어떻게 되나요?</dt>
                        <dd><div class="answer">임대료 선지급은 가능합니다.<br/>단, 선납이행보증보험 발급비는 임대인이 부담해주셔야 합니다.</div></dd>
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
                $('.solar_con01').addClass('on')
            },100)
        });

         // solar_con04
        var calSlide = new Swiper(".solar_con04 .calculator_box .img_slide", {
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

     	// 지붕임대 수익계산기
        const calculatorBtnSolar = (btn) => {
            const $rightBox = $(btn).closest('.right_box');
            const $calBox   = $rightBox.find('.cal_box');
            const $input    = $calBox.find('.input_box input');

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

            // 3) 각 항목 계산 (최종은 정수로 반올림)
            // 1. 건물 지붕 수입: base * 4 * 20
            const income = Math.round(base * 4 * 20); // 만원 단위 총액

            // 2. 발전용량: base
            const capacity = Math.round(base);        // kW / 년

            // 3. 임대료: base * 4
            const rent = Math.round(base * 4);        // 만원

            // 4) .count 요소에 값 세팅 (0: 수입, 1: 발전량, 2: 임대료)
            const $counts = $calBox.find('.count');
            $counts.eq(0).data('num', income).attr('data-num', income);
            $counts.eq(1).data('num', capacity).attr('data-num', capacity);
            $counts.eq(2).data('num', rent).attr('data-num', rent);

            // 5) 기존 애니메이션 연출 유지
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