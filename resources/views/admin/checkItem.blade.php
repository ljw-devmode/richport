@php 
	$header_skip = false; 
	$depth1_num = '5'; 
	$depth2_num = '41'; 
@endphp 
@include('admin.header._header')

	<div class="mt-6 px-5">
		<h3 class="intro-y text-xl font-bold">체크 아이템 관리</h3>
		<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
			<form method="GET" action="/admin/checkitem" class="flex items-center gap-3">
				<div class="flex items-end gap-3">
					<div class="w-44">
						<p class="mb-1">타입</p>
						<select id="regions_idx" name="regions_idx" class="form-select w-44">
							<option value="">전체</option>
							<option value="radio" {{ request('checkitem_type') == 'radio' ? 'selected' : '' }}>객관식 (라디오, 택1)</option>
							<option value="check" {{ request('checkitem_type') == 'check' ? 'selected' : '' }}>다중선택 (체크박스)</option>
							<option value="input" {{ request('checkitem_type') == 'input' ? 'selected' : '' }}>주관식 (입력)</option>
						</select>
					</div>
					<div>
						<p class="mb-1">아이템 제목</p>
						<input type="text" id="checkitem_title" name="checkitem_title" class="form-control" placeholder="아이템 제목을 입력해주세요" value="{{ request('checkitem_title') }}" >
					</div>
					<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
				</div>
			</form>
		</div>

		<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
			<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
				<div class="text-base text-primary">총 {{ $data->total() }}개</div>
				@if(auth()->user()->hasPerm('checkitem.create'))
				<div class="flex items-center gap-2">
					<a href="javascript:;" class="btn btn-dark px-4" onclick="modalOpen('checkitem_regist-modal')">체크 아이템 등록</a>
				</div>
        		@endif
			</div>

			<div class="p-6">
				<table class="table table-bordered table-hover">
					<thead class="bg-slate-50 text-center">
						<tr>
							<th class="whitespace-nowrap">번호</th>
							<th class="whitespace-nowrap">타입</th>
							<th class="whitespace-nowrap">아이템 제목</th>
							<th class="whitespace-nowrap">적용 템플릿수</th>
							<th class="whitespace-nowrap">등록일</th>
						</tr>
					</thead>
					<tbody class="text-center font-medium">
						@forelse ($data as $index => $item)
							<tr 
							@if(auth()->user()->hasPerm('checkitem.detail') || auth()->user()->hasPerm('checkitem.detail') || auth()->user()->hasPerm('checkitem.delete')) 
								onclick="checkitemDetail('{{ $item->idx }}')"
								style="cursor: pointer;" 
								class="hover:bg-slate-100"
							@else 
								style="cursor: default;" 
							@endif
							>
								<td>{{ $index + 1 }}</td>
								<td>{{ $item->checkitem_type }}</td>
								<td>{{ $item->checkitem_title }}</td>
								<td>{{ $item->template_count }}</td>
								<td>{{ $item->created_at?->format('Y-m-d') }}</td>
							</tr>
						@empty
						<tr>
							<td colspan="9" class="text-center">등록된 체크 아이템이 없습니다.</td>
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
						{{-- 첫 페이지 --}}
						<li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $data->url(1) }}"> <i data-lucide="chevrons-left" class="w-4 h-4"></i></a>
						</li> 
						{{-- 이전 페이지 --}}
						<li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $data->previousPageUrl() }}"> <i data-lucide="chevron-left" class="w-4 h-4"></i></a>
						</li> 
						{{-- 페이지 번호 --}} 
						@for ($i = $start; $i <= $end; $i++)
							<li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
								<a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
							</li>
						@endfor 
						{{-- 다음 페이지 --}}
						<li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $data->nextPageUrl() }}"> <i data-lucide="chevron-right" class="w-4 h-4"></i></a>
						</li> 
						{{-- 마지막 페이지 --}}
						<li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $data->url($lastPage) }}"> <i data-lucide="chevrons-right" class="w-4 h-4"></i></a>
						</li>
					</ul>
				</nav>	
			</div>
		</div>
	</div>

	<!-- 체크 아이템 등록 모달 -->
	<div id="checkitem_regist-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">체크 아이템 등록</h2>
					<button onclick="modalClose('checkitem_regist-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">부서 <span class="text-danger">*</span></p>
							<select id="regist_depart_idx" name="regist_depart_idx" class="form-select">
								<option value="">부서 선택</option>
								@foreach ($department as $item)
								<option value="{{$item->idx}}">{{$item->depart_name}}</option>
								@endforeach
							</select>
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">카테고리 <span class="text-danger">*</span></p>
							<select id="regist_cat_idx" name="regist_cat_idx" class="form-select">
								<option value="">카테고리 선택</option>
								@foreach ($category as $item)
								<option value="{{$item->idx}}">{{$item->cat_name}}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">질문, 확인사항 <span class="text-danger">*</span></p>
						<input type="text" id="regist_item_title" name="regist_item_title" class="form-control" placeholder="질문, 확인사항을 입력해주세요">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">답변 유형 <span class="text-danger">*</span></p>
						<select name="regist_item_type" id="regist_item_type" class="form-control">
                        	<option value="radio">객관식 (라디오, 택1)</option>    
                            <option value="check">다중선택 (체크박스)</option>
							<option value="input">주관식 (입력)</option>
                        </select>
					</div>
					
					<!-- 답변 옵션 영역 -->
                    <div id="area_options" class="mt-3 p-3 bg-light border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>답변 옵션(선택지) 설정</strong>
                            <!-- 주관식일 때는 추가 버튼 숨김 -->
                            <button type="button" id="btn_add_option" class="btn btn-xs btn-primary" onclick="addOptionRow()">+ 항목 추가</button>
                        </div>
                        
                        <div id="list_options">
                            <!-- JS로 옵션 행이 추가됨 -->
                        </div>

                        <!-- 기타 의견 추가 여부 (라디오/체크일 때만 표시) -->
                        <div id="area_etc" class="mt-3 pt-2 border-top">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="chk_etc" name="is_etc" value="Y">
                                <label class="custom-control-label" for="chk_etc">서술형 '기타' 입력란 추가</label>
                            </div>
                        </div>
                    </div>					
				</div>
				
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('checkitem_regist-modal')">취소</button>
					@if(auth()->user()->hasPerm('checkitem.create'))
					<button type="button" class="btn btn-primary w-20" onclick="regist();">등록</button>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END: Content -->
