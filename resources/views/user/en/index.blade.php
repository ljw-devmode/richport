@include('user.en.header._head')
<body>
	@include('user.en.header._header')
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
                            <p>K-Product exports powered by Vietnamese sales data</p>
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
                        <h3>RIPS Service</h3>
                        <div class="hover_txt">
                            <p>Demand forecasting with local Vietnamese sales data</br>for more accurate ordering and product management</p>
                            <a href="/en/rips/about">Learn More <img src="{{ asset('user_assets/img/main/main_con02_arrow.svg') }}" alt=""></a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="txt_box">
                        <h3>Testbed</h3>
                        <div class="hover_txt">
                            <p>Test real customer reactions in Vietnam</br>and validate export potential and product improvements</p>
                            <a href="/en/testbed/about">Learn More <img src="{{ asset('user_assets/img/main/main_con02_arrow.svg') }}" alt=""></a>
                        </div>
                    </div>
                </li>   
            </ul>
        </div>


        <!-- 리치포트 현황 -->
        <div class="section main_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">Connecting people and value to a wider market</span>
                    <h3 class="effect delay_1">RICHPORT at a Glance</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <div class="txt_box">
                            <i>Vietnam Partners</i>
                            <p><b>50+</b> companies</p>
                            <span>Hypermarkets and convenience stores incl. Central Retail, emart, LOTTE Mart and GS25</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_1.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <div class="txt_box">
                            <i>Product Licensing</i>
                            <p><b>500+</b> products</p>
                            <span>Experience supporting product registration and licensing for distribution in Vietnam</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_2.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <div class="txt_box">
                            <i>Korea Partners</i>
                            <p><b>100+</b> partners</p>
                            <span>Leading Korean food manufacturers incl. Daesang (Chung Jung One), Dongwon F&B and Ignis</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_3.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <div class="txt_box">
                            <i>Vietnam Retail</i>
                            <p><b>15</b> years</p>
                            <span>Corporate retail experience in Vietnam combined with technology for local distribution</span>
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
                    <span class="effect">A smarter future for retail, connected by data</span>
                    <h3 class="effect delay_1">RIPS Key Services</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/main/main_con04_1.svg') }}" alt="">
                        <h5>Sales Data Analysis</h5>
                        <p>Integrated analysis of <b>sales and inventory</b> flow by store</p>
                    </li>
                    <li class="effect delay_3">
                        <img src="{{ asset('user_assets/img/main/main_con04_2.svg') }}" alt="">
                        <h5>Demand Forecasting</h5>
                        <p>Predicts <b>demand changes</b> based on sales patterns</p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/main/main_con04_3.svg') }}" alt="">
                        <h5>Optimal Order Recommendations</h5>
                        <p>Suggests <b>ordering plans</b> that reduce inventory burden</p>
                    </li>
                </ul>
            </div>
        </div>


        <div class="section main_con05">
            <div class="sub_inner">
                <div class="txt_box">
                    <span class="effect">#SafeFood #TasteOfKorea</span>
                    <div class="txt effect delay_1">Winning Vietnam with </div>
                    <div class="txt effect delay_2"><span>K-Product</span></div>
                    <p class="effect delay_3">Great Korean products,</br>for more consumers in Vietnam</p>
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
                    <h3>Retail Partnerships</h3>
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
                                            <dt>Category</dt>
                                            <dd>{{ $item->forms?->tr('subject') ?? '' }}</dd>
                                        </dl>
                                        <dl>
                                            <dt>Popular Products</dt>
                                            <dd>{{ $item->products->take(3)->map(fn ($p) => $p->tr('product_name'))->implode(', ') }}</dd>
                                        </dl>
                                        {{-- 베트남 : 유통 채널 / 한국 : 유통기간 --}}
                                        @if (strtolower($item->country) === 'vietnam' && $item->tr('channel'))
                                        <dl>
                                            <dt>Retail Channels</dt>
                                            <dd>{{ $item->tr('channel') }}</dd>
                                        </dl>
                                        @endif
                                        @if (strtolower($item->country) === 'korea' && $item->logistic_text)
                                        <dl>
                                            <dt>Delivery Time</dt>
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
             @include('user.en.footer._footer')
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
@include('user.en.footer._gtm')
</html>
