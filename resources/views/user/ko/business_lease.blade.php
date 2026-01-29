@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
        <!-- 리스 -->
        <div class="section lease_con01">
            <div class="sub_inner">
                <b class="effects">RE100 LEASE</b>
                <h4 class="effects delay_1">RE100 리스</h4>
                <p class="effects delay_2">신재생에너지를 <span><i>20년간 리스 형태</i></span>로</p>
                <p class="effects delay_3">이용할 수 있는 사업입니다</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 리스 구독 사업 -->
        <div class="section lease_con02">
            <div class="sub_inner">
                <div class="tit">
                    <h4>고정 단가로 <b>20년간<br class="mo_ver"/> 전기를 구독</b>하는 사업</h4>
                    <p>전기료 절감·탄소 감축·RE100 달성을 <b>동시에 실현하는 모델</b></p>
                </div>
                <div class="top"><p>건물 지붕에 태양광 설치</p></div>
                <ul>
                    <li>
                        <i>01</i>
                        <div class="path">
                            <div>
                                <b>신재생 에너지</b>
                                <p>지속 가능한<br/>전력 인프라 구축</p>
                            </div>
                            <div class="arrow">
                                <img src="{{ asset('user_assets/img/sub/business/lease_con02_arrow.svg') }}" alt="">
                                <span>20년 리스,<br/> 고정 단가</span>
                            </div>
                            <div>
                                <b>전기료 절감</b>
                                <p>20년간 안정적<br/>비용 관리</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <i>02</i>
                        <div class="path">
                            <div>
                                <b>탄소 감축 실적</b>
                                <p>연간 CO₂<br/>절감 효과 발생</p>
                            </div>
                            <div class="arrow">
                                <img src="{{ asset('user_assets/img/sub/business/lease_con02_arrow.svg') }}" alt="">
                                <span>ESG,<br/> 탄소국경세</span>
                            </div>
                            <div>
                                <b>RE100 달성</b>
                                <p>기업 ESG 평가<br/>직접 기여</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- 리스 전기료 절감 -->
        <div class="section lease_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">PRIVILEGE</span>
                    <h3 class="effect delay_1">전기료 절감과 RE100 달성</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p>전기료</p>
                        <h5><b class="blue">20%</b><img src="{{ asset('user_assets/img/icon/down_arrow.svg') }}" alt=""></h5>
                        <span>매년 상승하는 한국전력 전기료 대비<br/> 약 20% 저렴한 단가로 전력 공급</span>
                    </li>
                    <li class="effect delay_3">
                        <p>RE100</p>
                        <h5><b>달성</b></h5>
                        <span>신재생에너지 사용으로<br/>RE100 이행, 탄소 국경세 대응</span>
                    </li>
                    <li class="effect delay_4">
                        <p>잉여전력</p>
                        <h5><b>판매수익</b></h5>
                        <span>잉여 전력은 재생에너지<br/>공급 인증서 판매로 수익 확보</span>
                    </li>
                </ul>
            </div>
        </div>
       
        <!-- 계산기 -->
        <div class="section lease_con04">
            <div class="calculator_box">
                <div class="img_box">
                    <div class="img_slide">
                        <ul class="swiper-wrapper">
                            <li class="swiper-slide">
                                <img class="pc_img" src="{{ asset('user_assets/img/sub/business/lease_con04_bg.jpg') }}" alt="">
                                <img class="fold_img" src="{{ asset('user_assets/img/sub/business/lease_con04_bg.jpg') }}" alt="">
                                <img class="mo_img" src="{{ asset('user_assets/img/sub/business/lease_con04_bg.jpg') }}" alt="">
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="right_box type02">
                    <div class="cal_box">
                        <div class="top">
                            <h4>내 건물 발전 효과는</h4>
                            <p>평수를 입력하고 '효과 확인하기' 를 눌러보세요</p>
                            <div class="flex_type">
                                <div class="input_box">
                                    <input type="text">
                                    <b>평</b>
                                </div>
                                <button onclick="calculatorBtnLease(this)"><span>효과 확인하기</span><i><img src="{{ asset('user_assets/img/icon/loading.svg') }}" alt=""></i></button>
                            </div>
                        </div>
                        <div class="bot">
                        	<h5>20년 총 예상 절감액</h5>
                            <b><span class="count" data-num="0">0</span>만원</b>
                            <div class="table_box">
                                <div class="tit">
                                    <strong>• 전기료 예상 절감액(단위 만원)</strong>
                                </div>
                                <table>
                                    <colgroup>
                                        <col width="20%">
                                        <col width="20%">
                                        <col width="20%">
                                        <col width="20%">
                                        <col width="20%">
                                    </colgroup>
                                    <tr>
                                        <th>1년차</th>
                                        <th>2년차</th>
                                        <th>3년차</th>
                                        <th>4년차</th>
                                        <th>5년차</th>
                                    </tr>
                                    <tr>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                    </tr>
                                    <tr>
                                        <th>6년차</th>
                                        <th>7년차</th>
                                        <th>8년차</th>
                                        <th>...</th>
                                        <th>20년차</th>
                                    </tr>
                                    <tr>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td>...</td>
                                        <td class="count" data-num="0">0</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="table_box pc_ver" style="display:none;">
                                <div class="tit">
                                    <strong>• 탄소 감축량</strong>
                                    <span>총 CO2 감축 : 40,000만톤</span>
                                </div>
                                <table>
                                    <colgroup>
                                        <col width="20%">
                                        <col width="20%">
                                        <col width="20%">
                                        <col width="20%">
                                        <col width="20%">
                                    </colgroup>
                                    <tr>
                                        <th>1년차</th>
                                        <th>2년차</th>
                                        <th>3년차</th>
                                        <th>4년차</th>
                                        <th>5년차</th>
                                    </tr>
                                    <tr>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                    </tr>
                                    <tr>
                                        <th>6년차</th>
                                        <th>7년차</th>
                                        <th>8년차</th>
                                        <th>...</th>
                                        <th>20년차</th>
                                    </tr>
                                    <tr>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td>...</td>
                                        <td class="count" data-num="0">0</td>
                                    </tr>
                                </table>
                            </div>
                            <p class="txt">
                                ※ 절감금액은 발전량 X (한국전력 전기료 - 리스료 단가)로 계산됩니다.<br/>
                                ※ 한국전력 전기료는 매년 2% 인상을 기준으로 산정합니다.<br/>
                                ※ 절약 금액은 한국전력 전기료 실제 인상분과 실제 발전량에 따라 달라질 수 있습니다.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- 사업설명 -->
        <div class="section lease_con05">
            <div class="box">
                <div class="left_txt">
                    <div class="txt_box">
                        <i class="effect">WHY SOLARTEQ</i>
                        <h3 class="effect delay_1">솔라테크의 <b>RE100 리스</b></h3>
                    </div>
                </div>
                <div class="right_box">
                    <ul>
                        <li class="effect">
                            <i><img src="{{ asset('user_assets/img/sub/business/lease_icon_1.svg') }}" alt="">투자비용</i>
                            <p><b>0</b>원</p>
                            <span>초기 투자 없이 시작</span>
                        </li>
                        <li class="effect delay_1">
                            <i><img src="{{ asset('user_assets/img/sub/business/lease_icon_2.svg') }}" alt="">리스 기간</i>
                            <p><b>20</b>년</p>
                            <span>장기 보장 계약</span>
                        </li>
                        <li class="effect delay_2">
                            <i><img src="{{ asset('user_assets/img/sub/business/lease_icon_3.svg') }}" alt="">지붕관리/수리</i>
                            <p><b>무상</b></p>
                            <span>유지보수 걱정 없음</span>
                        </li>
                        <li class="effect delay_3">
                            <i><img src="{{ asset('user_assets/img/sub/business/lease_icon_4.svg') }}" alt="">잉여전력 판매</i>
                            <p><b>추가수익</b></p>
                            <span>유지보수 걱정 없음</span>
                        </li>
                    </ul>
                    <p class="txt effect delay_4">※ 수익은 20년 총액을 기준으로 표시하였습니다.<br/>※ 발전 용량에 따라 수익은 달라질 수 있습니다.</p>
                </div>
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
	                                    <div class="tit"><h5>{{$item->station_name}}</h5><strong>{{$item->price}}</strong><span>(20년 예상 절감액)</span></div>
	                                    <div class="info">
	                                        <dl>
	                                            <dt>용량</dt>
	                                            <dd>{{number_format($item->volume)}}kW / 년</dd>
	                                        </dl>
	                                        <dl>
	                                            <dt>지역</dt>
	                                            <dd>{{ $item->regions->name_kr ?? '' }} {{ $item->cities->name_kr ?? '' }}</dd>
	                                        </dl>
	                                        <dl>
	                                            <dt>형태</dt>
	                                            <dd>{{ $item->formTypes->subject ?? '' }}</dd>
	                                        </dl>
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
        <div class="section lease_con06">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">STEP-BY-STEP PROCESS</span>
                    <h3 class="effect delay_1">RE100 리스 진행 절차 </h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_1.svg') }}" alt=""></i>상담 및<br/> 수익성 검토</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/lease_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_2.svg') }}" alt=""></i>리스법인과<br/> 리스 계약</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/lease_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_3.svg') }}" alt=""></i>3D 맵핑으로<br/> 맞춤형 설계</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/lease_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_4.svg') }}" alt=""></i>튼튼하고<br/> 안전한 시공</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/lease_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_5.svg') }}" alt=""></i>전력공급<br/> RE100 실적</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(2)">상담 신청하기</button>
                </div>
            </div>
        </div>
        
        <!-- QnA -->
        <div class="section qna_con lease_qna">
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
                        <dd><div class="answer">태양태양광 발전소 설치 전 지붕에 대한 보수, 방수 시공을 먼저 진행하며,<br/>보수, 방수 시송의 비용은 솔라테크에서 부담합니다.</div></dd>
                    </dl>
                    <dl>
                        <dt onclick="toggleQna(this)">전기료 단가는 20년간 고정인가요?</dt>
                        <dd><div class="answer">20년간 고정된 RE100 리스 구독료로 태양광 발전소에서 생산된 전기를 공급받으실 수 있습니다.</div></dd>
                    </dl>
                </div>
                <div class="btn_box effect delay_3">
                    <a href="/ko/business/qna">더보기</a>
                </div>
            </div>
        </div>

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
                $('.lease_con01').addClass('on')
            },100)
        });

         // solar_con04
        var calSlide = new Swiper(".lease_con04 .calculator_box .img_slide", {
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

        const calculatorBtnLease = (btn) => {
            const $rightBox = $(btn).closest('.right_box');
            const $calBox   = $rightBox.find('.cal_box');
            const $input    = $calBox.find('.input_box input');

            // 1) 입력값
            let val   = $input.val().replace(/,/g, '').trim();
            let pyung = parseFloat(val);

            if (isNaN(pyung) || pyung <= 0) {
                alert('평수를 올바르게 입력해주세요.');
                $input.focus();
                return;
            }

            // 2) 기본 평수 변환: (입력평 / 3, 소숫점 둘째 자리 반올림)
            let base = Math.round((pyung / 3) * 100) / 100;

            // -----------------------------
            // [A] 전기료 절감액 테이블
            // -----------------------------

            // 1~8년차 + 20년차 "총 절감액" 계수 (만원 단위)
            const totalCoeffs = [1.91, 2.07, 2.24, 2.41, 2.58, 2.75, 2.93, 3.12, 5.61];

            // 각 연도별 절감액 (만원, 정수로 반올림)
            const yearSavings = totalCoeffs.map(c => Math.round(base * c));

         	// 20년 총 절감액: 73만원 * base
            const totalSaving20 = Math.round(base * 73);  // 단위: 만원

            // 첫 번째 table_box (전기료 절감액)
            const $electricBox       = $calBox.find('.table_box').eq(0);
            const $totalSavingSpan   = $calBox.find('.bot span.count');
            const $electricCells     = $electricBox.find('.count'); // 1~8년 + 20년 = 9개 셀

            // 총 절감액 data-num 세팅 (jQuery data + attribute 둘 다)
            $totalSavingSpan
                .data('num', totalSaving20)
                .attr('data-num', totalSaving20)
                .text('0'); // 애니메이션용으로 0으로 초기화 (선택사항이지만 깔끔)

            // 각 연도 셀에 값 세팅
            yearSavings.forEach((v, i) => {
                const $cell = $electricCells.eq(i);
                $cell
                    .data('num', v)
                    .attr('data-num', v)
                    .text('0');
            });

            // -----------------------------
            // [B] 탄소 감축량 테이블
            // -----------------------------

            // 연간 감축량 (톤) → 정수로 처리
            //const carbonPerYear = Math.round(base * 0.45941 / 1000);
            const carbonPerYear = Math.round(base * 0.45941);
            const carbonTotal20 = carbonPerYear * 20;

            // 두 번째 table_box (탄소 감축량)
            const $carbonBox   = $calBox.find('.table_box').eq(1);
            const $carbonTitle = $carbonBox.find('.tit span');
            const $carbonCells = $carbonBox.find('.count'); // 8개 셀 (1~8, 20년)

            // 제목의 "총 CO2 감축" 갱신 (단위는 원하는 대로: 톤 / 만톤 등)
            $carbonTitle.text(`총 CO2 감축 : ${carbonTotal20.toLocaleString('ko-KR')}톤`);

            // 각 연도 셀에 동일 값 세팅 (매년 동일)
            $carbonCells.each(function () {
                $(this).data('num', carbonPerYear)
                       .attr('data-num', carbonPerYear)
                       .text('0');
            });

            // -----------------------------
            // [C] 애니메이션 연출
            // -----------------------------

            $(btn).addClass('active');
            $rightBox.removeClass('active');
            $calBox.find('.bot').hide();

            setTimeout(function () {
                $rightBox.addClass('active');
                $calBox.find('.bot').fadeIn();
                $(btn).removeClass('active');

                // 전기료 + 탄소 테이블 안의 모든 .count 숫자 애니메이션
                $calBox.find('.count').each(function () {
                    const num = $(this).data('num');
                    animateNumber($(this), num, 1500);
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