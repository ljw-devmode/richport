@include('user.vn.header._head')
<body>
	@include('user.vn.header._header')
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
                            <p>Xuất khẩu K-Product bắt đầu từ dữ liệu bán hàng tại Việt Nam</p>
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
                        <h3>Dịch vụ RIPS</h3>
                        <div class="hover_txt">
                            <p>Dự báo nhu cầu từ dữ liệu bán hàng tại Việt Nam</br>Hỗ trợ đặt hàng và vận hành sản phẩm chính xác hơn</p>
                            <a href="/vn/rips/about">Xem chi tiết <img src="{{ asset('user_assets/img/main/main_con02_arrow.svg') }}" alt=""></a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="txt_box">
                        <h3>Testbed</h3>
                        <div class="hover_txt">
                            <p>Thử nghiệm phản hồi thực tế của khách hàng tại Việt Nam</br>và xác định tiềm năng xuất khẩu, hướng cải tiến sản phẩm</p>
                            <a href="/vn/testbed/about">Xem chi tiết <img src="{{ asset('user_assets/img/main/main_con02_arrow.svg') }}" alt=""></a>
                        </div>
                    </div>
                </li>   
            </ul>
        </div>


        <!-- 리치포트 현황 -->
        <div class="section main_con03">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">Kết nối con người và giá trị, vươn tới thị trường rộng lớn hơn</span>
                    <h3 class="effect delay_1">RICHPORT qua những con số</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <div class="txt_box">
                            <i>Đối tác Việt Nam</i>
                            <p><b>50+</b> doanh nghiệp</p>
                            <span>Siêu thị lớn và chuỗi cửa hàng tiện lợi như Central Retail, emart, LOTTE Mart, GS25</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_1.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <div class="txt_box">
                            <i>Cấp phép sản phẩm</i>
                            <p><b>500+</b> sản phẩm</p>
                            <span>Kinh nghiệm hỗ trợ đăng ký và cấp phép sản phẩm để phân phối tại Việt Nam</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_2.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <div class="txt_box">
                            <i>Đối tác Hàn Quốc</i>
                            <p><b>100+</b> đối tác</p>
                            <span>Các nhà sản xuất thực phẩm hàng đầu Hàn Quốc như Daesang (Chung Jung One), Dongwon F&B, Ygnis</span>
                        </div>
                        <div class="img_box"><img src="{{ asset('user_assets/img/main/main_con03_3.png') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <div class="txt_box">
                            <i>Bán lẻ tại Việt Nam</i>
                            <p><b>15</b> năm</p>
                            <span>Kết hợp kinh nghiệm bán lẻ hiện đại tại Việt Nam với công nghệ phân phối</span>
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
                    <span class="effect">Tương lai bán lẻ thông minh hơn, kết nối bằng dữ liệu</span>
                    <h3 class="effect delay_1">Dịch vụ chính của RIPS</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/main/main_con04_1.svg') }}" alt="">
                        <h5>Phân tích dữ liệu bán hàng</h5>
                        <p>Phân tích tổng hợp dòng <b>bán hàng và tồn kho</b> theo từng cửa hàng</p>
                    </li>
                    <li class="effect delay_3">
                        <img src="{{ asset('user_assets/img/main/main_con04_2.svg') }}" alt="">
                        <h5>Dự báo nhu cầu</h5>
                        <p>Dự báo <b>biến động nhu cầu</b> dựa trên xu hướng bán hàng</p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/main/main_con04_3.svg') }}" alt="">
                        <h5>Đề xuất đặt hàng tối ưu</h5>
                        <p>Đề xuất <b>phương án đặt hàng</b> giúp giảm áp lực tồn kho</p>
                    </li>
                </ul>
            </div>
        </div>


        <div class="section main_con05">
            <div class="sub_inner">
                <div class="txt_box">
                    <span class="effect">#ThựcPhẩmAnToàn #HươngVịHànQuốc</span>
                    <div class="txt effect delay_1">Chinh phục Việt Nam </div>
                    <div class="txt effect delay_2"><span>K-Product</span></div>
                    <p class="effect delay_3">Đưa sản phẩm chất lượng của Hàn Quốc</br>đến với nhiều người tiêu dùng Việt Nam hơn</p>
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
                    <h3>Đối tác phân phối</h3>
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
                                            <dt>Danh mục</dt>
                                            <dd>{{ $item->forms?->tr('subject') ?? '' }}</dd>
                                        </dl>
                                        <dl>
                                            <dt>Sản phẩm nổi bật</dt>
                                            <dd>{{ $item->products->take(3)->map(fn ($p) => $p->tr('product_name'))->implode(', ') }}</dd>
                                        </dl>
                                        {{-- 베트남 : 유통 채널 / 한국 : 유통기간 --}}
                                        @if (strtolower($item->country) === 'vietnam' && $item->tr('channel'))
                                        <dl>
                                            <dt>Kênh phân phối</dt>
                                            <dd>{{ $item->tr('channel') }}</dd>
                                        </dl>
                                        @endif
                                        @if (strtolower($item->country) === 'korea' && $item->logistic_text)
                                        <dl>
                                            <dt>Thời gian giao hàng</dt>
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
             @include('user.vn.footer._footer')
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
@include('user.vn.footer._gtm')
</html>
