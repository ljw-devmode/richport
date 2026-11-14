@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="wrap">
        <div class="portfolio_detail">
            <div class="w1700">
                <!-- 타이틀 -->
                <div class="tit">
                    <div class="left_tit">
                        <span>[{{ $data->forms?->tr('subject') }}] {{$data->partners?->tr('partner_name')}} {{$data->tr('product_name')}}</span>
                        <h4>{{$data->tr('contents')}}</h4>
                    </div>
                </div>
            </div>

			<div class="w1700">
				@if($files && count($files) > 0)
            	<div class="case_box">
                    <div class="portfolio_tit">
                        <h5>시공 사진</h5>
                        <div class="right_btn">
                            <button class="arrow prev"></button>
                            <button class="arrow next"></button>
                        </div>
                    </div>
                    <div class="case_slide">
                        <ul class="swiper-wrapper">
                        	@foreach ($files as $index => $file)
                            <li class="swiper-slide"><a href="javascript:;"><img src="{{ Storage::disk('public')->url($file->up_name) }}" alt=""></a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                <div class="btn_box">
                    <a href="/ko/portfolio?form_idx=&station_name=">목록으로 돌아가기</a>
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
        });

        // portfolio_con
        var case_slide = new Swiper(".case_slide", {
            speed:1000,
            slidesPerView: 1,
            spaceBetween: 20,
            //loop:true,
            navigation: {
                nextEl: ".case_box .next",
                prevEl: ".case_box .prev",
            },
            breakpoints: {
                500:{
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                767:{
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
            }
        });

    </script>
</body>
@include('user.ko.footer._gtm')
</html>