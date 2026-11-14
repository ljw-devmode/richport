@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <div class="intro">
        <svg width="100%" height="100%" viewBox="0 0 800 400" fill="#083967" xmlns="http://www.w3.org/2000/svg">
            <g fill="white" font-size="72" font-weight="700" text-anchor="middle" dominant-baseline="middle" font-family="Pretendard, Noto Sans KR, sans-serif">
                <text x="100" y="200" opacity="0">R</text>
                <text x="180" y="200" opacity="0">I</text>
                <text x="260" y="200" opacity="0">C</text>
                <text x="340" y="200" opacity="0">H</text>
                <text x="420" y="200" opacity="0">P</text>
                <text x="500" y="200" opacity="0">O</text>
                <text x="580" y="200" opacity="0">R</text>
                <text x="660" y="200" opacity="0">T</text>
            </g>
        </svg>
    </div>
        
    <main id="fullpage">
        <!-- main_visual -->
        <div class="section">
            <div class="visual_slide">
                <ul class="swiper-wrapper">
                    <li class="swiper-slide">
                        <img class="bg_img" src="{{ asset('user_assets/img/main/1.메인_소개_PC.png') }}" alt="">
                        <img class="bg_img mo_ver" src="{{ asset('user_assets/img/main/1.메인_소개_M.png') }}" alt="">
                        <div class="txt_box">
                            <p>베트남 판매데이터로 시작하는 K-Product 수출</p>
                            <img src="{{ asset('user_assets/img/main/logo_white.png') }}" alt="">
                        </div>
                    </li>
                </ul>
                <div class="pager">
                    <button class="prev"><img src="{{ asset('user_assets/img/main/visual_arrow.svg') }}" alt=""></button>
                    <div class="pagination"></div>
                    <button class="next"><img src="{{ asset('user_assets/img/main/visual_arrow.svg') }}" alt=""></button>
                </div>
            </div>
        </div>

        <!-- 주요 서비스 -->
        <div class="section main_con02">
            <ul>
                <li>
                    <div class="txt_box">
                        <h3>RIPS 서비스</h3>
                        <div class="hover_txt">
                            <p>베트남 현지 판매데이터로 수요 예측</br>더 정확한 발주와 상품 운영 지원</p>
                            <a href="/ko/rips/about">자세히 보기 <img src="{{ asset('user_assets/img/main/main_con02_arrow.svg') }}" alt=""></a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="txt_box">
                        <h3>테스트 베드</h3>
                        <div class="hover_txt">
                            <p>베트남에서 실제 고객 반응을 테스트하고</br>수출 가능성과 상품 개선방향 확인</p>
                            <a href="/ko/testbed/about">자세히 보기 <img src="{{ asset('user_assets/img/main/main_con02_arrow.svg') }}" alt=""></a>
                        </div>
                    </div>
                </li>   
            </ul>
        </div>


        <!-- 리치포트 현황 -->
        <div class="section main_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">사람과 가치를 잇는, 더 넓은 시장으로</span>
                    <h3 class="effect delay_1">리치포트 현황</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <div class="txt_box">
                            <i>베트남  파트너</i>
                            <p><b>50+</b>개사</p>
                            <span>Central Retail, emart, LOTTE Mart, GS25 등 대형마트 및 편의점</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_1.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <div class="txt_box">
                            <i>상품 인허가</i>
                            <p><b>500+</b>개</p>
                            <span>베트남 유통을 위한 상품 등록·인허가 지원 경험</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_2.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <div class="txt_box">
                            <i>한국 파트너</i>
                            <p><b>100+</b>건</p>
                            <span>대상(청정원), 동원F&B, 이그니스 등 한국 주요 식품 제조사</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_3.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <div class="txt_box">
                            <i>베트남 유통</i>
                            <p><b>15</b>년</p>
                            <span>베트남 기업형 리테일 경험과 현지 유통에 기술을 접목</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_4.png') }}" alt=""></div>
                    </li>
                </ul>
            </div>
        </div>


        <!-- RIPS 주요 서비스 -->
        <div class="section main_con04">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">데이터로 연결하는 더 스마트한 유통의 내일</span>
                    <h3 class="effect delay_1">RIPS 주요 서비스</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/main/main_con04_1.svg') }}" alt="">
                        <h5>판매데이터 분석</h5>
                        <p>매장별 <b>판매·재고</b> 흐름을 통합 분석</p>
                    </li>
                    <li class="effect delay_3">
                        <img src="{{ asset('user_assets/img/main/main_con04_2.svg') }}" alt="">
                        <h5>수요예측</h5>
                        <p>판매 패턴을 기반으로 <b>수요 변화</b>를 예측</p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/main/main_con04_3.svg') }}" alt="">
                        <h5>적정발주 추천</h5>
                        <p>재고 부담을 줄이는<b>발주 방안</b> 제시</p>
                    </li>
                </ul>
            </div>
        </div>


        <div class="section main_con05">
            <div class="sub_inner">
                <div class="txt_box">
                    <span class="effect">#안심먹거리 #한국의 맛</span>
                    <div class="txt effect delay_1">베트남을 사로잡은 </div>
                    <div class="txt effect delay_2"><span>K-Product</span></div>
                    <p class="effect delay_3">한국의 좋은 제품을,</br>베트남의 더 많은 소비자에게</p>
                </div>
                <div class="product_box main_con05_slide effect delay_4">
                    <ul class="swiper-wrapper">
                        @foreach ($products->chunk(4) as $chunk)
                        <li class="swiper-slide">
                            <div class="product_list">
                                @foreach ($chunk as $item)
                                <div class="product_item">
                                    <span class="partner">{{ $item->partners?->tr('partner_name') ?? '' }}</span>
                                    <div class="thumb">
                                        <img src="{{ Storage::disk('public')->url($item->pc_up_name) }}" alt="{{ $item->tr('product_name') }}">
                                    </div>
                                    <p class="name">{{ $item->tr('product_name') }}</p>
                                </div>
                                @endforeach
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <div class="pagination"></div>
                </div>
            </div>
        </div>


        <!-- portfolio -->
        <div class="section portfolio_con">
            <div class="port_slide">
                <div class="tit_box">
                    <h3>유통 파트너십</h3>
                    <button class="arrow prev"></button>
                    <button class="arrow next"></button>
                </div>
                <ul class="swiper-wrapper">
                    @foreach ($partners as $item)
                    <li class="swiper-slide">
                        <img class="bg_img pc_img" src="{{ Storage::disk('public')->url($item->pc_up_name) }}" alt="">
                        <img class="bg_img fold_img" src="{{ Storage::disk('public')->url($item->fo_up_name) }}" alt="">
                        <img class="bg_img mo_img" src="{{ Storage::disk('public')->url($item->mo_up_name) }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit">@if ($item->partner_name_en !== $item->tr('partner_name'))<b>{{ $item->partner_name_en }}</b>@endif<h4>{{ $item->tr('partner_name') }}</h4></div>
                                    <div class="info">
                                        <dl>
                                            <dt>카테고리</dt>
                                            <dd>{{ $item->forms?->tr('subject') ?? '' }}</dd>
                                        </dl>
                                        <dl>
                                            <dt>인기상품</dt>
                                            <dd>{{ $item->products->take(3)->map(fn ($p) => $p->tr('product_name'))->implode(', ') }}</dd>
                                        </dl>
                                        {{-- 베트남 : 유통 채널 / 한국 : 유통기간 --}}
                                        @if (strtolower($item->country) === 'vietnam' && $item->tr('channel'))
                                        <dl>
                                            <dt>유통 채널</dt>
                                            <dd>{{ $item->tr('channel') }}</dd>
                                        </dl>
                                        @endif
                                        @if (strtolower($item->country) === 'korea' && $item->logistic_text)
                                        <dl>
                                            <dt>유통기간</dt>
                                            <dd>{{ $item->logistic_text }}</dd>
                                        </dl>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- footer -->
        <section class="section fp-auto-height">
             @include('user.ko.footer._footer')
        </section>
    </main>

    <script>


        // fullpage
        $('#fullpage').fullpage({
            scrollingSpeed:1000,
            autoScrolling:true,
            normalScrollElements: '.in_scroll'
        });
        $(window).on('resize', function() {
            $.fn.fullpage.reBuild();
        });
        
        // intro
        $(document).on('ready', function(){
            $('.intro').addClass('active');
            setTimeout(function(){
                $('header').addClass('active');

                $('.intro').removeClass('active');
                $('.intro').addClass('off');
                
                $('#fullpage').addClass('active')

                $('.visual_slide').addClass('active');
                visualSwiper.autoplay.start();
            }, 2000);
        });


        // main_visual swiper
        var visualSwiper = new Swiper(".visual_slide", {
            effect:'fade',
            speed:1000,
            loop:true,
            autoplay: {
                delay: 4000,
            },
            pagination: {
                el: ".visual_slide .pagination",
                type: "progressbar",
            },
            navigation: {
                nextEl: ".visual_slide .next",
                prevEl: ".visual_slide .prev",
            },
        });
        visualSwiper.autoplay.stop();

        // main_con02
        $(window).on('load resize',function(){
            $('.main_con02 ul li').each(function(){
                let hoverH = $(this).find('.hover_txt').outerHeight()
                $(this).find('.txt_box').css('margin-top',hoverH)
            });
        })

        // main_con05_slide
        var main_con05_slide = new Swiper(".main_con05 .main_con05_slide", {
            speed:1000,
            loop:$('.main_con05_slide .swiper-slide').length > 1,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".main_con05_slide .pagination",
                clickable: true,
            },
        });
        



        // portfolio_con
        var port_slide = new Swiper(".port_slide", {
            speed:1000,
            loop:$('.port_slide .swiper-slide').length > 1,
            navigation: {
                nextEl: ".port_slide .next",
                prevEl: ".port_slide .prev",
            },
        });



    </script>
</body>
@include('user.ko.footer._gtm')
</html>
