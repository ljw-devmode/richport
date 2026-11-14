@include('user.ko.header._head')
<body>
	@include('user.ko.header._header')
    <main id="wrap">
        <div class="sub_banner history_banner ">
            <div class="sub_inner">
                <h4 class="effects">연혁</h4>
                <p class="historyp effect delay_1" >현지 실행력과 데이터로 한국 브랜드의</br>베트남 진출을 만들어온 팀의 여정입니다.</p>
            </div>
            <div id="scroll_down"><p>Scroll down</p></div>
        </div>

        <div class="history_cont">
            <div class="sub_inner">
                
                <div class="sub_tit">
                    <span data-aos="fade-up">HISTORY</span>
                    <h3 data-aos="fade-up" data-aos-delay="150">회사 연혁</h3>
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
				                        <dd><p>{{ $row->tr('subject') }}</p></dd>
				                    </dl>
				                </div>
				            @endforeach
				        </div>
				    </div>
				@endforeach
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

    </script>
</body>
@include('user.ko.footer._gtm')
</html>