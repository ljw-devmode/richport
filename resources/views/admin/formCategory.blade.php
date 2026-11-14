@php
	$header_skip = false;
	$depth1_num = '3';
	$depth2_num = '4';
	$maxDepth = \App\Models\FormTypes::MAX_DEPTH;
@endphp
@include('admin.header._header')
<style>
	.fc-cols { display: grid; grid-template-columns: repeat({{ $maxDepth }}, minmax(0, 1fr)); gap: 1rem; }
	.fc-col { display: flex; flex-direction: column; min-height: 560px; }
	.fc-col .fc-head { display: flex; align-items: center; justify-content: space-between; gap: .5rem; padding: .75rem 1rem; border-bottom: 1px solid rgb(var(--color-slate-200)); }
	.fc-col .fc-head .path { display: block; font-size: .75rem; color: rgb(var(--color-slate-500)); font-weight: 400; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
	.fc-col ul { flex: 1; overflow-y: auto; max-height: 640px; }
	.fc-col li { display: flex; align-items: center; gap: .5rem; padding: .6rem 1rem; border-bottom: 1px solid rgb(var(--color-slate-100)); cursor: pointer; }
	.fc-col li:hover { background: rgb(var(--color-slate-50)); }
	.fc-col li.active { background: rgb(var(--color-primary) / .08); box-shadow: inset 3px 0 0 rgb(var(--color-primary)); }
	.fc-col li.empty { justify-content: center; text-align: center; color: rgb(var(--color-slate-400)); cursor: default; padding: 3rem 1rem; }
	.fc-col li.empty:hover { background: none; }
	.fc-col li .name { flex: 1; min-width: 0; }
	.fc-col li .name b { font-weight: 600; }
	.fc-col li .name small { display: block; font-size: .75rem; color: rgb(var(--color-slate-500)); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
	.fc-col li .name .missing { color: rgb(var(--color-danger)); }
	.fc-col li .cnt { display: block; font-size: .7rem; color: rgb(var(--color-slate-400)); }
	.fc-col li .tools { flex-shrink: 0; display: none; gap: .2rem; }
	.fc-col li:hover .tools, .fc-col li.active .tools { display: flex; }
	.fc-col li .tools button { width: 1.6rem; height: 1.6rem; border-radius: .375rem; border: 1px solid rgb(var(--color-slate-200)); background: #fff; color: rgb(var(--color-slate-600)); font-size: .65rem; }
	.fc-col li .tools button:hover { border-color: rgb(var(--color-primary)); color: rgb(var(--color-primary)); }
	.fc-col li .tools button.danger:hover { border-color: rgb(var(--color-danger)); color: rgb(var(--color-danger)); }
	.fc-col li .tools button:disabled { opacity: .3; pointer-events: none; }
	.fc-col.is-disabled { background: rgb(var(--color-slate-50)); }
	.fc-col.is-disabled .fc-head .btn { pointer-events: none; opacity: .4; }
</style>
<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">카테고리 관리</h3>
	<p class="intro-y mt-2 text-slate-500">카테고리는 최대 {{ $maxDepth }}단계까지 만들 수 있습니다. 왼쪽에서 상위 카테고리를 선택하면 오른쪽에 하위 카테고리가 표시됩니다. 카테고리명은 한국어 · 영어 · 베트남어로 관리합니다.</p>

	<div class="fc-cols mt-6" id="fcCols">
		@for ($depth = 1; $depth <= $maxDepth; $depth++)
		<div class="fc-col intro-y border border-slate-200 rounded-lg bg-white" data-depth="{{ $depth }}">
			<div class="fc-head">
				<div class="font-semibold text-base min-w-0">
					{{ $depth }}차 카테고리 <span class="text-primary total"></span>
					<small class="path">&nbsp;</small>
				</div>
				@if(auth()->user()->hasPerm('formCategory.create'))
				<button type="button" class="btn btn-dark btn-sm px-3 shrink-0" onclick="openForm({{ $depth }})">등록</button>
				@endif
			</div>
			<ul></ul>
		</div>
		@endfor
	</div>
	<p class="mt-3 text-slate-500 text-xs">
		· 제품 · 파트너 · RIPS 매핑 등 다른 화면에는 1차 카테고리만 표시됩니다.
		&nbsp;· 하위 카테고리가 있거나 제품 · 파트너 · RIPS 카테고리에서 사용 중인 카테고리는 삭제할 수 없습니다.
		&nbsp;· ▲▼ 버튼으로 같은 단계 안의 순서를 바꿀 수 있습니다.
	</p>

	<!-- 카테고리 등록/수정 모달 -->
	<div id="fc-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto" id="fc_modal_title">카테고리 등록</h2>
					<button onclick="modalClose('fc-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="fc_idx">
					<input type="hidden" id="fc_parent_idx">
					<div class="flex items-center gap-2" id="fc_parent_row">
						<p class="shrink-0 w-32 font-medium">상위 카테고리</p>
						<p class="font-semibold text-primary" id="fc_parent_name"></p>
					</div>
					@foreach (['' => 'ko', '_en' => 'en', '_vn' => 'vn'] as $suffix => $lang)
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium">카테고리명({{ $lang }}) @if($lang === 'ko')<span class="text-danger">*</span>@endif</p>
						<input type="text" id="fc_subject{{ $suffix }}" class="form-control" maxlength="200" placeholder="{{ ['ko' => '한국어', 'en' => '영어', 'vn' => '베트남어'][$lang] }} 카테고리명을 입력해주세요" onkeydown="if (event.key === 'Enter') saveCategory()">
					</div>
					@endforeach
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('fc-modal')">취소</button>
					<button type="button" class="btn btn-primary w-20" onclick="saveCategory()">저장</button>
				</div>
			</div>
		</div>
	</div>
</div>

@include('admin.footer._footer')
<script>
const FC_MAX_DEPTH  = {{ $maxDepth }};
const FC_CAN_UPDATE = @json(auth()->user()->hasPerm('formCategory.update'));
const FC_CAN_DELETE = @json(auth()->user()->hasPerm('formCategory.delete'));
const FC_FIELDS     = ['subject', 'subject_en', 'subject_vn'];

let categories = [];
const selected = { 0: 0 };   // depth => 선택한 카테고리 idx (0: 최상위)

function escapeHtml(v) {
    return String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
}

function fcAjax(url, method, data, onSuccess) {
    $.ajax({
        url: url,
        method: method,
        data: data,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') onSuccess(res);
            else showToast(res.message || '처리 실패', 'error');
        },
        error: function (xhr) {
            showToast(xhr.responseJSON?.message || '오류 발생', 'error');
            console.error(xhr.responseText);
        }
    });
}

function loadCategories() {
    fcAjax('/admin/formCategory/list', 'GET', {}, function (res) {
        categories = res.data || [];
        // 삭제 등으로 사라진 선택 해제
        for (let d = 1; d <= FC_MAX_DEPTH; d++) {
            if (selected[d] && !categories.some(c => c.idx === selected[d])) {
                for (let k = d; k <= FC_MAX_DEPTH; k++) delete selected[k];
            }
        }
        render();
    });
}

function pathText(idx) {
    const names = [];
    let current = categories.find(c => c.idx === idx);
    while (current) {
        names.unshift(current.subject);
        current = categories.find(c => c.idx === current.parent_idx);
    }
    return names.join(' > ');
}

// 다국어명: ko 굵게, en / vn 은 아래 작게 (비어 있으면 빨간색 미입력 표시)
function nameHtml(c) {
    const lang = (v, code) => v ? escapeHtml(v) : '<span class="missing">' + code + ' 미입력</span>';
    const used = [];
    if (c.children_count) used.push('하위 ' + c.children_count);
    if (c.products_count) used.push('제품 ' + c.products_count);
    if (c.partners_count) used.push('파트너 ' + c.partners_count);

    return '<span class="name"><b>' + escapeHtml(c.subject) + '</b>' +
        '<small>' + lang(c.subject_en, 'en') + ' / ' + lang(c.subject_vn, 'vn') + '</small>' +
        (used.length ? '<span class="cnt">' + used.join(' · ') + '</span>' : '') + '</span>';
}

function toolsHtml(c, index, count) {
    const buttons = [];
    if (FC_CAN_UPDATE) {
        buttons.push({ act: 'up', title: '위로', icon: '▲', disabled: index === 0 });
        buttons.push({ act: 'down', title: '아래로', icon: '▼', disabled: index === count - 1 });
        buttons.push({ act: 'edit', title: '수정', icon: '✎' });
    }
    if (FC_CAN_DELETE) buttons.push({ act: 'delete', title: '삭제', icon: '✕', danger: true });
    if (!buttons.length) return '';

    return '<span class="tools">' + buttons.map(b =>
        '<button type="button" title="' + b.title + '" data-act="' + b.act + '"' + (b.danger ? ' class="danger"' : '') + (b.disabled ? ' disabled' : '') + '>' + b.icon + '</button>'
    ).join('') + '</span>';
}

function render() {
    for (let depth = 1; depth <= FC_MAX_DEPTH; depth++) {
        const $col   = $('.fc-col[data-depth="' + depth + '"]');
        const parent = selected[depth - 1];
        const $ul    = $col.find('ul').empty();

        if (parent === undefined) {
            $col.addClass('is-disabled');
            $col.find('.total').text('');
            $col.find('.path').html('&nbsp;');
            $ul.append('<li class="empty">' + (depth - 1) + '차 카테고리를 선택해주세요.</li>');
            continue;
        }

        const items = categories.filter(c => c.parent_idx === parent);
        $col.removeClass('is-disabled');
        $col.find('.total').text(items.length + '개');
        $col.find('.path').text(depth === 1 ? '최상위' : pathText(parent) + ' >');

        if (!items.length) $ul.append('<li class="empty">등록된 카테고리가 없습니다.</li>');
        items.forEach(function (c, i) {
            $ul.append(
                '<li data-idx="' + c.idx + '" class="' + (selected[depth] === c.idx ? 'active' : '') + '">' +
                    nameHtml(c) + toolsHtml(c, i, items.length) +
                '</li>'
            );
        });
    }
}

// 등록(item 없음) / 수정 모달
function openForm(depth, item) {
    const parentIdx = item ? item.parent_idx : selected[depth - 1];
    if (parentIdx === undefined) return showToast((depth - 1) + '차 카테고리를 먼저 선택해주세요.', 'error');

    $('#fc_modal_title').text(depth + '차 카테고리 ' + (item ? '수정' : '등록'));
    $('#fc_idx').val(item ? item.idx : '');
    $('#fc_parent_idx').val(parentIdx);
    FC_FIELDS.forEach(f => $('#fc_' + f).val(item ? item[f] : ''));

    if (parentIdx) {
        $('#fc_parent_name').text(pathText(parentIdx));
        $('#fc_parent_row').show();
    } else {
        $('#fc_parent_row').hide();
    }

    modalOpen('fc-modal');
    setTimeout(() => $('#fc_subject').trigger('focus'), 200);
}

function saveCategory() {
    const idx  = $('#fc_idx').val();
    const data = {};
    FC_FIELDS.forEach(f => data[f] = $('#fc_' + f).val().trim());

    if (!data.subject) return showToast('카테고리명(ko)을 입력해주세요.', 'error');

    let url = '/admin/formCategory/modify/' + idx;
    if (!idx) {
        url = '/admin/formCategory/regist';
        data.parent_idx = $('#fc_parent_idx').val();
    }

    fcAjax(url, 'POST', data, function () {
        showToast(idx ? '수정되었습니다.' : '등록되었습니다.', 'success');
        modalClose('fc-modal');
        loadCategories();
    });
}

$('#fcCols').on('click', 'li[data-idx]', function (e) {
    const idx   = Number($(this).data('idx'));
    const depth = Number($(this).closest('.fc-col').data('depth'));
    const act   = $(e.target).closest('button').data('act');
    const item  = categories.find(c => c.idx === idx);

    if (act === 'edit') return openForm(depth, item);

    if (act === 'up' || act === 'down') {
        return fcAjax('/admin/formCategory/sort/' + idx, 'POST', { direction: act }, loadCategories);
    }

    if (act === 'delete') {
        if (!confirm('[' + item.subject + '] 카테고리를 삭제하시겠습니까?')) return;
        return fcAjax('/admin/formCategory/delete/' + idx, 'DELETE', {}, function () {
            showToast('삭제되었습니다.', 'success');
            loadCategories();
        });
    }

    // 선택 → 다음 단계 표시
    selected[depth] = idx;
    for (let d = depth + 1; d <= FC_MAX_DEPTH; d++) delete selected[d];
    render();
});

loadCategories();
</script>
