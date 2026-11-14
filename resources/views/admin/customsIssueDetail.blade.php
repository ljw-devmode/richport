<!-- 통관 이슈 상세 레이어 (대시보드, 통관 이슈 목록 공통) -->
<style>
	.ci-tabs {display:flex; gap:6px; }
	.ci-tabs button {padding:5px 14px; border:1px solid #e2e8f0; border-radius:999px; font-size:13px; color:#475569; background:#fff; }
	.ci-tabs button.active {border-color:rgb(var(--color-primary)); color:rgb(var(--color-primary)); font-weight:600; }
	.ci-title {font-size:16px; font-weight:600; overflow-wrap:anywhere; }
	.ci-contents {min-height:160px; white-space:pre-wrap; overflow-wrap:anywhere; line-height:1.6; }
	.ci-empty {color:#94a3b8; }
</style>
<div id="customs-issue-detail-modal" class="modal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="font-semibold text-lg mr-auto">통관 이슈</h2>
				<button type="button" onclick="modalClose('customs-issue-detail-modal')">
					<i data-lucide="x" class="w-6 h-6"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="ci-tabs">
					<button type="button" data-lang="" onclick="renderCustomsIssueDetail('')">한국어</button>
					<button type="button" data-lang="_en" onclick="renderCustomsIssueDetail('_en')">English</button>
					<button type="button" data-lang="_vn" onclick="renderCustomsIssueDetail('_vn')">Tiếng Việt</button>
				</div>
				<p id="ci_detail_title" class="ci-title mt-4"></p>
				<p id="ci_detail_meta" class="mt-1 text-xs text-slate-500"></p>
				<div id="ci_detail_contents" class="ci-contents mt-4 pt-4 border-t border-slate-200"></div>
				<div id="ci_detail_file_wrap" class="mt-4 pt-4 border-t border-slate-200 flex items-center gap-2">
					<i data-lucide="paperclip" class="w-4 h-4 shrink-0"></i>
					<a id="ci_detail_file" href="#" class="text-primary" style="overflow-wrap:anywhere;"></a>
				</div>
			</div>
			<div class="modal-footer flex items-center justify-center">
				<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('customs-issue-detail-modal')">닫기</button>
			</div>
		</div>
	</div>
</div>
<script>
let customsIssueDetail = null;

function openCustomsIssueDetail(idx) {
    $.ajax({
        url: '/admin/customsIssue/detailForm/' + idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status !== 'success') return showToast(res.message || '불러오기 실패', 'error');

            const d = res.data;
            customsIssueDetail = d;
            $('#ci_detail_meta').text([d.created_by, (d.created_at || '').substring(0, 10)].filter(Boolean).join(' · '));
            $('#ci_detail_file_wrap').toggle(!!d.ori_name);
            $('#ci_detail_file').text(d.ori_name || '').attr('href', '/admin/customsIssue/download/' + d.idx);
            renderCustomsIssueDetail('');
            modalOpen('customs-issue-detail-modal');
        },
        error: function (xhr) {
            showToast('오류 발생', 'error');
            console.error(xhr.responseText);
        }
    });
}

// 언어 탭 (suffix : '', '_en', '_vn')
function renderCustomsIssueDetail(suffix) {
    const d = customsIssueDetail;
    const title = d['title' + suffix];
    const contents = d['contents' + suffix];

    $('.ci-tabs button').each(function () {
        $(this).toggleClass('active', $(this).data('lang') === suffix);
    });
    $('#ci_detail_title').text(title || '-');
    $('#ci_detail_contents').text(contents || '등록된 내용이 없습니다.').toggleClass('ci-empty', !contents);
}
</script>
