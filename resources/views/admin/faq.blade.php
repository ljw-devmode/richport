@php
	$header_skip = false;
	$depth1_num = '5';
	$depth2_num = '4';
@endphp
@include('admin.header._header')

<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">FAQ</h3>
	<p class="mt-1 text-slate-500">사용자 화면 RIPS &gt; FAQ 에 노출됩니다. 정렬순서가 작은 항목이 위에 표시됩니다.</p>

	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/faq">
			<div class="flex items-end gap-3">
				<div class="w-1/5">
					<p class="mb-1">노출여부</p>
					<select name="use_yn" class="form-select">
						<option value="">전체</option>
						<option value="1" {{ request('use_yn') === '1' ? 'selected' : '' }}>노출</option>
						<option value="0" {{ request('use_yn') === '0' ? 'selected' : '' }}>미노출</option>
					</select>
				</div>
				<div class="w-4/5">
					<p class="mb-1">질문</p>
					<input type="text" name="keyword" class="form-control" placeholder="질문을 입력해주세요 (ko/en/vn)" value="{{ request('keyword') }}">
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">총 {{ $data->total() }}개</div>
			@if(auth()->user()->hasPerm('faq.create'))
			<div class="flex items-center gap-2">
				<a href="javascript:;" class="btn btn-dark px-4" onclick="openFaqModal()">FAQ 등록</a>
			</div>
			@endif
		</div>

		<div class="p-6">
			<table class="table table-bordered table-hover">
				<colgroup>
					<col width="8%">
					<col width="8%">
					<col width="*">
					<col width="*">
					<col width="*">
					<col width="8%">
					<col width="12%">
				</colgroup>
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="whitespace-nowrap">번호</th>
						<th class="whitespace-nowrap">정렬순서</th>
						<th class="whitespace-nowrap">질문(ko)</th>
						<th class="whitespace-nowrap">질문(en)</th>
						<th class="whitespace-nowrap">질문(vn)</th>
						<th class="whitespace-nowrap">노출</th>
						<th class="whitespace-nowrap">등록일</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $item)
						<tr
						@if(auth()->user()->hasPerm('faq.update') || auth()->user()->hasPerm('faq.delete'))
							onclick="openFaqModal('{{ $item->idx }}')" style="cursor: pointer;"
						@endif
						>
							<td>{{ $item->idx }}</td>
							<td>{{ $item->orderby }}</td>
							<td class="text-left">{{ $item->question }}</td>
							<td class="text-left">{{ $item->question_en ?: '-' }}</td>
							<td class="text-left">{{ $item->question_vn ?: '-' }}</td>
							<td>{{ $item->use_yn ? 'Y' : 'N' }}</td>
							<td>{{ $item->created_at?->format('Y-m-d') }}</td>
						</tr>
					@empty
					<tr>
						<td colspan="7" class="text-center">등록된 FAQ가 없습니다.</td>
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

	<!-- FAQ 등록/수정 모달 -->
	<div id="faq-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto" id="faq_modal_title">FAQ 등록</h2>
					<button onclick="modalClose('faq-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="faq_idx">
					<div class="flex items-center gap-10">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">정렬순서</p>
							<input type="number" id="faq_orderby" class="form-control" placeholder="작은 값이 위에 표시됩니다 (기본 0)">
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">노출여부</p>
							<div class="form-check form-switch">
								<input id="faq_use_yn" class="form-check-input" type="checkbox" checked>
								<label class="form-check-label" for="faq_use_yn">사용자 화면에 노출</label>
							</div>
						</div>
					</div>

					@foreach (['' => 'ko', '_en' => 'en', '_vn' => 'vn'] as $suffix => $lang)
					<h3 class="mt-5 mb-1 text-base font-semibold" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">{{ ['ko' => '한국어', 'en' => '영어', 'vn' => '베트남어'][$lang] }} ({{ $lang }})</h3>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium">질문({{ $lang }}) @if($lang === 'ko')<span class="text-danger">*</span>@endif</p>
						<input type="text" id="faq_question{{ $suffix }}" class="form-control" maxlength="500" placeholder="질문을 입력해주세요">
					</div>
					<div class="flex gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium pt-2">답변({{ $lang }}) @if($lang === 'ko')<span class="text-danger">*</span>@endif</p>
						<textarea id="faq_answer{{ $suffix }}" class="form-control h-32" placeholder="답변을 입력해주세요 (줄바꿈은 그대로 표시됩니다)"></textarea>
					</div>
					@endforeach
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					@if(auth()->user()->hasPerm('faq.delete'))
					<button type="button" class="btn btn-outline-danger w-20" id="faq_delete_btn" onclick="deleteFaq()" style="display:none;">삭제</button>
					@endif
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('faq-modal')">취소</button>
					<button type="button" class="btn btn-primary w-20" id="faq_save_btn" onclick="saveFaq()">저장</button>
				</div>
			</div>
		</div>
	</div>
</div>

@include('admin.footer._footer')
<script>
const FAQ_CAN_CREATE = @json(auth()->user()->hasPerm('faq.create'));
const FAQ_CAN_UPDATE = @json(auth()->user()->hasPerm('faq.update'));
const FAQ_FIELDS     = ['question', 'question_en', 'question_vn', 'answer', 'answer_en', 'answer_vn'];

function faqAjax(url, method, data, onSuccess) {
    $.ajax({
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
    });
}

function openFaqModal(idx) {
    $('#faq_idx').val('');
    FAQ_FIELDS.forEach(f => $('#faq_' + f).val(''));
    $('#faq_orderby').val('');
    $('#faq_use_yn').prop('checked', true);

    if (!idx) {
        $('#faq_modal_title').text('FAQ 등록');
        $('#faq_delete_btn').hide();
        $('#faq_save_btn').toggle(FAQ_CAN_CREATE);
        return modalOpen('faq-modal');
    }

    faqAjax('/admin/faq/detailForm/' + idx, 'POST', {}, function (res) {
        const d = res.data;
        $('#faq_idx').val(d.idx);
        FAQ_FIELDS.forEach(f => $('#faq_' + f).val(d[f] ?? ''));
        $('#faq_orderby').val(d.orderby);
        $('#faq_use_yn').prop('checked', d.use_yn == 1);
        $('#faq_modal_title').text('FAQ 수정');
        $('#faq_delete_btn').show();
        $('#faq_save_btn').toggle(FAQ_CAN_UPDATE);
        modalOpen('faq-modal');
    });
}

function saveFaq() {
    const idx  = $('#faq_idx').val();
    const data = { orderby: $('#faq_orderby').val(), use_yn: $('#faq_use_yn').is(':checked') ? 1 : 0 };
    FAQ_FIELDS.forEach(f => data[f] = $('#faq_' + f).val().trim());

    if (!data.question) return showToast('질문(ko)을 입력해주세요.', 'error');
    if (!data.answer)   return showToast('답변(ko)을 입력해주세요.', 'error');

    faqAjax(idx ? '/admin/faq/modify/' + idx : '/admin/faq/regist', 'POST', data, function () {
        showToast(idx ? '수정되었습니다.' : '등록되었습니다.', 'success');
        setTimeout(function () { location.reload(); }, 500);
    });
}

function deleteFaq() {
    const idx = $('#faq_idx').val();
    if (!idx || !confirm('FAQ를 삭제하시겠습니까?')) return;

    faqAjax('/admin/faq/delete/' + idx, 'DELETE', {}, function () {
        showToast('삭제되었습니다.', 'success');
        setTimeout(function () { location.reload(); }, 500);
    });
}
</script>
