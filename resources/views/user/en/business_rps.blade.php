@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="fullpage">
        <!-- 지붕임대 태양광 -->
        <div class="section rps_con01">
            <div class="sub_inner">
                <h4 class="effects">RPS Solar Power Generation Business</h4>
                <p class="effects delay_1">A revenue-generating power project </p>
                <p class="effects delay_2">where all generated electricity is sold rather than self-consumed</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 태양광 발전 사업 -->
        <div class="section rps_con02">
            <div class="sub_inner">
                <div class="tit">
                    <h3 class="effect">RPS RPS Solar Power Generation Project</h3>
                    <p class="effect delay_1">A profit model based on selling electricity and certificates</p>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <div class="txt">
                            <i>01</i>
                            <p>Renewable Electricity Sales<span>SMP Sales</span></p>
                        </div>
                        <div class="path">
                            <div class="box"><span>Generation</span>Solar Power Plant</div>
                            <div class="box"><span>Where to buy</span>KEPCO (Korea Electric Power Corporation)</div>
                        </div>
                    </li>
                    <li class="effect delay_3">
                        <div class="txt">
                            <i>02</i>
                            <p>Renewable Energy Certificate Sales</b><span>REC Sales</span></p>
                        </div>
                        <div class="path">
                            <div class="box"><span>Generation</span>REC Certificate</div>
                            <div class="box"><span>Where to buy</span>Power Producer</div>
                        </div>
                    </li>
                </ul>
                <div class="result_box effect delay_4">
                    <span>Revenue Realization</span>
                </div>
            </div>
        </div>

        <!-- 지속가능한 경영을 위한 선택 -->
        <div class="section rps_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">SUSTAINABLE MANAGEMENT</span>
                    <h3 class="effect delay_1">A Choice for Sustainable Management</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/business/rps_con03_icon_1.svg') }}" alt="">
                        <h5><b>Profitability</b></h5>
                        <p>SMP + REC <b>A stable dual-revenue structure for 20 years</b></p>
                    </li>
                    <li class="effect delay_3">
                        <img src="{{ asset('user_assets/img/sub/business/rps_con03_icon_2.svg') }}" alt="">
                        <h5><b>Stability</b></h5>
                        <p>Power projects based on the RPS scheme, supported by various incentive programs</p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/business/rps_con03_icon_3.svg') }}" alt="">
                        <h5><b>ESG</b></h5>
                        <p>Sustainable management, GHG reduction, transition to clean energy</p>
                    </li>
                    <li class="effect delay_5">
                        <img src="{{ asset('user_assets/img/sub/business/rps_con03_icon_4.svg') }}" alt="">
                        <h5><b>Growth Potential</b></h5>
                        <p>Renewable energy expansion <b>More than 30% by 2030</b></p>
                    </li>
                </ul>
                <div class="txt effect delay_6">A next-generation energy investment solution that delivers stable returns and growth</div>
                <div class="info effect delay_7">
                    *RPS projects are carried out in accordance with the Act on the Promotion of the Development, Use and Dissemination of New and Renewable Energy.<br/>
                    *It has positive local economic effects such as job creation and increased tax revenue.
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
                                <span>Dongchang R&S - 1,533kW</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="right_box type02">
                    <div class="cal_box">
                        <div class="top">
                            <h4>My power plant revenue</h4>
                            <p>Enter the area and click “Calculate Income”</p>
                            <div class="input_area">
                                <div class="radio_box">
                                    <input type="radio" id="cal_1" name="cal" checked><label for="cal_1">Building</label>
                                    <input type="radio" id="cal_2" name="cal"><label for="cal_2">Land</label>
                                </div>
                                <div class="input_box">
                                    <input type="text">
                                    <b>pyeong (㎡)</b>
                                </div>
                            </div>
                            <button onclick="calculatorBtnRps(this)"><span>Profit Calculator</span><i><img src="{{ asset('user_assets/img/icon/loading.svg') }}" alt=""></i></button>
                        </div>
                        <div class="bot">
                            <h5>Estimated total income over 20 years</h5>
                            <b><span class="count">0</span>KRW</b>
                            <p>• Annual Generation : <strong><span class="count">0</span> kWh/Year</strong></p>
                            <p>• KEPCO (SMP) : <strong><span class="count">0</span> KRW/Year</strong></p>
                            <p>• Power Producer (REC) : <strong><span class="count">0</span> KRW/Year</strong></p>
                            <p class="txt">※ Installation area may vary; annual income depends on generation capacity.<br/>※ KEPCO/power producer unit prices may vary depending on the commercial operation start date.</p>
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
                    <h3 class="effect delay_1">Why Choose SOLARTEQ?</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <h4>Extensive Project Experience</h4>
                        <p><b>600+ projects</b>, 700MW+ capacity, Carbon reduction equivalent to 2.5M+ trees</p>
                    </li>
                    <li class="effect delay_3">
                        <h4>Proven Technology</h4>
                        <p>Technology recognized by 7 patents and POSCO PosMAC structures</p>
                    </li>
                    <li class="effect delay_4">
                        <h4>A Trusted Partner</h4>
                        <p>A reliable partner chosen by major companies such as POSCO and CJ</b></p>
                    </li>
                </ul>
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
	                                    <div class="tit"><h5>{{$item->station_name_en}}</h5><strong>{{number_format($item->volume)}}kW</strong></div>
	                                    <div class="info">
	                                        <dl>
	                                            <dt>Region</dt>
	                                            <dd>{{ $item->regions->name_en ?? '' }} {{ $item->cities->name_en ?? '' }}</dd>
	                                        </dl>
	                                        <dl>
	                                            <dt>Type</dt>
	                                            <dd>{{ $item->formTypes->subject_en ?? '' }}</dd>
	                                        </dl>
	                                        @if($item->completion_date)
	                                        <dl>
	                                            <dt>Completion Date</dt>
	                                            <dd>{{ $item->completion_date ?? '' }}</dd>
	                                        </dl>
	                                        @endif
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
        <div class="section rps_con06">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">STEP-BY-STEP PROCESS</span>
                    <h3 class="effect delay_1">RPS Solar Project Process</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_1.svg') }}" alt=""></i>Consultation & Profitability Review</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/rps_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_2.svg') }}" alt=""></i>Local government permits & KEPCO contract</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/rps_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_3.svg') }}" alt=""></i>Detailed Engineering Design</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/rps_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_4.svg') }}" alt=""></i>Solid and Safe Construction</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/rps_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_5.svg') }}" alt=""></i>Realize revenue through electricity sales</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(3)">Request Consultation</button>
                </div>
            </div>
        </div>

        <!-- QnA -->
        <div class="section qna_con rps_qna">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">Q&A</span>
                    <h3 class="effect delay_1">What would you like to know?</h3>
                </div>
                <div class="qna_list effect delay_2">
                    <dl>
                        <dt onclick="toggleQna(this)">How are construction costs determined?</dt>
                        <dd><div class="answer">Construction costs are calculated based on plant area and generation capacity, and can be clearly determined through consultation and feasibility review.</div></dd>
                    </dl>
                    <dl>
                        <dt onclick="toggleQna(this)">How long does it take from contract signing to construction?</dt>
                        <dd><div class="answer">Typical construction takes about 3 months, or about 6 months including permitting. The duration may vary depending on installation capacity.</div></dd>
                    </dl>
                    <dl>
                        <dt onclick="toggleQna(this)">Are there any requirements for power plant sites?</dt>
                        <dd><div class="answer">Power plant sites are subject to various conditions such as access roads, grid connection, and notification requirements. Additional conditions may be imposed by local ordinances, so consultation is required.</div></dd>
                    </dl>
                </div>
                <div class="btn_box effect delay_3">
                    <a href="/business/qna">View More</a>
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
                alert('Please enter a valid area.');
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
            total = Math.round(totalWon);

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
@include('user.en.footer._gtm')
</html>