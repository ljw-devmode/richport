@include('user.cn.header._head')
<body>
	@include('user.cn.header._header')
    <main id="wrap">
        <!-- 포트폴리오 -->
        <div class="sub_banner portfolio_banner">
            <div class="sub_inner type02">
                <p class="effect">项目案例</p>
                <span class="effect delay_1">展示 SOLARTEQ 在新能源与智能工厂领域取得的成果</span>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="portfolio_area">
            <div class="w1700">
                <div class="sub_tit">
                    <span data-aos="fade-up">项目案例</span>
                    <h3 data-aos="fade-up" data-aos-delay="150">以数据证明对 SOLARTEQ 的信赖</h3>
                </div>
				<!--
                <div class="portfolio_tab" data-aos="fade-up">
                    <a href="javascript:;" class="active">新能源</a>
                    <a href="javascript:alert('준비중입니다')">智能工厂</a>
                </div>
				-->
            </div>

            <div class="portfolio_box">
                <div class="w1700">
                	<form method="GET" action="/cn/portfolio">
	                    <div class="portfolio_form" data-aos="fade-up">
	                        <div class="category">
	                        	<input type="radio" id="form_idx_all" name="form_idx"  value="" {{ request('form_idx') === null || request('form_idx') === '' ? 'checked' : '' }}><label for="form_idx_all">查看全部</label>
	                        	@foreach ($types as $item)
		                            <input type="radio" id="form_idx_{{$item->idx}}" name="form_idx" value="{{$item->idx}}" {{ request('form_idx') == $item->idx ? 'checked' : '' }}><label for="form_idx_{{$item->idx}}">{{$item->subject_cn}}</label>
	                            @endforeach
	                        </div>
	                        <div class="search">
	                            <input type="text" id="station_name" name="station_name" placeholder="请按电站名称搜索。" value="{{ request('station_name') }}">
	                            <button><img src="{{ asset('user_assets/img/icon/search.svg') }}" alt=""></button>
	                        </div>
	                    </div>
	                </form>
                    <div class="portfolio_list" data-aos="fade-up">
                        <ul>
                        	@forelse ($data as $index => $item)
                            <li>
                            	<!-- a href="/cn/portfolio/detail/{{ $item->idx }}" -->
	                                <div class="img_box">
	                                	<span>{{ $item->formTypes->subject_cn ?? '' }}</span>
	                                	<img src="{{ Storage::disk('public')->url($item->pc_up_name) }}" alt="">
	                                </div>
	                                <div class="txt_box">
	                                    <div class="top">
	                                        <span>{{ $item->regions->name_cn ?? '' }} {{ $item->cities->name_cn ?? '' }}</span>
	                                        <b>{{number_format($item->volume)}}kW</b>
	                                    </div>
	                                    <div class="tit">
	                                        <p>{{$item->station_name_cn}}</p>
	                                        <div class="link_box">
	                                        	@if($item->url)
	                                            <a href="{{$item->url}}" target="_blank"><img src="{{ asset('user_assets/img/sub/solarteq/youtube.svg') }}" alt=""></a>
	                                            @endif
	                                            @if($item->blog)
	                                            <a href="{{$item->blog}}" target="_blank"><img src="{{ asset('user_assets/img/sub/solarteq/blog.svg') }}" alt=""></a>
	                                            @endif
	                                        </div>
	                                    </div>
	                                </div>
	                            <!-- /a -->
                            </li>
                            @empty
                            	<li class="empty">暂无已登记的项目案例。</li>
                            @endforelse
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
				let portTop = $('.portfolio_box').offset().top - 50;
		        //let portTop = $('.portfolio_area').offset().top - 50;
	
	            window.lenis.scrollTo(portTop, {
	                duration: 0,
	                easing: (t) => t * (2 - t), // ease-out curve
	            });
	        }
	    });

	    $(function () {
	        $('input[name="form_idx"]').on('change', function () {
	            $(this).closest('form').submit();
	        });
	    });

    </script>
</body>
@include('user.cn.footer._gtm')
</html>