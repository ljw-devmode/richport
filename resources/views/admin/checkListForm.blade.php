@php 
	$header_skip = false; 
	$depth1_num = '4';
	$depth2_num = '1';
@endphp
@include('admin.header._header')

	<div class="mt-6 px-5 tabrelative">
		<h3 class="intro-y text-xl font-bold">설문조사 저장</h3>


		<div id="check-list-builder" class="max-w-5xl mx-auto p-6 space-y-6">
			<!-- [상단] 기본 정보 -->
			<div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-primary">
				<div class="flex gap-4">
					<div class="flex-1 pr-2">
						<label class="block text-sm font-medium mb-1">설문조사 이름</label>
						<input type="text" id="check_name" class="form-control text-xl font-bold" placeholder="제목 없는 설문조사">
					</div>
					<div class="w-64">
						<label class="block text-sm font-medium mb-1">테스트베드 상품</label>
						<select id="product_idx" class="form-select">
							<option value="">상품 선택</option>
							@foreach($products as $product)
								<option value="{{ $product->idx }}">{{ $product->product_name }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>

			<!-- [중앙] 섹션 컨테이너 -->
			<div id="section-wrapper" class="space-y-8">
				<!-- JS로 섹션이 추가됨 -->
			</div>
			<!-- [하단] 섹션 추가 버튼 -->
			<div class="flex gap-3 justify-center pt-4 mt-4">
				<button type="button" onclick="history.back();" class="btn btn-outline-secondary w-20 px-10 py-3 border-dashed hover:bg-blue-50">
					취소
				</button>                
				<button type="button" onclick="addSection()" class="btn btn-outline-primary px-10 py-3 border-dashed hover:bg-blue-50">
					<i data-lucide="plus" class="w-5 h-5 mr-2"></i> 새 섹션 추가
				</button>
				<button type="button" onclick="saveBuilderData()" class="btn btn-primary px-10 py-3 border-dashed hover:bg-blue-50">
					{{ isset($list) ? '설문조사 수정' : '설문조사 저장' }}
				</button>                
			</div>
		</div>
	</div>

<!-- END: Content -->
</div>
</div>
@include('admin.footer._footer')

<script>
// 등록화면 수정 화면 구분
// 화면 표시를 위한 list 의 존재여부를 바탕으로 list 있으면 수정, 없으면 등록으로 처리
$(document).ready(function() {
    // 1. 서버(Controller)에서 보낸 수정 데이터가 있는지 확인
    const existingData = @json($list ?? null);

    // 2. 데이터가 있는 경우 (수정 모드)
    if (existingData) {
        // 제목과 상품 먼저 채우기
        $('#check_name').val(existingData.check_name);
        $('#product_idx').val(existingData.product_idx);

        // 기존 데이터를 루프 돌며 화면에 섹션-아이템-옵션을 순서대로 그려줌
        existingData.checksection.forEach(section => {
            // 섹션을 추가하고 생성된 임시 sId를 받음
            const sId = addSection(section); 
        });
    } else {
        // 3. 데이터가 없는 경우 (신규 등록 모드)
        // 사용자가 바로 입력을 시작할 수 있게 빈 섹션 하나를 기본으로 띄워줌
        addSection();
    }
});

// 섹션 추가
function addSection(data = null) {
    const sId = data ? data.idx : Date.now();
    const layout = data ? data.layout_type : '3';
    const sectionName = data ? data.section_name : '';

    const html = `
        <div class="section-card bg-white p-6 rounded-lg shadow-md border border-gray-200 mb-6 mt-4" data-section-id="${sId}" data-layout="${layout}">
            <div class="flex items-center gap-3 mb-6 pb-4">
                <span class="cursor-move text-gray-400 handle-section">:::</span>
                <input type="text" class="section-name form-control text-lg font-semibold border-none focus:ring-0 w-100p" value="${sectionName}" placeholder="섹션 제목을 입력해주세요.">
                <!-- 레이아웃 1,2,3단계 선택 -->

            </div>
            <div class="flex mt-2 ml-2">
                <div class="flex bg-slate-100 p-1 rounded ml-4">
                    <button type="button" onclick="changeLayout('${sId}', '1')" class="btn-layout-1 px-3 py-1 text-xs rounded ${layout == '1' ? 'bg-white shadow font-bold' : ''}">1단계</button>
                    <button type="button" onclick="changeLayout('${sId}', '2')" class="btn-layout-2 px-3 py-1 text-xs rounded ${layout == '2' ? 'bg-white shadow font-bold' : ''}">2단계</button>
                    <button type="button" onclick="changeLayout('${sId}', '3')" class="btn-layout-3 px-3 py-1 text-xs rounded ${layout == '3' ? 'bg-white shadow font-bold' : ''}">3단계</button>
                </div>            
                <div class="ml-auto flex gap-2">
                    <button type="button" onclick="addItem('${sId}', 'radio')" class="btn btn-sm btn-outline-secondary w-20">● 1개 선택</button>
                    <button type="button" onclick="addItem('${sId}', 'checkbox')" class="btn btn-sm btn-outline-secondary w-20">■ 다중 선택</button>
                    <button type="button" onclick="addItem('${sId}', 'input')" class="btn btn-sm btn-outline-secondary w-20">Text 입력</button>
                    <button type="button" onclick="$(this).closest('.section-card').remove()" class="btn btn-sm text-red-500 w-20">삭제</button>
                </div>
            </div>
            <div id="item-wrapper-${sId}" class="item-sort-area space-y-4 min-h-[50px]"></div>
        </div>
    `;
    $('#section-wrapper').append(html);
    
    // [수정] 새 섹션이 추가된 후 해당 섹션의 아이템 영역에 Sortable을 즉시 적용합니다.
    const newWrapper = document.getElementById(`item-wrapper-${sId}`);
    initItemSortable(newWrapper);

    // [중요] 수정 모드일 때 하위 아이템들을 루프 돌며 추가
    if (data && data.checkitem) {
        data.checkitem.forEach(item => {
            addItem(sId, item.input_type, item);
        });
    }    
}

// 2depth, 3depth 구조 변경
function changeLayout(sId, type) {
    const card = $(`.section-card[data-section-id="${sId}"]`);
    card.attr('data-layout', type); // CSS가 이 값을 보고 필드를 숨깁니다.
    
    // 버튼 UI 업데이트
    card.find('.btn-layout-1, .btn-layout-2, .btn-layout-3').removeClass('bg-white shadow font-bold');
    card.find(`.btn-layout-${type}`).addClass('bg-white shadow font-bold');
}

// 아이템 추가
function addItem(sId, type, data = null) {
    const iId = data ? data.idx : Date.now() + Math.floor(Math.random() * 1000);  // ID 중복 방지
    const d2 = data ? data.d2_name : '';
    const d3 = data ? data.d3_name : '';
    const itemName = data ? data.item_name : '';
    const hasText = data ? data.has_text : 'N'; // 비고란 사용 여부

    let optionArea = '';

    if (type === 'radio' || type === 'checkbox') {
        const inputType = type === 'radio' ? 'radio' : 'checkbox';
        optionArea = `
            <div class="mt-4 pl-8">
                <div class="flex items-center mb-3">
                    <input type="${inputType}" class="mr-2" checked>
                    <div id="option-list-${iId}" class="space-y-2 flex-1"></div>
                    <button type="button" class="text-blue-500 text-sm ml-4 hover:underline" onclick="addOption('${iId}')">+ 옵션 추가</button>
                </div>
                <!-- 비고란 활성화 옵션 -->
                <div class="flex items-center ml-6 mt-2 p-2 bg-white rounded">
                    <input type="checkbox" class="has-text-check mr-2" ${hasText === 'Y' ? 'checked' : ''} placeholder="이슈, 참고사항 등을 기재해주세요">
                    <span class="text-xs text-slate-500">비고 입력란(이슈, 참고사항 등을 기재)을 함께 표시합니다.</span>
                </div>
            </div>
        `;
    }

    const html = `
        <div class="item-block mt-3 p-4 border rounded-lg bg-gray-50 relative" data-item-id="${iId}" data-type="${type}">
            <div class="flex gap-3 items-start">
                <span class="handle-item cursor-move mt-2 text-gray-400">:::</span>
                <div class="flex gap-1 w-100p">
                    <div class="d2-field  w-20p">
                        <input type="text" class="d2-name form-control" value="${d2}" placeholder="구분을 입력해주세요">
                    </div>
                    <div class="d3-field w-20p">
                        <input type="text" class="d3-name form-control" value="${d3}" placeholder="항목을 입력해주세요">
                    </div>
                    <input type="text" class="item-name form-control font-medium w-50p" value="${itemName}"  placeholder="질문 내용을 입력해주세요">
                    <button type="button" class="btn dark btn-primary text-gray-400 hover:text-red-500" onclick="$(this).closest('.item-block').remove()">삭제</button>
                </div>
            </div>
            ${optionArea}
        </div>
    `;
    $(`#item-wrapper-${sId}`).append(html);

    // 수정 모드일 때 하위 옵션들을 루프 돌며 추가
    if (data && data.checkoption && data.checkoption.length > 0) {
        // 수정 모드: DB에 데이터가 있으면 그 데이터만 그립니다.
        data.checkoption.forEach(opt => { addOption(iId, opt.option_name); });
    } else {
        // 등록 모드 혹은 옵션이 없는 경우: 객관식(radio/check)일 때만 기본 빈 옵션 1개를 생성합니다.
        if (type === 'radio' || type === 'checkbox') {
            addOption(iId); 
        }
    }

    // 드래그&드랍을 위한 아이콘 설정
    if (typeof lucide !== 'undefined') {
        lucide.createIcons({icons: lucide.icons });
    }  
}

// 옵션 추가
function addOption(iId, name = '') {
    const oId = Date.now() + Math.random();
    const html = `
        <div class="flex items-center gap-2 option-row mb-2" data-option-id="${oId}">
            <input type="text" class="w-60p form-control option-name border-b border-gray-300 focus:border-blue-500 outline-none px-1 text-sm flex-1 w-80p" value="${name}" placeholder="옵션 내용">
            <button type="button" class="btn dark btn-secondary h-9 text-gray-400 hover:text-red-500" onclick="$(this).parent().remove()">삭제</button>
        </div>
    `;
    
    $(`#option-list-${iId}`).append(html);
    if (typeof lucide !== 'undefined') {
        lucide.createIcons({
            icons: lucide.icons // 모든 아이콘 객체를 주입
        });
    }    
}

// item 초기화
function initItemSortable(element) {
    if (!element) return;
    new Sortable(element, {
        group: 'items',
        handle: '.handle-item',
        animation: 150,
        ghostClass: 'bg-gray-100',
        onEnd: function() {
            console.log('아이템 순서 변경됨');
        }
    });
}

// 드래그앤 드랍으로 순서변경을 위한 라이브러리 초기화
$(document).ready(function() {
    // 섹션(1Depth) 정렬 설정
    const sectionWrapper = document.getElementById('section-wrapper');
    if (sectionWrapper) {
        new Sortable(sectionWrapper, {
            group: 'sections',
            handle: '.handle-section',
            animation: 150,
            ghostClass: 'bg-blue-50',
            onEnd: function() {
                console.log('섹션 순서 변경됨');
            }
        });
    }

    // 초기 로드된 섹션들에 대해 Sortable 적용
    $('.item-sort-area').each(function() {
        initItemSortable(this);
    });
});

// 데이터 세팅
function getBuilderData() {
    const sections = [];

    $('.section-card').each(function(sIdx) {
        const sectionData = {
            section_name: $(this).find('.section-name').val(),
            sort_order: sIdx,
            layout_type: $(this).data('layout') || '3',
            items: []
        };

        $(this).find('.item-block').each(function(iIdx) {
            const hasText = $(this).find('.has-text-check').is(':checked') ? 'Y' : 'N';
            const itemData = {
                d2_name: $(this).find('.d2-name').val(),
                d3_name: $(this).find('.d3-name').val(),
                item_name: $(this).find('.item-name').val(),
                input_type: $(this).data('type'),
                sort_order: iIdx,
                has_text: hasText,
                options: []
            };

            $(this).find('.option-row').each(function(oIdx) {
                itemData.options.push({
                    option_name: $(this).find('.option-name').val(),
                    sort_order: oIdx
                });
            });

            sectionData.items.push(itemData);
        });
        sections.push(sectionData);
    });

    return {
        check_name: $('#check_name').val(),
        product_idx: $('#product_idx').val(),
        sections: sections
    };
}

function saveBuilderData() {
    const data = getBuilderData();
    
    if(!data.check_name) return alert('설문조사 제목을 입력하세요.');
    if(!data.product_idx) return alert('테스트베드 상품을 선택하세요.');
    if(data.sections.length === 0) return alert('최소 하나 이상의 섹션을 추가해야 합니다.');

    // 수정 모드인지 확인 (Blade에서 넘겨준 $list->idx 존재 여부)
    const idx = "{{ $list->idx ?? '' }}";
    const mode = idx ? 'modify' : 'regist';
    
    // 수정된 라우트 규칙 반영: /admin/checkregist 또는 /admin/checkmodify/{idx}
    const url = mode === 'regist' ? '/admin/checkregist' : `/admin/checkmodify/${idx}`;

    if(!confirm(mode === 'regist' ? '등록하시겠습니까?' : '수정하시겠습니까?')) return;

    $.ajax({
        url: url,
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            if(res.status === 'success') {
                alert(mode === 'regist' ? '등록되었습니다.' : '수정되었습니다.');
                location.href = '/admin/checklist'; // 목록 페이지로 이동
            } else {
                alert(res.message); // 컨트롤러에서 보낸 에러 메시지(예: 결과 존재 시 수정 불가) 출력
            }
        },
        error: function(xhr) {
            const err = xhr.responseJSON;
            alert(err ? err.message : '저장 중 오류가 발생했습니다.');
        }
    });
}


// 저장 실행 함수
function saveCheckList(mode = 'create', idx = null) {
    const data = getBuilderData();
    if(!data.check_name) return alert('제목을 입력하세요.');

    const url = mode === 'create' ? '/admin/checklist/store' : `/admin/checklist/update/${idx}`;

    $.ajax({
        url: url,
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            if(res.status === 'success') {
                alert(mode === 'create' ? '등록되었습니다.' : '수정되었습니다.');
                location.href = '/admin/checklist/list';
            }
        }
    });
}
</script>
