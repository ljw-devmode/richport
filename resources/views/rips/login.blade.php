@php
	$header_skip = true;
@endphp
@include('rips.header._header')
<div class="content_wrap">
    <!-- BEGIN: Content -->
    <div class="content">
        <div class="flex items-center justify-center h-full">
            <div class="w-[500px] my-auto mx-auto border border-slate-200 px-12 py-16 rounded-lg bg-white shadow-lg">
                <img src="{{ asset('admin_assets/dist/images/logo.svg') }}" class="intro-x mx-auto h-7" alt="">
                <p class="intro-x mt-4 text-center font-semibold" style="color:#4C8498;">RIPS 재고·발주 관리 서비스</p>
                <div class="login_box mx-auto mt-10">
                    <form id="login" action="{{ route('rips.login') }}" method="post">
                    	@csrf
                        <div class="intro-x">
                            <input type="text" id="id" name="id" value="{{ old('id') }}" class="intro-x login__input form-control px-4 py-2.5 block" placeholder="아이디를 입력해주세요" autocomplete="username">
                            <input type="password" id="pw" name="pw" class="intro-x login__input form-control px-4 py-2.5 block mt-3" placeholder="비밀번호를 입력해주세요" autocomplete="current-password">
                        </div>
                        @if ($errors->any())
                            <div class="mt-4 text-sm text-danger text-center">
                            	{{ $errors->first('msg') ?: '아이디와 비밀번호를 입력해주세요.' }}
							</div>
						@endif
                        <div class="intro-x mt-5 text-center">
                            <button type="submit" class="btn btn-dark px-4 w-full py-3 text-base align-top">로그인</button>
                        </div>
                        <div class="intro-x mt-6 text-center text-slate-500">계정 발급은 리치포트 담당자에게 문의해 주세요.</div>
                        <div class="intro-x mt-2 text-center text-slate-500">© 2026 RICHPORT. All Rights Reserved.</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content -->
</div>
@include('rips.footer._footer')
