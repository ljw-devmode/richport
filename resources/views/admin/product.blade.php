@php 
	$header_skip = false; 
	$depth1_num = '3'; 
	$depth2_num = '3'; 
@endphp 
@include('admin.header._header')
<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">제품</h3>

	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/product">
			<div class="flex items-end gap-3 flex-wrap">
				<div>
					<p class="mb-1">파트너</p>
					<select id="partner_idx" name="partner_idx" class="form-select w-36">
						<option value="">전체</option>
						@foreach ($partners as $item)
							<option value="{{$item->idx}}" {{ request('partner_idx') == $item->idx ? 'selected' : '' }}>{{$item->partner_name}}</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">카테고리</p>
					<div class="forms-cascade flex items-center gap-1" data-target="#search_forms_idx" data-placeholder="전체">
						<input type="hidden" id="search_forms_idx" name="forms_idx" value="{{ request('forms_idx') }}">
						@foreach ([1, 2, 3, 4] as $depth)
						<select data-depth="{{ $depth }}" class="form-select w-36" title="{{ $depth }}차 카테고리"></select>
						@endforeach
					</div>
				</div>
				<div>
					<p class="mb-1">Congbo</p>
					<select id="congbo" name="congbo" class="form-select">
						<option value="">전체</option>
						<option value="Y" {{ request('congbo') == 'Y' ? 'selected' : '' }}>등록완료</option>
						<option value="N" {{ request('congbo') == 'N' ? 'selected' : '' }}>미등록</option>
					</select>
				</div>
				<div>
					<p class="mb-1">신상품</p>
					<select id="new" name="new" class="form-select">
						<option value="">전체</option>
						<option value="Y" {{ request('new') == 'Y' ? 'selected' : '' }}>등록완료</option>
						<option value="N" {{ request('new') == 'N' ? 'selected' : '' }}>미등록</option>
					</select>
				</div>
				<div>
					<p class="mb-1">테스트베드</p>
					<select id="testbed" name="testbed" class="form-select">
						<option value="">전체</option>
						<option value="Y" {{ request('testbed') == 'Y' ? 'selected' : '' }}>등록완료</option>
						<option value="N" {{ request('testbed') == 'N' ? 'selected' : '' }}>미등록</option>
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
					<p class="mb-1">제품 추천</p>
					<select id="product_recommend" name="product_recommend" class="form-select w-36">
						<option value="" {{ request('product_recommend') === null || request('product_recommend') === '' ? 'selected' : '' }}>전체</option>
			            <option value="1" {{ request('product_recommend') == '1' ? 'selected' : '' }}>추천</option>
			            <option value="0" {{ request('product_recommend') == '0' ? 'selected' : '' }}>미추천</option>
					</select>
				</div>
				<div>
					<p class="mb-1">등록번호</p>
					<input type="text" id="congbo_number" name="congbo_number" class="form-control w-72" placeholder="등록번호를 입력해주세요" value="{{ request('congbo_number') }}" >
				</div>
				<div>
					<p class="mb-1">파트너명</p>
					<input type="text" id="partner_name" name="partner_name" class="form-control w-72" placeholder="파트너명을 입력해주세요" value="{{ request('partner_name') }}" >
				</div>
				<div>
					<p class="mb-1">제품명</p>
					<input type="text" id="product_name" name="product_name" class="form-control w-72" placeholder="제품명을 입력해주세요" value="{{ request('product_name') }}" >
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">총 {{ $data->total() }}개</div>
			@if(auth()->user()->hasPerm('product.create') || auth()->user()->hasPerm('product.excel'))
			<div class="flex items-center gap-2">
				@if(auth()->user()->hasPerm('product.create'))
				<a href="javascript:;" class="btn btn-dark w-20" onclick="modalOpen('product_regist-modal')">등록</a>
				@endif
				@if(auth()->user()->hasPerm('product.excel'))
				<button type="button" class="btn border-[#1b6c40] bg-[#1b6c40] text-white px-4" onclick="window.location.href='{{ route('admin.product.export', request()->query()) }}'">엑셀 다운로드</button>
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
				</colgroup>
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="whitespace-nowrap">번호</th>
						<th class="whitespace-nowrap">카테고리</th>
						<th class="whitespace-nowrap">파트너</th>
						<th class="whitespace-nowrap">등록번호</th>
						<th class="whitespace-nowrap">제품명</th>
						<th class="whitespace-nowrap">바코드</th>
						<th class="whitespace-nowrap">기본 발주금액</th>
						<th class="whitespace-nowrap">CongBo</th>
						<th class="whitespace-nowrap">신상품</th>
						<th class="whitespace-nowrap">테스트베드</th>
						<th class="whitespace-nowrap">홈 추천</th>
						<th class="whitespace-nowrap">제품 추천</th>
						<th class="whitespace-nowrap">등록일</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $index => $item)
						@php
					        $number = ($data->currentPage() - 1) * $data->perPage() + ($index + 1);
					    @endphp
						<tr
						@if(auth()->user()->hasPerm('product.update') || auth()->user()->hasPerm('product.delete')) 
							onclick="setModifyForm('{{ $item->idx }}')"
							style="cursor: pointer;" 
							class="hover:bg-slate-100"
						@else 
							style="cursor: default;" 
						@endif
						>
							<td>{{ $number }}</td>
							<td class="text-left">{{ $formPaths[$item->forms_idx] ?? '-' }}</td>
							<td>{{ $item->partners->partner_name }}</td>
							<td>{{ $item->congbo_number }}</td>
							<td>{{ $item->product_name }}</td>
							<td>@if ($item->barcode){{ $item->barcode }}@else<span class="text-slate-400">미입력</span>@endif</td>
							<td class="text-right whitespace-nowrap">{!! $item->order_price !== null ? number_format($item->order_price) : '<span class="text-slate-400">미설정</span>' !!}</td>
							<td>{{ $item->congbo_text }}</td>
							<td>{{ $item->new_text }}</td>
							<td>{{ $item->testbed_text }}</td>
							<td>{{ $item->home_recommend_text }}</td>
							<td>{{ $item->product_recommend_text }}</td>
							<td>{{ $item->created_at?->format('Y-m-d') }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="14" class="text-center">등록된 제품이 없습니다.</td>
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

	<!-- 제품 등록 모달 -->
	<div id="product_regist-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">제품 등록</h2>
					<button onclick="modalClose('product_regist-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<h3 class="mb-1 text-base font-semibold">제품 정보</h3>
					<div class="flex items-center gap-10">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">파트너 <span class="text-danger">*</span></p>
                            <select id="regist_partner_idx" name="regist_partner_idx" class="form-select">
                                <option value="">파트너를 선택해주세요.</option>
                                @foreach ($partners as $item)
									<option value="{{$item->idx}}">{{$item->partner_name}}</option>
								@endforeach
                            </select>
                        </div>
                    </div>
					<div class="flex items-center gap-10">
                        <div class="flex-1 flex items-center gap-2 mt-3">
                            <p class="shrink-0 w-32 font-medium">카테고리 <span class="text-danger">*</span></p>
                            <div class="forms-cascade w-full grid grid-cols-4 gap-2" data-target="#regist_forms_idx" data-placeholder="선택">
                                <input type="hidden" id="regist_forms_idx" name="regist_forms_idx">
                                @foreach ([1, 2, 3, 4] as $depth)
                                <select data-depth="{{ $depth }}" class="form-select" title="{{ $depth }}차 카테고리"></select>
                                @endforeach
                            </div>
                        </div>
                    </div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">상품 표시 <span class="text-danger">*</span></p>
						<input type="checkbox" id="regist_congbo" name="regist_congbo" value="Y">
						<label for="regist_congbo">Cong Bo</label>					
						<input type="checkbox" id="regist_new" name="regist_new" value="Y">
						<label for="regist_new">신상품</label>
						<input type="checkbox" id="regist_testbed" name="regist_testbed" value="Y">
						<label for="regist_testbed">테스트 베드</label>
					</div>

					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">등록번호</p>
						<input type="text" id="regist_congbo_number" name="regist_congbo_number" class="form-control" placeholder="Cong Bo 등록번호를 입력해주세요">
					</div>

					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">제품명(ko) <span class="text-danger">*</span></p>
						<input type="text" id="regist_product_name" name="regist_product_name" class="form-control" placeholder="한국어 제품명을 입력해주세요">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">제품명(en) <span class="text-danger">*</span></p>
						<input type="text" id="regist_product_name_en" name="regist_product_name_en" class="form-control" placeholder="영어 제품명을 입력해주세요">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">제품명(vn) <span class="text-danger">*</span></p>
						<input type="text" id="regist_product_name_vn" name="regist_product_name_vn" class="form-control" placeholder="베트남어 제품명을 입력해주세요">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">바코드 <span class="text-danger">*</span></p>
						<input type="text" id="regist_barcode" name="regist_barcode" class="form-control" maxlength="50" placeholder="상품 바코드 번호 (영문, 숫자, - 4~50자, 예: 8801073110502)">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">발주금액(기본)</p>
						<input type="number" id="regist_order_price" class="form-control" min="0" placeholder="RIPS 발주 시 기본 단가 (업체별 개별금액이 없으면 이 금액 적용)">
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
						<p class="shrink-0 w-32 font-medium ">제품설명(ko)</p>
						<textarea id="regist_contents" name="regist_contents" class="form-control h-32" placeholder="한국어 내용을 입력하세요."></textarea>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">제품설명(en)</p>
						<textarea id="regist_contents_en" name="regist_contents_en" class="form-control h-32" placeholder="영어 내용을 입력하세요."></textarea>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">제품설명(vn)</p>
						<textarea id="regist_contents_vn" name="regist_contents_vn" class="form-control h-32" placeholder="베트남어 내용을 입력하세요."></textarea>
					</div>
				

					<h3 class="mt-5 mb-1 font-medium" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">상세 이미지 <span class="text-danger">*</span></h3>
					<input type="file" id="regist_productFiles" name="regist_productFiles" class="filepond filepond-media" multiple />
					
					<h3 class="mt-5 mb-1 text-lg font-semibold">추천 제품</h3>
					<div class="flex items-center gap-2 mt-3">
						<div class="flex-1 form-check">
							<input id="regist_home_recommend" name="regist_home_recommend" class="form-check-input" type="checkbox" value="">
							<label class="form-check-label" for="regist_home_recommend">홈</label>
						</div>
						<div class="flex-1 form-check">
							<input id="regist_product_recommend" name="regist_product_recommend" class="form-check-input" type="checkbox" value="">
							<label class="form-check-label" for="regist_product_recommend">제품</label>
						</div>
					</div>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('product_regist-modal')">취소</button>
					@if(auth()->user()->hasPerm('product.create'))
					<button type="button" class="btn btn-primary w-20" onclick="regist()">등록</button>
					@endif
				</div>
			</div>
		</div>
	</div>

	<!-- 제품 수정 모달 -->
	<div id="product_modify-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">제품 수정</h2>
					<button onclick="modalClose('product_modify-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<h3 class="mb-1 text-base font-semibold">업체 정보</h3>
					<input type="hidden" id="modify_idx" name="modify_idx">
					<div class="flex items-center gap-10 ">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">카테고리 <span class="text-danger">*</span></p>
                            <div class="forms-cascade w-full grid grid-cols-4 gap-2" id="modifyFormsBox" data-target="#modify_forms_idx" data-placeholder="선택">
                                <input type="hidden" id="modify_forms_idx" name="modify_forms_idx">
                                @foreach ([1, 2, 3, 4] as $depth)
                                <select data-depth="{{ $depth }}" class="form-select" title="{{ $depth }}차 카테고리"></select>
                                @endforeach
                            </div>
                        </div>
                    </div>

					<div class="flex items-center gap-10 mt-3">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">파트너 <span class="text-danger">*</span></p>
                            <select id="modify_partner_idx" name="modify_partner_idx" class="form-select">
                                <option value="">파트너를 선택해주세요.</option>
                                @foreach ($partners as $item)
									<option value="{{$item->idx}}">{{$item->partner_name}}</option>
								@endforeach
                            </select>
                        </div>
                    </div>					
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">상품 표시 <span class="text-danger">*</span></p>
						<input type="checkbox" type="checkbox" id="modify_congbo" name="modify_congbo">
						<label for="agree">Cong Bo</label>					
						<input type="checkbox" type="checkbox" id="modify_new" name="modify_new">
						<label for="agree">신상품</label>
						<input type="checkbox" type="checkbox" id="modify_testbed" name="modify_testbed">
						<label for="agree">테스트 베드</label>
					</div>

					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">등록번호</p>
						<input type="text" id="modify_congbo_number" name="modify_congbo_number" class="form-control" placeholder="Cong Bo 등록번호를 입력해주세요">
					</div>
					
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">제품명(ko) <span class="text-danger">*</span></p>
						<input type="text" id="modify_product_name" name="modify_product_name" class="form-control" placeholder="">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">제품명(en) <span class="text-danger">*</span></p>
						<input type="text" id="modify_product_name_en" name="modify_product_name_en" class="form-control" placeholder="">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">제품명(vn) <span class="text-danger">*</span></p>
						<input type="text" id="modify_product_name_vn" name="modify_product_name_vn" class="form-control" placeholder="">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">바코드</p>
						<input type="text" id="modify_barcode" name="modify_barcode" class="form-control" maxlength="50" placeholder="상품 바코드 번호 (영문, 숫자, - 4~50자)">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">발주금액(기본)</p>
						<input type="number" id="modify_order_price" class="form-control" min="0" placeholder="RIPS 발주 시 기본 단가 (업체별 개별금액이 없으면 이 금액 적용)">
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
						<p class="shrink-0 w-32 font-medium ">제품설명(ko)</p>
						<textarea id="modify_contents" name="modify_contents" class="form-control h-32" placeholder="내용을 입력하세요."></textarea>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">제품설명(en)</p>
						<textarea id="modify_contents_en" name="modify_contents_en" class="form-control h-32" placeholder="내용을 입력하세요."></textarea>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">제품설명(vn)</p>
						<textarea id="modify_contents_vn" name="modify_contents_vn" class="form-control h-32" placeholder="내용을 입력하세요."></textarea>
					</div>

					<h3 class="mt-5 mb-1 font-medium" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">상세 이미지 <span class="text-danger">*</span></h3>
					<input type="file" id="modify_productFiles" name="modify_productFiles" class="filepond filepond-media" multiple />
					
					<h3 class="mt-5 mb-1 text-lg font-semibold">추천 제품</h3>
					<div class="flex items-center gap-2 mt-3">
						<div class="flex-1 form-check">
							<input id="modify_home_recommend" name="modify_home_recommend"  class="form-check-input" type="checkbox">
							<label class="form-check-label" for="modify_home_recommend">홈</label>
						</div>
						<div class="flex-1 form-check">
							<input id="modify_product_recommend" name="modify_product_recommend"  class="form-check-input" type="checkbox">
							<label class="form-check-label" for="modify_product_recommend">제품</label>
						</div>
					</div>

				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="javascript:window.location.reload()">취소</button>
					@if(auth()->user()->hasPerm('product.update'))
					<button type="button" class="btn btn-primary w-20" onclick="modify()">수정</button>
					@endif
					@if(auth()->user()->hasPerm('product.delete'))
					<button type="button" class="btn btn-outline-danger w-20" onclick="deleteproduct()">삭제</button>
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

/* ===============================
 *  카테고리 1~4차 연동 셀렉트
 *  - 상위 단계를 골라야 다음 단계가 활성화 (1 → 2 → 3 → 4)
 *  - data-target 의 hidden 값 = 가장 깊게 고른 카테고리
 * =============================== */
const FORM_TREE = @json($formTree);

function formOf(idx) {
    return FORM_TREE.find(f => f.idx === Number(idx));
}

// 1차부터 해당 카테고리까지의 idx 목록
function formsChain(idx) {
    const chain = [];
    for (let f = formOf(idx); f && chain.length < 4; f = formOf(f.parent_idx)) chain.unshift(f.idx);
    return chain;
}

function setFormsCascade($box, idx) {
    const chain       = idx ? formsChain(idx) : [];
    const placeholder = $box.data('placeholder') || '선택';

    $box.find('select[data-depth]').each(function () {
        const depth  = Number($(this).data('depth'));
        const parent = depth === 1 ? 0 : chain[depth - 2];
        const items  = parent === undefined ? [] : FORM_TREE.filter(f => f.parent_idx === parent);
        const $sel   = $(this).empty().append($('<option>').val('').text(depth + '차 ' + placeholder));

        items.forEach(f => $sel.append($('<option>').val(f.idx).text(f.subject)));
        $sel.val(chain[depth - 1] ? String(chain[depth - 1]) : '').prop('disabled', !items.length);
    });

    $($box.data('target')).val(chain.length ? chain[chain.length - 1] : '');
}

$(document).on('change', '.forms-cascade select[data-depth]', function () {
    const $box  = $(this).closest('.forms-cascade');
    const depth = Number($(this).data('depth'));
    // 비우면 상위 단계까지만 선택된 상태로
    const idx   = $(this).val() || (depth > 1 ? $box.find('select[data-depth="' + (depth - 1) + '"]').val() : '');
    setFormsCascade($box, idx);
});

$('.forms-cascade').each(function () {
    setFormsCascade($(this), $($(this).data('target')).val());
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
        const registEl = document.querySelector('input[name="regist_productFiles"]');
        const modifyEl = document.querySelector('input[name="modify_productFiles"]');

        // 등록용
        if (registEl) {
            window.pondRegist = FilePond.create(registEl, {
                allowMultiple: true,
                allowReorder: true,
                acceptedFileforms: ['image/*'],
                maxFileSize: '20MB',
                labelIdle: '제품 이미지를 여기에 드래그하거나 <span class="filepond--label-action">클릭하여 업로드</span>하세요.',
                imagePreviewHeight: 150,
                credits: false,
            });
        }

        // 수정용
        if (modifyEl) {
            window.pondModify = FilePond.create(modifyEl, {
                allowMultiple: true,
                allowReorder: true,
                acceptedFileforms: ['image/*', 'video/*'],
                maxFileSize: '20MB',
                labelIdle: '제품 이미지를 여기에 드래그하거나 <span class="filepond--label-action">클릭하여 업로드</span>하세요.',
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
        $('input[name="regist_productFiles"]').attr('accept', 'image/*');
        $('input[name="modify_productFiles"]').attr('accept', 'image/*');

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

/* $('input[name="regist_productFiles"]').attr('accept', 'image/*');
$('input[name="modify_productFiles"]').attr('accept', 'image/*'); */

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
 *  제품 등록
 * =============================== */

function regist() {
  	const forms_idx				= $('#regist_forms_idx').val();
	const partner_idx			= $('#regist_partner_idx').val();
	const congbo_number			= $('#regist_congbo_number').val();
  	const product_name			= $('#regist_product_name').val().trim();
  	const product_name_en		= $('#regist_product_name_en').val().trim();
  	const product_name_vn		= $('#regist_product_name_vn').val().trim();
  	const order_price			= $('#regist_order_price').val().trim();
  	const contents				= $('#regist_contents').val();
  	const contents_en			= $('#regist_contents_en').val();
  	const contents_vn			= $('#regist_contents_vn').val();
  	const pcThumbInput 			= $('input[name="regist_pc_thumb"]')[0];
  	const foThumbInput 			= $('input[name="regist_fo_thumb"]')[0];
  	const moThumbInput 			= $('input[name="regist_mo_thumb"]')[0];

	const congbo				= $('#regist_congbo').is(':checked') ? 1 : 0;
	const newproduct					= $('#regist_new').is(':checked') ? 1 : 0;
  	const testbed				= $('#regist_testbed').is(':checked') ? 1 : 0;
  	const home_recommend		= $('#regist_home_recommend').is(':checked') ? 1 : 0;
  	const product_recommend		= $('#regist_product_recommend').is(':checked') ? 1 : 0;

  	if (!forms_idx)				return showToast('카테고리을 선택해주세요.','error');
	if (!partner_idx)			return showToast('파트너를 선택해주세요.','error');
	if (!product_name)			return showToast('제품명(한국어)을 입력해주세요.','error');
	if (!product_name_en)		return showToast('제품명(영어)을 입력해주세요.','error');
	if (!product_name_vn)		return showToast('제품명(베트남어)을 입력해주세요.','error');
	const barcode = $('#regist_barcode').val().trim();
	if (!barcode)				return showToast('바코드를 입력해주세요.','error');
	if (!/^[0-9A-Za-z\-]{4,50}$/.test(barcode)) return showToast('바코드는 영문, 숫자, 하이픈(-) 4~50자로 입력해주세요.','error');
	
	const fd = new FormData();
	fd.append('forms_idx', forms_idx);
	fd.append('partner_idx', partner_idx);
	fd.append('congbo_number', congbo_number);

	fd.append('product_name', product_name);
	fd.append('product_name_en', product_name_en);
	fd.append('product_name_vn', product_name_vn);
	fd.append('barcode', barcode);
	fd.append('order_price', order_price);
	fd.append('contents', contents);
	fd.append('contents_en', contents_en);
	fd.append('contents_vn', contents_vn);

	fd.append('congbo', congbo);
	fd.append('new', newproduct);
	fd.append('testbed', testbed);
	fd.append('home_recommend', home_recommend);
	fd.append('product_recommend', product_recommend);


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

	const productFiles = window.pondRegist?.getFiles?.() || [];
	if (!productFiles.length) return showToast('제품이미지를 한 개 이상 업로드해주세요.','error');
	{
		const invalid=[], tooLarge=[];
		productFiles.forEach(({file})=>{
			const img=isImage(file);
			if(!img) invalid.push(file.name);
			if(img && file.size>MAX_IMG) tooLarge.push(`${file.name} (이미지 > 20MB)`);
		});
		if(invalid.length) return showToast(`제품이미지 형식 오류:\n- ${invalid.join('\n- ')}`,'error');
		if(tooLarge.length) return showToast(`제품이미지 용량 초과:\n- ${tooLarge.join('\n- ')}`,'error');
	}
	productFiles.forEach(({file})=> fd.append('productFiles[]', file, file.name));
	
	if (!confirm('제품을 등록하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/product/regist',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	showToast('제품이 저장되었습니다.','success');
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
    	url: '/admin/product/detailForm/'+idx,
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
			setFormsCascade($('#modifyFormsBox'), d.forms_idx);
			$('#modify_partner_idx').val(d.partner_idx);

			$('#modify_congbo').prop('checked', d.congbo == 1);
			$('#modify_new').prop('checked', d.new == 1);
			$('#modify_testbed').prop('checked', d.testbed == 1);

			$('#modify_congbo_number').val(d.congbo_number);
			$('#modify_product_name').val(d.product_name);
			$('#modify_product_name_en').val(d.product_name_en);
			$('#modify_product_name_vn').val(d.product_name_vn);
			$('#modify_barcode').val(d.barcode || '');
			$('#modify_order_price').val(d.order_price ?? '');
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
			$('#modify_product_recommend').prop('checked', d.product_recommend == 1);
			

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

			modalOpen('product_modify-modal');
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
  	const forms_idx				= $('#modify_forms_idx').val();
	const congbo_number			= $('#modify_congbo_number').val();
  	const partner_idx			= $('#modify_partner_idx').val();
  	const product_name			= $('#modify_product_name').val().trim();
  	const product_name_en		= $('#modify_product_name_en').val().trim();
  	const product_name_vn		= $('#modify_product_name_vn').val().trim();
  	const order_price			= $('#modify_order_price').val().trim();
  	const contents				= $('#modify_contents').val();
  	const contents_en			= $('#modify_contents_en').val();
  	const contents_vn			= $('#modify_contents_vn').val();
  	const pcThumbInput 			= $('input[name="modify_pc_thumb"]')[0];
  	const foThumbInput 			= $('input[name="modify_fo_thumb"]')[0];
  	const moThumbInput 			= $('input[name="modify_mo_thumb"]')[0];
  	const home_recommend		= $('#modify_home_recommend').is(':checked') ? 1 : 0;
  	const product_recommend	= $('#modify_product_recommend').is(':checked') ? 1 : 0;

  	if (!forms_idx)				return showToast('카테고리을 선택해주세요.','error');
  	if (!partner_idx)			return showToast('파트너를 선택해주세요.','error');
	if (!product_name)			return showToast('제품명(한국어)을 입력해주세요.','error');
	if (!product_name_en)		return showToast('제품명(영어)을 입력해주세요.','error');
	if (!product_name_vn)		return showToast('제품명(베트남어)을 입력해주세요.','error');
	const barcode = $('#modify_barcode').val().trim();
	if (barcode && !/^[0-9A-Za-z\-]{4,50}$/.test(barcode)) return showToast('바코드는 영문, 숫자, 하이픈(-) 4~50자로 입력해주세요.','error');

	const fd = new FormData();
	fd.append('forms_idx', forms_idx);
	fd.append('partner_idx', partner_idx);
	fd.append('congbo_number', congbo_number);
	fd.append('product_name', product_name);
	fd.append('product_name_en', product_name_en);
	fd.append('product_name_vn', product_name_vn);
	fd.append('barcode', barcode);
	fd.append('order_price', order_price);
	fd.append('contents', contents);
	fd.append('contents_en', contents_en);
	fd.append('contents_vn', contents_vn);
	fd.append('home_recommend', home_recommend);
	fd.append('product_recommend', product_recommend);

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
	if (!files.length) return showToast('제품이미지를 한 개 이상 업로드해주세요.','error');
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
		if(invalid.length) return showToast(`제품이미지 형식 오류:\n- ${invalid.join('\n- ')}`,'error');
		if(tooLarge.length) return showToast(`제품이미지 용량 초과:\n- ${tooLarge.join('\n- ')}`,'error');
	}
	
	fd.append('removedFileIdxs', JSON.stringify(removedFileIdxs));
	fd.append('existingFileOrder', JSON.stringify(existingFileOrder));

	if (!confirm('제품을 수정하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/product/modify/'+idx,
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('제품가 수정되었습니다.','success');
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

function deleteproduct() {
	const idx = $('#modify_idx').val();
  	if (!idx) return showToast('오류가 발생하였습니다.','error');
	
	if (!confirm('정말로 해당 제품을 삭제하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/product/delete/'+idx,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('제품가 삭제되었습니다.','success');
                location.href = '/admin/product';
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
