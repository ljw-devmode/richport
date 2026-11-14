@php 
	$header_skip = false; 
	$depth1_num = '5'; 
	$depth2_num = '31'; 
@endphp 
@include('admin.header._header')

<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">외주사 관리</h3>
	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/user">
			<div class="flex items-end gap-3">
				<div class="w-4/5">
					<p class="mb-1">발전소</p>
					<select id="comp_idx" name="comp_idx" class="form-select w-36">
						<option value="">전체</option>
						@foreach ($companyList as $item)
						<option value="{{$item->idx}}" {{ request('comp_idx') == $item->idx ? 'selected' : '' }}>{{$item->comp_name}}</option>
						@endforeach
					</select>					
				</div>
				<div class="w-4/5">
					<p class="mb-1">외주사</p>
					<select id="out_idx" name="out_idx" class="form-select w-36">
						<option value="">전체</option>
						@foreach ($outcompanyList as $item)
						<option value="{{$item->idx}}" {{ request('out_idx') == $item->idx ? 'selected' : '' }}>{{$item->out_name}}</option>
						@endforeach
					</select>
				</div>
				<div class="w-4/5">
					<p class="mb-1">외주사 담당자</p>
					<select id="user_idx" name="user_idx" class="form-select w-36">
						<option value="">전체</option>
						@foreach ($outuserList as $item)
						<option value="{{$item->idx}}" {{ request('user_idx') == $item->idx ? 'selected' : '' }}>{{$item->user_name}}</option>
						@endforeach
					</select>
				</div>				
				<div class="w-4/5">
					<p class="mb-1">발전소명</p>
					<input type="text" id="comp_name" name="comp_name" class="form-control" placeholder="발전소명 입력" value="{{ request('comp_name') }}" >
				</div>				
				<div class="w-4/5">
					<p class="mb-1">외주사명</p>
					<input type="text" id="out_name" name="out_name" class="form-control" placeholder="외주사명 입력" value="{{ request('out_name') }}" >
				</div>
				<div class="w-4/5">
					<p class="mb-1">외주사ID</p>
					<input type="text" id="out_id" name="out_id" class="form-control" placeholder="아이디 입력" value="{{ request('out_id') }}" >
				</div>
				<div class="w-4/5">
					<p class="mb-1">담당자명</p>
					<input type="text" id="user_name" name="user_name" class="form-control" placeholder="담당자명 입력" value="{{ request('user_name') }}" >
				</div>				
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">총 {{ $data->total() }}개 업체</div>
			@if(auth()->user()->hasPerm('outcompany.create'))
			<div class="flex items-center gap-2">
				<a href="javascript:;" class="btn btn-dark px-4" onclick="modalOpen('outcompany_regist-modal')">외주사 등록</a>
			</div>
        	@endif
		</div>

		<div class="p-6">
			<table class="table table-bordered table-hover">
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="whitespace-nowrap">번호</th>
						<th class="whitespace-nowrap">외주사명</th>
						<th class="whitespace-nowrap">아이디</th>
						<th class="whitespace-nowrap">대표자명</th>
						<th class="whitespace-nowrap">사업자번호</th>
						<th class="whitespace-nowrap">전화번호</th>
						<th class="whitespace-nowrap">이메일</th>
						<th class="whitespace-nowrap">등록 임직원 수</th>
						<th class="whitespace-nowrap">등록일</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $index => $item)
					@php
				        $number = ($data->currentPage() - 1) * $data->perPage() + ($index + 1);
				    @endphp

						<tr 
						@if(auth()->user()->hasPerm('outcompany.update') || auth()->user()->hasPerm('outcompany.delete')) 
							onclick="location.href='/admin/outcompany/detail/{{ $item->idx }}'" 
							style="cursor: pointer;" 
							class="hover:bg-slate-100"
						@else 
							style="cursor: default;" 
						@endif
						>
							<td>{{ $item->idx }}</td>
							<td>{{ $item->out_name }}</td>
							<td>{{ $item->id }}</td>
							<td>{{ $item->ceo }}</td>
							<td>{{ $item->business_number }}</td>
							<td>{{ $item->phone }}</td>
							<td>{{ $item->email }}</td>
							<td>{{ $item->outuser_count }}</td>
							<td>{{ $item->created_at?->format('Y-m-d') }}</td>
						</tr>
					@empty
					<tr>
						<td colspan="9" class="text-center">등록된 외주사가 없습니다.</td>
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

	<!-- 외주사 등록 모달 -->
	<div id="outcompany_regist-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">외주사 등록</h2>
					<button onclick="modalClose('outcompany_regist-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class=" mt-3">
						<p class="mb-1 font-medium ">업체명 <span class="text-danger">*</span></p>
						<input type="text" id="reg_out_name" name="reg_out_name" class="form-control" placeholder="성명을 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">대표자명 </p>
						<input type="text" id="reg_ceo" name="reg_ceo" class="form-control" placeholder="대표자명을 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">아이디 <span class="text-danger">*</span></p>
						<input type="text" id="reg_id" name="reg_id" class="form-control" placeholder="아이디를 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">비밀번호 <span class="text-danger">*</span></p>
						<input type="password" id="reg_password" name="reg_password" class="form-control" placeholder="비밀번호를 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">연락처 </p>
						<input type="text" id="reg_phone" name="reg_phone" class="form-control" placeholder="연락처를 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">이메일 </p>
						<input type="text" id="reg_email" name="reg_email" class="form-control" placeholder="이메일을 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">주소 </p>
						<input type="text" id="reg_address" name="reg_address" class="form-control" placeholder="주소를 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">사업자 등록번호 </p>
						<input type="text" id="reg_business_number" name="reg_business_number" class="form-control" placeholder="사업자 등록번호를 입력해주세요">
					</div>					
				</div>


				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('outcompany_regist-modal')">취소</button>
					@if(auth()->user()->hasPerm('outcompany.create'))
		        	<button type="button" class="btn btn-primary w-20" onclick="regist();">등록</button>
					@endif
				</div>
			</div>
		</div>
	</div>

<!-- END: Content -->
</div>
</div>
@include('admin.footer._footer')

<script>

// 사용자 등록
function regist() {
  	const out_name	= $('#reg_out_name').val().trim();
  	const id		= $('#reg_id').val().trim();
  	const password	= $('#reg_password').val().trim();
  	const ceo		= $('#reg_ceo').val().trim();
  	const phone		= $('#reg_phone').val().trim();
  	const email		= $('#reg_email').val().trim();
  	const address	= $('#reg_address').val().trim();
  	const business_number	= $('#reg_business_number').val().trim();	

  	if (!out_name)	return showToast('외주사명을 입력해주세요.','error');
  	if (!id)		return showToast('아이디를 입력해주세요.','error');  
	if (!password)	return showToast('비밀번호를 입력해주세요.','error');
	if (!ceo)		return showToast('대표자명을 입력해주세요.','error');
	
	const fd = new FormData();
	fd.append('out_name', out_name);
	fd.append('id', id);
	fd.append('password', password);
	fd.append('ceo', ceo);
	fd.append('phone', phone);
	fd.append('email', email);
	fd.append('address', address);
	fd.append('business_number', business_number);

	if (!confirm('외주사를 등록하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/outcompany/regist',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	showToast('외주사가 등록되었습니다.','success');
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