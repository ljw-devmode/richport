@include('user.cn.header._head')
<body>
	@include('user.cn.header._header')
    <main id="wrap">
        <div class="sub_banner news_banner">
            <div class="sub_inner">
                <p class="effect delay_1">通过视频、博客、新闻稿等多种内容展示 SOLARTEQ 的成果与故事</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>
        <div class="news_cont">
            <div class="news_box">
                <div class="w1700">
                    <div class="sub_tit">
                        <span data-aos="fade-up">新闻</span>
                        <h3 data-aos="fade-up" data-aos-delay="150">SOLARTEQ 新闻</h3>
                        <p data-aos="fade-up" data-aos-delay="300">通过多种媒体传递的 SOLARTEQ 故事</p>
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
                        <h3 data-aos="fade-up" data-aos-delay="150">Solartube</h3>
                        <p data-aos="fade-up" data-aos-delay="300">通过视频了解 SOLARTEQ</p>
                    </div>
                    <div class="tube_list" data-aos="fade-up" data-aos-delay="300">
                        <ul>
                            <li><a href="https://www.youtube.com/watch?v=B3hzO4AkKrI" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/1. 영화금속 인터뷰.png') }}" alt=""></div>
                                <div class="tit">客户访谈┃为 600 户供电！电影金属的 RE100 实践</div>
                            </a></li>
                            <li><a href="javascript:;" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/2. 화성 파카코리아 드론.png') }}" alt=""></div>
                                <div class="tit">华城液压设备工厂 1.3MW 无人机影像</div>
                            </a></li>
                            <li><a href="javascript:;" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/3. 모나크CC 인터뷰.png') }}" alt=""></div>
                                <div class="tit">客户访谈┃Monarch CC 2.2MW 太阳能：意想不到的影响？</div>
                            </a></li>
                            <li><a href="javascript:;" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/4. 청주 한청판지 드론.png') }}" alt=""></div>
                                <div class="tit">清州造纸厂 2.3MW 无人机影像</div>
                            </a></li>
                            <li><a href="javascript:;" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/5. 아이소매트릭.png') }}" alt=""></div>
                                <div class="tit">Your Energy Asset Partner</div>
                            </a></li>
                            <li><a href="javascript:;" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/6. 평택 매일유업 드론.png') }}" alt=""></div>
                                <div class="tit">平泽每日乳业 250kW 屋顶太阳能竣工</div>
                            </a></li>
                        </ul>

                        <div class="btn_box">
                            <a href="https://www.youtube.com/channel/UC09-6cslYKyVAiH86JmuvrA" target="_blank">查看更多视频</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="news_box">
                <div class="w1700">
                    <div class="sub_tit">
                        <span data-aos="fade-up">博客</span>
                        <h3 data-aos="fade-up" data-aos-delay="150">SOLARTEQ 博客</h3>
                        <p data-aos="fade-up" data-aos-delay="300">Soltek 分享的多样资讯</p>
                    </div>
                    <div class="blog_list" data-aos="fade-up" data-aos-delay="300">
                        <ul>
                            <li><a href="https://blog.naver.com/solarteq/224022706649" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/1. 대표님 수상 건.png') }}" alt=""></div>
                                <div class="txt_box">
                                    <div class="tit">充满温情的邻里关爱</div>
                                    <div class="desc">SOLARTEQ 荣获 2025 年志愿者日纪念活动华城特例市市长表彰</div>
                                </div>
                            </a></li>
                            <li><a href="https://blog.naver.com/solarteq/223712733798" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/2. 울산 신화인텍.png') }}" alt=""></div>
                                <div class="txt_box">
                                    <div class="tit">蔚山新和 Intech 施工案例</div>
                                    <div class="desc">金融支持项目：300kW 电站如何降低成本并创造收益？</div>
                                </div>
                            </a></li>
                            <li><a href="https://blog.naver.com/solarteq/223904842286" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/3. 울산 세일.png') }}" alt=""></div>
                                <div class="txt_box">
                                    <div class="tit">蔚山 SAEL 施工案例</div>
                                    <div class="desc">蔚山广域市 SAEL 株式会社 300kW 屋顶式太阳能电站施工过程</div>
                                </div>
                            </a></li>
                            <li><a href="https://blog.naver.com/solarteq/224029603233" target="_blank">
                                <div class="img_box"><img src="{{ asset('user_assets/img/sub/solarteq/4. 청주 한청판지.png') }}" alt=""></div>
                                <div class="txt_box">
                                    <div class="tit">清州韩清纸业施工案例</div>
                                    <div class="desc">韩清纸业 2.3MW 太阳能发电站竣工，智能生态工厂施工过程</div>
                                </div>
                            </a></li>
                        </ul>
                        
                        <div class="btn_box">
                            <a href="https://blog.naver.com/solarteq" target="_blank">查看更多 Soltek 博客</a>
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
        @include('user.cn.footer._footer') 
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
                    alert('error');
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
</body>
@include('user.cn.footer._gtm')
</html>