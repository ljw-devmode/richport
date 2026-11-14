@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="wrap">
        <!-- 포트폴리오 -->
        <div class="sub_banner portfolio_banner">
            <div class="sub_inner type02">
                <p class="effect">도입사례</p>
                <span class="effect delay_1">설명문구1 <br/>설명문구2</span>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="portfolio_area">
            <div class="w1700">
                <div class="sub_tit">
                    <span data-aos="fade-up">PORTFOLIO</span>
                    <h3 data-aos="fade-up" data-aos-delay="150"><b>다양한 기업들이 증명</b>하는<br/> 리치포트 서비스</h3>
                </div>
            </div>

            <div class="portfolio_box">
                <div class="w1700">
                	<form method="GET" action="/ko/portfolio">
	                    <div class="portfolio_form" data-aos="fade-up">
	                        <div class="category">
	                        	<input type="radio" id="form_idx_all" name="form_idx"  value="" {{ request('form_idx') === null || request('form_idx') === '' ? 'checked' : '' }}><label for="form_idx_all">전체보기</label>
	                        	@foreach ($types as $item)
		                            <input type="radio" id="form_idx_{{$item->idx}}" name="form_idx" value="{{$item->idx}}" {{ request('form_idx') == $item->idx ? 'checked' : '' }}><label for="form_idx_{{$item->idx}}">{{$item->tr('subject')}}</label>
	                            @endforeach
	                        </div>
	                        <div class="search">
	                            <input type="text" id="station_name" name="station_name" placeholder="업체명으로 검색해 주세요." value="{{ request('station_name') }}">
	                            <button><img src="{{ asset('user_assets/img/icon/search.svg') }}" alt=""></button>
	                        </div>
	                    </div>
	                </form>
                    <div class="portfolio_list" data-aos="fade-up">
                        <ul>
                        	@forelse ($data as $index => $item)
                            <li>
								<div class="img_box">
									<span>{{ $item->formTypes?->tr('subject') ?? '' }}</span>
									<img src="{{ Storage::disk('public')->url($item->pc_up_name) }}" alt="">
								</div>
								<div class="txt_box">
									<div class="top">
										<span>{{ $item->regions?->tr('name') ?? '' }} {{ $item->cities?->tr('name') ?? '' }}</span>
										<b>{{number_format($item->volume)}}kW</b>
									</div>
									<div class="tit">
										<p>{{$item->tr('station_name')}}</p>
										<div class="link_box">
											@if($item->url)
											<a href="{{$item->url}}" target="_blank"><img src="{{ asset('user_assets/img/icon/youtube.svg') }}" alt=""></a>
											@endif
											@if($item->blog)
											<a href="{{$item->blog}}" target="_blank"><img src="{{ asset('user_assets/img/icon/blog.svg') }}" alt=""></a>
											@endif
										</div>
									</div>
								</div>
                            </li>
                            @empty
                            	<li class="empty">등록된 포트폴리오가 없습니다.</li>
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

        @include('user.ko.footer._footer')
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
	        $('input[name="form_idx"]').on('change', function () {
	            $(this).closest('form').submit();
	        });
	    });

    </script>
</body>
@include('user.ko.footer._gtm')
</html>