@include('user.vn.header._head')
<body>
	@include('user.vn.header._header')
    <main id="fullpage">
        <div class="section rips_about_con01">
            <div class="sub_inner">
                <h4 class="effects">Tính năng chính</h4>
                <p class="effects delay_1">Đưa ra quyết định xuất khẩu dựa trên dữ liệu.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section rips_about_con02">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">WHY RIPS</span>
                    <h3 class="effect delay_1">RIPS biến dữ liệu thị trường địa phương thành cơ hội xuất khẩu</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con02_icon_1.svg') }}" alt="">
                        <h5><b>Giảm hàng hủy</b></h5>
                        <p>Giảm thiểu tổn thất nhờ<br/> ngăn tồn kho dư thừa</p>
                    </li>
                    <li class="effect delay_3">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con02_icon_2.svg') }}" alt="">
                        <h5><b>Mở rộng cơ hội doanh thu</b></h5>
                        <p>Ngăn thiếu hàng nhờ<br/> đặt hàng ổn định</p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con02_icon_3.svg') }}" alt="">
                        <h5><b>Đặt hàng hợp lý</b></h5>
                        <p>Dự báo bán hàng chính xác<br/> dựa trên AI</p>
                    </li>
                    <li class="effect delay_5">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con02_icon_4.svg') }}" alt="">
                        <h5 style="letter-spacing: -2px;"><b>Đề xuất sản phẩm mới</b></h5>
                        <p>Cung cấp nhanh sản phẩm mới<br/> được ưa chuộng tại Hàn Quốc</p>
                    </li>
                </ul>

            </div>
        </div>

        <!-- portfolio -->
        <div class="section portfolio_con">
            <div class="port_slide">
                <div class="tit_box">
                    <h3>Dịch vụ chính</h3>
                    <button class="arrow prev"></button>
                    <button class="arrow next"></button>
                </div>
                <ul class="swiper-wrapper">
                    <li class="swiper-slide">
                        <img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/rips/rips 상품.png') }}" alt="">
                        <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/rips/rips 상품.png') }}" alt="">
                        <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/rips/rips 상품.png') }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit"><b>Trang chính</b><h4>Quản lý sản phẩm</h4></div>
                                    <div class="info">
                                        <dl>
                                            <dt>Tồn kho</dt>
                                            <dd>Tính ngày dự kiến hết hàng theo từng sản phẩm</dd>
                                        </dl>
                                        <dl>
                                            <dt>Ngày đặt hàng</dt>
                                            <dd>Đề xuất ngày đặt hàng dựa trên ngày hết hàng và thời gian nhập kho</dd>
                                        </dl>
                                        <dl>
                                            <dt>Số lượng đặt hàng</dt>
                                            <dd>Đề xuất số lượng đặt hàng để tránh tồn kho dư thừa và thiếu hàng</dd>
                                        </dl>
                                        <dl>
                                            <dt>Thời gian nhập kho</dt>
                                            <dd>Dự báo thời gian từ sản xuất, vận chuyển đến nhập kho sau khi đặt hàng</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="swiper-slide">
                        <img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/rips/rips 발주관리.png') }}" alt="">
                        <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/rips/rips 발주관리.png') }}" alt="">
                        <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/rips/rips 발주관리.png') }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit"><b>Trang chính</b><h4>Quản lý đặt hàng</h4></div>
                                    <div class="info">
                                        <dl>
                                            <dt>Trạng thái đặt hàng</dt>
                                            <dd>Cập nhật trạng thái từ sản xuất, vận chuyển, thông quan đến nhập kho</dd>
                                        </dl>
                                        <dl>
                                            <dt>Thông tin đặt hàng</dt>
                                            <dd>Cung cấp thông tin trạng thái, nhà cung cấp và sản phẩm</dd>
                                        </dl>
                                        <dl>
                                            <dt>Thông tin người đặt hàng</dt>
                                            <dd>Quản lý trách nhiệm người đặt hàng</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="swiper-slide">
                        <img class="bg_img pc_img" src="{{ asset('user_assets/img/sub/rips/rips 설문조사.png') }}" alt="">
                        <img class="bg_img fold_img" src="{{ asset('user_assets/img/sub/rips/rips 설문조사.png') }}" alt="">
                        <img class="bg_img mo_img" src="{{ asset('user_assets/img/sub/rips/rips 설문조사.png') }}" alt="">
                        <div class="inner">
                            <div class="txt_box in_scroll">
                                <div class="top">
                                    <div class="tit"><b>Trang chính</b><h4>Khảo sát</h4></div>
                                    <div class="info">
                                        <dl>
                                            <dt>Test bed</dt>
                                            <dd>Thử nghiệm bán sản phẩm mới</dd>
                                        </dl>
                                        <dl>
                                            <dt>Khảo sát</dt>
                                            <dd>Thu thập và phân tích ý kiến khách hàng</dd>
                                        </dl>
                                        <dl>
                                            <dt>Phân tích người mua</dt>
                                            <dd>Phân tích theo độ tuổi, giới tính và khu vực</dd>
                                        </dl>
                                        <dl>
                                            <dt>Báo cáo</dt>
                                            <dd>Báo cáo hiệu quả bán hàng của sản phẩm</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>


        <div class="section rips_about_con05">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">STEP-BY-STEP PROCESS</span>
                    <h3 class="effect delay_1">Quy trình triển khai RIPS</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/apply.svg') }}" alt=""></i>Đăng ký<br/>sử dụng RIPS</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/account.svg') }}" alt=""></i>Xác minh doanh nghiệp,<br/>cấp tài khoản</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/mapping.svg') }}" alt=""></i>Liên kết cửa hàng<br/> và sản phẩm</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/sync.svg') }}" alt=""></i>Kết nối dữ liệu<br/>bán hàng và tồn kho</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/launch.svg') }}" alt=""></i>Sử dụng<br/>dịch vụ RIPS</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(2)">Đăng ký sử dụng RIPS</button>
                    <p>✱ Sau khi nhận được đăng ký, nhân viên phụ trách sẽ liên hệ với bạn.</p>
                </div>
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
        });
        $(window).on('resize', function() {
            $.fn.fullpage.reBuild();
        });

        $(window).on('load', function(){
            setTimeout(function(){
                $('.rips_about_con01').addClass('on')
            },100);
        });

        // portfolio_con
        var port_slide = new Swiper(".port_slide", {
            speed:1000,
            loop:true,
            navigation: {
                nextEl: ".port_slide .next",
                prevEl: ".port_slide .prev",
            },
        });

    </script>
</body>
@include('user.vn.footer._gtm')
</html>