@php
	$header_skip = false;
	$depth1_num = '7';
	$depth2_num = '4';
	$canEdit = auth()->user()->hasPerm('customsIssue.update') || auth()->user()->hasPerm('customsIssue.delete');
@endphp
@include('admin.header._header')

<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">통관 이슈</h3>
	<p class="mt-1 text-slate-500">베트남에서 발생한 통관 이슈를 한/영/베로 등록합니다. 최근 5건이 대시보드에 표시됩니다.</p>

	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/customsIssue">
			<div class="flex items-end gap-3">
				<div class="w-full">
					<p class="mb-1">제목</p>
					<input type="text" name="keyword" class="form-control" placeholder="제목을 입력해주세요 (ko/en/vn)" value="{{ request('keyword') }}">
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">총 {{ $data->total() }}개</div>
			@if(auth()->user()->hasPerm('customsIssue.create'))
			<div class="flex items-center gap-2">
				<a href="javascript:;" class="btn btn-dark px-4" onclick="openIssueModal()">통관 이슈 등록</a>
			</div>
			@endif
		</div>

		<div class="p-6">
			<table class="table table-bordered table-hover">
				<colgroup>
					<col width="8%">
					<col width="*">
					<col width="*">
					<col width="*">
					<col width="6%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="whitespace-nowrap">번호</th>
						<th class="whitespace-nowrap">제목(ko)</th>
						<th class="whitespace-nowrap">제목(en)</th>
						<th class="whitespace-nowrap">제목(vn)</th>
						<th class="whitespace-nowrap">첨부</th>
						<th class="whitespace-nowrap">등록자</th>
						<th class="whitespace-nowrap">등록일</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $item)
						<tr onclick="{{ $canEdit ? 'openIssueModal' : 'openCustomsIssueDetail' }}('{{ $item->idx }}')" style="cursor: pointer;">
							<td>{{ $item->idx }}</td>
							<td class="text-left">{{ $item->title }}</td>
							<td class="text-left">{{ $item->title_en ?: '-' }}</td>
							<td class="text-left">{{ $item->title_vn ?: '-' }}</td>
							<td>
								@if($item->ori_name)
								<i data-lucide="paperclip" class="w-4 h-4 inline-block"></i>
								@endif
							</td>
							<td>{{ $item->created_by ?: '-' }}</td>
							<td>{{ $item->created_at?->format('Y-m-d') }}</td>
						</tr>
					@empty
					<tr>
						<td colspan="7" class="text-center">등록된 통관 이슈가 없습니다.</td>
					</tr>
					@endforelse
				</tbody>
			</table>
			@php
                $currentPage = $data->currentPage();
                $lastPage = $data->lastPage();

                $blockSize = 10;
			    $currentBlock = (int) ceil($currentPage / $blockSize);

			    $start = ($currentBlock - 1) * $blockSize + 1;
			    $end = min($start + $blockSize - 1, $lastPage);
            @endphp
			<nav class="w-full mt-5">
				<ul class="pagination justify-center">
					<li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
						<a class="page-link" href="{{ $data->url(1) }}"> <i data-lucide="chevrons-left" class="w-4 h-4"></i></a>
					</li>
					<li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
						<a class="page-link" href="{{ $data->previousPageUrl() }}"> <i data-lucide="chevron-left" class="w-4 h-4"></i></a>
					</li>
					@for ($i = $start; $i <= $end; $i++)
						<li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
							<a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
						</li>
					@endfor
					<li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
						<a class="page-link" href="{{ $data->nextPageUrl() }}"> <i data-lucide="chevron-right" class="w-4 h-4"></i></a>
					</li>
					<li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
						<a class="page-link" href="{{ $data->url($lastPage) }}"> <i data-lucide="chevrons-right" class="w-4 h-4"></i></a>
					</li>
				</ul>
			</nav>
		</div>
	</div>

	<!-- 통관 이슈 등록/수정 모달 -->
	<div id="issue-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto" id="issue_modal_title">통관 이슈 등록</h2>
					<button onclick="modalClose('issue-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="issue_idx">

					@foreach (['' => 'ko', '_en' => 'en', '_vn' => 'vn'] as $suffix => $lang)
					<h3 class="mb-1 text-base font-semibold {{ $lang === 'ko' ? '' : 'mt-5' }}" @if($lang !== 'ko') style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;" @endif>{{ ['ko' => '한국어', 'en' => '영어', 'vn' => '베트남어'][$lang] }} ({{ $lang }})</h3>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium">제목({{ $lang }}) @if($lang === 'ko')<span class="text-danger">*</span>@endif</p>
						<input type="text" id="issue_title{{ $suffix }}" class="form-control" maxlength="500" placeholder="제목을 입력해주세요">
					</div>
					<div class="flex gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium pt-2">내용({{ $lang }}) @if($lang === 'ko')<span class="text-danger">*</span>@endif</p>
						<textarea id="issue_contents{{ $suffix }}" class="form-control h-32" placeholder="내용을 입력해주세요 (줄바꿈은 그대로 표시됩니다)"></textarea>
					</div>
					@endforeach

					<div class="flex gap-2 mt-5" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">
						<p class="shrink-0 w-32 font-medium pt-2">첨부파일</p>
						<div class="w-full">
							<input type="file" id="issue_file" class="form-control">
							<p class="mt-1 text-xs text-slate-500">1개, 20MB 이하. 새 파일을 선택하면 기존 파일은 교체됩니다.</p>
							<div id="issue_current_file_wrap" class="mt-2 flex items-center gap-3" style="display:none;">
								<a id="issue_current_file" href="#" class="text-primary" style="overflow-wrap:anywhere;"></a>
								<div class="form-check shrink-0">
									<input id="issue_remove_file" class="form-check-input" type="checkbox">
									<label class="form-check-label" for="issue_remove_file">파일 삭제</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					@if(auth()->user()->hasPerm('customsIssue.delete'))
					<button type="button" class="btn btn-outline-danger w-20" id="issue_delete_btn" onclick="deleteIssue()" style="display:none;">삭제</button>
					@endif
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('issue-modal')">취소</button>
					<button type="button" class="btn btn-primary w-20" id="issue_save_btn" onclick="saveIssue()">저장</button>
				</div>
			</div>
		</div>
	</div>

	@include('admin.customsIssueDetail')
