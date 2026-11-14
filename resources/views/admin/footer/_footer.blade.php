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
                        <form id="logout-form" method="POST" action="{{ route('admin.logout') }}" style="display: none;">
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

        <!-- 사용자 정보수정 모달 -->
        <div id="top_user_modify-modal" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="font-semibold text-lg mr-auto">정보수정</h2>
                        <button onclick="modalClose('top_user_modify-modal')">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class=" mt-3">
                            <p class="mb-1 font-medium ">성명 <span class="text-danger">*</span></p>
                            <input type="text" id="top_modify_name" name="top_modify_name" class="form-control" placeholder="성명을 입력해주세요">
                        </div>
                        <div class=" mt-3">
                            <p class="mb-1 font-medium ">아이디 <span class="text-danger">*</span></p>
                            <input type="text" id="top_modify_id" name="top_modify_id" class="form-control" placeholder="아이디를 입력해주세요" readonly="readonly">
                        </div>
                        <div class=" mt-3">
                            <p class="mb-1 font-medium ">비밀번호 <span class="text-danger">*</span></p>
                            <input type="password" id="top_modify_pw" name="top_modify_pw" class="form-control" placeholder="비밀번호를 입력해주세요">
                        </div>
                        <div class=" mt-3">
                            <p class="mb-1 font-medium ">연락처 </p>
                            <input type="text" id="top_modify_hp" name="top_modify_hp" class="form-control" placeholder="연락처를 입력해주세요">
                        </div>
                        <div class=" mt-3">
                            <p class="mb-1 font-medium ">이메일 </p>
                            <input type="text" id="top_modify_email" name="top_modify_email" class="form-control" placeholder="이메일을 입력해주세요">
                        </div>
                        <input type="hidden" id="top_modify_idx" name="top_modify_idx" value="">
                    </div>

                    <div class="modal-footer flex items-center justify-center gap-3">
                        <button type="button" class="btn btn-outline-secondary w-20 " onclick="modalClose('top_user_modify-modal')">취소</button>
                        <button type="button" class="btn btn-primary w-20" onclick="modifyCurrentUser()">수정</button>
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
        <!-- 파일업로드 -->
        <script src="{{ asset('admin_assets/dist/js/filepond.min.js') }}"></script>
        <script src="{{ asset('admin_assets/dist/js/filepond-plugin-image-preview.min.js') }}"></script>
        <script src="{{ asset('admin_assets/dist/js/filepond-plugin-file-validate-size.min.js') }}"></script>
        <script src="{{ asset('admin_assets/dist/js/filepond-plugin-file-validate-type.min.js') }}"></script>

        <script>
        const __currentAdminIdx = @json(auth()->check() ? auth()->user()->idx : null);

        // 사용자 정보수정 모달 열기
        function openUserModifyModal() {
            if (!__currentAdminIdx) {
                showToast('로그인이 필요한 기능입니다. 로그인 페이지로 이동합니다.', 'error');
                setTimeout(function () {
                    window.location.href = '/admin/login';
                }, 500);
                return;
            }

            $.ajax({
                url: '/admin/user/detailForm/' + __currentAdminIdx,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    if (res.status === 'success') {
                        $('#top_modify_idx').val(res.data.idx);
                        $('#top_modify_id').val(res.data.id);
                        $('#top_modify_name').val(res.data.name);
                        $('#top_modify_hp').val(res.data.hp);
                        $('#top_modify_email').val(res.data.email);
                        modalOpen('top_user_modify-modal');
                    } else {
                        showToast(res.message || '불러오기 실패','error');
                    }
                },
                error: function (xhr) {
                    showToast('오류 발생','error');
                    console.error(xhr.responseText);
                }
            });
        }

        // 현재 사용자 정보 수정
        function modifyCurrentUser() {
            const idx   = $('#top_modify_idx').val();
            const name  = $('#top_modify_name').val();
            const pw    = $('#top_modify_pw').val();
            const hp    = $('#top_modify_hp').val();
            const email = $('#top_modify_email').val();

            if (!name)  return showToast('이름을 입력해주세요.','error');
            if (!hp)    return showToast('연락처를 입력해주세요.','error');
            if (!email) return showToast('이메일을 입력해주세요.','error');

            const fd = new FormData();
            fd.append('name', name);
            fd.append('pw', pw);
            fd.append('hp', hp);
            fd.append('email', email);

            if (!confirm('정보를 수정하시겠습니까?')) return;

            $.ajax({
                url: '/admin/user/modify/' + idx,
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    if (res.status) {
                        showToast('정보가 수정되었습니다.','success');
                        modalClose('top_user_modify-modal');
                    } else {
                        showToast(res.message || '수정 실패','error');
                    }
                },
                error: function (xhr) {
                    showToast('오류 발생','error');
                    console.error(xhr.responseText);
                }
            });
        }

        // 글로벌 AJAX 에러 핸들링 - 419 CSRF 토큰 만료 시 로그인 페이지로 리다이렉트
        $(document).ajaxError(function(event, xhr, settings, thrownError) {
            if (xhr.status === 419) {
                showToast('세션이 만료되었습니다. 로그인 페이지로 이동합니다.', 'error');
                setTimeout(function() {
                    window.location.href = '/admin/login';
                }, 2000);
            }
        });
        </script>
        <!-- END: JS Assets-->
    </body>
</html>