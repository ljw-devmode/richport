<?php
$header_skip = true;
?>
@include('admin.header._header')
<div class="content_wrap">
    <!-- BEGIN: Content -->
    <div class="content">
        <div class="flex items-center justify-center h-full">
            <div class="w-[500px] my-auto mx-auto border border-slate-200 px-12 py-16 rounded-lg bg-white shadow-lg">
                <img src="{{ asset('admin_assets/dist/images/logo.svg') }}" class="intro-x mx-auto h-7" alt="">
                <div class="login_box mx-auto mt-12">
                    <form id="login" action="{{ route('admin.login') }}" method="post">
                    	@csrf
                        <div class="intro-x">
                            <input type="text" id="id" name="id" class="intro-x login__input form-control px-4 py-2.5 block" placeholder="아이디를 입력해주세요">
                            <input type="password" id="pw" name="pw" class="intro-x login__input form-control px-4 py-2.5 block mt-3" placeholder="비밀번호를 입력해주세요">
                        </div>
                        @if ($errors->has('msg'))
                            <div class="mt-4 text-sm text-danger text-center">
                            	{{ $errors->first('msg') }}
							</div>
						@endif
                        <div class="intro-x mt-5 text-center">
                            <button type="submit" class="btn btn-dark px-4 w-full py-3 text-base align-top" id="btnLoginC">관리자 로그인</button>
                        </div>
                        <div class="intro-x mt-6 text-center text-slate-500">© 2026 SOLARTEQ. All Rights Reserved.</div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
    <!-- END: Content -->
</div>
@include('admin.footer._footer')