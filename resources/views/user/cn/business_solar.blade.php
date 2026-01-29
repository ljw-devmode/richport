@include('user.cn.header._head')
<body>
	@include('user.cn.header._header')
    <main id="fullpage">
        <!-- 지붕임대 태양광 -->
        <div class="section solar_con01">
            <div class="sub_inner">
                <h4 class="effects">屋顶租赁式太阳能</h4>
                <p class="effects delay_1">如果您的建筑屋顶闲置，通过 SOLARTEQ 可获得 20 年稳定收益</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section solar_con02">
            <div class="sub_inner">
                <p class="effect">屋顶租赁式太阳能项目是在闲置屋顶安装太阳能电站，并在 20 年内支付租金的业务</p>
            </div>
        </div>

        <!-- 혜택 -->
        <div class="section solar_con03">
            <div class="box">
                <div class="left_txt">
                    <div class="txt_box">
                        <i class="effect">专属优势</i>
                        <h3 class="effect delay_1">提供最优厚的条件</h3>
                    </div>
                </div>
                <div class="right_box">
                    <ul>
                        <li class="effect">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_1.svg') }}" alt="">初始投资</i>
                            <p><b>0</b>韩元</p>
                            <span>无需初期投资即可开始</span>
                        </li>
                        <li class="effect delay_1">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_2.svg') }}" alt="">稳定收益</i>
                            <p><b>20</b>年</p>
                            <span>长期保障合同</span>
                        </li>
                        <li class="effect delay_2">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_3.svg') }}" alt="">以 100 kW 为基准</i>
                            <p>8 <b>千万韩元</b></p>
                            <span>20 年预计总收益</span>
                        </li>
                        <li class="effect delay_3">
                            <i><img src="{{ asset('user_assets/img/sub/business/solar_icon_4.svg') }}" alt="">屋顶维护 / 修理</i>
                            <p><b>免费</b></p>
                            <span>无需担心维护问题</span>
                        </li>
                    </ul>
                    <p class="txt effect delay_4">※ 收益以 20 年总额为基准显示。<br/>※ 收益可能因发电容量不同而有所差异。</p>
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
                                <span>泰信英派克 - 300kW</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="right_box type02">
                    <div class="cal_box">
                        <div class="top">
                            <h4>您的屋顶租赁收益</h4>
                            <p>输入面积并点击“计算收益”</p>
                            <div class="input_box">
                                <input type="text">
                                <b>平方米</b>
                            </div>
                            <button onclick="calculatorBtnSolar(this)"><span>计算</span><i><img src="{{ asset('user_assets/img/icon/loading.svg') }}" alt=""></i></button>
                        </div>
                        <div class="bot">
                            <h5>20 年预计总收益</h5>
                            <b><span class="count" data-num="23000000">0</span>万韩元</b>
                            <p>• 发电容量 : <strong><span class="count" data-num="500">0</span> kW</strong></p>
                            <p>• 租赁费用 : <strong><span class="count" data-num="2000">0</span>万韩元/年</strong></p>
                            <p class="txt">※ 屋顶面积与实际安装面积可能不同，年度收益取决于发电容量</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- why solarteq -->
        <div class="section solar_con05">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">为什么选择 SOLARTEQ</span>
                    <h3 class="effect delay_1">SOLARTEQ 值得信赖</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <div class="txt_box">
                            <i>01</i>
                            <p>高额租金</p>
                            <span>提供行业领先水平的租金</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/sub/business/solar_con05_1.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <div class="txt_box">
                            <i>02</i>
                            <p>稳定可靠的企业</p>
                            <span>拥有太阳能工厂的规模化企业</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/sub/business/solar_con05_2.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <div class="txt_box">
                            <i>03</i>
                            <p>值得信赖的企业</p>
                            <span>与 POSCO、CJ 等大型企业合作的 SOLARTEQ</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/sub/business/solar_con05_3.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <div class="txt_box">
                            <i>04</i>
                            <p>经认证的技术实力</p>
                            <span>拥有 7 项专利与 6 项设计认证的企业</span>
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
	                                    <div class="tit"><h5>{{$item->station_name}}</h5><strong>{{$item->price}}</strong><span>约 00 亿韩元</span></div>
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

        <!-- procedure -->
        <div class="section solar_con06">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">分步流程</span>
                    <h3 class="effect delay_1">屋顶租赁式太阳能流程</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/solar_con06_icon_1.svg') }}" alt=""></i>提交咨询</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/solar_con06_arrow.svg') }}" alt="">24 小时内</div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/solar_con06_icon_2.svg') }}" alt=""></i>经安全性与收益性评估后签约</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/solar_con06_arrow.svg') }}" alt="">一周内</div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/solar_con06_icon_3.svg') }}" alt=""></i>坚固安全的施工</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/solar_con06_arrow.svg') }}" alt="">约 3 个月</div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/solar_con06_icon_4.svg') }}" alt=""></i>20 年持续支付租金</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_6">
                    <button onclick="openInquire(1)">申请咨询</button>
                    <p>✱ 各阶段所需时间可能因屋顶规模不同而有所变化。</p>
                </div>
            </div>
        </div>

        <!-- q&a -->
        <div class="section qna_con solar_qna">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">问答</span>
                    <h3 class="effect delay_1">您想了解什么？</h3>
                </div>
                <div class="qna_list effect delay_2">
                    <dl>
                        <dt onclick="toggleQna(this)">投资费用真的为 0 吗？</dt>
                        <dd><div class="answer">太阳能电站安装所需的全部投资费用均由 SOLARTEQ 承担。</div></dd>
                    </dl>
                    <dl>
                        <dt onclick="toggleQna(this)">租金如何计算及支付？</dt>
                        <dd><div class="answer">租金以装机容量为基准计算。首次租金从商业运营开始日至当年年底按日计算支付。之后每年 1 月预付全年租金。</div></dd>
                    </dl>
                    <dl>
                        <dt onclick="toggleQna(this)">可以提前支付租金吗？</dt>
                        <dd><div class="answer">可以提前支付租金，但提前履约保证保险的费用需由出租方承担。</div></dd>
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
                alert('请输入正确的面积。');
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
@include('user.cn.footer._gtm')
</html>