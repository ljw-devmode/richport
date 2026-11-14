		@if (!$header_skip)
        <!-- 로그아웃 -->
        <div id="logout-modal" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="flex p-5 gap-2 items-center">
                            <span class="w-10 h-10 flex items-center justify-center rounded-full bg-stone">
                                <i data-lucide="lock" class="w-5 h-5 text-white"></i>
                            </span>
                            <div>
                                <div class="text-lg font-medium">로그아웃</div>
                                <div class="text-xs">로그아웃 하시겠습니까?</div>
                            </div>
                        </div>
                        <form id="logout-form" method="POST" action="{{ route('check.logout') }}" style="display: none;">
						    @csrf
					    </form>
                        <div class="px-5 pb-5 text-right">
                            <button type="button" class="btn btn-stone" onclick="document.getElementById('logout-form').submit();">로그아웃</button>
                            <button type="button" onclick="modalClose('logout-modal')" class="btn btn-outline-stone ml-1 w-20">취소</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="toast-container"></div>
		@endif
	    @if(session('error'))
		    <script>
	        	alert(@json(session('error')));
			</script>
		@endif

        <!-- BEGIN: JS Assets-->
        <script src="{{ asset('admin_assets/dist/js/app.js') }}"></script>
        <script src="{{ asset('admin_assets/dist/js/jquery-1.12.4.js') }}"></script>
        <!-- <script src="https://code.jquery.com/jquery-latest.min.js"></script> -->
        <script src="{{ asset('admin_assets/dist/js/custom.js') }}"></script>
        <script src="{{ asset('admin_assets/dist/js/sortable.js') }}"></script>
        <script src="{{ asset('admin_assets/dist/js/signature_pad.min.js') }}"></script>
        <!-- 파일업로드 -->
        <script src="{{ asset('admin_assets/dist/js/filepond.min.js') }}"></script>
        <script src="{{ asset('admin_assets/dist/js/filepond-plugin-image-preview.min.js') }}"></script>
        <script src="{{ asset('admin_assets/dist/js/filepond-plugin-file-validate-size.min.js') }}"></script>
        <script src="{{ asset('admin_assets/dist/js/filepond-plugin-file-validate-type.min.js') }}"></script>

        <script>
        const __currentAdminIdx = @json(auth()->check() ? auth()->user()->idx : null);

        // 글로벌 AJAX 에러 핸들링 - 419 CSRF 토큰 만료 시 로그인 페이지로 리다이렉트
        $(document).ajaxError(function(event, xhr, settings, thrownError) {
            if (xhr.status === 419) {
                showToast('세션이 만료되었습니다. 로그인 페이지로 이동합니다.', 'error');
                setTimeout(function() {
                    window.location.href = '/check/login';
                }, 2000);
            }
        });
        </script>
        <!-- END: JS Assets-->
    </body>
</html>