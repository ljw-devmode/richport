@php 
	$header_skip = false; 
	$depth1_num = '5'; 
	$depth2_num = '3'; 
@endphp 
@include('admin.header._header')
<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">테스트베드 문의</h3>

	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/inquireTestbed">
			<div class="flex items-end gap-3 flex-wrap">
				<div>
					<p class="mb-1">신청구분</p>
					<select id="source" name="source" class="form-select">
						<option value="">전체</option>
						<option value="homepage" {{ request('source') === 'homepage' ? 'selected' : '' }}>홈페이지</option>
						<option value="rips" {{ request('source') === 'rips' ? 'selected' : '' }}>RIPS 업체</option>
					</select>
				</div>
				<div>
					<p class="mb-1">처리상태</p>
					<select id="status" name="status" class="form-select">
						<option value=""  {{ request('status') === null || request('status') === '' ? 'selected' : '' }}>전체</option>
						@foreach (\App\Models\Testbedinquire::STATUSES as $key => $label)
						<option value="{{ $key }}" {{ request('status') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">회사명</p>
					<input type="text" id="inquire_company" name="inquire_company" class="form-control" placeholder="회사명 또는 성명 입력" value="{{ request('inquire_company') }}">
				</div>
				<div>
					<p class="mb-1">연락처</p>
					<input type="text" id="inquire_hp" name="inquire_hp" class="form-control" placeholder="예 : 01012345678" value="{{ request('inquire_hp') }}">
				</div>
				<div>
					<p class="mb-1">주소</p>
					<input type="text" id="inquire_address" name="inquire_address" class="form-control" placeholder="주소 입력" value="{{ request('inquire_address') }}">
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">

		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">총 {{ $data->total() }}개</div>
			@if(auth()->user()->hasPerm('inquireTestbed.excel'))
			<div class="flex items-center gap-2">
				<button type="button" class="btn border-[#1b6c40] bg-[#1b6c40] text-white px-4" onclick="window.location.href='{{ route('admin.inquireTestbed.export', request()->query()) }}'">엑셀 다운로드</button>
			</div>
			@endif
		</div>

		<div class="p-6">
			<table class="table table-sm table-bordered table-hover">
				<colgroup>
					<col width="5%">
					<col width="10%">
					<col width="12%">
					<col width="12%">
					<col width="12%">
					<col width="*">
					<col width="8%">
					<col width="8%">
					<col width="8%">
				</colgroup>
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="whitespace-nowrap">번호</th>
						<th class="whitespace-nowrap">신청구분</th>
						<th class="whitespace-nowrap">신청상품</th>
						<th class="whitespace-nowrap">회사명</th>
						<th class="whitespace-nowrap">연락처</th>
						<th class="whitespace-nowrap">주소</th>
						<th class="whitespace-nowrap">등록일</th>
						<th class="whitespace-nowrap">처리상태</th>
						<th class="whitespace-nowrap">처리완료일</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $index => $item)
					@php
				        $number = ($data->currentPage() - 1) * $data->perPage() + ($index + 1);
				    @endphp
						<tr 
						@if(auth()->user()->hasPerm('inquireTestbed.save')) 
							onclick="setModifyForm('{{ $item->idx }}')"
							style="cursor: pointer;" 
							class="hover:bg-slate-100"
						@else 
							style="cursor: default;" 
						@endif
						>
							<td>{{ $number }}</td>
							<td>
								@if ($item->partner_idx)
									<span style="display:inline-block;padding:2px 10px;border-radius:999px;font-size:12px;background:#e6f0f3;color:#2f6476;">RIPS 업체</span>
									<div class="text-xs text-slate-500 mt-1">{{ optional($item->partner)->partner_name ?? '-' }}</div>
								@else
									<span style="display:inline-block;padding:2px 10px;border-radius:999px;font-size:12px;background:#f1f5f9;color:#475569;">홈페이지</span>
								@endif
							</td>
							<td>{{ optional($item->product)->product_name ?? '-' }}</td>
							<td>{{ $item->inquire_company }}</td>
							<td>{{ \App\Support\Privacy::phone($item->inquire_hp) }}</td>
							<td>{{ $item->inquire_address }}</td>
							<td>{{ $item->inquire_date }}</td>
							<td>{{ $item->status_kr }}</td>
							<td>{{ $item->complete_at }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="9" class="text-center">등록된 콘텐츠가 없습니다.</td>
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

	<!-- 문의상세 모달 -->
	<div id="inquiry_detail-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">문의 상세</h2>
					<button onclick="modalClose('inquiry_detail-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<h3 class="mb-1 text-lg font-semibold">문의내용</h3>
					<div class="flex items-center gap-10">
						<input type="hidden" id="modify_idx" name="modify_idx">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">등록일시</p>
							<input type="text" id="modify_inquire_date" name="modify_inquire_date" class="form-control" disabled>
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">신청상품</p>
							<input type="text" id="modify_types_subject" name="modify_types_subject" class="form-control" disabled>
						</div>
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">신청구분</p>
							<input type="text" id="modify_source" name="modify_source" class="form-control" disabled>
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">RIPS 업체</p>
							<input type="text" id="modify_partner_name" name="modify_partner_name" class="form-control" disabled>
						</div>
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">회사명</p>
							<input type="text" id="modify_inquire_company" name="modify_inquire_company" class="form-control" disabled>
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">연락처</p>
							<input type="text" id="modify_inquire_hp" name="modify_inquire_hp" class="form-control" disabled>
						</div>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">주소</p>
						<input type="text" id="modify_inquire_address" name="modify_inquire_address" class="form-control" disabled>
					</div>
					<div class="flex items-start gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium pt-2">요청 내용</p>
						<textarea id="modify_inquire_contents" name="modify_inquire_contents" class="form-control h-32" disabled>지붕 임대 시 계약 기간과 임대 수익 구조 문의드립니다.</textarea>
					</div>

					<h3 class="mt-3 mb-2 text-lg font-semibold">처리내용</h3>
					<div class="flex items-center gap-2">
						<div class="flex-1">
							<p class="mb-1 font-medium">확인일시</p>
							<input type="text" id="modify_response_at" name="modify_response_at" class="form-control" placeholder="" value="2025-03-30 10:12:00" disabled>
						</div>
						<div class="flex-1">
							<p class="mb-1 font-medium">처리상태</p>
							<select id="modify_status" name="modify_status" class="form-select">
								@foreach (\App\Models\Testbedinquire::STATUSES as $key => $label)
								<option value="{{ $key }}">{{ $label }}</option>
								@endforeach
							</select>
						</div>
						<div class="flex-1">
							<p class="mb-1 font-medium">처리완료일</p>
							<input type="text" id="modify_complete_at" name="modify_complete_at" class="form-control" placeholder="답변완료·진행완료시 업데이트됩니다." disabled>
						</div>
					</div>
					<div class="mt-3">
						<p class="mb-1 font-medium">처리내용 <span class="text-xs font-normal text-slate-500">(관리자 내부 메모, 업체에 공개되지 않음)</span></p>
						<textarea id="modify_response_contents" name="modify_response_contents" class="form-control h-32" placeholder="고객에게 응대한 내용, 메모 등을 입력하세요."></textarea>
					</div>
					<div class="mt-3">
						<p class="mb-1 font-medium">
							피드백 <span class="text-xs font-normal text-primary">(RIPS 업체 화면 &gt; 테스트베드 신청 상세에 공개)</span>
							<span class="text-xs font-normal text-slate-500 ml-2" id="modify_feedback_info"></span>
						</p>
						<textarea id="modify_feedback" name="modify_feedback" class="form-control h-32" placeholder="테스트베드 진행 결과, 소비자 반응, 개선 의견 등 업체에 전달할 피드백을 입력하세요."></textarea>
						<p class="mt-1 text-xs text-slate-500" id="modify_feedback_hint"></p>
					</div>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('inquiry_detail-modal')">목록</button>
					@if(auth()->user()->hasPerm('inquireTestbed.save'))
					<button type="button" class="btn btn-primary w-20" onclick="modify()">저장</button>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- END: Content -->
</div>
@include('admin.footer._footer')
<script>
function setModifyForm(idx) {
    $.ajax({
    	url: '/admin/inquireTestbed/detailForm/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
        	if (res.status !== 'success') {
        		showToast('오류가 발생하였습니다.','error');
                return;
            }

            const d = res.data;

            $('#modify_idx').val(d.idx);
			$('#modify_inquire_date').val(d.inquire_date);
			$('#modify_types_subject').val(d.product ? d.product.product_name : '-');
			$('#modify_source').val(d.source_kr);
			$('#modify_partner_name').val(d.partner ? (d.partner.partner_name + (d.partner.rips_id ? ' (' + d.partner.rips_id + ')' : '')) : '-');
			$('#modify_inquire_company').val(d.inquire_company);
			$('#modify_inquire_hp').val(d.inquire_hp);
			$('#modify_inquire_address').val(d.inquire_address);
			$('#modify_inquire_contents').val(d.inquire_contents);
			$('#modify_response_at').val(d.response_at);
			$('#modify_status').val(d.status);
			$('#modify_complete_at').val(d.complete_at);
			$('#modify_response_contents').val(d.response_contents);
			$('#modify_feedback').val(d.feedback || '');
			$('#modify_feedback_info').text(d.feedback_at ? '최종 작성 ' + d.feedback_at + (d.feedback_by ? ' · ' + d.feedback_by : '') : '');
			$('#modify_feedback_hint').text(d.partner_idx ? '' : '· 홈페이지 신청 건은 업체 화면이 없어 피드백이 공개되지 않습니다.');
			
			modalOpen('inquiry_detail-modal');
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}

function modify() {
	const idx					= $('#modify_idx').val();
  	const status				= $('#modify_status').val();
  	const response_contents		= $('#modify_response_contents').val();

  	if (!status)		return showToast('처리상태를 선택해주세요.','error');
	if (!response_contents)	return showToast('처리내용을 입력해주세요.','error');

	const fd = new FormData();
	fd.append('status', status);
	fd.append('response_contents', response_contents);
	fd.append('feedback', $('#modify_feedback').val());
	
	if (!confirm('문의를 처리하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/inquireTestbed/modify/'+idx,
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('문의가 처리되었습니다.','success');
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
</script>
