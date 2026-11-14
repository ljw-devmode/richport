@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="fullpage">
        <div class="section test_con01">
            <div class="sub_inner">
                <h4 class="effects">테스트베드 소개</h4>
                <p class="effects delay_1">한국의 인기 신상품을 테스트로 판매해보세요.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section test_con02">
            <div class="box">
                <div class="left_txt">
                    <div class="txt_box">
                        <i class="effect">TESTBED</i>
                        <h3 class="effect delay_1">리치포트 <b>테스트베드</b></h3>
                    </div>
                </div>
                <div class="right_box">
                    <ul>
                        <li class="effect">
                            <i><img src="{{ asset('user_assets/img/sub/icon/box.svg') }}" alt="">최소수량</i>
                            <p><b>100</b>개</p>
                            <span>최소 수량으로 한국 K-Product를 시험 판매</span>
                        </li>
                        <li class="effect delay_1">
                            <i><img src="{{ asset('user_assets/img/sub/icon/price_tag.svg') }}" alt="">사전 판매</i>
                            <p><b>2~3</b>개월</p>
                            <span>타 유통업체 대비 2~3개월 빠른 신상품 판매</span>
                        </li>
                        <li class="effect delay_2">
                            <i><img src="{{ asset('user_assets/img/sub/icon/survey.svg') }}" alt="">설문조사</i>
                            <p><b>시장 조사</b></p>
                            <span>구매자 설문조사 및 결과 리포트</span>
                        </li>
                        <li class="effect delay_3">
                            <i><img src="{{ asset('user_assets/img/sub/icon/report.svg') }}" alt="">상품 리포트</i>
                            <p><b>판매 예측</b></p>
                            <span>판매가능성, 예상수요 예측으로 리스크 최소화</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="section test_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">PROCESS</span>
                    <h3 class="effect delay_1">테스트베드 이용 절차</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/apply.svg') }}" alt=""></i>이용신청</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/store_research.svg') }}" alt=""></i>판매처 현황조사</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/contract.svg') }}" alt=""></i>판매 계약</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/survey.svg') }}" alt=""></i>판매·설문조사</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/report.svg') }}" alt=""></i>판매 리포트</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(2)">테스트베드 이용 신청</button>
                    <p>✱ 테스트베드를 통해서 상품 수출입 리스크를 최소화하세요.</p>
                </div>
            </div>
        </div>
        
        <!-- <div class="section test_con04">
            <div class="w1700">
                <div class="sub_tit">
                    <span data-aos="fade-up">TESTBED PRODUCTS</span>
                    <h3 data-aos="fade-up" data-aos-delay="150">테스트베드 상품</h3>
                </div>
            </div>

            <div class="test_box">
                <div class="w1700">
                	<form method="GET" action="/ko/testbed/about">
	                    <div class="test_form" data-aos="fade-up">
	                        <div class="search">
	                            <input type="text" id="product_name" name="product_name" placeholder="상품명 검색" value="{{ request('product_name') }}">
	                            <button><img src="{{ asset('user_assets/img/icon/search.svg') }}" alt=""></button>
	                        </div>
	                    </div>
	                </form>
                    <div class="test_list" data-aos="fade-up">
                        <ul>
                        	@forelse ($data as $index => $item)
                            <li>
                            	<a href="/ko/portfolio/detail/{{ $item->idx }}">
	                                <div class="img_box">
	                                	<span>{{ $item->forms?->tr('subject') ?? '' }}</span>
	                                	<img src="{{ Storage::disk('public')->url($item->pc_up_name) }}" alt="">
	                                </div>
	                                <div class="txt_box">
	                                    <div class="top">
	                                        <span>{{ $item->partners?->tr('partner_name') ?? '' }}</span>
	                                    </div>
	                                    <div class="tit">
	                                        <p>{{$item->tr('product_name')}}</p>
	                                    </div>
	                                </div>
	                            </a>
                            </li>
                            @empty
                            	<li class="empty">등록된 테스트 상품이 없습니다.</li>
                            @endforelse
                        </ul>
                        @php
						    $currentPage = $data->currentPage();
						    $lastPage = $data->lastPage();
						    
						    $blockSize = 5;                                
						    $currentBlock = (int) ceil($currentPage / $blockSize);
						
						    $start = ($currentBlock - 1) * $blockSize + 1;
						    $end = min($start + $blockSize - 1, $lastPage);
						@endphp
						<div class="pager">
						    {{-- 이전 페이지 버튼 --}}
						    <a class="prev {{ $currentPage == 1 ? 'disabled' : '' }}"
						       href="{{ $currentPage == 1 ? 'javascript:;' : $data->previousPageUrl() }}">
						        <img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt="">
						    </a>
						
						    {{-- 페이지 번호 --}}
						    @for ($i = $start; $i <= $end; $i++)
						        <a href="{{ $data->url($i) }}" class="{{ $i == $currentPage ? 'active' : '' }}">
						            {{ $i }}
						        </a>
						    @endfor
						
						    {{-- 다음 페이지 버튼 --}}
						    <a class="next {{ $currentPage == $lastPage ? 'disabled' : '' }}"
						       href="{{ $currentPage == $lastPage ? 'javascript:;' : $data->nextPageUrl() }}">
						        <img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt="">
						    </a>
						</div>
                    </div>
                </div>
            </div>
        </div>         -->

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
        });
        $(window).on('resize', function() {
            $.fn.fullpage.reBuild();
        });

        $(window).on('load', function(){
            setTimeout(function(){
                $('.about_con01').addClass('on')
            },100);

            setTimeout(function(){
                $('.tab_content > div').eq(1).removeClass('active')
            },400);

            $('.tab_box a').on('click',function(){
                let liN = $(this).index();
                $(this).addClass('active').siblings().removeClass('active');
                $('.tab_content > div').eq(liN).addClass('active').siblings().removeClass('active');
            })
        });

    </script>
</body>
@include('user.ko.footer._gtm')
</html>