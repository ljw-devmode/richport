@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="wrap">
        <!-- 포트폴리오 -->
        <div class="sub_banner partner_korea_banner">
            <div class="sub_inner type02">
                <p class="effect font_color_white">Korea Partners</p>
                <span class="effect delay_1 font_color_white">Korea partner description</span>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="portfolio_area">
            <div class="w1700">
                <div class="sub_tit">
                    <span data-aos="fade-up">KOREA PARTNER</span>
                    <h3 data-aos="fade-up" data-aos-delay="150">Korean partners<br/><b>exporting products to Vietnam</b></h3>
                </div>
            </div>

            <div class="portfolio_box">
                <div class="w1700">
                	<form method="GET" action="/en/partner/korea">
	                    <div class="portfolio_form" data-aos="fade-up">
	                        <!-- <div class="category">
	                        	<input type="radio" id="forms_idx_all" name="forms_idx"  value="" {{ request('forms_idx') === null || request('forms_idx') === '' ? 'checked' : '' }}><label for="forms_idx_all">View All</label>
	                        	@foreach ($types as $item)
		                            <input type="radio" id="forms_idx_{{$item->idx}}" name="forms_idx" value="{{$item->idx}}" {{ request('forms_idx') == $item->idx ? 'checked' : '' }}><label for="forms_idx_{{$item->idx}}">{{$item->tr('subject')}}</label>
	                            @endforeach
	                        </div> -->
	                        <div class="search">
	                            <input type="text" id="partner_name" name="partner_name" placeholder="Search by company name" value="{{ request('partner_name') }}">
	                            <button><img src="{{ asset('user_assets/img/icon/search.svg') }}" alt=""></button>
	                        </div>
	                    </div>
	                </form>
                    <div class="portfolio_list" data-aos="fade-up">
                        <ul>
                        	@forelse ($data as $index => $item)
                            <li>
                            	<!-- <a href="/en/partner/detail/{{ $item->idx }}"> -->
	                                <div class="img_box">
	                                	<span>{{ $item->forms?->tr('subject') ?? '' }}</span>
	                                	<img src="{{ Storage::disk('public')->url($item->pc_up_name) }}" alt="">
	                                </div>
	                                <div class="txt_box">
	                                    <div class="top">
	                                        <span>{{ $item->cities?->tr('name') ?? '' }} {{ $item->districts?->tr('name') ?? '' }}</span>
	                                    </div>
	                                    <div class="tit">
	                                        <p>{{$item->tr('partner_name')}}</p>
	                                    </div>
	                                </div>
	                            <!-- </a> -->
                            </li>
                            @empty
                            	<li class="empty">No partners found.</li>
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
        </div>

        @include('user.en.footer._footer')
    </main>
    <script>

	    $(window).on('load', function(){
	        setTimeout(function(){
	            $('.sub_banner').addClass('sub-active')
	        },100);
	
	        let search = window.location.search;
	        if(search.split('?')[1]){
				let portTop = $('.portfolio_box').offset().top - 50;
		        //let portTop = $('.portfolio_area').offset().top - 50;
	
	            window.lenis.scrollTo(portTop, {
	                duration: 0,
	                easing: (t) => t * (2 - t), // ease-out curve
	            });
	        }
	    });

	    $(function () {
	        $('input[name="forms_idx"]').on('change', function () {
	            $(this).closest('form').submit();
	        });
	    });

    </script>
</body>
@include('user.en.footer._gtm')
</html>