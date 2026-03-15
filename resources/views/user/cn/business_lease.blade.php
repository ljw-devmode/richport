@include('user.cn.header._head')
<body>
	@include('user.cn.header._header')
    <main id="fullpage">
        <!-- 리스 -->
        <div class="section lease_con01">
            <div class="sub_inner">
                <b class="effects">RE100 租赁</b>
                <h4 class="effects delay_1">RE100 租赁</h4>
                <p class="effects delay_2">可通过租赁模式使用可再生能源 20 年的项目</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 리스 구독 사업 -->
        <div class="section lease_con02">
            <div class="sub_inner">
                <div class="tit">
                    <h4>以固定单价订阅 20 年电力的模式</h4>
                    <p>同时实现电费节省, 减碳与 RE100 达成的模式</b></p>
                </div>
                <div class="top"><p>在建筑屋顶安装太阳能</p></div>
                <ul>
                    <li>
                        <i>01</i>
                        <div class="path">
                            <div>
                                <b>可再生能源</b>
                                <p>构建可持续电力基础设施</p>
                            </div>
                            <div class="arrow">
                                <img src="{{ asset('user_assets/img/sub/business/lease_con02_arrow.svg') }}" alt="">
                                <span>20年租赁, 固定电价</span>
                            </div>
                            <div>
                                <b>降低电费</b>
                                <p>20年稳定成本管理</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <i>02</i>
                        <div class="path">
                            <div>
                                <b>减碳成效</b>
                                <p>每年产生 CO₂ 减排效果</p>
                            </div>
                            <div class="arrow">
                                <img src="{{ asset('user_assets/img/sub/business/lease_con02_arrow.svg') }}" alt="">
                                <span>ESG, 碳边境税</span>
                            </div>
                            <div>
                                <b>达成 RE100</b>
                                <p>直接提升企业 ESG 评价</p>
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
                    <span class="effect">专属优势</span>
                    <h3 class="effect delay_1">电费节省与 RE100 达成</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p>电费</p>
                        <h5><b class="blue">20%</b><img src="{{ asset('user_assets/img/icon/down_arrow.svg') }}" alt=""></h5>
                        <span>以约低于韩国电力公司（KEPCO）逐年上涨电价 20% 的单价供电</span>
                    </li>
                    <li class="effect delay_3">
                        <p>RE100</p>
                        <h5><b>达成</b></h5>
                        <span>通过使用可再生能源履行 RE100，并应对碳边境相关措施</span>
                    </li>
                    <li class="effect delay_4">
                        <p>剩余电力</p>
                        <h5><b>销售收入</b></h5>
                        <span>余电可通过可再生能源证书获取额外收益</span>
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
                            <h4>我的大楼发电效果</h4>
                            <p>输入面积并点击“查看效果”</p>
                            <div class="flex_type">
                                <div class="input_box">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                    <b>坪</b>
                                </div>
                                <button onclick="calculatorBtnLease(this)"><span>查看效果</span><i><img src="{{ asset('user_assets/img/icon/loading.svg') }}" alt=""></i></button>
                            </div>
                        </div>
                        <div class="bot">
                        	<h5>20 年预计总节省额</h5>
                            <b><span class="count" data-num="0">0</span>万韩元</b>
                            <div class="table_box">
                                <div class="tit">
                                    <strong>• 预计电费节省额（单位：万韩元）</strong>
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
                                        <th>第 1 年</th>
                                        <th>第 2 年</th>
                                        <th>第 3 年</th>
                                        <th>第 4 年</th>
                                        <th>第 5 年</th>
                                    </tr>
                                    <tr>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                    </tr>
                                    <tr>
                                        <th>第 6 年</th>
                                        <th>第 7 年</th>
                                        <th>第 8 年</th>
                                        <th>...</th>
                                        <th>第 20 年</th>
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
                                    <strong>• 减碳成效</strong>
                                    <span>总 CO2 减排 : 40,000万吨</span>
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
                                        <th>第 1 年</th>
                                        <th>第 2 年</th>
                                        <th>第 3 年</th>
                                        <th>第 4 年</th>
                                        <th>第 5 年</th>
                                    </tr>
                                    <tr>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                        <td class="count" data-num="0">0</td>
                                    </tr>
                                    <tr>
                                        <th>第 6 年</th>
                                        <th>第 7 年</th>
                                        <th>第 8 年</th>
                                        <th>...</th>
                                        <th>第 20 年</th>
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
                                ※ 节省金额按：发电量 ×（韩国电力电价 − 租赁电价）计算。<br/>
                                ※ 韩国电力电价按每年上涨 2% 进行测算。<br/>
                                ※ 实际节省金额可能因韩国电力实际涨价幅度及实际发电量而变化。
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
                        <i class="effect">为什么选择 SOLARTEQ</i>
                        <h3 class="effect delay_1">SOLARTEQ 的 RE100 租赁</b></h3>
                    </div>
                </div>
                <div class="right_box">
                    <ul>
                        <li class="effect">
                            <i><img src="{{ asset('user_assets/img/sub/business/lease_icon_1.svg') }}" alt="">初始投资</i>
                            <p><b>0</b>韩元</p>
                            <span>无需初期投资即可开始</span>
                        </li>
                        <li class="effect delay_1">
                            <i><img src="{{ asset('user_assets/img/sub/business/lease_icon_2.svg') }}" alt="">租赁期限</i>
                            <p><b>20</b>年</p>
                            <span>长期保障合同</span>
                        </li>
                        <li class="effect delay_2">
                            <i><img src="{{ asset('user_assets/img/sub/business/lease_icon_3.svg') }}" alt="">屋顶维护/修理</i>
                            <p><b>免费</b></p>
                            <span>无需担心维护</span>
                        </li>
                        <li class="effect delay_3">
                            <i><img src="{{ asset('user_assets/img/sub/business/lease_icon_4.svg') }}" alt="">余电销售</i>
                            <p><b>额外收益</b></p>
                            <span>无需担心维护</span>
                        </li>
                    </ul>
                    <p class="txt effect delay_4">※ 数据以 20 年总额为基准展示。<br/>※ 结果可能因装机容量不同而有所差异。</p>
                </div>
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
	                                    <div class="tit"><h5>{{$item->station_name_cn}}</h5><strong>{{$item->price_cn}}</strong><span>(20 年预计节省额)</span></div>
	                                    <div class="info">
	                                        <dl>
	                                            <dt>发电容量</dt>
	                                            <dd>{{number_format($item->volume)}}kW</dd>
	                                        </dl>
	                                        <dl>
	                                            <dt>地区</dt>
	                                            <dd>{{ $item->regions->name_cn ?? '' }} {{ $item->cities->name_cn ?? '' }}</dd>
	                                        </dl>
	                                        <dl>
	                                            <dt>类型</dt>
	                                            <dd>{{ $item->formTypes->subject_cn ?? '' }}</dd>
	                                        </dl>
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
        <div class="section lease_con06">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">分步流程</span>
                    <h3 class="effect delay_1">RE100 租赁流程</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_1.svg') }}" alt=""></i>咨询与可行性/收益性评估</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/lease_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_2.svg') }}" alt=""></i>与租赁法人签订租赁合同</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/lease_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_3.svg') }}" alt=""></i>通过 3D 建模进行定制化设计</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/lease_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_4.svg') }}" alt=""></i>坚固安全的施工</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/sub/business/lease_con06_arrow.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/business/lease_con06_icon_5.svg') }}" alt=""></i>供电并形成 RE100 履约实</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(2)">申请咨询</button>
                </div>
            </div>
        </div>
        
        <!-- QnA -->
        <div class="section qna_con lease_qna">
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
                        <dt onclick="toggleQna(this)">屋顶修缮费用是多少？</dt>
                        <dd><div class="answer">在安装太阳能电站前会先进行屋顶修缮及防水施工，相关费用均由 SOLARTEQ 承担。</div></dd>
                    </dl>
                    <dl>
                        <dt onclick="toggleQna(this)">电价是否 20 年固定？</dt>
                        <dd><div class="answer">可通过 20 年固定的 RE100 租赁订阅电价，使用太阳能电站所生产的电力。</div></dd>
                    </dl>
                </div>
                <div class="btn_box effect delay_3">
                    <a href="/ko/business/qna">查看更多</a>
                </div>
            </div>
        </div>

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
                alert('请输入正确的面积。');
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
            $carbonTitle.text(`总 CO2 减排 : ${carbonTotal20.toLocaleString('ko-KR')}万吨`);
 
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
@include('user.cn.footer._gtm')
</html>