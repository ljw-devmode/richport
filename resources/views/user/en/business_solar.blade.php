@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="fullpage">
        <!-- 지붕임대 태양광 -->
        <div class="section solar_con01">
            <div class="sub_inner">
                <h4 class="effects">Rooftop Solar Leasing</h4>
                <p class="effects delay_1">If your building rooftop is unused, SOLARTEQ enables stable income for 20 years</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section solar_con02">
            <div class="sub_inner">
                <p class="effect">Rooftop solar leasing installs solar power systems on unused rooftops and provides lease payments for 20 years</p>
            </div>
        </div>

        <!-- 혜택 -->
        <div class="section solar_con03">
            <div class="box">
                <div class="left_txt">
                    <div class="txt_box">
                        <i class="effect">PRIVILEGE</i>
                        <h3 class="effect delay_1">We Offer <span>the Best Benefits</span></h3>
                    </div>
                </div>
                <div class="right_box">
                    <ul>
                        <li class="effect">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_1.svg') }}" alt="">Initial Investment</i>
                            <p><b>0</b>KRW</p>
                            <span>Start without initial investment</span>
                        </li>
                        <li class="effect delay_1">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_2.svg') }}" alt="">Stable Income</i>
                            <p><b>20</b>Years</p>
                            <span>Long-term guaranteed contract</span>
                        </li>
                        <li class="effect delay_2">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_3.svg') }}" alt="">Based on 100 kW</i>
                            <p><b>80</b>million KRW</p>
                            <span>Estimated total income over 20 years</span>
                        </li>
                        <li class="effect delay_3">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_4.svg') }}" alt="">Rooftop Maintenance</i>
                            <p><b>No Charge</b></p>
                            <span>No maintenance concerns</span>
                        </li>
                    </ul>
                    <p class="txt effect delay_4">※ Income figures are based on total revenue over 20 years.<br/>※ Income may vary depending on generation capacity.</p>
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
                                <span>Taeshin Inpack - 300kW</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="right_box type02">
                    <div class="cal_box">
                        <div class="top">
                            <h4>Your rooftop leasing income</h4>
                            <p>Enter the area and click “Calculate Income”</p>
                            <div class="input_box">
                                <input type="text" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                <b>pyeong (3.3㎡)</b>
                            </div>
                            <button onclick="calculatorBtnSolar(this)"><span>Calculate</span><i><img src="{{ asset('user_assets/img/icon/loading.svg') }}" alt=""></i></button>
                        </div>
                        <div class="bot">
                            <h5>Estimated total income over 20 years</h5>
                            <b><span class="count" data-num="23000000">0</span>KRW</b>
                            <p>• Power Generation : <strong><span class="count" data-num="500">0</span> kW</strong></p>
                            <p>• Lease Fee : <strong><span class="count" data-num="2000">0</span>KRW/Years</strong></p>
                            <p class="txt">※ Rooftop area and actual installation area may differ; annual income depends on generation capacity.</p>
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
                    <h3 class="effect delay_1">SOLARTEQ Is Trustworthy</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <div class="txt_box">
                            <i>01</i>
                            <p>High Lease Payments</p>
                            <span>We offer industry-leading lease rates</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/sub/business/solar_con05_1.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <div class="txt_box">
                            <i>02</i>
                            <p>A Stable Company</p>
                            <span>A large-scale company with its own solar manufacturing facilities</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/sub/business/solar_con05_2.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <div class="txt_box">
                            <i>03</i>
                            <p>A Trusted Company</p>
                            <span>SOLARTEQ partners with major corporations such as POSCO and CJ</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/sub/business/solar_con05_3.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <div class="txt_box">
                            <i>04</i>
                            <p>Certified Technology</p>
                            <span>Recognized with 9 patents and 6 design registrations</span>
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
	                                    <div class="tit"><h5>{{$item->station_name_en}}</h5><strong>{{$item->price_en}}</strong><span>(Total over 20 years)</span></div>
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

        <!-- procedure -->
        <div class="section solar_con06">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">STEP-BY-STEP PROCESS</span>
                    <h3 class="effect delay_1">Rooftop Solar Leasing Process</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/solar_con06_icon_1.svg') }}" alt=""></i>Consultation Request</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/solar_con06_arrow.svg') }}" alt="">Within 24 hours</div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/solar_con06_icon_2.svg') }}" alt=""></i>Contract after Safety & Profitability Review</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/solar_con06_arrow.svg') }}" alt="">Within one week</div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/solar_con06_icon_3.svg') }}" alt=""></i>Solid and Safe Construction</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/solar_con06_arrow.svg') }}" alt="">Approx. 3 months</div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/solar_con06_icon_4.svg') }}" alt=""></i>Lease Payments for 20 Years</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_6">
                    <button onclick="openInquire(1)">Request Consultation</button>
                    <p>✱ Timelines may vary depending on rooftop size.</p>
                </div>
            </div>
        </div>

        <!-- q&a -->
        <div class="section qna_con solar_qna">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">Q&A</span>
                    <h3 class="effect delay_1">What Would You Like to Know?</h3>
                </div>
                <div class="qna_list effect delay_2">
                    <dl>
                        <dt onclick="toggleQna(this)">Is the investment cost really zero?</dt>
                        <dd><div class="answer">All investment costs required for installing the solar power plant are fully covered by SOLARTEQ.</div></dd>
                    </dl>
                    <dl>
                        <dt onclick="toggleQna(this)">Is advance payment of the lease fee possible?</dt>
                        <dd><div class="answer">Yes, advance payment is possible. However, the cost of issuing the advance payment performance guarantee insurance must be borne by the lessor.</div></dd>
                    </dl>
                    <dl>
                        <dt onclick="toggleQna(this)">How is the lease fee calculated and paid?</dt>
                        <dd><div class="answer">Lease fees are calculated based on installed capacity. The initial lease fee is prorated from the commercial operation start date to the end of the year. Regular lease fees are prepaid annually every January.</div></dd>
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
                alert('Please enter a valid area.');
                $input.focus();
                return;
            }

            // 2) 기본값: (입력평 / 3, 소숫점 2자리 반올림)
            let base = Math.round((pyung / 3) * 100) / 100; // 소수 둘째 자리 반올림

            // 3) 각 항목 계산 (최종은 정수로 반올림)
            // 1. 건물 지붕 수입: base * 40000 * 20
            const income = Math.round(base * 40000 * 20); // 만원 단위 총액

            // 2. 발전용량: base
            const capacity = Math.round(base);        // kW / 년

            // 3. 임대료: base * 40000
            const rent = Math.round(base * 40000);        // 만원

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
@include('user.en.footer._gtm')
</html>