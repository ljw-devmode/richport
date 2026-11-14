@php 
	$header_skip = false; 
	$depth1_num = '3'; 
	$depth2_num = '2'; 
@endphp 
@include('admin.header._header')
<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">파트너</h3>

	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/partner">
			<div class="flex items-end gap-3 flex-wrap">
				<div>
					<p class="mb-1">국가</p>
					<select id="country" name="country" class="form-select">
						<option value="">전체</option>
						<option value="vietnam">Vietnam</option>
						<option value="korea">Korea</option>
					</select>
				</div>
				<div>
					<p class="mb-1">본점/지점</p>
					<select id="branch_type" name="branch_type" class="form-select">
						<option value="">전체</option>
						<option value="head" {{ request('branch_type') === 'head' ? 'selected' : '' }}>본점</option>
						<option value="branch" {{ request('branch_type') === 'branch' ? 'selected' : '' }}>지점</option>
					</select>
				</div>
				<div>
					<p class="mb-1">구분</p>
					<select id="types_idx" name="types_idx" class="form-select">
						<option value="">전체</option>
						@foreach ($types as $item)
							<option value="{{$item->idx}}" {{ request('types_idx') == $item->idx ? 'selected' : '' }}>{{$item->subject}}</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">카테고리</p>
					<select id="forms_idx" name="forms_idx" class="form-select">
						<option value="">전체</option>
						@foreach ($forms as $item)
							<option value="{{$item->idx}}" {{ request('forms_idx') == $item->idx ? 'selected' : '' }}>{{$item->subject}}</option>
						@endforeach
					</select>
				</div>				
				<div>
					<p class="mb-1">홈 추천</p>
					<select id="home_recommend" name="home_recommend" class="form-select w-36">
						<option value="" {{ request('home_recommend') === null || request('home_recommend') === '' ? 'selected' : '' }}>전체</option>
            			<option value="1" {{ request('home_recommend') == '1' ? 'selected' : '' }}>추천</option>
            			<option value="0" {{ request('home_recommend') == '0' ? 'selected' : '' }}>미추천</option>
					</select>
				</div>
				<div>
					<p class="mb-1">파트너 추천</p>
					<select id="partner_recommend" name="partner_recommend" class="form-select w-36">
						<option value="" {{ request('partner_recommend') === null || request('partner_recommend') === '' ? 'selected' : '' }}>전체</option>
			            <option value="1" {{ request('partner_recommend') == '1' ? 'selected' : '' }}>추천</option>
			            <option value="0" {{ request('partner_recommend') == '0' ? 'selected' : '' }}>미추천</option>
					</select>
				</div>
				<div>
					<p class="mb-1">도시</p>
					<select id="cities_idx" name="citiess_idx" class="form-select w-36">
						<option value="">전체</option>
						@foreach ($cities as $item)
						<option value="{{$item->idx}}" {{ request('cities_idx') == $item->idx ? 'selected' : '' }}>{{$item->name_kr}}</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">구역</p>
					<select id="districts_idx" name="districts_idx" class="form-select w-36">
						<option value="">전체</option>
					</select>
				</div>
				<div>
					<p class="mb-1">업체명</p>
					<input type="text" id="partner_name" name="partner_name" class="form-control w-72" placeholder="업체명을 입력해주세요" value="{{ request('partner_name') }}" >
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">총 {{ $data->total() }}개</div>
			@if(auth()->user()->hasPerm('partner.create') || auth()->user()->hasPerm('partner.excel'))
			<div class="flex items-center gap-2">
				@if(auth()->user()->hasPerm('partner.create'))
				<a href="javascript:;" class="btn btn-dark w-20" onclick="modalOpen('partner_regist-modal')">등록</a>
				@endif
				@if(auth()->user()->hasPerm('partner.excel'))
				<button type="button" class="btn border-[#1b6c40] bg-[#1b6c40] text-white px-4" onclick="window.location.href='{{ route('admin.partner.export', request()->query()) }}'">엑셀 다운로드</button>
				@endif
			</div>
			@endif
		</div>

		<div class="p-6">
			<table class="table table-sm table-bordered table-hover">
				<colgroup>
					<col>
					<col>
					<col>
					<col>
					<col>
					<col>
					<col>
					<col>
					<col>
					<col>
					<col>
					<col>
					<col>
					<col>
				</colgroup>
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="whitespace-nowrap">번호</th>
						<th class="whitespace-nowrap">국가</th>
						<th class="whitespace-nowrap">본점/지점</th>
						<th class="whitespace-nowrap">구분</th>
						<th class="whitespace-nowrap">카테고리</th>
						<th class="whitespace-nowrap">구역</th>
						<th class="whitespace-nowrap">도시</th>
						<th class="whitespace-nowrap">업체명</th>
						<th class="whitespace-nowrap">주요채널</th>
						<th class="whitespace-nowrap">유통기간</th>
						<th class="whitespace-nowrap">홈</th>
						<th class="whitespace-nowrap">파트너</th>
						<th class="whitespace-nowrap">RIPS</th>
						<th class="whitespace-nowrap">등록일</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $index => $item)
						@php
					        $number = ($data->currentPage() - 1) * $data->perPage() + ($index + 1);
					    @endphp
						<tr
						@if(auth()->user()->hasPerm('partner.update') || auth()->user()->hasPerm('partner.delete')) 
							onclick="setModifyForm('{{ $item->idx }}')"
							style="cursor: pointer;" 
							class="hover:bg-slate-100"
						@else 
							style="cursor: default;" 
						@endif
						>
							<td>{{ $number }}</td>
							<td>{{ $item->country }}</td>
							<td class="whitespace-nowrap">{{ $item->branch_type_text }}@if($item->branch_type === 'branch')<div class="text-xs text-slate-500">{{ $item->head->partner_name ?? '-' }}</div>@endif</td>
							<td>{{ $item->types->subject ?? '-' }}</td>
							<td>{{ $item->forms->subject ?? '-' }}</td>
							<td>{{ $item->cities->name_kr ?? '-' }}</td>
							<td>{{ $item->districts->name_kr ?? '-' }}</td>
							<td>{{ $item->partner_name }}</td>
							<td>{{ $item->channel }}</td>
							<td>{{ $item->logistic }}</td>
							<td>{{ $item->home_recommend_text }}</td>
							<td>{{ $item->partner_recommend_text }}</td>
							<td class="whitespace-nowrap">{{ $item->ripsPartner->rips_id ?? '-' }}@if($item->ripsPartner && auth()->user()->hasPerm('rips.view'))<a href="{{ route('admin.ripsPartner.info', ['idx' => $item->ripsPartner->idx]) }}" class="btn btn-sm btn-outline-secondary ml-2" onclick="event.stopPropagation();">RIPS 업체</a>@endif</td>
							<td>{{ $item->created_at?->format('Y-m-d') }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="14" class="text-center">등록된 파트너가 없습니다.</td>
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

	<!-- 파트너 등록 모달 -->
	<div id="partner_regist-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">파트너 등록</h2>
					<button onclick="modalClose('partner_regist-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<h3 class="mb-1 text-base font-semibold">파트너 정보</h3>
					<div class="flex items-center gap-10">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">국가 <span class="text-danger">*</span></p>
                            <select id="regist_country" name="regist_country" class="form-select">
                                <option value="korea">Korea</option>
                                <option value="vietnam">Vietnam</option>
                            </select>
                        </div>
                    </div>
					<div class="flex items-center gap-10 mt-3 only-vietnam">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">본점/지점</p>
                            <div class="flex items-center gap-4">
                                <div class="form-check">
                                    <input id="regist_branch_head" name="regist_branch_type" class="form-check-input" type="radio" value="head" checked>
                                    <label class="form-check-label" for="regist_branch_head">본점 (단일 매장 포함)</label>
                                </div>
                                <div class="form-check">
                                    <input id="regist_branch_branch" name="regist_branch_type" class="form-check-input" type="radio" value="branch">
                                    <label class="form-check-label" for="regist_branch_branch">지점</label>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 flex items-center gap-2 branch-head-box" style="display:none;">
                            <p class="shrink-0 w-32 font-medium">본점 <span class="text-danger">*</span></p>
                            <select id="regist_head_idx" class="form-select head-select">
                                <option value="">본점을 선택해주세요</option>
                                @foreach ($heads as $head)
									<option value="{{ $head->idx }}">{{ $head->partner_name }} ({{ $head->country }})</option>
								@endforeach
                            </select>
                        </div>
                    </div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">구분 <span class="text-danger">*</span></p>
                            <select id="regist_types_idx" name="regist_types_idx" class="form-select">
                                <option value="">구분을 선택해주세요.</option>
                                @foreach ($types as $item)
									<option value="{{$item->idx}}">{{$item->subject}}</option>
								@endforeach
                            </select>
                        </div>
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">카테고리 <span class="text-danger">*</span></p>
                            <select id="regist_forms_idx" name="regist_forms_idx" class="form-select">
                                <option value="">카테고리를 선택해주세요.</option>
                                @foreach ($forms as $item)
									<option value="{{$item->idx}}">{{$item->subject}}</option>
								@endforeach
                            </select>
                        </div>
                    </div>					
                    <div class="flex items-center gap-10 mt-3">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium ">도시 <span class="text-danger">*</span></p>
                            <select id="regist_cities_idx" name="regist_cities_idx" class="form-select">
                            	<option value="">도시을 선택해주세요</option>
								@foreach ($cities as $item)
								<option value="{{$item->idx}}">{{$item->name_kr}}</option>
								@endforeach
							</select>
                        </div>
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium ">구역 <span class="text-danger">*</span></p>
                            <select id="regist_districts_idx" name="regist_districts_idx" class="form-select">
								<option value="">구역을 선택해주세요</option>
							</select>
                        </div>
                    </div>					
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">업체명(ko) <span class="text-danger">*</span></p>
						<input type="text" id="regist_partner_name" name="regist_partner_name" class="form-control" placeholder="한국어 업체명을 입력해주세요">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">업체명(en) <span class="text-danger">*</span></p>
						<input type="text" id="regist_partner_name_en" name="regist_partner_name_en" class="form-control" placeholder="영어 업체명을 입력해주세요">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">업체명(vn) <span class="text-danger">*</span></p>
						<input type="text" id="regist_partner_name_vn" name="regist_partner_name_vn" class="form-control" placeholder="베트남어 업체명을 입력해주세요">
					</div>
					
					<div class="flex items-center gap-2 mt-3 only-vietnam">
						<p class="shrink-0 w-32 font-medium ">주요 유통채널(ko)</p>
						<input type="text" id="regist_channel" name="regist_channel" class="form-control" placeholder="한국어 주요 유통채널을 입력해주세요 (예: 대형마트, 편의점)">
					</div>
					<div class="flex items-center gap-2 mt-3 only-vietnam">
						<p class="shrink-0 w-32 font-medium ">주요 유통채널(en)</p>
						<input type="text" id="regist_channel_en" name="regist_channel_en" class="form-control" placeholder="영어 주요 유통채널을 입력해주세요">
					</div>
					<div class="flex items-center gap-2 mt-3 only-vietnam">
						<p class="shrink-0 w-32 font-medium ">주요 유통채널(vn)</p>
						<input type="text" id="regist_channel_vn" name="regist_channel_vn" class="form-control" placeholder="베트남어 주요 유통채널을 입력해주세요">
					</div>

					<div class="flex items-center gap-10 mt-3 only-korea">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">유통기간</p>
                            <select id="regist_logistic" name="regist_logistic" class="form-select">
								<option value="">선택 안 함</option>
                                <option value="5">평균 1~5일</option>
                                <option value="10">평균 5~10일</option>
								<option value="15">평균 10~15일</option>
								<option value="20">평균 15~20일</option>
								<option value="25">평균 20~25일</option>
								<option value="30">평균 25~30일</option>
                            </select>
                        </div>
                    </div>					

					<h3 class="mt-5 mb-1 text-base font-semibold" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">썸네일 이미지</h3>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">PC 썸네일 <span class="text-danger">*</span></p>
						<div class="w-full flex items-center gap-2">
							<input type="file" id="regist_pc_thumb" name="regist_pc_thumb" class="input-file hidden">
							<label class="btn btn-primary px-3 shrink-0">파일첨부</label>
		                    <div class="file-name pc-file-name form-control border text-slate-500" style="height:40px; padding:7px 15px;"></div>
						</div>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">Fold 썸네일 <span class="text-danger">*</span></p>
						<div class="w-full flex items-center gap-2">	
							<input type="file" id="regist_fo_thumb" name="regist_fo_thumb" class="input-file hidden">
							<label class="btn btn-primary px-3 shrink-0">파일첨부</label>
							<div class="file-name pc-file-name form-control border text-slate-500" style="height:40px; padding:7px 15px; "></div>
						</div>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">Mobile 썸네일 <span class="text-danger">*</span></p>
						<div class="w-full flex items-center gap-2">
							<input type="file" id="regist_mo_thumb" name="regist_mo_thumb" class="input-file hidden">
							<label class="btn btn-primary px-3 shrink-0">파일첨부</label>
							<div class="file-name pc-file-name form-control border text-slate-500" style="height:40px; padding:7px 15px; "></div>
		                </div>
					</div>
					
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">파트너 설명(ko)</p>
						<textarea id="regist_contents" name="regist_contents" class="form-control h-32" placeholder="한국어 내용을 입력하세요."></textarea>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">파트너 설명(en)</p>
						<textarea id="regist_contents_en" name="regist_contents_en" class="form-control h-32" placeholder="영어 내용을 입력하세요."></textarea>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">파트너 설명(vn)</p>
						<textarea id="regist_contents_vn" name="regist_contents_vn" class="form-control h-32" placeholder="베트남어 내용을 입력하세요."></textarea>
					</div>
				

					<h3 class="mt-5 mb-1 font-medium" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">파트너 이미지 <span class="text-danger">*</span></h3>
					<input type="file" id="regist_partnerFiles" name="regist_partnerFiles" class="filepond filepond-media" multiple />
					
					<h3 class="mt-5 mb-1 text-lg font-semibold">추천 파트너</h3>
					<div class="flex items-center gap-2 mt-3">
						<div class="flex-1 form-check">
							<input id="regist_home_recommend" name="regist_home_recommend" class="form-check-input" type="checkbox" value="">
							<label class="form-check-label" for="regist_home_recommend">홈</label>
						</div>
						<div class="flex-1 form-check">
							<input id="regist_partner_recommend" name="regist_partner_recommend" class="form-check-input" type="checkbox" value="">
							<label class="form-check-label" for="regist_partner_recommend">파트너</label>
						</div>
					</div>

					<h3 class="mt-5 mb-1 text-lg font-semibold" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">RIPS 업체 설정</h3>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">RIPS 업체</p>
						<select id="regist_rips_partner_idx" class="form-select rips-partner-select">
							<option value="">연결 안 함</option>
							@foreach ($ripsAccounts as $account)
							<option value="{{ $account->idx }}" data-mapped-by="{{ $account->mapped_by }}">{{ $account->partner_name }}{{ $account->store_name ? ' · '.$account->store_name : '' }} ({{ $account->rips_id }}){{ $account->mapped_name ? ' - '.$account->mapped_name.' 에 연결됨' : '' }}</option>
							@endforeach
						</select>
					</div>
					<p class="mt-1 text-xs text-slate-500" style="padding-left:136px;">· RIPS 업체는 RIPS 관리 &gt; RIPS 업체 등록 에서 만들고, 여기서는 이 파트너와 연결할 업체만 선택합니다. (RIPS 업체 1곳은 파트너 1곳에만 연결)</p>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('partner_regist-modal')">취소</button>
					@if(auth()->user()->hasPerm('partner.create'))
					<button type="button" class="btn btn-primary w-20" onclick="regist()">등록</button>
					@endif
				</div>
			</div>
		</div>
	</div>

	<!-- 파트너 수정 모달 -->
	<div id="partner_modify-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">파트너 수정</h2>
					<button onclick="modalClose('partner_modify-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<h3 class="mb-1 text-base font-semibold">업체 정보</h3>
					<input type="hidden" id="modify_idx" name="modify_idx">
					<div class="flex items-center gap-10">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">국가 <span class="text-danger">*</span></p>
                            <select id="modify_country" name="modify_country" class="form-select">
                                <option value="korea">Korea</option>
                                <option value="vietnam">Vietnam</option>
                            </select>
                        </div>
                    </div>
					<div class="flex items-center gap-10 mt-3 only-vietnam">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">본점/지점</p>
                            <div class="flex items-center gap-4">
                                <div class="form-check">
                                    <input id="modify_branch_head" name="modify_branch_type" class="form-check-input" type="radio" value="head" checked>
                                    <label class="form-check-label" for="modify_branch_head">본점 (단일 매장 포함)</label>
                                </div>
                                <div class="form-check">
                                    <input id="modify_branch_branch" name="modify_branch_type" class="form-check-input" type="radio" value="branch">
                                    <label class="form-check-label" for="modify_branch_branch">지점</label>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 flex items-center gap-2 branch-head-box" style="display:none;">
                            <p class="shrink-0 w-32 font-medium">본점 <span class="text-danger">*</span></p>
                            <select id="modify_head_idx" class="form-select head-select">
                                <option value="">본점을 선택해주세요</option>
                                @foreach ($heads as $head)
									<option value="{{ $head->idx }}">{{ $head->partner_name }} ({{ $head->country }})</option>
								@endforeach
                            </select>
                        </div>
                    </div>
					<div class="flex items-center gap-10 mt-3">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">구분 <span class="text-danger">*</span></p>
                            <select id="modify_types_idx" name="modify_types_idx" class="form-select">
                                <option value="">구분을 선택해주세요.</option>
                                @foreach ($types as $item)
									<option value="{{$item->idx}}">{{$item->subject}}</option>
								@endforeach
                            </select>
                        </div>
						<div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">카테고리 <span class="text-danger">*</span></p>
                            <select id="modify_forms_idx" name="modify_forms_idx" class="form-select">
                                <option value="">카테고리를 선택해주세요.</option>
                                @foreach ($forms as $item)
									<option value="{{$item->idx}}">{{$item->subject}}</option>
								@endforeach
                            </select>
                        </div>
                    </div>	
                    <div class="flex items-center gap-10 mt-3">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium ">도시 <span class="text-danger">*</span></p>
                             <select id="modify_cities_idx" name="modify_cities_idx" class="form-select">
                            	<option value="">도시를 선택해주세요</option>
								@foreach ($cities as $item)
								<option value="{{$item->idx}}">{{$item->name_kr}}</option>
								@endforeach
							</select>
                        </div>
                        <div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">구역 <span class="text-danger">*</span></p>
                            <select id="modify_districts_idx" name="modify_districts_idx" class="form-select">
								<option value="">구역을 선택해주세요</option>
							</select>
                        </div>
                    </div>
                    
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">업체명(ko) <span class="text-danger">*</span></p>
						<input type="text" id="modify_partner_name" name="modify_partner_name" class="form-control" placeholder="">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">업체명(en) <span class="text-danger">*</span></p>
						<input type="text" id="modify_partner_name_en" name="modify_partner_name_en" class="form-control" placeholder="">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">업체명(vn) <span class="text-danger">*</span></p>
						<input type="text" id="modify_partner_name_vn" name="modify_partner_name_vn" class="form-control" placeholder="">
					</div>
					
					<div class="flex items-center gap-2 mt-3 only-vietnam">
						<p class="shrink-0 w-32 font-medium ">주요 유통채널(ko)</p>
						<input type="text" id="modify_channel" name="modify_channel" class="form-control" placeholder="한국어 주요 유통채널을 입력해주세요 (예: 대형마트, 편의점)">
					</div>
					<div class="flex items-center gap-2 mt-3 only-vietnam">
						<p class="shrink-0 w-32 font-medium ">주요 유통채널(en)</p>
						<input type="text" id="modify_channel_en" name="modify_channel_en" class="form-control" placeholder="영어 주요 유통채널을 입력해주세요">
					</div>
					<div class="flex items-center gap-2 mt-3 only-vietnam">
						<p class="shrink-0 w-32 font-medium ">주요 유통채널(vn)</p>
						<input type="text" id="modify_channel_vn" name="modify_channel_vn" class="form-control" placeholder="베트남어 주요 유통채널을 입력해주세요">
					</div>

					<div class="flex items-center gap-10 mt-3 only-korea">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">유통기간</p>
                            <select id="modify_logistic" name="modify_logistic" class="form-select">
								<option value="">선택 안 함</option>
                                <option value="5">평균 1~5일</option>
                                <option value="10">평균 5~10일</option>
								<option value="15">평균 10~15일</option>
								<option value="20">평균 15~20일</option>
								<option value="25">평균 20~25일</option>
								<option value="30">평균 25~30일</option>
                            </select>
                        </div>
                    </div>	

					<h3 class="mt-5 mb-1 text-base font-semibold" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">썸네일 이미지</h3>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">PC 썸네일 <span class="text-danger">*</span></p>
						<div class="w-full flex items-center gap-2">
							<input type="file" id="modify_pc_thumb" name="modify_pc_thumb" class="input-file hidden">
							<label class="btn btn-primary px-3 shrink-0">파일첨부</label>
							<div id="modify_pc_thumb_name" class="file-name pc-file-name form-control border text-slate-500" style="height:40px; padding:7px 15px; "></div>
						</div>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">Fold 썸네일 <span class="text-danger">*</span></p>
						<div class="w-full flex items-center gap-2">	
							<input type="file" id="modify_fo_thumb" name="modify_fo_thumb" class="input-file hidden">
							<label class="btn btn-primary px-3 shrink-0">파일첨부</label>
							<div id="modify_fo_thumb_name" class="file-name pc-file-name form-control border text-slate-500" style="height:40px; padding:7px 15px; "></div>
						</div>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">Mobile 썸네일 <span class="text-danger">*</span></p>
						<div class="w-full flex items-center gap-2">
							<input type="file" id="modify_mo_thumb" name="modify_mo_thumb" class="input-file hidden">
							<label class="btn btn-primary px-3 shrink-0">파일첨부</label>
							<div id="modify_mo_thumb_name" class="file-name pc-file-name form-control border text-slate-500" style="height:40px; padding:7px 15px; "></div>
		                </div>
					</div>
					
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">파트너 설명(ko)</p>
						<textarea id="modify_contents" name="modify_contents" class="form-control h-32" placeholder="내용을 입력하세요."></textarea>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">파트너 설명(en)</p>
						<textarea id="modify_contents_en" name="modify_contents_en" class="form-control h-32" placeholder="내용을 입력하세요."></textarea>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">파트너 설명(vn)</p>
						<textarea id="modify_contents_vn" name="modify_contents_vn" class="form-control h-32" placeholder="내용을 입력하세요."></textarea>
					</div>

					<h3 class="mt-5 mb-1 font-medium" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">파트너 이미지 <span class="text-danger">*</span></h3>
					<input type="file" id="modify_partnerFiles" name="modify_partnerFiles" class="filepond filepond-media" multiple />
					
					<h3 class="mt-5 mb-1 text-lg font-semibold">추천 파트너</h3>
					<div class="flex items-center gap-2 mt-3">
						<div class="flex-1 form-check">
							<input id="modify_home_recommend" name="modify_home_recommend"  class="form-check-input" type="checkbox">
							<label class="form-check-label" for="modify_home_recommend">홈</label>
						</div>
						<div class="flex-1 form-check">
							<input id="modify_partner_recommend" name="modify_partner_recommend"  class="form-check-input" type="checkbox">
							<label class="form-check-label" for="modify_partner_recommend">파트너</label>
						</div>
					</div>

					<h3 class="mt-5 mb-1 text-lg font-semibold" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">RIPS 업체 설정</h3>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">RIPS 업체</p>
						<select id="modify_rips_partner_idx" class="form-select rips-partner-select">
							<option value="">연결 안 함</option>
							@foreach ($ripsAccounts as $account)
							<option value="{{ $account->idx }}" data-mapped-by="{{ $account->mapped_by }}">{{ $account->partner_name }}{{ $account->store_name ? ' · '.$account->store_name : '' }} ({{ $account->rips_id }}){{ $account->mapped_name ? ' - '.$account->mapped_name.' 에 연결됨' : '' }}</option>
							@endforeach
						</select>
					</div>
					<p class="mt-1 text-xs text-slate-500" style="padding-left:136px;">· RIPS 업체는 RIPS 관리 &gt; RIPS 업체 등록 에서 만들고, 여기서는 이 파트너와 연결할 업체만 선택합니다. (RIPS 업체 1곳은 파트너 1곳에만 연결)</p>

				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="javascript:window.location.reload()">취소</button>
					@if(auth()->user()->hasPerm('partner.update'))
					<button type="button" class="btn btn-primary w-20" onclick="modify()">수정</button>
					@endif
					@if(auth()->user()->hasPerm('partner.delete'))
					<button type="button" class="btn btn-outline-danger w-20" onclick="deletepartner()">삭제</button>
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
$(function () { setRipsPartnerOptions('regist', null); });

document.addEventListener('DOMContentLoaded', function () {

    let reqCities  = "{{ request('districts_idx') }}";
    let reqDistricts   = "{{ request('districts_idx') }}";

    // 구역이 선택된 상태라면 → 도시 목록 자동 로드 + select 유지
    if (reqCities) {

        $.ajax({
            url: '/admin/districts/' + reqCities,
            method: 'GET',
            success: function (res) {
                const $citySelect = $('#districts_idx');

                // 초기화
                $districtSelect.empty().append('<option value="">전체</option>');

                // 옵션 채우기
                res.forEach(function(city) {
                    $districtSelect.append(
                        `<option value="${districts.idx}" ${reqDistrict == districts.idx ? 'selected' : ''}>
                            ${districts.name_kr}
                        </option>`
                    );
                });
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }

});

let removedFileIdxs = [];
let existingFiles = [];

function initFilePond() {
    try {
        if (!window.__fp_reg) {
            FilePond.registerPlugin(
                FilePondPluginImagePreview,
                FilePondPluginFileValidateSize,
                FilePondPluginFileValidateType
            );
            window.__fp_reg = true;
        }

        window.pondRegist = null;
        window.pondModify = null;
        const registEl = document.querySelector('input[name="regist_partnerFiles"]');
        const modifyEl = document.querySelector('input[name="modify_partnerFiles"]');

        // 등록용
        if (registEl) {
            window.pondRegist = FilePond.create(registEl, {
                allowMultiple: true,
                allowReorder: true,
                acceptedFileTypes: ['image/*'],
                maxFileSize: '20MB',
                labelIdle: '파트너 이미지를 여기에 드래그하거나 <span class="filepond--label-action">클릭하여 업로드</span>하세요.',
                imagePreviewHeight: 150,
                credits: false,
            });
        }

        // 수정용
        if (modifyEl) {
            window.pondModify = FilePond.create(modifyEl, {
                allowMultiple: true,
                allowReorder: true,
                acceptedFileTypes: ['image/*', 'video/*'],
                maxFileSize: '20MB',
                labelIdle: '파트너 이미지를 여기에 드래그하거나 <span class="filepond--label-action">클릭하여 업로드</span>하세요.',
                imagePreviewHeight: 150,
                credits: false,
                server: {
                    // files 옵션에서 넘겨준 source를 받아 실제 File 객체로 변환
                    load: (source, load, error) => {
                        const { url, name, type } = source;

                        fetch(url)
                            .then(res => {
                                if (!res.ok) throw new Error('Fetch failed');
                                return res.blob();
                            })
                            .then(blob => {
                                const file = new File(
                                    [blob],
                                    name || 'file',
                                    {
                                        type: type || blob.type,
                                        lastModified: new Date().getTime()
                                    }
                                );
                                load(file);
                            })
                            .catch(err => {
                                console.error('파일 로드 실패:', err);
                                error(err.message);
                            });
                    }
                },
                files: existingFiles,
                onremovefile: (error, file) => {
                    const fileId = file.getMetadata('idx');
                    if (fileId) {
                    	removedFileIdxs.push(fileId);
                    }
                }
            });
        }

        // input accept 속성
        $('input[name="regist_partnerFiles"]').attr('accept', 'image/*');
        $('input[name="modify_partnerFiles"]').attr('accept', 'image/*');

    } catch (err) {
        console.error('FilePond 초기화 오류:', err);
    }
}

// DOM 준비 상태에 따라 실행
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFilePond);
} else {
    initFilePond();
}

/* $('input[name="regist_partnerFiles"]').attr('accept', 'image/*');
$('input[name="modify_partnerFiles"]').attr('accept', 'image/*'); */

/* ===============================
 *  이미지 검증 유틸
 * =============================== */
 
const IMG_MIME = ['image/jpeg','image/png','image/webp','image/gif'];
const IMG_EXT  = /\.(jpe?g|png|webp|gif)$/i;
const MAX_IMG = 20 * 1024 * 1024;    // 20MB


function isImage(file) {
	const t = (file.type || '').toLowerCase();
	return (t && IMG_MIME.includes(t)) || IMG_EXT.test(file.name);
}

/* ===============================
 *  구역 / 도시 연동
 * =============================== */
function bindCityDisticts(citieSelector, districtSelector, defaultText) {
    $(citieSelector).on('change', function() {
		
        let citiesIdx = $(this).val();
        let $districtSelector = $(districtSelector);

        // 초기화
        $districtSelector.empty().append(`<option value="">${defaultText}</option>`);

        if (!citiesIdx) return;

        $.ajax({
            url: '/admin/districts/' + citiesIdx,
            method: 'GET',
            success: function (res) {
                res.forEach(function(city) {
                    $districtSelector.append(
                        `<option value="${city.idx}">${city.name_kr}</option>`
                    );
                });
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                showToast('구역 목록을 불러오지 못했습니다.','error');
            }
        });
    });
}

$(function () {
    bindCityDisticts('#cities_idx', '#districts_idx', '전체');
    bindCityDisticts('#regist_cities_idx', '#regist_districts_idx', '구역을 선택해주세요.');
    bindCityDisticts('#modify_cities_idx', '#modify_districts_idx', '구역을 선택해주세요.');

    $('input[name="regist_branch_type"]').on('change', function () { toggleBranchHead('regist'); });
    $('input[name="modify_branch_type"]').on('change', function () { toggleBranchHead('modify'); });

    $('#regist_country').on('change', function () { toggleCountryFields('regist'); });
    $('#modify_country').on('change', function () { toggleCountryFields('modify'); });
    toggleCountryFields('regist');
});

/* ===============================
 *  국가별 항목 (등록/수정 공통)
 *  - vietnam : 본점/지점, 주요 유통채널
 *  - korea   : 유통기간
 * =============================== */
function countryOf(prefix) {
	return String($('#' + prefix + '_country').val() || '').toLowerCase();
}

function toggleCountryFields(prefix) {
	const $modal = $('#partner_' + prefix + '-modal');
	$modal.find('.only-vietnam').toggle(countryOf(prefix) === 'vietnam');
	$modal.find('.only-korea').toggle(countryOf(prefix) === 'korea');
}

/* ===============================
 *  본점/지점 (등록/수정 공통)
 * =============================== */
function toggleBranchHead(prefix) {
	const isBranch = $('input[name="' + prefix + '_branch_type"]:checked').val() === 'branch';
	$('#' + prefix + '_head_idx').closest('.branch-head-box').toggle(isBranch);
}

function appendBranch(prefix, fd) {
	// 본점/지점은 vietnam 만 사용 (그 외 국가는 본점으로 저장)
	const branch_type = countryOf(prefix) === 'vietnam' ? $('input[name="' + prefix + '_branch_type"]:checked').val() : 'head';
	const head_idx    = branch_type === 'branch' ? $('#' + prefix + '_head_idx').val() : '';

	if (branch_type === 'branch' && !head_idx) { showToast('지점은 본점을 선택해주세요.','error'); return false; }

	fd.append('branch_type', branch_type);
	fd.append('head_idx', head_idx);
	return true;
}

/* ===============================
 *  RIPS 업체 연결 (등록/수정 공통)
 *  다른 파트너에 연결된 RIPS 업체는 선택 불가 (자기 자신에 연결된 업체는 선택 가능)
 * =============================== */
function setRipsPartnerOptions(prefix, partnerIdx) {
	$('#' + prefix + '_rips_partner_idx option[data-mapped-by]').each(function () {
		const by = String($(this).data('mapped-by') || '');
		$(this).prop('disabled', by !== '' && by !== String(partnerIdx || ''));
	});
}

function appendRipsAccount(prefix, fd, isRegist) {
	fd.append('rips_partner_idx', $('#' + prefix + '_rips_partner_idx').val() || '');
	return true;
}

/* ===============================
 *  파트너 등록
 * =============================== */

function regist() {
	const country				= $('#regist_country').val();
  	const types_idx				= $('#regist_types_idx').val();
	const forms_idx				= $('#regist_forms_idx').val();
	const completion_date		= $('#regist_completion_date').val();
  	const cities_idx			= $('#regist_cities_idx').val();
  	const districts_idx			= $('#regist_districts_idx').val();
  	const partner_name			= $('#regist_partner_name').val().trim();
  	const partner_name_en		= $('#regist_partner_name_en').val().trim();
  	const partner_name_vn		= $('#regist_partner_name_vn').val().trim();
  	const channel			= $('#regist_channel').val().trim();
  	const channel_en		= $('#regist_channel_en').val().trim();
  	const channel_vn		= $('#regist_channel_vn').val().trim();
  	const logistic				= $('#regist_logistic').val();
  	const contents				= $('#regist_contents').val();
  	const contents_en			= $('#regist_contents_en').val();
  	const contents_vn			= $('#regist_contents_vn').val();
  	const pcThumbInput 			= $('input[name="regist_pc_thumb"]')[0];
  	const foThumbInput 			= $('input[name="regist_fo_thumb"]')[0];
  	const moThumbInput 			= $('input[name="regist_mo_thumb"]')[0];
  	const home_recommend		= $('#regist_home_recommend').is(':checked') ? 1 : 0;
  	const partner_recommend		= $('#regist_partner_recommend').is(':checked') ? 1 : 0;

  	if (!types_idx)				return showToast('구분을 선택해주세요.','error');
	if (!forms_idx)				return showToast('카테고리를 선택해주세요.','error');
	if (!cities_idx)			return showToast('도시을 선택해주세요.','error');
	if (!districts_idx)			return showToast('구역를 선택해주세요.','error');
	if (!partner_name)			return showToast('업체명(한국어)을 입력해주세요.','error');
	if (!partner_name_en)		return showToast('업체명(영어)을 입력해주세요.','error');
	if (!partner_name_vn)		return showToast('업체명(베트남어)을 입력해주세요.','error');
	
	
	const fd = new FormData();
	fd.append('country', country);
	fd.append('types_idx', types_idx);
	fd.append('forms_idx', forms_idx);
	fd.append('cities_idx', cities_idx);
	fd.append('districts_idx', districts_idx);
	fd.append('partner_name', partner_name);
	fd.append('partner_name_en', partner_name_en);
	fd.append('partner_name_vn', partner_name_vn);
	fd.append('logistic', country.toLowerCase() === 'korea' ? logistic : '');
	fd.append('channel', country.toLowerCase() === 'vietnam' ? channel : '');
	fd.append('channel_en', country.toLowerCase() === 'vietnam' ? channel_en : '');
	fd.append('channel_vn', country.toLowerCase() === 'vietnam' ? channel_vn : '');
	fd.append('contents', contents);
	fd.append('contents_en', contents_en);
	fd.append('contents_vn', contents_vn);
	fd.append('home_recommend', home_recommend);
	fd.append('partner_recommend', partner_recommend);
	if (!appendBranch('regist', fd)) return;
	if (!appendRipsAccount('regist', fd, true)) return;


	// ========= PC Thumb =========
	if (!pcThumbInput || pcThumbInput.files.length === 0) {
		showToast('PC 썸네일을 등록해주세요.','error');
	  return;
	}
	
	const pcThumbFile = pcThumbInput.files[0];
	if (!isImage(pcThumbFile)) { showToast('PC 썸네일은 이미지 파일만 업로드 가능합니다.','error'); return; }
	if (pcThumbFile.size > MAX_IMG) { showToast('PC 썸네일은 최대 20MB까지 업로드 가능합니다.','error'); return; }
	fd.append('pcThumb', pcThumbFile);

	// ========= PC Thumb =========
	if (!foThumbInput || foThumbInput.files.length === 0) {
		showToast('Fold 썸네일을 등록해주세요.','error');
	  return;
	}
	
	const foThumbFile = foThumbInput.files[0];
	if (!isImage(foThumbFile)) { showToast('Fold 썸네일은 이미지 파일만 업로드 가능합니다.','error'); return; }
	if (foThumbFile.size > MAX_IMG) { showToast('Fold 썸네일은 최대 20MB까지 업로드 가능합니다.','error'); return; }
	fd.append('foThumb', foThumbFile);

	// ========= PC Thumb =========
	if (!moThumbInput || moThumbInput.files.length === 0) {
		showToast('Mobile 썸네일을 등록해주세요.','error');
	  return;
	}
	
	const moThumbFile = moThumbInput.files[0];
	if (!isImage(moThumbFile)) { showToast('Mobile 썸네일은 이미지 파일만 업로드 가능합니다.','error'); return; }
	if (moThumbFile.size > MAX_IMG) { showToast('Mobile 썸네일은 최대 20MB까지 업로드 가능합니다.','error'); return; }
	fd.append('moThumb', moThumbFile);

	const partnerFiles = window.pondRegist?.getFiles?.() || [];
	if (!partnerFiles.length) return showToast('파트너이미지를 한 개 이상 업로드해주세요.','error');
	{
		const invalid=[], tooLarge=[];
		partnerFiles.forEach(({file})=>{
			const img=isImage(file);
			if(!img) invalid.push(file.name);
			if(img && file.size>MAX_IMG) tooLarge.push(`${file.name} (이미지 > 20MB)`);
		});
		if(invalid.length) return showToast(`파트너이미지 형식 오류:\n- ${invalid.join('\n- ')}`,'error');
		if(tooLarge.length) return showToast(`파트너이미지 용량 초과:\n- ${tooLarge.join('\n- ')}`,'error');
	}
	partnerFiles.forEach(({file})=> fd.append('partnerFiles[]', file, file.name));
	
	if (!confirm('파트너을 등록하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/partner/regist',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	showToast('파트너가 저장되었습니다.','success');
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

function setModifyForm(idx) {

	removedFileIdxs = [];
	existingFiles = [];
	
    $.ajax({
    	url: '/admin/partner/detailForm/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
        	if (res.status !== 'success') {
        		showToast(res.message || '불러오기 실패','error');
                return;
            }

            const d = res.data;
			console.log(d);

            $('#modify_idx').val(d.idx);
			$('#modify_country').val(String(d.country || '').toLowerCase());
			$('#modify_types_idx').val(d.types_idx);
			$('#modify_forms_idx').val(d.forms_idx);
			$('#modify_cities_idx').val(d.cities_idx);
			$('#modify_partner_name').val(d.partner_name);
			$('#modify_partner_name_en').val(d.partner_name_en);
			$('#modify_partner_name_vn').val(d.partner_name_vn);
			$('#modify_channel').val(d.channel);
			$('#modify_channel_en').val(d.channel_en);
			$('#modify_channel_vn').val(d.channel_vn);
			$('#modify_logistic').val(d.logistic || '');
			$('#modify_contents').val(d.contents);
			$('#modify_contents_en').val(d.contents_en);
			$('#modify_contents_vn').val(d.contents_vn);
			$('#modify_pc_thumb_name').text(d.pc_ori_name);
			$('#modify_fo_thumb_name').text(d.fo_ori_name);
			$('#modify_mo_thumb_name').text(d.mo_ori_name);

			$('#modify_pc_thumb').attr('data-up_name',d.pc_up_name);
			$('#modify_fo_thumb').attr('data-up_name',d.fo_up_name);
			$('#modify_mo_thumb').attr('data-up_name',d.mo_up_name);

			$('#modify_home_recommend').prop('checked', d.home_recommend == 1);
			$('#modify_partner_recommend').prop('checked', d.partner_recommend == 1);

			$('input[name="modify_branch_type"][value="' + (d.branch_type === 'branch' ? 'branch' : 'head') + '"]').prop('checked', true);
			// 자기 자신은 본점 목록에서 제외
			$('#modify_head_idx option').prop('disabled', false).filter('[value="' + d.idx + '"]').prop('disabled', true);
			$('#modify_head_idx').val(d.head_idx || '');
			toggleBranchHead('modify');
			toggleCountryFields('modify');

			setRipsPartnerOptions('modify', d.idx);
			$('#modify_rips_partner_idx').val(d.rips_partner_idx || '');
			
			const $districtSelect = $('#modify_districts_idx');
            $districtSelect.empty().append('<option value="">구역를 선택해주세요</option>');

            if (d.cities_idx) {
                $.ajax({
                    url: '/admin/districts/' + d.cities_idx,
                    method: 'GET',
                    success: function (res) {
                        res.forEach(function (districts) {
                            $districtSelect.append(
                                `<option value="${districts.idx}">${districts.name_kr}</option>`
                            );
                        });

                        if (d.districts_idx) {
                            $districtSelect.val(d.districts_idx);
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        showToast('구역 목록을 불러오지 못했습니다.','error');
                    }
                });
            }


            if (window.pondModify) {
            	removedFileIdxs = [];
            	existingFiles = [];

                (res.files || []).forEach(function (f) {
                    const url = '/storage/' + f.up_name;

                    existingFiles.push({
                        source: {
                            url: url,
                            name: f.ori_name || 'file',
                            type: null,
                        },
                        options: {
                            type: 'local',
                            metadata: {
                                idx: f.idx,
                                orderby: f.orderby
                            }
                        }
                    });
                });

                window.pondModify.removeFiles();
                window.pondModify.setOptions({
                    files: existingFiles
                });
            }

			modalOpen('partner_modify-modal');
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}

const existingFileOrder = [];

function modify() {

	existingFileOrder.length = 0;
	
	const idx					= $('#modify_idx').val();
  	const country				= $('#modify_country').val();
  	const types_idx				= $('#modify_types_idx').val();
  	const forms_idx				= $('#modify_forms_idx').val();
  	const cities_idx			= $('#modify_cities_idx').val();
  	const districts_idx			= $('#modify_districts_idx').val();
  	const partner_name			= $('#modify_partner_name').val().trim();
  	const partner_name_en		= $('#modify_partner_name_en').val().trim();
  	const partner_name_vn		= $('#modify_partner_name_vn').val().trim();
  	const channel				= $('#modify_channel').val().trim();
  	const channel_en			= $('#modify_channel_en').val().trim();
  	const channel_vn			= $('#modify_channel_vn').val().trim();
  	const logistic				= $('#modify_logistic').val();	
  	const contents				= $('#modify_contents').val();
  	const contents_en			= $('#modify_contents_en').val();
  	const contents_vn			= $('#modify_contents_vn').val();
  	const pcThumbInput 			= $('input[name="modify_pc_thumb"]')[0];
  	const foThumbInput 			= $('input[name="modify_fo_thumb"]')[0];
  	const moThumbInput 			= $('input[name="modify_mo_thumb"]')[0];
  	const home_recommend		= $('#modify_home_recommend').is(':checked') ? 1 : 0;
  	const partner_recommend		= $('#modify_partner_recommend').is(':checked') ? 1 : 0;

  	if (!types_idx)				return showToast('구분을 선택해주세요.','error');
	if (!forms_idx)				return showToast('카테고리를 선택해주세요.','error');
	if (!cities_idx)			return showToast('도시을 선택해주세요.','error');
	if (!districts_idx)			return showToast('구역를 선택해주세요.','error');
	if (!partner_name)			return showToast('업체명(한국어)을 입력해주세요.','error');
	if (!partner_name_en)		return showToast('업체명(영어)을 입력해주세요.','error');
	if (!partner_name_vn)		return showToast('업체명(베트남어)을 입력해주세요.','error');

	const fd = new FormData();
	fd.append('country', country);
	fd.append('types_idx', types_idx);
	fd.append('forms_idx', forms_idx);
	fd.append('cities_idx', cities_idx);
	fd.append('districts_idx', districts_idx);
	fd.append('partner_name', partner_name);
	fd.append('partner_name_en', partner_name_en);
	fd.append('partner_name_vn', partner_name_vn);
	fd.append('logistic', country.toLowerCase() === 'korea' ? logistic : '');
	fd.append('channel', country.toLowerCase() === 'vietnam' ? channel : '');
	fd.append('channel_en', country.toLowerCase() === 'vietnam' ? channel_en : '');
	fd.append('channel_vn', country.toLowerCase() === 'vietnam' ? channel_vn : '');
	fd.append('contents', contents);
	fd.append('contents_en', contents_en);
	fd.append('contents_vn', contents_vn);
	fd.append('home_recommend', home_recommend);
	fd.append('partner_recommend', partner_recommend);
	if (!appendBranch('modify', fd)) return;
	if (!appendRipsAccount('modify', fd, false)) return;

	// pc 썸네일
	if($('input[name="modify_pc_thumb"]').data('up_name') == null){
		if (!pcThumbInput || pcThumbInput.files.length === 0) {
			showToast('PC 썸네일을 등록해주세요.','error');
		  return;
		}
 	}
	const pcThumbFile = pcThumbInput.files[0];
	if(pcThumbFile){
		if (!isImage(pcThumbFile)) { showToast('PC 썸네일은 이미지 파일만 업로드 가능합니다.','error'); return; }
		if (pcThumbFile.size > MAX_IMG) { showToast('PC 썸네일은 최대 20MB까지 업로드 가능합니다.','error'); return; }
		fd.append('pcThumb', pcThumbFile);
	}
	
	// 폴드 썸네일
	if($('input[name="modify_fo_thumb"]').data('up_name') == null){
		if (!foThumbInput || foThumbInput.files.length === 0) {
			showToast('Fold 썸네일을 등록해주세요.','error');
		  return;
		}
 	}
	const foThumbFile = foThumbInput.files[0];
	if(foThumbFile){
		if (!isImage(foThumbFile)) { showToast('Fold 썸네일은 이미지 파일만 업로드 가능합니다.','error'); return; }
		if (foThumbFile.size > MAX_IMG) { showToast('Fold 썸네일은 최대 20MB까지 업로드 가능합니다.','error'); return; }
		fd.append('foThumb', foThumbFile);
	}

	// 모바일 썸네일
	if($('input[name="modify_mo_thumb"]').data('up_name') == null){
		if (!moThumbInput || moThumbInput.files.length === 0) {
			showToast('Mobile 썸네일을 등록해주세요.','error');
		  return;
		}
 	}
	const moThumbFile = moThumbInput.files[0];
	if(moThumbFile){
		if (!isImage(moThumbFile)) { showToast('Mobile 썸네일은 이미지 파일만 업로드 가능합니다.','error'); return; }
		if (moThumbFile.size > MAX_IMG) { showToast('Mobile 썸네일은 최대 20MB까지 업로드 가능합니다.','error'); return; }
		fd.append('moThumb', moThumbFile);
	}

	// MO 썸네일 - FilePond
	const files = window.pondModify?.getFiles?.() || [];
	if (!files.length) return showToast('파트너이미지를 한 개 이상 업로드해주세요.','error');
	{
		const invalid=[], tooLarge=[];
		files.forEach((item, idx) => {
			const files_idx = item.getMetadata('idx');
            const orderby = idx + 1;
            if (files_idx) {
            	existingFileOrder.push({ idx: files_idx, orderby: orderby });
            } else {
            	const img=isImage(item.file);
				if(!img) invalid.push(item.file.name);
				if(img && item.file.size>MAX_IMG) tooLarge.push(`${item.file.name} (이미지 > 20MB)`);
				fd.append('new_file_upload[]', item.file, item.file.name);
				fd.append('new_file_order[]', orderby); // ✅ 순서 일치 시켜 전송
            }
		});
		if(invalid.length) return showToast(`파트너이미지 형식 오류:\n- ${invalid.join('\n- ')}`,'error');
		if(tooLarge.length) return showToast(`파트너이미지 용량 초과:\n- ${tooLarge.join('\n- ')}`,'error');
	}
	
	fd.append('removedFileIdxs', JSON.stringify(removedFileIdxs));
	fd.append('existingFileOrder', JSON.stringify(existingFileOrder));

	if (!confirm('파트너을 수정하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/partner/modify/'+idx,
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('파트너가 수정되었습니다.','success');
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

function deletepartner() {
	const idx = $('#modify_idx').val();
  	if (!idx) return showToast('오류가 발생하였습니다.','error');
	
	if (!confirm('정말로 해당 파트너을 삭제하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/partner/delete/'+idx,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('파트너가 삭제되었습니다.','success');
                location.href = '/admin/partner';
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
