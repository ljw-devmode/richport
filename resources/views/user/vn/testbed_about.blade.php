@include('user.vn.header._head')
<body>
	@include('user.vn.header._header')
    <main id="fullpage">
        <div class="section test_con01">
            <div class="sub_inner">
                <h4 class="effects">Giới thiệu Testbed</h4>
                <p class="effects delay_1">Hãy bán thử các sản phẩm mới đang được ưa chuộng tại Hàn Quốc.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section test_con02">
            <div class="box">
                <div class="left_txt">
                    <div class="txt_box">
                        <i class="effect">TESTBED</i>
                        <h3 class="effect delay_1"><b>Testbed</b> của RICHPORT</h3>
                    </div>
                </div>
                <div class="right_box">
                    <ul>
                        <li class="effect">
                            <i><img src="{{ asset('user_assets/img/sub/icon/box.svg') }}" alt="">Số lượng tối thiểu</i>
                            <p><b>100</b> sản phẩm</p>
                            <span>Bán thử K-Product Hàn Quốc với số lượng tối thiểu</span>
                        </li>
                        <li class="effect delay_1">
                            <i><img src="{{ asset('user_assets/img/sub/icon/price_tag.svg') }}" alt="">Bán trước</i>
                            <p><b>2~3</b> tháng</p>
                            <span>Bán sản phẩm mới sớm hơn 2~3 tháng so với các nhà phân phối khác</span>
                        </li>
                        <li class="effect delay_2">
                            <i><img src="{{ asset('user_assets/img/sub/icon/survey.svg') }}" alt="">Khảo sát</i>
                            <p><b>Nghiên cứu thị trường</b></p>
                            <span>Khảo sát người mua và báo cáo kết quả</span>
                        </li>
                        <li class="effect delay_3">
                            <i><img src="{{ asset('user_assets/img/sub/icon/report.svg') }}" alt="">Báo cáo sản phẩm</i>
                            <p><b>Dự báo bán hàng</b></p>
                            <span>Giảm thiểu rủi ro nhờ dự báo khả năng bán và nhu cầu dự kiến</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="section test_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">PROCESS</span>
                    <h3 class="effect delay_1">Quy trình sử dụng Testbed</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/apply.svg') }}" alt=""></i>Đăng ký sử dụng</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/store_research.svg') }}" alt=""></i>Khảo sát điểm bán</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/contract.svg') }}" alt=""></i>Ký hợp đồng bán hàng</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/survey.svg') }}" alt=""></i>Bán hàng & khảo sát</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/report.svg') }}" alt=""></i>Báo cáo bán hàng</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(2)">Đăng ký Testbed</button>
                    <p>✱ Giảm thiểu rủi ro xuất nhập khẩu sản phẩm với Testbed.</p>
                </div>
            </div>
        </div>
        
        <!-- <div class="section test_con04">
            <div class="w1700">
                <div class="sub_tit">
                    <span data-aos="fade-up">TESTBED PRODUCTS</span>
                    <h3 data-aos="fade-up" data-aos-delay="150">Sản phẩm Testbed</h3>
                </div>
            </div>

            <div class="test_box">
                <div class="w1700">
                	<form method="GET" action="/vn/testbed/about">
	                    <div class="test_form" data-aos="fade-up">
	                        <div class="search">
	                            <input type="text" id="product_name" name="product_name" placeholder="Tìm kiếm tên sản phẩm" value="{{ request('product_name') }}">
	                            <button><img src="{{ asset('user_assets/img/icon/search.svg') }}" alt=""></button>
	                        </div>
	                    </div>
	                </form>
                    <div class="test_list" data-aos="fade-up">
                        <ul>
                        	@forelse ($data as $index => $item)
                            <li>
                            	<a href="/vn/portfolio/detail/{{ $item->idx }}">
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
                            	<li class="empty">Chưa có sản phẩm thử nghiệm nào được đăng ký.</li>
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
            @include('user.vn.footer._footer')
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
@include('user.vn.footer._gtm')
</html>