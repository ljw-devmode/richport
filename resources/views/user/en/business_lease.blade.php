@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="fullpage">
        <!-- 리스 -->
        <div class="section lease_con01">
            <div class="sub_inner">
                <h4 class="effects">RE100 Leasing</h4>
                <p class="effects delay_1">A program that lets you use renewable energy in a leasing model for 20 years</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 리스 구독 사업 -->
        <div class="section lease_con02">
            <div class="sub_inner">
                <div class="tit">
                    <h4>Subscribe to electricity <b>for 20 years at a fixed rate </b></h4>
                    <p>A model that delivers electricity cost savings, carbon reduction, and RE100 compliance <b>all at once</b></p>
                </div>
                <div class="top"><p>Install solar panels on your rooftop</p></div>
                <ul>
                    <li>
                        <i>01</i>
                        <div class="path">
                            <div>
                                <b>Renewable Energy</b>
                                <p>Build sustainable power infrastructure</p>
                            </div>
                            <div class="arrow">
                                <img src="{{ asset('user_assets/img/sub/business/lease_con02_arrow.svg') }}" alt="">
                                <span>20-Year, Fixed Lease Rate</span>
                            </div>
                            <div>
                                <b>Reduce Electricity Costs</b>
                                <p>Stable cost management for 20 years</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <i>02</i>
                        <div class="path">
                            <div>
                                <b>Carbon Reduction Results</b>
                                <p>Annual CO₂ reduction impact</p>
                            </div>
                            <div class="arrow">
                                <img src="{{ asset('user_assets/img/sub/business/lease_con02_arrow.svg') }}" alt="">
                                <span>ESG, Carbon Border Tax</span>
                            </div>
                            <div>
                                <b>Achieve RE100</b>
                                <p>Directly contributes to ESG ratings</p>
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
                    <h3 class="effect delay_1">Electricity Savings & RE100 Compliance</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p>Electricity Cost</p>
                        <h5><b class="blue">20%</b><img src="{{ asset('user_assets/img/icon/down_arrow.svg') }}" alt=""></h5>
                        <span>Power supplied at rates about 20% lower than KEPCO’s annually increasing tariffs</span>
                    </li>
                    <li class="effect delay_3">
                        <p>RE100</p>
                        <h5><b>Compliance</b></h5>
                        <span>Meet RE100 by using renewable energy and respond to carbon border measures</span>
                    </li>
                    <li class="effect delay_4">
                        <p>Surplus Power</p>
                        <h5><b>Sales Revenue</b></h5>
                        <span>Monetize surplus power and secure additional revenue via renewable energy certificates</span>
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
                            <h4>My Building’s Generation Impact</h4>
                            <p>Enter the area and click “Check Impact”</p>
                            <div class="flex_type">
                                <div class="input_box">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                    <b>pyeong (㎡)</b>
                                </div>
                                <button onclick="calculatorBtnLease(this)"><span>Check Impact</span><i><img src="{{ asset('user_assets/img/icon/loading.svg') }}" alt=""></i></button>
                            </div>
                        </div>
                        <div class="bot">
                        	<h5>Estimated Total Savings Over 20 Years</h5>
                            <b><span class="count" data-num="0">0</span>,000 KRW</b>
                            <div class="table_box">
                                <div class="tit">
                                    <strong>• Estimated Electricity Savings (Unit: 10,000 KRW)</strong>
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
                                        <th>Year 1</th>
                                        <th>Year 2</th>
                                        <th>Year 3</th>
                                        <th>Year 4</th>
                                        <th>Year 5</th>
                                    </tr>
                                    <tr>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                    </tr>
                                    <tr>
                                        <th>Year 6</th>
                                        <th>Year 7</th>
                                        <th>Year 8</th>
                                        <th>...</th>
                                        <th>Year 20</th>
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
                                    <strong>• Carbon Reduction Amount</strong>
                                    <span>Total CO2 Reduction : 00 tons</span>
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
                                        <th>Year 1</th>
                                        <th>Year 2</th>
                                        <th>Year 3</th>
                                        <th>Year 4</th>
                                        <th>Year 5</th>
                                    </tr>
                                    <tr>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                    </tr>
                                    <tr>
                                        <th>Year 6</th>
                                        <th>Year 7</th>
                                        <th>Year 8</th>
                                        <th>...</th>
                                        <th>Year 20</th>
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
                                ※ Savings are calculated as generation × (KEPCO tariff − lease rate).<br/>
                                ※ KEPCO tariffs are estimated with a 2% annual increase.<br/>
                                ※ Actual savings may vary depending on actual KEPCO tariff increases and actual generation.
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
                        <h3 class="effect delay_1">SOLARTEQ’s RE100 Leasing</h3>
                    </div>
                </div>
                <div class="right_box">
                    <ul>
                        <li class="effect">
                            <i><img src="{{ asset('user_assets/img/sub/business/lease_icon_1.svg') }}" alt="">Initial Investment</i>
                            <p><b>0</b>KRW</p>
                            <span>Start with no upfront investment</span>
                        </li>
                        <li class="effect delay_1">
                            <i><img src="{{ asset('user_assets/img/sub/business/lease_icon_2.svg') }}" alt="">Lease Term</i>
                            <p><b>20</b> Yesrs</p>
                            <span>Long-term Guaranteed Contract</span>
                        </li>
                        <li class="effect delay_2">
                            <i><img src="{{ asset('user_assets/img/sub/business/lease_icon_3.svg') }}" alt="">Rooftop Maintenance</i>
                            <p><b>Free of Charge</b></p>
                            <span>No maintenance worries</span>
                        </li>
                        <li class="effect delay_3">
                            <i><img src="{{ asset('user_assets/img/sub/business/lease_icon_4.svg') }}" alt="">Surplus Power Sales</i>
                            <p><b>Additional Revenue</b></p>
                            <span>No maintenance worries</span>
                        </li>
                    </ul>
                    <p class="txt effect delay_4">※ Figures are based on total amounts over 20 years.<br/>※ Results may vary depending on generation capacity.</p>
                </div>
            </div>
        </div>

        <!-- portfolio -->
        @if (!$data->isEmpty())
        <div class="section portfolio_con">
            <div class="port_slide">
                <div class="tit_box">
                    <h3>Portfolio</h3>
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
	                                    <div class="tit"><h5>{{$item->station_name_en}}</h5><strong>{{$item->price_en}}</strong><span>(Estimated 20-year savings)</span></div>
	                                    <div class="info">
	                                        <dl>
	                                            <dt>Power Generation Capacity</dt>
	                                            <dd>{{number_format($item->volume_en)}}kW</dd>
	                                        </dl>
	                                        <dl>
	                                            <dt>Region</dt>
	                                            <dd>{{ $item->regions->name_en ?? '' }} {{ $item->cities->name_en ?? '' }}</dd>
	                                        </dl>
	                                        <dl>
	                                            <dt>Type</dt>
	                                            <dd>{{ $item->formTypes->subject_en ?? '' }}</dd>
	                                        </dl>
	                                    </div>
	                                </div>
	                                <div class="bot">
	                                    <div class="btn">
	                                    	@if($item->blog)
	                                        <a href="{{ $item->blog }}" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_blog.svg') }}" alt="">Blog</a>
	                                        @endif
											@if($item->url)	                                        
											<a href="{{ $item->url }}" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_youtube.svg') }}" alt="">Video</a>
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
                    <h3 class="effect delay_1">RE100 Leasing Process </h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_1.svg') }}" alt=""></i>Consultation & Feasibility Review</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/lease_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_2.svg') }}" alt=""></i>Lease contract with the leasing entity</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/lease_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_3.svg') }}" alt=""></i>Customized design via 3D mapping</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/lease_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_4.svg') }}" alt=""></i>Solid and Safe Construction</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/lease_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_5.svg') }}" alt=""></i>Power supply & RE100 performance results</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(2)">Request Consultation</button>
                </div>
            </div>
        </div>
        
        <!-- QnA -->
        <div class="section qna_con lease_qna">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">Q&A</span>
                    <h3 class="effect delay_1">What would you like to know?</h3>
                </div>
                <div class="qna_list effect delay_2">
                    <dl>
                        <dt onclick="toggleQna(this)">Is the investment cost really zero?</dt>
                        <dd><div class="answer">All investment costs required for installing the solar power plant are fully covered by SOLARTEQ.</div></dd>
                    </dl>
                    <dl>
                        <dt onclick="toggleQna(this)">How much does roof repair cost?</dt>
                        <dd><div class="answer">Roof repair and waterproofing are performed before installation, and all related costs are covered by SOLARTEQ.</div></dd>
                    </dl>
                    <dl>
                        <dt onclick="toggleQna(this)">Is the electricity rate fixed for 20 years?</dt>
                        <dd><div class="answer">Electricity generated by the solar plant is supplied at a fixed RE100 lease subscription rate for 20 years.</div></dd>
                    </dl>
                </div>
                <div class="btn_box effect delay_3">
                    <a href="/ko/business/qna">View More</a>
                </div>
            </div>
        </div>

        <section class="section fp-auto-height">
            @include('user.en.footer._footer')
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
                alert('Please enter a valid area.');
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
            $carbonTitle.text(`Total CO2 Reduction : ${carbonTotal20.toLocaleString('ko-KR')}tons`);

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
@include('user.en.footer._gtm')
</html>