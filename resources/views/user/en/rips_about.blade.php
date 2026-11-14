@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="fullpage">
        <div class="section rips_about_con01">
            <div class="sub_inner">
                <h4 class="effects">Key Features</h4>
                <p class="effects delay_1">We turn export decisions into data-driven decisions.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="section rips_about_con02">
            <div class="sub_inner">
                <div class="sub_tit">
                    <span class="effect">WHY RIPS</span>
                    <h3 class="effect delay_1">RIPS Turns Local Data into Export Opportunities</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con02_icon_1.svg') }}" alt="">
                        <h5><b>Less Waste</b></h5>
                        <p>Minimize losses by<br/> preventing overstock</p>
                    </li>
                    <li class="effect delay_3">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con02_icon_2.svg') }}" alt="">
                        <h5><b>More Sales Opportunities</b></h5>
                        <p>Prevent stockouts with<br/> stable ordering</p>
                    </li>
                    <li class="effect delay_4">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con02_icon_3.svg') }}" alt="">
                        <h5><b>Optimal Ordering</b></h5>
                        <p>Accurate AI-based<br/> sales forecasting</p>
                    </li>
                    <li class="effect delay_5">
                        <img src="{{ asset('user_assets/img/sub/business/busi_con02_icon_4.svg') }}" alt="">
                        <h5 style="letter-spacing: -2px;"><b>New Product Proposals</b></h5>
                        <p>Fast supply of popular<br/> new Korean products</p>
                    </li>
                </ul>

            </div>
        </div>

        <!-- portfolio -->
        <div class="section portfolio_con">
            <div class="port_slide">
                <div class="tit_box">
                    <h3>Key Services</h3>
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
                                    <div class="tit"><b>Main Page</b><h4>Product Management</h4></div>
                                    <div class="info">
                                        <dl>
                                            <dt>Inventory</dt>
                                            <dd>Estimated stock depletion date for each product</dd>
                                        </dl>
                                        <dl>
                                            <dt>Order Date</dt>
                                            <dd>Recommended order date based on stock depletion date and inbound lead time</dd>
                                        </dl>
                                        <dl>
                                            <dt>Order Quantity</dt>
                                            <dd>Recommended order quantity to prevent overstock and stockouts</dd>
                                        </dl>
                                        <dl>
                                            <dt>Inbound Lead Time</dt>
                                            <dd>Forecast of time from order through production, logistics and receipt</dd>
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
                                    <div class="tit"><b>Main Page</b><h4>Order Management</h4></div>
                                    <div class="info">
                                        <dl>
                                            <dt>Order Status</dt>
                                            <dd>Status updates from production, logistics and customs to receipt</dd>
                                        </dl>
                                        <dl>
                                            <dt>Order Details</dt>
                                            <dd>Status, supplier and product information</dd>
                                        </dl>
                                        <dl>
                                            <dt>Orderer Details</dt>
                                            <dd>Orderer accountability management</dd>
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
                                    <div class="tit"><b>Main Page</b><h4>Survey</h4></div>
                                    <div class="info">
                                        <dl>
                                            <dt>Test Bed</dt>
                                            <dd>Sales tests for new products</dd>
                                        </dl>
                                        <dl>
                                            <dt>Survey</dt>
                                            <dd>Collecting and analyzing customer feedback</dd>
                                        </dl>
                                        <dl>
                                            <dt>Buyer Analysis</dt>
                                            <dd>Analysis by age, gender and region</dd>
                                        </dl>
                                        <dl>
                                            <dt>Report</dt>
                                            <dd>Product sales performance report</dd>
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
                    <h3 class="effect delay_1">How to Get Started with RIPS</h3>
                </div>
                <ul>
                    <li class="effect delay_2">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/apply.svg') }}" alt=""></i>Apply<br/>for RIPS</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_3">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/account.svg') }}" alt=""></i>Company Check,<br/>Account Setup</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_4">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/mapping.svg') }}" alt=""></i>Store &amp;<br/> Product Mapping</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_5">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/sync.svg') }}" alt=""></i>Sales &amp;<br/>Inventory Sync</p>
                        <div class="arrow"><img src="{{ asset('user_assets/img/icon/arrow_black.svg') }}" alt=""></div>
                    </li>
                    <li class="effect delay_6">
                        <p><i><img src="{{ asset('user_assets/img/sub/icon/launch.svg') }}" alt=""></i>Start Using<br/>RIPS</p>
                    </li>
                </ul>
                <div class="btn_box effect delay_7">
                    <button onclick="openInquire(2)">Apply for RIPS</button>
                    <p>✱ Our team will contact you once your application is received.</p>
                </div>
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
@include('user.en.footer._gtm')
</html>