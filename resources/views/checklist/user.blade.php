<?php
$header_skip = true;
?>
@include('checklist.header._header')
<div class="content_wrap">
    <!-- BEGIN: Content -->
    <div class="content">
        <div class="flex items-center justify-center h-full">
            <div class="w-[500px] my-auto mx-auto border border-slate-200 px-12 py-16 rounded-lg bg-white shadow-lg">
                <img src="{{ asset('admin_assets/dist/images/logo.svg') }}" class="intro-x mx-auto h-7" alt="">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-primary">2단계 - 담당자 정보 입력</h2>
                    <p class="text-slate-500 mt-2">성명과 전화번호를 입력해주세요</p>
                </div>
                <div class="login_box mx-auto mt-12">
                    <form id="login" action="{{ route('check.userauth') }}" method="post">
                    	@csrf
                        <div class="intro-x">
                            업체명 <input type="text" id="out_name" name="out_name" class="intro-x login__input form-control px-4 py-2.5 block mb-5" value="{{ $user['out_name'] }}" disabled>
                            성명 <input type="text" id="user_name" name="user_name" class="intro-x login__input form-control px-4 py-2.5 block mb-5" placeholder="성명을 입력해주세요">
                            전화번호 <input type="text" id="user_phone" name="user_phone" class="intro-x login__input form-control px-4 py-2.5 block " placeholder="전화번호를 숫자만 입력해주세요">
                        </div>
                        <div class="intro-x mt-5 text-center">
                            <button type="submit" class="btn btn-dark px-4 w-full py-3 text-base align-top" id="btnLoginC">체크리스트 작성하기</button>
                        </div>
                        <div class="intro-x mt-6 text-center text-slate-500">© 2026 SOLARTEQ. All Rights Reserved.</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content -->
</div>
@include('checklist.footer._footer')


<script>
$(document).ready(function() {
    $('#login').on('submit', function(e) {
        e.preventDefault(); // 기본 제출 막기

        const user_name = $('#user_name').val();
        const user_phone = $('#user_phone').val();

        if(!user_name || !user_phone) {
            alert('성명과 전화번호를 모두 입력해주세요.');
            return;
        }

        $.ajax({
            url: '/check/userauth', // 아까 만든 인증 컨트롤러 주소
            type: 'POST',
            data: {
                user_name: user_name,
                user_phone: user_phone
            },
    		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },		
            success: function(res) {
                if(res.status === 'success') {
                    // 인증 성공 시 메인 탭 화면으로 이동
                    location.href = '/check/main'; 
                }
            },
            error: function() {
                alert('인증 처리 중 오류가 발생했습니다.');
            }
        });
    });
});

</script>