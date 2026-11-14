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
                    <h2 class="text-2xl font-bold text-primary">1단계 - 업체 인증</h2>
                    <p class="text-slate-500 mt-2">업체 아이디와 비밀번호로 접속하세요.</p>
                </div>
                <div class="login_box mx-auto mt-12">
                    <form id="login" action="{{ route('check.auth') }}" method="post">
                    	@csrf
                        <div class="intro-x">
                            아이디 <input type="text" id="id" name="id" class="intro-x login__input form-control px-4 py-2.5 block mb-5" placeholder="아이디를 입력해주세요">
                            비밀번호 <input type="password" id="pw" name="pw" class="intro-x login__input form-control px-4 py-2.5 block" placeholder="비밀번호를 입력해주세요">
                        </div>
                        <div class="intro-x mt-5 text-center">
                            <button type="submit" class="btn btn-dark px-4 w-full py-3 text-base align-top" id="btnLoginC">접속하기</button>
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

        const id = $('#id').val();
        const pw = $('#pw').val();

        if(!id || !pw) {
            alert('아이디와 비밀번호를 모두 입력해주세요.');
            return;
        }

        $.ajax({
            url: '/check/auth', // 아까 만든 인증 컨트롤러 주소
            type: 'POST',
            data: {
                id: id,
                pw: pw
            },
    		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },		
            success: function(res) {
                if(res.status === 'success') {
                    // 인증 성공 시 사용자 확인으로 이동
                    location.href = '/check/usercheck'; 
                } else {
                     alert('아이디 또는 비밀번호가 잘못되었습니다.');
                }
            },
            error: function() {
                alert('인증 처리 중 오류가 발생했습니다.');
            }
        });
    });
});

</script>