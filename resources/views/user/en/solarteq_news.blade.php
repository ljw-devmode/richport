@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="wrap">
        <div class="sub_banner news_banner">
            <div class="sub_inner">
                <p class="effect delay_1">We present SOLARTEQ’s achievements and stories through videos, blogs, and press releases</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>
        <div class="news_cont">
            <div class="news_box">
                <div class="w1700">
                    <div class="sub_tit">
                        <span data-aos="fade-up">NEWS</span>
                        <h3 data-aos="fade-up" data-aos-delay="150">SOLARTEQ News</h3>
                        <p data-aos="fade-up" data-aos-delay="300">SOLARTEQ stories shared across various media</p>
                    </div>
                    <div class="news_list" data-aos="fade-up" data-aos-delay="300">
                        <ul>
                        	@foreach ($data as $index => $item)
                        		<li>
                        			<!-- a href="javascript:;" onclick="openNotice({{$item->idx}})" -->
		                                <div class="img_box">
		                                	@if($item->firstFile)
											    <img src="{{ Storage::disk('public')->url($item->firstFile->up_name) }}" alt="">
											@endif
		                                </div>
		                                <div class="tit">{{$item->writed_at}}</div>
		                                <div class="tit">{{$item->subject_en}}</div>
	                            	<!-- /a -->
                            	</li>
                        	@endforeach
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

            <div class="news_box">
                <div class="w1700">
                    <div class="sub_tit">
                        <span data-aos="fade-up">SOLARTUBE</span>
                        <h3 data-aos="fade-up" data-aos-delay="150">Solartube</h3>
                        <p data-aos="fade-up" data-aos-delay="300">Experience SOLARTEQ Through Video</p>
                    </div>
                    <div class="tube_list" data-aos="fade-up" data-aos-delay="300">
                        <ul>
                            <li><a href="https://www.youtube.com/watch?v=B3hzO4AkKrI" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/1. 영화금속 인터뷰.png') }}" alt=""></div>
                                <div class="tit">Customer Interview: Power for 600 Homes! Yeonghwa Metal’s RE100 Journey</div>
                            </a></li>
                            <li><a href="javascript:;" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/2. 화성 파카코리아 드론.png') }}" alt=""></div>
                                <div class="tit">1.3MW Solar Plant Drone Footage – Hwaseong Hydraulic Factory</div>
                            </a></li>
                            <li><a href="javascript:;" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/3. 모나크CC 인터뷰.png') }}" alt=""></div>
                                <div class="tit">Customer Interview: Monarch CC 2.2MW Solar Installation. Unexpected Effects?</div>
                            </a></li>
                            <li><a href="javascript:;" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/4. 청주 한청판지 드론.png') }}" alt=""></div>
                                <div class="tit">2.3MW Solar Drone Footage – Cheongju Paper Mill</div>
                            </a></li>
                            <li><a href="javascript:;" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/5. 아이소매트릭.png') }}" alt=""></div>
                                <div class="tit">SOLARTEQ┃Your Energy Asset Partner (Isometric)</div>
                            </a></li>
                            <li><a href="javascript:;" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/6. 평택 매일유업 드론.png') }}" alt=""></div>
                                <div class="tit">Pyeongtaek Maeil Dairy 250kW Rooftop Solar Completion</div>
                            </a></li>
                        </ul>

                        <div class="btn_box">
                            <a href="https://www.youtube.com/channel/UC09-6cslYKyVAiH86JmuvrA" target="_blank">View More Videos</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="news_box">
                <div class="w1700">
                    <div class="sub_tit">
                        <span data-aos="fade-up">BLOG</span>
                        <h3 data-aos="fade-up" data-aos-delay="150">SOLARTEQ Blog</h3>
                        <p data-aos="fade-up" data-aos-delay="300">Stories Delivered by Soltek</p>
                    </div>
                    <div class="blog_list" data-aos="fade-up" data-aos-delay="300">
                        <ul>
                            <li><a href="https://blog.naver.com/solarteq/224022706649" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/1. 대표님 수상 건.png') }}" alt=""></div>
                                <div class="txt_box">
                                    <div class="tit">Heartfelt Acts of Community Care</div>
                                    <div class="desc">SOLARTEQ Receives Hwaseong City Mayor’s Commendation at the 2025 Volunteer Day Ceremony</div>
                                </div>
                            </a></li>
                            <li><a href="https://blog.naver.com/solarteq/223712733798" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/2. 울산 신화인텍.png') }}" alt=""></div>
                                <div class="txt_box">
                                    <div class="tit">Ulsan Shinhwa Intech Construction Case</div>
                                    <div class="desc">Financial Support Program: How a 300kW Power Plant Reduced Costs and Increased Profit</div>
                                </div>
                            </a></li>
                            <li><a href="https://blog.naver.com/solarteq/223904842286" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/3. 울산 세일.png') }}" alt=""></div>
                                <div class="txt_box">
                                    <div class="tit">Ulsan SAEL Construction Case</div>
                                    <div class="desc">Construction Process of 300kW Rooftop Solar Power Plant at SAEIL Co., Ltd., Ulsan</div>
                                </div>
                            </a></li>
                            <li><a href="https://blog.naver.com/solarteq/224029603233" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/4. 청주 한청판지.png') }}" alt=""></div>
                                <div class="txt_box">
                                    <div class="tit">Cheongju Hancheong Paper Construction Case</div>
                                    <div class="desc">Completion of Hancheong Paper 2.3MW Solar Power Plant, Smart Eco-Factory Construction Process</div>
                                </div>
                            </a></li>
                        </ul>
                        
                        <div class="btn_box">
                            <a href="https://blog.naver.com/solarteq" target="_blank">View More Soltek Blog</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- news 모달 -->
        <div class="modal" id="news-modal">
            <div class="modal_wrap modal-lg">
                <div class="modal_header">
                    <h3>SOLARTEQ News</h3>
                    <button onclick="modalClose('news-modal')"><img src="{{ asset('user_assets/img/icon/x.svg') }}" alt=""></button>
                </div>
                <div class="modal_body in_scroll" data-lenis-prevent>
                    <div class="news_detail">
                        <div class="tit" id="detailSubject"></div>
                        <div class="content">
                            <div class="news_slide">
                                <ul class="swiper-wrapper" id="detailImgWrapper">
                                </ul>
                                <button class="arrow prev"></button>
                                <button class="arrow next"></button>
                            </div>
                            <div id="detailContents"></div>
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
	            let portTop = $('.sub_tit').offset().top - 50;
	
	            window.lenis.scrollTo(portTop, {
	                duration: 0.8,
	                easing: (t) => t * (2 - t), // ease-out curve
	            });
	        }
	    });

        let newsSwiper = null;

        $(function () {
            newsSwiper = new Swiper('.news_slide', {
            	speed:1000,
                slidesPerView: 1,
                spaceBetween: 10,
                //loop: true,
                navigation: {
                    nextEl: '.news_slide .next',
                    prevEl: '.news_slide .prev',
                },
                autoHeight: true,         // ← 내용 높이대로 컨테이너 높이 자동 조절
                observer: true,
                observeParents: true,
            });
        });

        function openNotice(idx) {
            $.ajax({
                url: '/news/detailForm/' + idx,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    if (res.status !== 'success') {
                        alert(res.message || '불러오기 실패');
                        return;
                    }

                    const d     = res.data || {};
                    const files = res.files || [];

                    $('#detailSubject').text(d.subject || '');
                    $('#detailContents').text(d.contents || '');

                    const slides = [];

                    files.forEach(file => {
                        if (!file.url) return;

                        slides.push(`
                            <li class="swiper-slide">
                                <img src="${file.url}" alt="${file.ori_name || ''}">
                            </li>
                        `);
                    });

                    if (newsSwiper) {
                        // 기존 슬라이드 다 지우고 새 슬라이드 추가
                        newsSwiper.removeAllSlides();
                        newsSwiper.appendSlide(slides);

                        // loop 구조 재정비 + 처음 슬라이드로 이동
                        newsSwiper.slideToLoop(0, 0); // (index, speed)
                        newsSwiper.updateAutoHeight();
                    }

                    modalOpen('news-modal');
                },
                error: function (xhr) {
                    alert('오류 발생');
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
</body>
@include('user.en.footer._gtm')
</html>