@include('user.cn.header._head')
<body>
	@include('user.cn.header._header')
    <main id="wrap">
        <div class="portfolio_detail">
            <div class="w1700">
                <!-- 타이틀 -->
                <div class="tit">
                    <div class="left_tit">
                        <span>{{$data->regions->name_cn}} - {{ $data->cities->name_cn }}</span>
                        <h4>{{$data->station_name_cn}}</h4>
                    </div>
                    <div class="right_txt">
                        <div>
                            <span>装机容量</span>
                            <p><b>{{ number_format($data->volume_cn)}}</b>kW</p>
                        </div>
                        <div>
                            <span>减碳效果</span>
                            <p><b>{{ number_format(round($data->volume_cn * 156))}}</b> 棵树</p>
                        </div>
                    </div>
                </div>
            </div>

			@if($data->youtube_iframe || $data->youtube_subject_cn || $data->youtube_contents_cn || $data->blog_contents_cn)
            <div class="portfolio_content">
                <div class="w1700">
                    <!-- 인터뷰 -->
                    @if($data->youtube_iframe || $data->youtube_subject_cn || $data->youtube_contents_cn)
	                    <div class="portfolio_tit">
	                        <h5>客户访谈</h5>
	                    </div>
	                    @if($data->youtube_iframe)
						    <div class="interview_video">
						        {!! $data->youtube_iframe !!}
						    </div>
						@endif
	                    
	                    @if($data->youtube_subject_cn || $data->youtube_contents_cn)
		                    <div class="interview_txt">
		                    	@if($data->youtube_subject_cn)
		                        <div class="subject">{{$data->youtube_subject_cn}}</div>
		                        @endif
		                        @if($data->youtube_contents_cn)
		                        <div class="desc">“{{$data->youtube_contents_cn}}”</div>
		                        @endif
		                    </div>
	                    @endif
                    @endif

                    <!-- 취재노트 -->
                    @if($data->blog_contents_cn)
                    <div class="portfolio_tit">
                        <h5>Soltek 的现场采访笔记</h5>
                        <div class="note_box">
                            <div class="note_txt">{{$data->blog_contents_cn}}</div>
                            <a href="https://blog.naver.com/solarteq">查看更多 Soltek 博客</a>
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
                        <h5>施工照片</h5>
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
                    <a href="/portfolio?form_idx=&station_name=">返回列表</a>
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
@include('user.cn.footer._gtm')
</html>