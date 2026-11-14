@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="wrap">
        <!-- 상품 등록 -->
        <div class="sub_banner partner_korea_banner">
            <div class="sub_inner type02">
                <p class="effect font_color_white">상품 등록(Tu Cong Bo)</p>
                <span class="effect delay_1 font_color_white">상품 등록(Tu Cong Bo)</span>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>


		@if (!$lastdata->isEmpty())
        <div class="product_cont">
            <div class="sub_inner3">
                <div class="sub_tit">
                    <span data-aos="fade-up">Tu Cong Bo</span>
                    <h3 data-aos="fade-up" data-aos-delay="150">최근 등록 상품</h3>
                </div>

				 <div class="product_area type02" data-aos="fade-up" data-aos-delay="450">
                    <div class="">
                        <ul class="">
                        	@forelse ($lastdata as $index => $item)
                            <li>
								<div class="img_box"><img src="{{ Storage::disk('public')->url($item->pc_up_name) }}" alt=""></div>
								<div class="txt_box">{{$item->tr('product_name')}}</div>
                            </li>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                </div>
			</div>
		</div>
		@endif

        <div class="product_cont">
            <div class="w1700">
                <div class="sub_tit">
                    <span data-aos="fade-up">Tu Cong Bo</span>
                    <h3 data-aos="fade-up" data-aos-delay="150">등록 완료 상품</h3>
                </div>
            </div>

            <div class="portfolio_box">
                <div class="w1700">
                	<form method="GET" action="/ko/product/congbo">
	                    <div class="portfolio_form" data-aos="fade-up">
	                        <div class="search">
	                            <input type="text" id="product_name" name="product_name" placeholder="상품명 검색" value="{{ request('product_name') }}">
	                            <button><img src="{{ asset('user_assets/img/icon/search.svg') }}" alt=""></button>
	                        </div>
	                    </div>
	                </form>
                    <div class="portfolio_list" data-aos="fade-up">
						<table class="table table-sm table-bordered table-hover product_table">

							<thead class="bg-slate-50 text-center">
								<tr>
									<th class="whitespace-nowrap">번호</th>
									<th class="whitespace-nowrap modisplaynone">등록번호</th>
									<!-- <th class="whitespace-nowrap">카테고리</th> -->
									<th class="whitespace-nowrap">업체명</th>
									<th class="whitespace-nowrap">상품명</th>
									<th class="whitespace-nowrap">등록일</th>
								</tr>
							</thead>
							<tbody class="text-center font-medium">
								@forelse ($data as $index => $item)
									@php
										$number = ($data->currentPage() - 1) * $data->perPage() + ($index + 1);
									@endphp
									<tr>
										<td>{{ $number }}</td>
										<td class="modisplaynone">{{ $item->congbo_number }}</td>
										<!-- <td>{{ $item->forms?->tr('subject') }}</td> -->
										<td>{{ $item->partners?->tr('partner_name') }}</td>
										<td>{{ $item->tr('product_name') }}</td>
										<td>{{ $item->created_at?->format('Y-m-d') }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="13" class="text-center">등록된 제품이 없습니다.</td>
									</tr>
								@endforelse
							</tbody>
						</table>


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