@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="wrap">
        <!-- 포트폴리오 -->
        <div class="sub_banner partner_korea_banner">
            <div class="sub_inner type02">
                <p class="effect font_color_white">New Products</p>
                <span class="effect delay_1 font_color_white">Discover newly launched Korean products</span>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="portfolio_area">
            <div class="w1700">
                <div class="sub_tit">
                    <span data-aos="fade-up">NEW PRODUCTS</span>
                    <h3 data-aos="fade-up" data-aos-delay="150">RICHPORT New Products</h3>
                </div>
            </div>

            <div class="portfolio_box">
                <div class="w1700">
                	<form method="GET" action="/en/product/new">
	                    <div class="portfolio_form" data-aos="fade-up">
	                        <div class="search">
	                            <input type="text" id="product_name" name="product_name" placeholder="Search by product name" value="{{ request('product_name') }}">
	                            <button><img src="{{ asset('user_assets/img/icon/search.svg') }}" alt=""></button>
	                        </div>
	                    </div>
	                </form>
                    <div class="portfolio_list" data-aos="fade-up">
                        <ul>
                        	@forelse ($data as $index => $item)
                            <li onclick="setViewForm('{{ $item->idx }}')">
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
                            </li>
                            @empty
                            	<li class="empty">No products found.</li>
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

	<div id="product_view-modal" class="modal "> 
		<div id="product_modal_body" class="product_detail_modal"></div>
	</div>


	
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

		function setViewForm(idx) {

			removedFileIdxs = [];
			existingFiles = [];
			
			$.ajax({
				url: '/en/product/detail/'+idx,
				method: 'POST',
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				success: function (res) {
					if (res.status !== 'success') {
						showToast(res.message || 'Failed to load','error');
						return;
					}

					const d = res.data;
					const files = res.files || [];
					//console.log(d);

					const subject = (d.forms && d.forms.subject) ? d.forms.subject : '';
					const partner_name = (d.partners && d.partners.partner_name) ? d.partners.partner_name : '';
					const product_name = d.product_name || '';
					const contents = d.contents || '';

					$('#testbed_types_idx').val(d.idx);
					$('#testbed_product_name').val(product_name);

					
					let filesHtml = '';
					if (files.length > 0) {
						filesHtml = `
						<div class="case_box">
								<ul >
						`;

						files.forEach(function (file) {
							filesHtml += `
								<li >
									<img src="/storage/${file.up_name}" alt="">
								</li>
							`;
						});

						filesHtml += `
								</ul>
						</div>
						`;
					}

					const totalHtml = `

						<div class="w1700">
							<div class="tit">
								<div class="left_tit">
									<span>[${subject}] ${partner_name} ${product_name}</span>
									<h4>${contents}</h4>
								</div>
							</div>
						</div>

						<div class="w1700">
							${filesHtml}
							<div class="btn_box">
								<a href="javascript:;" onclick="testbed_inquiry();">Apply for Testbed</a>
								<a href="javascript:;" onclick="modalClose('product_view-modal');">Close</a>
							</div>
						</div>

					`;		
					
					$('#product_modal_body').html(totalHtml);
					modalOpen('product_view-modal');


					// ==========================================
					// ★ [치트키] 플러그인 차단을 원천 봉쇄하는 네이티브 휠 스크롤
					// ==========================================
					
					// 1. 제이쿼리가 아닌 순수 자바스크립트로 엘리먼트 추적
					var modalBox = document.querySelector('.product_detail_modal');
					var modalWrapper = document.getElementById('product_view-modal');

					if (modalBox && modalWrapper) {
						// 기존에 제이쿼리나 테마가 걸어둔 휠 방어벽 이벤트를 완전히 삭제
						$(modalWrapper).off('wheel DOMMouseScroll mousewheel touchmove');

						// 2. 브라우저가 이벤트를 임의로 취소하지 못하게 'passive: false' 옵션을 줘서 바인딩
						modalWrapper.addEventListener('wheel', function(e) {
							// 풀페이지와 메인 윈도우로 전달되는 마우스 스크롤 신호를 삭제
							e.preventDefault();
							e.stopPropagation();

							// 휠 방향 감지 (아래: 양수 / 위: 음수)
							var delta = e.deltaY;
							
							// 정밀 보정: 자바스크립트가 부모 창 대신 모달 내부 박스의 탑 좌표를 강제로 픽셀 단위 조절
							modalBox.scrollTop += (delta * 0.8); 
						}, { passive: false });

						// 3. 모바일 터치 스크롤도 동일하게 강제 연동 (스마트폰 크롬/사파리 대응)
						var startTouchY = 0;
						modalWrapper.addEventListener('touchstart', function(e) {
							startTouchY = e.touches[0].clientY;
						}, { passive: true });

						modalWrapper.addEventListener('touchmove', function(e) {
							// 뒷배경 풀페이지가 흔들리지 않도록 모바일 터치 전파 차단
							e.preventDefault();
							e.stopPropagation();

							var currentTouchY = e.touches[0].clientY;
							var moveY = startTouchY - currentTouchY;
							
							modalBox.scrollTop += (moveY * 1.2);
							startTouchY = currentTouchY; // 위치 최신화
						}, { passive: false });
					}

					// 4. 풀페이지 API 기능 일시 중단
					if (typeof fullpage_api !== 'undefined') {
						fullpage_api.setAllowScrolling(false);
						fullpage_api.setKeyboardScrolling(false);
					}
				},
				error: function (xhr) {
					console.error(xhr.responseText);
				}
			});
		}		

		function testbed_inquiry() {
			modalClose('product_view-modal');
			modalOpen('testbed_inquiry-modal');
		}


    </script>
</body>
@include('user.en.footer._gtm')
</html>