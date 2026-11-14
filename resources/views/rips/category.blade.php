@php
	$menu = 'category';
@endphp
@include('rips.header._header')
<div class="rp-page">
	<div class="rp-page-head">
		<div>
			<h3>카테고리</h3>
			<p class="rp-desc">상품 카테고리는 최대 4단계까지 만들 수 있습니다. 상위 카테고리를 선택하면 오른쪽에 하위 카테고리가 표시됩니다.</p>
		</div>
	</div>

	<div class="rp-cat-cols" id="catCols">
		@foreach ([1 => '1차 카테고리', 2 => '2차 카테고리', 3 => '3차 카테고리', 4 => '4차 카테고리'] as $depth => $label)
		<div class="rp-cat-col" data-depth="{{ $depth }}">
			<div class="head">{{ $label }}<small class="path">&nbsp;</small></div>
			<ul></ul>
			<div class="add">
				<input type="text" class="form-control" placeholder="{{ $label }}명" maxlength="100" onkeydown="if (event.key === 'Enter') addCategory({{ $depth }})">
				<button type="button" class="btn btn-primary shrink-0" onclick="addCategory({{ $depth }})">추가</button>
			</div>
		</div>
		@endforeach
	</div>
	<p class="rp-help">· 하위 카테고리나 등록된 상품이 있는 카테고리는 삭제할 수 없습니다. &nbsp;· 상품은 어느 단계의 카테고리에든 등록할 수 있습니다.</p>
</div>
@include('rips.footer._footer')
<script>
let categories = [];
const selected = { 0: 0 };   // depth => 선택한 카테고리 idx (0: 최상위)

function loadCategories() {
    $.get(RIPS_BASE + '/category/list', function (res) {
        categories = res.data || [];
        // 삭제 등으로 사라진 선택 해제
        for (let d = 1; d <= 4; d++) {
            if (selected[d] && !categories.some(c => c.idx === selected[d])) {
                for (let k = d; k <= 4; k++) delete selected[k];
            }
        }
        render();
    });
}

function render() {
    for (let depth = 1; depth <= 4; depth++) {
        const $col     = $('.rp-cat-col[data-depth="' + depth + '"]');
        const parent   = selected[depth - 1];
        const $ul      = $col.find('ul').empty();

        if (parent === undefined) {
            $col.addClass('is-disabled');
            $col.find('.path').html('&nbsp;');
            $ul.append('<li class="empty" style="cursor:default;">' + (depth - 1) + '차 카테고리를 선택해주세요.</li>');
            continue;
        }

        $col.removeClass('is-disabled');
        $col.find('.path').text(depth === 1 ? '최상위' : pathText(parent));

        const items = categories.filter(c => c.parent_idx === parent);
        if (!items.length) {
            $ul.append('<li class="empty" style="cursor:default;">등록된 카테고리가 없습니다.</li>');
        }
        items.forEach(function (c) {
            const cnt = (c.children_count ? '하위 ' + c.children_count : '') + (c.products_count ? (c.children_count ? ' · ' : '') + '상품 ' + c.products_count : '');
            $ul.append(
                '<li data-idx="' + c.idx + '" class="' + (selected[depth] === c.idx ? 'active' : '') + '">' +
                    '<span class="name">' + escapeHtml(c.name) + '</span>' +
                    (cnt ? '<span class="cnt">' + cnt + '</span>' : '') +
                    '<span class="tools">' +
                        '<button type="button" title="위로" data-act="up">▲</button>' +
                        '<button type="button" title="아래로" data-act="down">▼</button>' +
                        '<button type="button" title="이름 수정" data-act="edit">✎</button>' +
                        '<button type="button" title="삭제" data-act="delete">✕</button>' +
                    '</span>' +
                '</li>'
            );
        });
    }
}

function pathText(idx) {
    const names = [];
    let current = categories.find(c => c.idx === idx);
    while (current) {
        names.unshift(current.name);
        current = categories.find(c => c.idx === current.parent_idx);
    }
    return names.join(' > ');
}

function addCategory(depth) {
    const $input = $('.rp-cat-col[data-depth="' + depth + '"] .add input');
    const name   = $input.val().trim();
    if (!name) return showToast('카테고리명을 입력해주세요.', 'error');

    ripsAjax(RIPS_BASE + '/category/regist', 'POST', { name: name, parent_idx: selected[depth - 1] || 0 }, function () {
        $input.val('');
        showToast('등록되었습니다.', 'success');
        loadCategories();
    });
}

$('#catCols').on('click', 'li[data-idx]', function (e) {
    const idx   = Number($(this).data('idx'));
    const depth = Number($(this).closest('.rp-cat-col').data('depth'));
    const act   = $(e.target).closest('button').data('act');
    const item  = categories.find(c => c.idx === idx);

    if (act === 'up' || act === 'down') {
        return ripsAjax(RIPS_BASE + '/category/sort', 'POST', { idx: idx, direction: act }, loadCategories);
    }
    if (act === 'edit') {
        const name = prompt('카테고리명을 입력해주세요.', item.name);
        if (name === null) return;
        if (!name.trim()) return showToast('카테고리명을 입력해주세요.', 'error');
        return ripsAjax(RIPS_BASE + '/category/modify/' + idx, 'POST', { name: name.trim() }, function () {
            showToast('수정되었습니다.', 'success');
            loadCategories();
        });
    }
    if (act === 'delete') {
        if (!confirm('[' + item.name + '] 카테고리를 삭제하시겠습니까?')) return;
        return ripsAjax(RIPS_BASE + '/category/delete/' + idx, 'DELETE', {}, function () {
            showToast('삭제되었습니다.', 'success');
            loadCategories();
        });
    }

    // 선택
    selected[depth] = idx;
    for (let d = depth + 1; d <= 4; d++) delete selected[d];
    render();
});

loadCategories();
</script>
