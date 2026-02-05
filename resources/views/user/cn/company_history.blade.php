@include('user.cn.header._head')
<body>
	@include('user.cn.header._header')
    <main id="wrap">
        <div class="sub_banner history_banner">
            <div class="sub_inner">
                <p class="effect delay_1">通过长期积累的经验与技术所建立的信任与口碑，构成了 SOLARTEQ 的发展足迹。</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="history_cont">
            <div class="sub_inner">
                
                <div class="sub_tit">
                    <span data-aos="fade-up">发展历程</span>
                    <h3 data-aos="fade-up" data-aos-delay="150">公司沿革</h3>
                </div>

                <div class="history_area">
					@php 
				    $boxIndex = 0; 
				@endphp
				
				@foreach ($histories as $year => $items)
				    @php
				        $boxIndex++;
				        $boxClass = $boxIndex % 2 === 0 ? 'history_box type02' : 'history_box';
				    @endphp
				
				    <div class="{{ $boxClass }}">
				        <div class="year" data-aos="fade-up">
				            <p>{{ $year }}</p>
				        </div>
				
				        <div class="month">
				            @foreach ($items as $row)
				                <div class="item">
				                    <dl data-aos="fade-up">
				                        <dt>{{ (int)$row->month }}</dt>
				                        <dd><p>{{ $row->subject_cn }}</p></dd>
				                    </dl>
				                </div>
				            @endforeach
				        </div>
				    </div>
				@endforeach
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

    </script>
</body>
@include('user.cn.footer._gtm')
</html>