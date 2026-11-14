@if (empty($header_skip))
	</div>
	<!-- END: content -->
</div>
<!-- END: content_wrap -->

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
                        <form id="logout-form" method="POST" action="{{ route('rips.logout') }}" style="display: none;">
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
@endif

        <div id="toast-container"></div>

	    @if(session('error'))
		    <script>
	        	alert(@json(session('error')));
			</script>
		@endif

        <!-- BEGIN: JS Assets-->
        <script src="{{ asset('admin_assets/dist/js/app.js') }}"></script>
        <script src="{{ asset('admin_assets/dist/js/jquery-1.12.4.js') }}"></script>
        <script src="{{ asset('admin_assets/dist/js/custom.js') }}"></script>
        <script>
        // RIPS 공통
        @php
            $ripsBase = Auth::guard('rips')->check() ? url('/rips/'.Auth::guard('rips')->user()->rips_id) : url('/rips');
        @endphp
        const RIPS_BASE = @json($ripsBase);

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json' }
        });

        // custom.js 의 관리자용 419 처리 대신 RIPS 로그인으로 이동
        $(document).off('ajaxError').ajaxError(function (event, xhr) {
            if (xhr.status === 419 || xhr.status === 401) {
                showToast('세션이 만료되었습니다. 로그인 페이지로 이동합니다.', 'error');
                setTimeout(function () { window.location.href = '/rips/login'; }, 1500);
            }
        });

        function numberFormat(value) {
            const n = Number(value || 0);
            return isNaN(n) ? '0' : n.toLocaleString('ko-KR');
        }

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, function (c) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
            });
        }

        // 카테고리와 모든 하위 카테고리 idx
        function ripsDescendantIds(categories, categoryIdx) {
            const ids = [Number(categoryIdx)];
            for (let i = 0; i < ids.length; i++) {
                categories.filter(c => c.parent_idx === ids[i]).forEach(c => ids.push(c.idx));
            }
            return ids;
        }

        /**
         * 카테고리 1~4차 단계별 선택 (rips.common._category_select 와 함께 사용)
         * - 상위를 고르면 다음 단계에 하위 카테고리가 채워지고, 그 아래 단계는 초기화
         * - get() : 마지막으로 선택한 카테고리 idx ('' : 미선택)
         */
        function RipsCategorySelect(box, categories, onChange) {
            const $box         = $(box);
            const $selects     = $box.find('select[data-depth]');
            const $hidden      = $box.find('input[type="hidden"]');
            const placeholder  = $box.data('placeholder') || '선택';
            const select       = d => $selects.filter('[data-depth="' + d + '"]');

            function render(fromDepth) {
                for (let d = fromDepth; d <= 4; d++) {
                    const parent   = d === 1 ? 0 : Number(select(d - 1).val());
                    const children = d === 1 || parent ? categories.filter(c => c.parent_idx === parent) : [];
                    const $sel     = select(d).empty().append('<option value="">' + d + '차 ' + placeholder + '</option>');

                    children.forEach(c => $sel.append($('<option>').val(c.idx).text(c.name)));
                    $sel.prop('disabled', !children.length);
                }
            }

            function get() {
                let idx = '';
                $selects.each(function () { if ($(this).val()) idx = $(this).val(); });
                return idx;
            }

            // 카테고리에서 최상위까지 거슬러 올라가 1~4차 선택
            function set(categoryIdx) {
                const path = [];
                let current = categories.find(c => c.idx === Number(categoryIdx));
                while (current) {
                    path.unshift(current.idx);
                    current = categories.find(c => c.idx === current.parent_idx);
                }

                render(1);
                path.forEach(function (idx, i) {
                    select(i + 1).val(String(idx));
                    render(i + 2);
                });
                $hidden.val(get());
            }

            $selects.on('change', function () {
                render(Number($(this).data('depth')) + 1);
                $hidden.val(get());
                onChange && onChange(get());
            });

            set($box.data('value') || null);

            return { get: get, set: set, reset: function () { set(null); } };
        }

        // 공통 AJAX 저장 (FormData 또는 객체)
        function ripsAjax(url, method, data, onSuccess) {
            const isForm = data instanceof FormData;
            return $.ajax({
                url: url,
                method: method,
                data: data,
                processData: !isForm,
                contentType: isForm ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (res) {
                    if (res.status === 'success') {
                        onSuccess && onSuccess(res);
                    } else {
                        showToast(res.message || '처리에 실패했습니다.', 'error');
                    }
                },
                error: function (xhr) {
                    if (xhr.status !== 419 && xhr.status !== 401) showToast('오류가 발생했습니다.', 'error');
                    console.error(xhr.responseText);
                }
            });
        }
        </script>
        <!-- END: JS Assets-->
    </body>
</html>
