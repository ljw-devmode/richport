@include('user.vn.header._head')
<body>
	@include('user.vn.header._header')
	<main id="wrap">

        <div class="qna_box">
            <div class="sub_tit">
                <span data-aos="fade-up">Danh mục kiểm tra</span>
                <h3 data-aos="fade-up" data-aos-delay="150">Những điều cần biết trước khi sử dụng RIPS</h3>
            </div>

            <div class="tab_content" data-aos="fade-up" data-aos-delay="450">
                <!-- 지붕임대태양광 -->
                <div class="active">
                    <div class="qna_con">
                        <div class="qna_list">
                            @forelse ($faqs as $faq)
                            <dl>
                                <dt onclick="toggleQna(this)">{{ $faq->tr('question') }}</dt>
                                <dd><div class="answer">{!! nl2br(e($faq->tr('answer'))) !!}</div></dd>
                            </dl>
                            @empty
                            <dl>
                                <dt style="cursor:default;">Chưa có câu hỏi nào được đăng.</dt>
                            </dl>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @include('user.vn.footer._footer')
    <script>

        $(window).on('load', function(){

            $('.tab_box a').on('click',function(){
                let liN = $(this).index();
                $(this).addClass('active').siblings().removeClass('active');
                $('.tab_content > div').eq(liN).addClass('active').siblings().removeClass('active');
            })
        });

    </script>
</body>
@include('user.vn.footer._gtm')
</html>