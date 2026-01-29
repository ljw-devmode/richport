@include('user.cn.header._head')
<body>
	@include('user.cn.header._header')
    <main id="fullpage">
        <!-- 지붕임대 태양광 -->
        <div class="section rps_con01">
            <div class="sub_inner">
                <h4 class="effects">RPS 太阳能发电项目</h4>
                <p class="effects delay_1">一种收益型发电业务：不自用所发电力, 仅进行对外销售</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 태양광 발전 사업 -->
        <div class="section rps_con02">
            <div class="sub_inner">
                <div class="tit">
                    <h3 class="effect">RPS 太阳能发电项目</h3>
                    <p class="effect delay_1">通过电力与证书销售实现收益的结构</p>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <div class="txt">
                            <i>01</i>
                            <p>可再生能源电力销售, SMP 售电收入</p>
                        </div>
                        <div class="path">
                            <div class="box"><span>发电</span>太阳能电站</div>
                            <div class="box"><span>销售方</span>韩国电力公司（KEPCO）</div>
                        </div>
                    </li>
                    <li class="effect delay_3">
                        <div class="txt">
                            <i>02</i>
                            <p>可再生能源供应证书销售, REC 销售</p>
                        </div>
                        <div class="path">
                            <div class="box"><span>发电</span>REC 证书</div>
                            <div class="box"><span>销售方</span>发电运营商</div>
                        </div>
                    </li>
                </ul>
                <div class="result_box effect delay_4">
                    <span>实现收益</span>
                </div>
            </div>
        </div>

        <!-- 지속가능한 경영을 위한 선택 -->
        <div class="section rps_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">可持续经营</span>
                    <h3 class="effect delay_1">面向可持续经营的选择</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/business/rps_con03_icon_1.svg') }}" alt="">
                        <h5><b>收益性</b></h5>
                        <p>SMP + REC<br/> <b>20 年稳定的双重收益结构</b></p>
                    </li>
                    <li class="effect delay_3">
                        <img src="{{ asset('user_assets/img/sub/business/rps_con03_icon_2.svg') }}" alt="">
                        <h5><b>稳定性</b></h5>
                        <p>基于 RPS 制度的发电项目，并配套多项支持政策</p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/business/rps_con03_icon_3.svg') }}" alt="">
                        <h5><b>ESG</b></h5>
                        <p>可持续经营、温室气体减排、向清洁能源转型</p>
                    </li>
                    <li class="effect delay_5">
                        <img src="{{ asset('user_assets/img/sub/business/rps_con03_icon_4.svg') }}" alt="">
                        <h5><b>成长性</b></h5>
                        <p>可再生能源占比提升, 到 2030 年达到 30% 以上</b></p>
                    </li>
                </ul>
                <div class="txt effect delay_6">兼顾收益稳定与增长的下一代能源投资解决方案</div>
                <div class="info effect delay_7">
                    ※ RPS 项目依据《促进新能源及可再生能源开发、利用与普及法》推进实施。<br/>
                    ※ 对地方经济具有积极影响，如创造就业、增加税收等。
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
                                <span>东昌R&S - 1,533kW</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="right_box type02">
                    <div class="cal_box">
                        <div class="top">
                            <h4>我的电站收益</h4>
                            <p>输入面积并点击“计算收益”</p>
                            <div class="input_area">
                                <div class="radio_box">
                                    <input type="radio" id="cal_1" name="cal" checked><label for="cal_1">建筑</label>
                                    <input type="radio" id="cal_2" name="cal"><label for="cal_2">土地</label>
                                </div>
                                <div class="input_box">
                                    <input type="text">
                                    <b>平方米</b>
                                </div>
                            </div>
                            <button onclick="calculatorBtnRps(this)"><span>查看效果</span><i><img src="{{ asset('user_assets/img/icon/loading.svg') }}" alt=""></i></button>
                        </div>
                        <div class="bot">
                            <h5>20 年预计总收益</h5>
                            <b><span class="count">0</span>万韩元</b>
                            <p>• 年发电量 : <strong><span class="count">0</span> kWh/年</strong></p>
                            <p>• 韩国电力（SMP） : <strong><span class="count">0</span> 韩元/年</strong></p>
                            <p>• 发电运营商（REC） : <strong><span class="count">0</span> 韩元/年</strong></p>
                            <p class="txt">※ 实际安装面积可能不同，年度收益取决于发电容量。<br/>※ 韩国电力/发电运营商的单价可能因商业运营开始日期不同而变化，从而影响收益。</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 왜 선택해야할까요 -->
        <div class="section rps_con05">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">为什么选择 SOLARTEQ</span>
                    <h3 class="effect delay_1">为什么选择 SOLARTEQ？</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <h4>丰富的项目案例</h4>
                        <p>全国 600+ 项目, 700MW+ 项目, <br/> 装机规模，减碳效果相当于种植 230 万棵以上树木</p>
                    </li>
                    <li class="effect delay_3">
                        <h4>经验证的技术实力</h4>
                        <p>拥有 7 项专利认可的技术实力, 以及 POSCO 的 PosMAC 结构体系</p>
                    </li>
                    <li class="effect delay_4">
                        <h4>值得信赖的合作伙伴</h4>
                        <p>POSCO、CJ 等大型企业选择的可靠伙伴</b></p>
                    </li>
                </ul>
            </div>
        </div>

        <!-- portfolio -->
        @if (!$data->isEmpty())
        <div class="section portfolio_con">
            <div class="port_slide">
                <div class="tit_box">
                    <h3>项目案例</h3>
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
	                                            <dt>地区</dt>
	                                            <dd>{{ $item->regions->name_kr ?? '' }} {{ $item->cities->name_kr ?? '' }}</dd>
	                                        </dl>
	                                        <dl>
	                                            <dt>类型</dt>
	                                            <dd>{{ $item->formTypes->subject ?? '' }}</dd>
	                                        </dl>
	                                        @if($item->completion_date)
	                                        <dl>
	                                            <dt>完成日期</dt>
	                                            <dd>{{ $item->completion_date ?? '' }}</dd>
	                                        </dl>
	                                        @endif
	                                    </div>
	                                </div>
	                                <div class="bot">
	                                    <div class="btn">
	                                    	@if($item->blog)
	                                        <a href="{{ $item->blog }}" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_blog.svg') }}" alt="">项目案例</a>
	                                        @endif
											@if($item->url)	                                        
											<a href="{{ $item->url }}" target="_blank"><img src="{{ asset('user_assets/img/main/main_con03_youtube.svg') }}" alt="">竣工视频</a>
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
                    <span class="effect">分步流程</span>
                    <h3 class="effect delay_1">RPS 太阳能发电项目流程</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_1.svg') }}" alt=""></i>咨询与收益性评估</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/rps_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_2.svg') }}" alt=""></i>地方政府审批与韩国电力合同</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/rps_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_3.svg') }}" alt=""></i>细致的工程设计</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/rps_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_4.svg') }}" alt=""></i>坚固安全的施工</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/rps_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/rps_con06_icon_5.svg') }}" alt=""></i>通过售电实现收益</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(3)">申请咨询</button>
                </div>
            </div>
        </div>

        <!-- QnA -->
        <div class="section qna_con rps_qna">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">问答</span>
                    <h3 class="effect delay_1">您想了解什么？</h3>
                </div>
                <div class="qna_list effect delay_2">
                    <dl>
                        <dt onclick="toggleQna(this)">施工费用如何计算？</dt>
                        <dd><div class="answer">施工费用根据电站面积及发电量计算，可通过与负责人咨询及项目可行性评估后明确确定。</div></dd>
                    </dl>
                    <dl>
                        <dt onclick="toggleQna(this)">从签约到施工大约需要多久？</dt>
                        <dd><div class="answer">一般施工约需 3 个月，如包含审批流程约需 6 个月，具体时间会根据装机容量有所调整。</div></dd>
                    </dl>
                    <dl>
                        <dt onclick="toggleQna(this)">对电站用地有条件要求吗？</dt>
                        <dd><div class="answer">电站用地需满足出入口、电网接入、通知条件等多项要求，且地方政府可能通过条例附加其他条件，需通过咨询进行确认。</div></dd>
                    </dl>
                </div>
                <div class="btn_box effect delay_3">
                    <a href="/business/qna">查看更多</a>
                </div>
            </div>
        </div>

        <!-- footer -->
        <section class="section fp-auto-height">
            @include('user.cn.footer._footer')
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
                alert('请输入正确的面积。');
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
@include('user.cn.footer._gtm')
</html>