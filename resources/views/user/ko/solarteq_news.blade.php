@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="wrap">
        <div class="sub_banner news_banner">
            <div class="sub_inner">
                <p class="effect delay_1">솔라테크의 성과와 스토리를<br/> 영상, 블로그, 보도자료 등<br/> 다양한 콘텐츠로 보여드립니다</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>
        <div class="news_cont">
            <div class="news_box">
                <div class="w1700">
                    <div class="sub_tit">
                        <span data-aos="fade-up">NEWS</span>
                        <h3 data-aos="fade-up" data-aos-delay="150">솔라테크 소식</h3>
                        <p data-aos="fade-up" data-aos-delay="300">다양한 매체를 통해 전하는 솔라테크 이야기</p>
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
		                                <div class="tit">{{$item->subject}}</div>
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
                        <h3 data-aos="fade-up" data-aos-delay="150">솔라튜브</h3>
                        <p data-aos="fade-up" data-aos-delay="300">영상으로 확인하는 솔라테크</p>
                    </div>
                    <div class="tube_list" data-aos="fade-up" data-aos-delay="300">
                        <ul>
                            <li><a href="https://www.youtube.com/watch?v=B3hzO4AkKrI" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/1. 영화금속 인터뷰.png') }}" alt=""></div>
                                <div class="tit">고객만족 인터뷰┃600가구 전력 생산! 영화금속이 RE100을 준비하는 현실적 방법</div>
                            </a></li>
                            <li><a href="https://www.youtube.com/watch?v=BLT1IR_Us7o" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/2. 화성 파카코리아 드론.png') }}" alt=""></div>
                                <div class="tit">화성 유압기기 제조공장 1.3MW 태양광 발전소 드론 영상</div>
                            </a></li>
                            <li><a href="https://www.youtube.com/watch?v=W4JUV0ABSwk" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/3. 모나크CC 인터뷰.png') }}" alt=""></div>
                                <div class="tit">고객만족 인터뷰┃모나크CC, 고객 편의를 위해 설치한 2.2MW 태양광, 예상 못 한 효과가?</div>
                            </a></li>
                            <li><a href="https://youtu.be/9MK7QRRRiik?si=EpPd315PkfcvfWW7" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/4. 청주 한청판지 드론.png') }}" alt=""></div>
                                <div class="tit">스마트 생태공장 구축사업 준공! 청주 제지공장 2.3MW급 드론영상</div>
                            </a></li>
                            <li><a href="https://www.youtube.com/watch?v=0FhNgL_ZABU" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/5. 아이소매트릭.png') }}" alt=""></div>
                                <div class="tit">SOLARTEQ┃당신의 에너지 자산 파트너 (아이소매트릭)</div>
                            </a></li>
                            <li><a href="https://www.youtube.com/watch?v=Ke6JeaoKOls" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/6. 평택 매일유업 드론.png') }}" alt=""></div>
                                <div class="tit">평택 매일유업 250kW 지붕형 태양광발전소 준공! 유제품 공장 태양광 설치 완료</div>
                            </a></li>
                        </ul>

                        <div class="btn_box">
                            <a href="https://www.youtube.com/channel/UC09-6cslYKyVAiH86JmuvrA" target="_blank">더 많은 영상 보러가기</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="news_box">
                <div class="w1700">
                    <div class="sub_tit">
                        <span data-aos="fade-up">BLOG</span>
                        <h3 data-aos="fade-up" data-aos-delay="150">솔라테크 블로그</h3>
                        <p data-aos="fade-up" data-aos-delay="300">솔택이가 전하는 다양한 소식</p>
                    </div>
                    <div class="blog_list" data-aos="fade-up" data-aos-delay="300">
                        <ul>
                            <li><a href="https://blog.naver.com/solarteq/224022706649" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/1. 대표님 수상 건.png') }}" alt=""></div>
                                <div class="txt_box">
                                    <div class="tit">마음을 담은 따뜻한 이웃사랑</div>
                                    <div class="desc">솔라테크, 2025 자원봉사자의 날 기념식 화성특례시장 표창 수상</div>
                                </div>
                            </a></li>
                            <li><a href="https://blog.naver.com/solarteq/223712733798" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/2. 울산 신화인텍.png') }}" alt=""></div>
                                <div class="txt_box">
                                    <div class="tit">울산 신화인텍 시공 사례</div>
                                    <div class="desc">금융지원사업, 300kW 발전소 비용 부담 줄여 수익 만든 비결은?</div>
                                </div>
                            </a></li>
                            <li><a href="https://blog.naver.com/solarteq/223904842286" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/3. 울산 세일.png') }}" alt=""></div>
                                <div class="txt_box">
                                    <div class="tit">울산 세일 시공 사례</div>
                                    <div class="desc">울산광역시 (주)세일 300kW 지붕형 태양광 발전소 시공과정</div>
                                </div>
                            </a></li>
                            <li><a href="https://blog.naver.com/solarteq/224029603233" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/4. 청주 한청판지.png') }}" alt=""></div>
                                <div class="txt_box">
                                    <div class="tit">청주 한청판지 시공 사례</div>
                                    <div class="desc">한청판지 2.3MW 태양광발전소 준공, 스마트 생태공장 시공 과정</div>
                                </div>
                            </a></li>
                        </ul>
                        
                        <div class="btn_box">
                            <a href="https://blog.naver.com/solarteq" target="_blank">더 많은 솔택이 블로그 보러가기</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- news 모달 -->
        <div class="modal" id="news-modal">
            <div class="modal_wrap modal-lg">
                <div class="modal_header">
                    <h3>회사소식</h3>
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
        @include('user.ko.footer._footer') 
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
@include('user.ko.footer._gtm')
</html>