</div>

@include('admin.footer._footer')
<script>
const ISSUE_CAN_CREATE = @json(auth()->user()->hasPerm('customsIssue.create'));
const ISSUE_CAN_UPDATE = @json(auth()->user()->hasPerm('customsIssue.update'));
const ISSUE_FIELDS     = ['title', 'title_en', 'title_vn', 'contents', 'contents_en', 'contents_vn'];

function issueAjax(url, method, data, onSuccess) {
    const options = {
        url: url,
        method: method,
        data: data,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') return onSuccess(res);
            showToast(res.message || '처리 실패', 'error');
        },
        error: function (xhr) {
            showToast('오류 발생', 'error');
            console.error(xhr.responseText);
        }
    };

    if (data instanceof FormData) {
        options.processData = false;
        options.contentType = false;
    }

    $.ajax(options);
}

function openIssueModal(idx) {
    $('#issue_idx').val('');
    ISSUE_FIELDS.forEach(f => $('#issue_' + f).val(''));
    $('#issue_file').val('');
    $('#issue_remove_file').prop('checked', false);
    $('#issue_current_file_wrap').hide();

    if (!idx) {
        $('#issue_modal_title').text('통관 이슈 등록');
        $('#issue_delete_btn').hide();
        $('#issue_save_btn').toggle(ISSUE_CAN_CREATE);
        return modalOpen('issue-modal');
    }

    issueAjax('/admin/customsIssue/detailForm/' + idx, 'POST', {}, function (res) {
        const d = res.data;
        $('#issue_idx').val(d.idx);
        ISSUE_FIELDS.forEach(f => $('#issue_' + f).val(d[f] ?? ''));
        if (d.ori_name) {
            $('#issue_current_file').text(d.ori_name).attr('href', '/admin/customsIssue/download/' + d.idx);
            $('#issue_current_file_wrap').css('display', 'flex');
        }
        $('#issue_modal_title').text('통관 이슈 수정');
        $('#issue_delete_btn').show();
        $('#issue_save_btn').toggle(ISSUE_CAN_UPDATE);
        modalOpen('issue-modal');
    });
}

function saveIssue() {
    const idx = $('#issue_idx').val();
    const fd  = new FormData();
    ISSUE_FIELDS.forEach(f => fd.append(f, $('#issue_' + f).val().trim()));

    if (!fd.get('title'))    return showToast('제목(ko)을 입력해주세요.', 'error');
    if (!fd.get('contents')) return showToast('내용(ko)을 입력해주세요.', 'error');

    const file = $('#issue_file')[0].files[0];
    if (file) fd.append('file', file);
    if ($('#issue_remove_file').is(':checked')) fd.append('remove_file', 1);

    issueAjax(idx ? '/admin/customsIssue/modify/' + idx : '/admin/customsIssue/regist', 'POST', fd, function () {
        showToast(idx ? '수정되었습니다.' : '등록되었습니다.', 'success');
        setTimeout(function () { location.reload(); }, 500);
    });
}

function deleteIssue() {
    const idx = $('#issue_idx').val();
    if (!idx || !confirm('통관 이슈를 삭제하시겠습니까?')) return;

    issueAjax('/admin/customsIssue/delete/' + idx, 'DELETE', {}, function () {
        showToast('삭제되었습니다.', 'success');
        setTimeout(function () { location.reload(); }, 500);
    });
}
</script>