</div>
@include('admin.footer._footer')

<script>
$(document).ready(function() {
    // 초기 로드 시 라디오 모드로 시작
    changeInputType('radio');

    $('#sel_input_type').on('change', function() {
        changeInputType($(this).val());
    });
});

function changeInputType(type) {
    $('#list_options').empty(); // 초기화

    if (type === 'input') {
        // 주관식: 입력란 하나만 보여주고 추가 버튼 숨김
        $('#btn_add_option').hide();
        $('#area_etc').hide();
        $('#list_options').append(`
            <div class="alert alert-secondary mb-0">
                <i class="fa fa-info-circle"></i> 체크리스트 화면에 주관식 입력창이 생성됩니다.
            </div>
        `);
    } else {
        // 라디오/체크: 추가 버튼 보이고 기본 옵션 2개 생성
        $('#btn_add_option').show();
        $('#area_etc').show();
    }
}

function addOptionRow(title = '') {
    const html = `
        <div class="form-check mb-2">
            <input type="text" name="option_titles[]" class="form-control w-40p" value="${title}" placeholder="옵션명을 입력해주세요" required>
            <div class="input-group-append ml-1">
                <button type="button" class="btn btn-sm btn-danger" onclick="$(this).closest('.option-item').remove()">삭제</button>
            </div>
        </div>
    `;
    $('#list_options').append(html);
}


function regist() {
	const depart_idx		= $('#regist_depart_idx').val();
	const cat_idx			= $('#regist_cat_idx').val();
	const item_title		= $('#regist_item_title').val();
	const item_type			= $('#regist_item_type').val();
	const is_etc			= $('#chk_etc').is(':checked') ? 'Y' : 'N';

    const options = [];
    if(item_type !== 'input') {
        $('input[name="option_titles[]"]').each(function() {
            if($(this).val().trim() !== '') {
                options.push($(this).val());
            }
        });
    }

  	if (!depart_idx)			return showToast('부서를 선택해주세요.','error');
  	if (!cat_idx)				return showToast('카테고리를 선택해주세요.','error');
	if (!item_title)			return showToast('항목명을 입력해주세요.','error');
  	if (!item_type)				return showToast('항목 유형을 선택해주세요.','error');
  	if (!options.length === 0)	return showToast('최소 하나 이상의 답변 옵션을 입력해주세요.','error');

	if (!confirm('발전소를 등록하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/company/regist',
        method: 'POST',
        data: {
            depart_idx: depart_idx,
            cat_idx: cat_idx,
            item_title: item_title,
            item_type: item_type,
            is_etc: is_etc,
            options: options // 배열로 전달
        },
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	showToast('발전소가 저장되었습니다.','success');
            	location.reload();
            } else {
            	showToast(res.message || '저장 실패','error');
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}

function companyDetail(idx) {
	location.href='/admin/company/detail/'+idx;
}
</script>