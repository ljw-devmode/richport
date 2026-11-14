@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="wrap">
        <!-- 포트폴리오 -->
        <div class="sub_banner partner_vietnam_banner">
            <div class="sub_inner type02">
                <p class="effect font_color_white">베트남 파트너</p>
                <span class="effect delay_1 font_color_white">베트남 파트너 설명</span>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="partner_area">
            <div class="w1700">
                <div class="sub_tit">
                    <span data-aos="fade-up">VIETNAM PARTNER</span>
                    <h3 data-aos="fade-up" data-aos-delay="150"><b>한국 상품을 유통</b>하는<br/>베트남 파트너</h3>
                </div>
            </div>

            <div class="partner_box">
                <div class="w1700">
                	<form method="GET" action="/ko/partner/vietnam">
	                    <div class="partner_form" data-aos="fade-up">
	                        <div class="category">
	                        	<input type="radio" id="types_idx_all" name="types_idx"  value="" {{ request('types_idx') === null || request('types_idx') === '' ? 'checked' : '' }}><label for="types_idx_all">전체보기</label>
	                        	@foreach ($types as $item)
		                            <input type="radio" id="types_idx_{{$item->idx}}" name="types_idx" value="{{$item->idx}}" {{ request('types_idx') == $item->idx ? 'checked' : '' }}><label for="types_idx_{{$item->idx}}">{{$item->tr('subject')}}</label>
	                            @endforeach
	                        </div>
	                        <div class="search">
	                            <input type="text" id="partner_name" name="partner_name" placeholder="업체명으로 검색" value="{{ request('partner_name') }}">
	                            <button><img src="{{ asset('user_assets/img/icon/search.svg') }}" alt=""></button>
	                        </div>
	                    </div>
	                </form>
                    <div class="partner_list" data-aos="fade-up">
                        <ul>
                        	@forelse ($data as $index => $item)
                            <li>
                            	<!-- <a href="/ko/partner/detail/{{ $item->idx }}"> -->
	                                <div class="img_box">
	                                	<span>{{ $item->types?->tr('subject') ?? '' }}</span>
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
                            	<li class="empty">등록된 파트너가 없습니다.</li>
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
	        $('input[name="types_idx"]').on('change', function () {
	            $(this).closest('form').submit();
	        });
	    });

    </script>
</body>
@include('user.ko.footer._gtm')
</html>