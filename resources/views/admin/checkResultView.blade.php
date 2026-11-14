@php 
	$header_skip = false; 
	$depth1_num = '3'; 
	$depth2_num = '52'; 
@endphp 
@include('admin.header._header')

	<div class="mt-6 px-5 tabrelative ">
        <div class="flex justify-between items-center mb-5" >
            <h3 class="intro-y text-xl font-bold">체크리스트 결과 보기</h3>
            <div class="flex gap-2 sm:mt-0">
                <!-- <button onclick="window.print()" class="btn btn-secondary bg-white">인쇄</button> -->
                <button onclick="window.close()" class="btn btn-dark">닫기</button>
            </div>
        </div>

		<div class="p-5 max-w-5xl mx-auto my-10 bg-white shadow-lg rounded-lg border">
            <div class="flex justify-between items-center">
                <h3 class="py-2 font-bold text-slate-700">체크리스트 정보</h3>
                 @if($execution->parent_idx)
                <div class="mt-1 flex items-center">
                    <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i> 이 데이터는 수정된 사본입니다.
                    <a href="/admin/checkResultView/{{ $execution->parent_idx }}" target="_blank" class="ml-2 underline">[원본 보기]</a>
                </div>
                @else
                <div class="mt-1 flex items-center">
                    <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i> 이 데이터는 원본입니다.
                </div>
                @endif 
            </div>
            <div class="flex items-center justify-between">
                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th>체크리스트</th><td>{{ $execution->checklist->check_name }}</td>
                            <th>프로젝트</th><td>{{ $execution->project_name }}</td>
                        </tr>
                        <tr>
                            <th>부서</th><td>{{ $execution->checklist->department->depart_name ?? '-' }}</td>
                            <th>점검 일시</th><td>{{ $execution->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>업체명</th><td>{{ $execution->user_comp }}</td>
                            <th>점검자</th><td>{{ $execution->user_name }} / {{ $execution->user_phone }}</td>
                        </tr>
                    </thead>
                </table>
            </div>
           

            <!-- [본문] 섹션별 점검 결과 -->
            <div class="intro-y space-y-8">
                @foreach($execution->checklist->checksection as $section)
                    <div class="mt-4">
                        <h3 class="py-2 font-bold text-slate-700">{{ $section->section_name }}</h3>
                        <table class="table table-bordered ">
                            <thead class="bg-slate-50 text-center">
                                <tr>
                                    <th>구분</th>
                                    <th>항목</th>
                                    <th>세부 확인사항</th>
                                    <th>점검 결과</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($section->checkitem as $item)
                                    @php
                                        $ans = $execution->checkresult->where('item_idx', $item->idx)->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $item->d2_name }}</td>
                                        <td>{{ $item->d3_name }}</td>
                                        <td>{{ $item->item_name }}</td>
                                        <td class="p-3 text-center">
                                            @if($ans)
                                                @if($ans->input_type == 'input')
                                                    <span class="font-bold text-slate-800">{{ $ans->result_value }}</span>
                                                @else
                                                    @php
                                                        $opt = $item->checkoption->where('idx', $ans->option_idx)->first();
                                                    @endphp
                                                    <span class="px-3 py-1 rounded-full bg-blue-100 text-primary font-bold">
                                                        {{ $opt->option_name ?? '데이터오류' }}
                                                    </span>
                                                    @if($item->has_text == 'Y' && $ans->result_value)
                                                        <br/>
                                                        비고: {{ $ans->result_value ?? '-' }}
                                                    @endif
                                                @endif
                                            @else
                                                <span class="text-slate-300 italic text-xs">미응답</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>

            <!-- [하단] 서명 및 위치 확인 -->
            <div class="mt-4">
                <h3 class="py-2 font-bold text-slate-700">서명·위치</h3>
                <table class="table table-bordered w-full text-sm">
                    <thead>
                        <tr>
                            <th class="w-50p">점검자 서명</th>
                            <th class="w-50p">점검 위치</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pd-010">

                                    @if($execution->user_sign)
                                        <img src="{{ asset('storage/' . $execution->user_sign) }}" alt="Signature">
                                    @else
                                        <span class="text-slate-400">서명 없음</span>
                                    @endif

                            </td>
                            <td class="text-center">
                                @php
                                	$url = "https://map.naver.com?lat=". $execution->user_lat . "&lng=" . $execution->user_lng . "&title=" . $execution->user_comp . " | " . $execution->user_name . " | " . $execution->user_phone;
								@endphp
                                @if($execution->user_lat && $execution->user_lng)
                                    <div>위도: {{ $execution->user_lat ?? '미획득' }}</div>
                                    <div>경도: {{ $execution->user_lng ?? '미획득' }}</div>
                                    <div>                                    
                                        <a href="{{ $url }}" target="_blank" class="btn btn-outline-primary bg-white shadow-sm px-6">
                                            <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i> 
                                        </a>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">위치 정보를 획득하지 못했습니다.</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
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
        // 제목과 부서 먼저 채우기
        $('#check_name').val(existingData.check_name);
        $('#depart_idx').val(existingData.depart_idx);

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
    const sectionName = data ? data.section_name : '';

    const html = `
        <div class="section-card bg-white p-6 rounded-lg shadow-md border border-gray-200 mb-6 mt-4" data-section-id="${sId}">
            <div class="flex items-center gap-3 mb-6 pb-4">
                <span class="cursor-move text-gray-400 handle-section">:::</span>
                <input type="text" class="section-name form-control text-lg font-semibold border-none focus:ring-0 w-60p" value="${sectionName}" placeholder="섹션 제목을 입력해주세요.">
                <div class="ml-auto flex gap-2">
                    <button type="button" onclick="addItem('${sId}', 'radio')" class="btn btn-sm btn-outline-secondary w-20">● 1개 선택</button>
                    <button type="button" onclick="addItem('${sId}', 'check')" class="btn btn-sm btn-outline-secondary w-20">■ 다중 선택</button>
                    <button type="button" onclick="addItem('${sId}', 'input')" class="btn btn-sm btn-outline-secondary w-20">T 입력</button>
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

// 아이템 추가
function addItem(sId, type, data = null) {
    const iId = data ? data.idx : Date.now() + Math.random(); // ID 중복 방지
    const d2 = data ? data.d2_name : '';
    const d3 = data ? data.d3_name : '';
    const itemName = data ? data.item_name : '';

    let optionArea = '';

    if (type === 'radio') {
        optionArea = `
            <div class="flex mt-2 space-y-2">
                <input type="radio" class="mt-3 mr-2">
                <div id="option-list-${iId}" class="space-y-2 w-80p"></div>
                <button type="button" class="h-10 text-blue-500 text-sm btn ml-2 font-medium hover:underline" onclick="addOption('${iId}')">+ 옵션 추가</button>
            </div>
        `;
    }

    if (type === 'check') {
        optionArea = `
            <div class="flex mt-2 space-y-2">
                <input type="checkbox" class="mt-3 mr-2">
                <div id="option-list-${iId}" class="space-y-2 w-80p"></div>
                <button type="button" class="h-10 text-blue-500 text-sm btn ml-2 font-medium hover:underline" onclick="addOption('${iId}')">+ 옵션 추가</button>
            </div>
        `;
    }

    const html = `
        <div class="item-block mt-3 p-4 border rounded-lg bg-gray-50 relative" data-item-id="${iId}" data-type="${type}">
            <div class="flex gap-3 items-start">
                <span class="handle-item cursor-move mt-2 text-gray-400">:::</span>
                <div class="flex-1 space-y-3">
                    <input type="text" class="d2-name form-control w-20p" value="${d2}" placeholder="구분을 입력해주세요">
                    <input type="text" class="d3-name form-control w-20p" value="${d3}" placeholder="항목을 입력해주세요">
                    <input type="text" class="item-name form-control font-medium w-50p" value="${itemName}"  placeholder="질문 내용을 입력해주세요">
                    <button type="button" class="btn dark btn-primary text-gray-400 hover:text-red-500" onclick="$(this).closest('.item-block').remove()">삭제</button>
                </div>
            </div>
            ${optionArea}
        </div>
    `;
    $(`#item-wrapper-${sId}`).append(html);

    // [중요] 수정 모드일 때 하위 옵션들을 루프 돌며 추가
    if (data && data.checkoption && data.checkoption.length > 0) {
        // 1. 수정 모드: DB에 데이터가 있으면 그 데이터만 그립니다.
        data.checkoption.forEach(opt => {
            addOption(iId, opt.option_name);
        });
    } else {
        // 2. 등록 모드 혹은 옵션이 없는 경우: 
        // 객관식(radio/check)일 때만 기본 빈 옵션 1개를 생성합니다.
        if (type === 'radio' || type === 'check') {
            addOption(iId); 
        }
    }

    if (typeof lucide !== 'undefined') {
        lucide.createIcons({
            icons: lucide.icons // 모든 아이콘 객체를 주입
        });
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
            items: []
        };

        $(this).find('.item-block').each(function(iIdx) {
            const itemData = {
                d2_name: $(this).find('.d2-name').val(),
                d3_name: $(this).find('.d3-name').val(),
                item_name: $(this).find('.item-name').val(),
                input_type: $(this).data('type'),
                sort_order: iIdx,
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
        depart_idx: $('#depart_idx').val(),
        sections: sections
    };
}

function saveBuilderData() {
    const data = getBuilderData();
    
    if(!data.check_name) return alert('체크리스트 제목을 입력하세요.');
    if(!data.depart_idx) return alert('담당 부서를 선택하세요.');
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
