@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="wrap">
        <!-- 포트폴리오 -->
        <div class="sub_banner portfolio_banner">
            <div class="sub_inner type02">
                <p class="effect font_color_white">Case Studies</p>
                <span class="effect delay_1 font_color_white">See the real results of brands and projects that have worked with RICHPORT.</span>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="portfolio_area">
            <div class="w1700">
                <div class="sub_tit">
                    <span data-aos="fade-up">PORTFOLIO</span>
                    <h3 data-aos="fade-up" data-aos-delay="150">RICHPORT RIPS,<br/><b>proven by companies of all kinds</b></h3>
                </div>
            </div>

            <div class="portfolio_box">
                <div class="w1700">
                	<form method="GET" action="/en/rips/portpolio">
	                    <div class="portfolio_form" data-aos="fade-up">
	                        <!-- <div class="category">
								<select id="types_idx" name="types_idx">
									<option value="">View All</option>
									@foreach ($types as $item)
										<option value="{{$item->idx}}" {{ request('types_idx') == $item->idx ? 'selected' : '' }}>{{$item->tr('subject')}}</option>
	                        	 	@endforeach
								</select>
	                        </div> -->
	                        <div class="search">
	                            <input type="text" id="station_name" name="station_name" placeholder="Search by company name" value="{{ request('station_name') }}">
	                            <button><img src="{{ asset('user_assets/img/icon/search.svg') }}" alt=""></button>
	                        </div>
	                    </div>
	                </form>
                    <div class="portfolio_list" data-aos="fade-up">
                        <ul>
                        	@forelse ($data as $index => $item)
                            <li>
                            	<!-- <a href="/en/portfolio/detail/{{ $item->idx }}"> -->
	                                <div class="img_box">
	                                	<span>{{ $item->types?->tr('subject') ?? '' }}</span>
	                                	<img src="{{ Storage::disk('public')->url($item->pc_up_name) }}" alt="">
	                                </div>
	                                <div class="txt_box">
	                                    <div class="top">
	                                        <span>{{ $item->cities?->tr('name') ?? '' }} {{ $item->districts?->tr('name') ?? '' }}</span>
	                                    </div>
	                                    <div class="tit">
	                                        <p>{{$item->tr('station_name')}}</p>
	                                    </div>
	                                </div>
	                            <!-- </a> -->
                            </li>
                            @empty
                            	<li class="empty">No case studies found.</li>
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
	        $('select[name="types_idx"]').on('change', function () {
	            $(this).closest('form').submit();
	        });
	    });

    </script>
</body>
@include('user.en.footer._gtm')
</html>