@include('user.en.header._head')
<body>
	@include('user.en.header._header')
    <main id="fullpage">
        <!-- 솔라테크 소개 -->
        <div class="section contact_con01">
            <div class="sub_inner">
                <h4 class="effects">Location</h4>
                <p class="effects delay_1">Starting in Korea, connecting to the Vietnamese market.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <!-- 찾아오시는 길 -->
        <div class="section contact_con02">
            <div class="sub_inner2">
                <div class="sub_tit">
                    <span class="effect">CONTACT</span>
                    <h3 class="effect delay_1">How to Find Us</h3>
                    <div class="tab_box effect delay_2">
                        <a href="javascript:;" class="active">Korea HQ</a>
                        <a href="javascript:;">Seoul Office</a>
                        <a href="javascript:;">Vietnam Office</a>
                    </div>
                </div>
                
                <div class="tab_content">
                    <!-- [TAB 1] 한국 본사 (창원) -->
                    <div class="active">
                        <div class="map_box">
                            <div class="map effect delay_3" style="width:100%; height:360px;">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m5!3m3!1m2!1s0x356f55bbd3a10527%3A0xa9496351e71c29b2!2z6rK97IOB64Ko64-EIOywveybkOyLnCA3SjVHKzVNRw!5e0!3m2!1sko!2skr!4v1787367875141!5m2!1sko!2skr" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>                                
                            </div>
                            <div class="info effect delay_4">
                                <h5>Korea HQ</h5>
                                <div>
                                    <b>Address</b>
                                    <p>4F 410-03, Business Bldg., 23 Jungdongbuk-ro, Uichang-gu, Changwon-si, Gyeongsangnam-do, Korea <a href="javascript:;" onclick="copyText('4F 410-03, Business Bldg., 23 Jungdongbuk-ro, Uichang-gu, Changwon-si, Gyeongsangnam-do, Korea')">Copy</a></p>
                                </div>
                                <div>
                                    <b>Phone</b>
                                    <p>055-000-0000 <a href="tel:055-000-0000">Call</a></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- [TAB 2] 서울 사무실 -->
                    <div>
                        <div class="map_box">
                            <div class="map effect delay_3" style="width:100%; height:360px;">
                               <iframe src="https://www.google.com/maps/embed?pb=!1m5!3m3!1m2!1s0x357ca41193e317b9%3A0x4cbd5502d602fa8d!2z7ISc7Jq47Yq567OE7IucIOqwleuCqOq1rCDthYztl6TrnoDroZw2Oeq4uCA1!5e0!3m2!1sko!2skr!4v1787367165136!5m2!1sko!2skr" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                            </div>
                            <div class="info effect delay_4">
                                <h5>Seoul Office</h5>
                                <div>
                                    <b>Address</b>
                                    <p>13F DT Center, 5 Teheran-ro 69-gil, Gangnam-gu, Seoul (Samseong-dong) <a href="javascript:;" onclick="copyText('13F DT Center, 5 Teheran-ro 69-gil, Gangnam-gu, Seoul (Samseong-dong)')">Copy</a></p>
                                </div>
                                <div>
                                    <b>Phone</b>
                                    <p>02-000-0000 <a href="tel:02-000-0000">Call</a></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- [TAB 3] 베트남 사무실 -->
                    <div>
                        <div class="map_box">
                            <div class="map effect delay_3" style="width:100%; height:360px;">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d979.9485892953948!2d106.70694592854042!3d10.750326316902157!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTDCsDQ1JzAxLjIiTiAxMDbCsDQyJzI3LjMiRQ!5e0!3m2!1sko!2skr!4v1787367675969!5m2!1sko!2skr" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                            </div>
                            <div class="info effect delay_4">
                                <h5>Vietnam Office</h5>
                                <div>
                                    <b>Address</b>
                                    <p>117/7C Trần Xuân Soạn, Quận 7, Thành phố Hồ Chí Minh, Việt Nam <a href="javascript:;" onclick="copyText('117/7C Trần Xuân Soạn, Quận 7, Thành phố Hồ Chí Minh, Việt Nam')">Copy</a></p>
                                </div>
                                <div>
                                    <b>Phone</b>
                                    <p>+84-28-0000-0000 <a href="tel:+842800000000">Call</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                $('.contact_con01').addClass('on')
            },100);

            setTimeout(function(){
                $('.tab_content > div').eq(1).removeClass('active')
            },400);

            $('.tab_box a').on('click',function(){
                let liN = $(this).index();
                $(this).addClass('active').siblings().removeClass('active');
                $('.tab_content > div').eq(liN).addClass('active').siblings().removeClass('active');
            })
        });

        const copyText = (text)=>{
            navigator.clipboard.writeText(text);
            alert('Address copied to clipboard.')
        }
    </script>
</body>
@include('user.en.footer._gtm')
</html>
