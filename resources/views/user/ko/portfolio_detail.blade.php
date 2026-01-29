@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="wrap">
        <div class="portfolio_detail">
            <div class="w1700">
                <!-- 타이틀 -->
                <div class="tit">
                    <div class="left_tit">
                        <span>{{$data->regions->name_kr}} - {{ $data->cities->name_kr }}</span>
                        <h4>{{$data->station_name}}</h4>
                    </div>
                    <div class="right_txt">
                        <div>
                            <span>설비 용량</span>
                            <p><b>{{ number_format($data->volume)}}</b>kW</p>
                        </div>
                        <div>
                            <span>탄소 감축 효과</span>
                            <p><b>{{ number_format(round($data->volume * 156))}}</b> 그루</p>
                        </div>
                    </div>
                </div>
            </div>

			@if($data->youtube_iframe || $data->youtube_subject || $data->youtube_contents || $data->blog_contents)
            <div class="portfolio_content">
                <div class="w1700">
                    <!-- 인터뷰 -->
                    @if($data->youtube_iframe || $data->youtube_subject || $data->youtube_contents)
	                    <div class="portfolio_tit">
	                        <h5>고객 인터뷰</h5>
	                    </div>
	                    @if($data->youtube_iframe)
						    <div class="interview_video">
						        {!! $data->youtube_iframe !!}
						    </div>
						@endif
	                    
	                    @if($data->youtube_subject || $data->youtube_contents)
		                    <div class="interview_txt">
		                    	@if($data->youtube_subject)
		                        <div class="subject">{{$data->youtube_subject}}</div>
		                        @endif
		                        @if($data->youtube_contents)
		                        <div class="desc">“{{$data->youtube_contents}}”</div>
		                        @endif
		                    </div>
	                    @endif
                    @endif

                    <!-- 취재노트 -->
                    @if($data->blog_contents)
                    <div class="portfolio_tit">
                        <h5>솔택이의 현장 취재 노트</h5>
                        <div class="note_box">
                            <div class="note_txt">{{$data->blog_contents}}</div>
                            <a href="https://blog.naver.com/solarteq">더 많은 솔택이 블로그 보러가기</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            <!-- 시공사례 -->
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
                    <a href="/portfolio?form_idx=&station_name=">목록으로 돌아가기</a>
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