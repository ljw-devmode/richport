@php 
	$header_skip = false; 
	$depth1_num = '3'; 
	$depth2_num = '22'; 
@endphp 
@include('admin.header._header')

	<div class="mt-6 px-5">
		<h3 class="intro-y text-xl font-bold">발전소 매출 관리</h3>
		<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
			<form method="GET" action="/admin/companysales">
				<div class="flex items-end gap-3  flex-wrap">
					<div>
						<p class="mb-1">대상 년도</p>
						<select id="tgt_year" name="tgt_year" class="form-select w-28">
							<option value="">전체</option>	
							@for ($i = 2026; $i >= 2024; $i--)
								<option value="{{ $i }}" {{ request('tgt_year') == $i ? 'selected' : '' }}>{{ $i }}</option>
							@endfor
						</select>
					</div>
					<div>
						<p class="mb-1">대상 월</p>
						<select id="tgt_month" name="tgt_month" class="form-select w-28">
							<option value="">전체</option>	
							@for ($i = 1; $i <= 12; $i++)
								<option value="{{ $i }}" {{ request('tgt_month') == $i ? 'selected' : '' }}>{{ $i }}</option>
							@endfor
						</select>
					</div>
					<div>
						<p class="mb-1">대상 SPC</p>
						<select id="group_idx" name="group_idx" class="form-select w-48">
							<option value="">전체</option>
							@foreach ($companygroup as $item)
							<option value="{{$item->idx}}" {{ request('group_idx') == $item->idx ? 'selected' : '' }}>{{$item->group_name}}</option>
							@endforeach
						</select>
					</div>
					<div>
						<p class="mb-1">대상 발전소</p>
						<select id="comp_idx" name="comp_idx" class="form-select w-48">
							<option value="">전체</option>
							@foreach ($company as $item)
							<option value="{{$item->idx}}" {{ request('comp_idx') == $item->idx ? 'selected' : '' }}>{{$item->comp_name}}</option>
							@endforeach
						</select>
					</div>
					<div>
						<p class="mb-1">대상 발전소명</p>
						<input type="text" id="comp_name" name="comp_name" class="form-control" placeholder="발전소명 입력" value="{{ request('comp_name') }}" >
					</div>													
					<div>
						<p class="mb-1">계산서 발행일</p>
						<div class="relative w-40 mx-auto">
							<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
								<i data-lucide="calendar" class="w-4 h-4"></i>
							</div>
							<input type="text" id="day" name="day" class="form-control pl-12" data-single-mode="true" value="{{ request('day') }}">
						</div>
					</div>
					<div>
						<p class="mb-1">계산서 매출구분</p>
						<select id="type" name="type" class="form-select w-36">
							<option value="">전체</option>
							<option value="smp" {{ request('type') == 'smp' ? 'selected' : '' }}>SMP</option>
							<option value="rec" {{ request('type') == 'rec' ? 'selected' : '' }}>REC</option>
							<option value="om" {{ request('type') == 'om' ? 'selected' : '' }}>관리운영비</option>
							<option value="lease" {{ request('type') == 'lease' ? 'selected' : '' }}>발전대금(리스)</option>
						</select>
					</div>					

					<div>
						<p class="mb-1">계산서 매출귀속</p>
						<select id="supplier" name="supplier" class="form-select w-48">
							<option value="">전체</option>
							@foreach ($supplierlist as $item)	
							<option value="{{$item}}" {{ request('supplier') == $item ? 'selected' : '' }}>{{$item}}</option>
							@endforeach
						</select>
					</div>									
					<div>
						<p class="mb-1">계산서 공급받는자</p>
						<select id="buyer" name="buyer" class="form-select w-48">
							<option value="">전체</option>
							@foreach ($buyerlist as $item)	
							<option value="{{$item}}" {{ request('buyer') == $item ? 'selected' : '' }}>{{$item}}</option>
							@endforeach
						</select>
					</div>
					<div>
						<p class="mb-1">계산서 품목명</p>
						<input type="text" id="sales_title" name="sales_title" class="form-control" placeholder="품목명 입력" value="{{ request('sales_title') }}" >
					</div>
					<div>
						<p class="mb-1">수금일</p>
						<div class="relative w-40 mx-auto">
							<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
								<i data-lucide="calendar" class="w-4 h-4"></i>
							</div>
							<input type="text" id="receive_date" name="receive_date" class="form-control pl-12" data-single-mode="true" value="{{ request('receive_date') }}">
						</div>
					</div>
					<div>
						<p class="mb-1">수금상태</p>
						<select id="receive_status" name="receive_status" class="form-select w-32">
							<option value="">전체</option>
							<option value="1" {{ request('receive_status') === '1' ? 'selected' : '' }}>수금 완료</option>
							<option value="2" {{ request('receive_status') === '2' || !request('receive_status') ? 'selected' : '' }}>수금 미완료</option>
						</select>
					</div>
					<div>
						<p class="mb-1">수금계좌</p>
						<select id="receive_bank" name="receive_bank" class="form-select w-40">
							<option value="">전체</option>
							@foreach ($bankaccounts as $bank)
							<option value="{{ $bank->idx }}" {{ request('receive_bank') == $bank->idx ? 'selected' : '' }}>{{ $bank->bank_name }} {{ $bank->account_number }}</option>
							@endforeach
						</select>
					</div>
					<div>
						<p class="mb-1">수금 차액</p>
						<select id="receive_remain" name="receive_remain" class="form-select w-32">
							<option value="">전체</option>
							<option value="1" {{ request('receive_remain') === '1' ? 'selected' : '' }}>차액있음</option>
							<option value="0" {{ request('receive_remain') === '0' ? 'selected' : '' }}>차액없음</option>
						</select>
					</div>


					<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
				</div>
			</form>
		</div>

		<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
			<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
				<div class="text-base text-primary">총 {{ $data->total() }}개</div>
				<div class="flex items-center gap-2">
				@if(auth()->user()->hasPerm('companysales.save'))
				<div class="flex items-center gap-2">
					<a href="javascript:;" class="btn btn-dark px-4" id="btnSalesReg" name="btnSalesReg">매출 등록</a>
				</div>
        		@endif
				@if(auth()->user()->hasPerm('companysales.excel'))
				<button type="button" class="btn border-[#1b6c40] bg-[#1b6c40] text-white px-4" onclick="window.location.href='{{ route('admin.companysales.export', request()->query()) }}'">엑셀 다운로드</button>
				@endif				
				</div>
			</div>

			<div class="p-6">
				<div style="overflow-y: auto; max-height: 600px; border: 1px solid #e2e8f0;">
					<table class="table table-bordered table-hover">
						<thead class="bg-slate-50 text-center">
							<tr>
								<th class="whitespace-nowrap">번호</th>							
								<th class="whitespace-nowrap">대상 SPC</th>
								<th class="whitespace-nowrap">대상 발전소</th>								
								<th class="whitespace-nowrap">계산서 발행일</th>
								<th class="whitespace-nowrap">매출 귀속</th>
								<th class="whitespace-nowrap">공급 받는 자</th>
								<th class="whitespace-nowrap">발전 년월</th>
								<th class="whitespace-nowrap">구분</th>
								<th class="whitespace-nowrap">계산서 품목명</th>
								<th class="whitespace-nowrap">수량</th>
								<th class="whitespace-nowrap">단가</th>
								<th class="whitespace-nowrap">매출</th>
								<th class="whitespace-nowrap">공급가</th>
								<th class="whitespace-nowrap">부가세</th>
								<th class="whitespace-nowrap">수금상태</th>
								<!-- <th class="whitespace-nowrap">수금일</th> -->
								<!-- <th class="whitespace-nowrap">수금계좌</th> -->
								<!-- <th class="whitespace-nowrap">수금액</th> -->
								<!-- <th class="whitespace-nowrap">차액</th> -->
								<!-- <th class="whitespace-nowrap">차액 사유</th> -->
							</tr>
						</thead>
						<tbody class="text-center font-medium">
						@forelse ($data as $index => $item)
							<tr class="btn-sales-edit sales-row-popover" 
								style="cursor: pointer; {{ $item->receive_status == 1 ? 'background-color: #d3d3d3;' : '' }}" 
						        data-idx="{{ $item->idx }}"
								data-tgt_year="{{ $item->tgt_year ?? '' }}"
								data-tgt_month="{{ $item->tgt_month ?? '' }}"
								data-supplier="{{ $item->supplier ?? '' }}"
								data-buyer="{{ $item->buyer ?? '' }}"
								data-day="{{ $item->day ?? '' }}"
								data-type="{{ $item->type ?? '' }}"
								data-title="{{ $item->title ?? '' }}"
								data-quantity="{{ $item->quantity ?? '' }}"
								data-unitprice="{{ $item->unitprice ?? '' }}"
								data-sales="{{ $item->sales ?? '' }}"
								data-supplyprice="{{ $item->supplyprice ?? '' }}"
								data-vat="{{ $item->vat ?? '' }}"
								data-memo="{{ $item->memo ?? '' }}"
								data-receive_status="{{ $item->receive_status ?? '' }}"
								data-receive_date="{{ $item->receive_date ?? '' }}"
								data-receive_bank="{{ $item->receive_bank ?? '' }}"
								data-receive_bankname="{{ $item->bank_name ?? '' }}"
								data-receive_bankaccount="{{ $item->account_number ?? '' }}"
								data-receive_amount="{{ $item->receive_amount ?? '' }}"
								data-receive_memo="{{ $item->receive_memo ?? '' }}"
								data-group="{{ $item->group_name_list ?: '-' }}" 
	    						data-comp="{{ $item->comp_name_list ?: '-' }}"
								data-group_idx="{{ $item->group_idx_list ?? '' }}"
								data-comp_idx="{{ $item->comp_idx_list ?? '' }}">
								<td>{{ $index + 1 + ($data->currentPage() - 1) * $data->perPage() }}</td>
								<td title="{{ $item->group_name_list ?? '' }}">{{ $item->group_name_list ?? '' }}</td>
								<td title="{{ $item->comp_name_list ?? '' }}">{{ $item->comp_name_list ?? '' }}</td>
								<td>{{ $item->day ?? '' }}</td>
								<td>{{ $item->supplier ?? '' }}</td>
								<td>{{ $item->buyer ?? '' }}</td>
								<td>{{ ($item->tgt_year ?? '') . '-' . str_pad($item->tgt_month ?? '', 2, '0', STR_PAD_LEFT) }}</td>
								<td>{{ $item->type ?? '' }}</td>
								<td>{{ $item->title ?? '' }}</td>
								<td>{{ number_format($item->quantity ?? 0) }}</td>
								<td>{{ number_format($item->unitprice ?? 0) }}</td>
								<td>{{ number_format($item->sales ?? 0) }}</td>
								<td>{{ number_format($item->supplyprice ?? 0) }}</td>
								<td>{{ number_format($item->vat ?? 0) }}</td>
								<td>
									@if($item->receive_status == 1)
										<span class="badge badge-success">수금 완료</span>
									@elseif($item->receive_status == 2)
										<span class="badge badge-warning">수금 미완료</span>
									@else
										-
									@endif
								</td>
								<!-- <td>{{ $item->receive_date ?? '' }}</td> -->
								<!-- <td>{{ $item->receive_bank ? ($item->bank_name . ' ' . $item->account_number) : '' }}</td> -->
								<!-- <td>{{ number_format($item->receive_amount ?? 0) }}</td> -->
								<!-- <td>{{ number_format(ceil(($item->supplyprice ?? 0) + ($item->vat ?? 0)) - ($item->receive_amount ?? 0)) }}</td> -->
								<!-- <td>{{ $item->receive_memo ?? '' }}</td>	 -->
							</tr>
						@empty
						<tr>
							<td colspan="18" class="text-center">매출 내역이 없습니다.</td>
						</tr>
						@endforelse
					</tbody>
				</table>
				</div>
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


	<!-- 매출 모달 -->
	@if(auth()->user()->hasPerm('companysales.save')) 
	<div id="companysales_save-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl modalmaxheight">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">매출 저장</h2>
					<button onclick="modalClose('companysales_save-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class="flex items-center gap-10 mt-3">								
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">대상 년도<span class="text-danger">*</span></p>
							<select id="tgt_year" name="tgt_year" class="form-select">
								<option value="">년도 선택</option>
								<option value="2026">2026</option>
								<option value="2025">2025</option>
								<option value="2024">2024</option>
							</select>
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">대상 월<span class="text-danger">*</span></p>
							<select id="tgt_month" name="tgt_month" class="form-select">
								<option value="">월 선택</option>	
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
							</select>
						</div>
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">대상 SPC<br/>(ctrl+클릭)</p>
							<select id="group_idx" name="group_idx[]" class="form-select w-56" multiple size="5">
								@foreach ($companygroup as $item)
									<option value="{{ $item->idx }}" 
										{{ in_array($item->idx, (array)request('group_idx', [])) ? 'selected' : '' }}>
										{{ $item->group_name }}
									</option>
								@endforeach
							</select>							
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">대상 발전소<br/>(ctrl+클릭)</p>
							<select id="comp_idx" name="comp_idx[]" class="form-select w-56" multiple size="5">
								@foreach ($company as $item)
									<option value="{{ $item->idx }}" 
										{{ in_array($item->idx, (array)request('comp_idx', [])) ? 'selected' : '' }}>
										{{ $item->comp_name }}
									</option>
								@endforeach
							</select>
						</div>
					</div>									
					<div class="flex items-center gap-10 mt-3">	
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">계산서 발행일<span class="text-danger">*</span></p>
							<div class="relative w-full">
								<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
									<i data-lucide="calendar" class="w-4 h-4"></i>
								</div>
								<input type="text" id="day" name="day" class="datepicker form-control pl-12" data-single-mode="true">
							</div>
						</div>						
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">계산서 매출 구분<span class="text-danger">*</span></p>
							<select id="type" name="type" class="form-select">
								<option value="REC" selected>REC</option>
								<option value="SMP">SMP</option>
								<option value="운영비">운영비</option>
								<option value="발전대금(리스)">발전대금(리스)</option>
							</select>
						</div>							
					</div>					
					<div class="flex items-center gap-10 mt-3">	
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">계산서 매출 귀속</p>
						<input type="text" id="supplier" name="supplier" class="form-control" placeholder="매출 귀속을 입력해주세요">
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">계산서 공급받는자</p>
						<input type="text" id="buyer" name="buyer" class="form-control" placeholder="공급 받는 자를 입력해주세요">
						</div>
					</div>
					<div class="flex items-center gap-10 mt-3">							
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">계산서 품목명 </p>
						<input type="text" id="title" name="title" class="form-control" placeholder="계산서 품목명을 입력해주세요">
						</div>		
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">계산서 비고 </p>
						<input type="text" id="memo" name="memo" class="form-control" placeholder="비고를 입력해주세요">
						</div>											
					</div>	
					<div class="flex items-center gap-10 mt-3">						
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">계산서 수량 </p>
						<input type="text" id="quantity" name="quantity" class="form-control" placeholder="수량을 입력해주세요">
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">계산서 단가 </p>
						<input type="text" id="unitprice" name="unitprice" class="form-control" placeholder="단가를 입력해주세요">
						</div>
					</div>					
					<div class="flex items-center gap-10 mt-3">							
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">계산서 매출<span class="text-danger">*</span></p>
						<input type="text" id="sales" name="sales" class="form-control" placeholder="매출을 입력해주세요">
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">계산서 총액</p>
						<input type="text" id="total_sales" name="total_sales" class="form-control" placeholder="계산서 총액은 자동 계산됩니다" disabled value="">
						</div>																		
					</div>	
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">계산서 공급가 </p>
						<input type="text" id="supplyprice" name="supplyprice" class="form-control" placeholder="공급가를 입력해주세요">
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">계산서 부가세 </p>
						<input type="text" id="vat" name="vat" class="form-control" placeholder="부가세를 입력해주세요">
						</div>
					</div>		
					
					@if(auth()->user()->hasPerm('companysales.receive'))					
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">수금 상태</p>
							<select id="receive_status" name="receive_status" class="form-select">
								<option value="" selected>수금상태 선택</option>
								<option value="1" selected>수금 완료</option>
								<option value="2" selected>수금 미완료</option>
							</select>
						</div>						
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">수금 일자</p>
							<div class="relative w-full">
								<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
									<i data-lucide="calendar" class="w-4 h-4"></i>
								</div>
								<input type="text" id="receive_date" name="receive_date" class="datepicker form-control pl-12" data-single-mode="true">
							</div>
						</div>
					</div>					
					<div class="flex items-center gap-10 mt-3">							
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">수금 계좌</p>
							<select id="receive_bank" name="receive_bank" class="form-select">
								<option value="">수금계좌 선택</option>
								@foreach ($bankaccounts as $bank)
									<option value="{{ $bank->idx }}">{{ $bank->bank_name }} {{ $bank->account_number }}</option>
								@endforeach
							</select>
						</div>						
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">수금 금액 </p>
							<input type="text" id="receive_amount" name="receive_amount" class="form-control" placeholder="수금액을 입력해주세요">
						</div>
					</div>
					<div class="flex items-center gap-10 mt-3">							
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">수금 차액</p>
							<input type="text" id="receive_remain" name="receive_remain" class="form-control" disabled placeholder="수금 차액은 자동계산 됩니다" value="">
						</div>						
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">차액 발생 사유</p>
							<input type="text" id="receive_memo" name="receive_memo" class="form-control" placeholder="차액 발생 사유를 입력해주세요">
						</div>
					</div>
					@endif																	
				</div>

				<input type="hidden" id="sales_idx" name="sales_idx" value="">

				<div class="modal-footer flex items-center justify-center gap-3">
					@if(auth()->user()->hasPerm('companysales.save'))
					<button type="button" id="btnSalesDelete" name="btnSalesDelete" class="btn btn-outline-danger w-24" style="display:none;">삭제</button>
					@endif
					<button type="button" id="btnSaleCancel" name="btnSaleCancel" class="btn btn-outline-secondary w-20 " onclick="modalClose('companysales_save-modal')">취소</button>
					@if(auth()->user()->hasPerm('companysales.save'))
					<button type="button" id="btnSalesSave" name="btnSalesSave"  class="btn btn-primary w-20" >저장</button>
					@endif
				</div>
			</div>
		</div>
</div>
	@endif
	

</div>
<!-- END: Content -->
</div>
@include('admin.footer._footer')

<script>
// "," 를 줄바꿈으로 변경
const formatList = (str) => {
    if (!str || str === '-') return '-';
    // 콤마 뒤에 공백이 있을 수도 있으므로 정규식으로 처리
    return str.replace(/,\s*/g, '<br>');
};


@if(auth()->user()->hasPerm('companysales.receive'))

// 목록에 마우스 오버시 대상 SPC, 발전소 정보 표시
document.addEventListener('DOMContentLoaded', function () {
    const rows = document.querySelectorAll('.sales-row-popover');
    
    rows.forEach(row => {
        row.addEventListener('mousemove', function(e) { // mouseenter 대신 mousemove 사용 시 커서를 따라다님
            const group = formatList(this.getAttribute('data-group') || '-');
            const comp = formatList(this.getAttribute('data-comp') || '-');
            const supplyprice = this.getAttribute('data-supplyprice') || '-';
            const vat = this.getAttribute('data-vat') || '-';
			const receive_date = this.getAttribute('data-receive_date') || '-';
			const receive_amount = this.getAttribute('data-receive_amount') || '-';
            const receive_bankname = this.getAttribute('data-receive_bankname') || '-';
            const receive_bankaccount = this.getAttribute('data-receive_bankaccount') || '-';
            const receive_memo = this.getAttribute('data-receive_memo') || '-';

			const remain_amount = Number(supplyprice) + Number(vat) - Number(receive_amount);


            let tooltip = document.querySelector('.custom-tooltip');
            
            // 툴팁이 없으면 생성
            if (!tooltip) {
                tooltip = document.createElement('div');
                tooltip.className = 'custom-tooltip';
                tooltip.style.cssText = `
                    position: fixed; 
                    z-index: 9999; 
                    background: white; 
                    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
                    pointer-events: none; /* 마우스 간섭 방지 */
                    border-radius: 4px;
                    overflow: hidden;
                `;
                document.body.appendChild(tooltip);
            }

						// 툴팁에 그룹, 발전소 필요시 아래 내용 추가
			            // <tr>
                        //     <td class="tooltip-inner">대상 그룹</td>
                        //     <td class="tooltip-inner">${group}</td>
                        // </tr>
						// <tr>
						// 	<td class="tooltip-inner">대상 발전소</td>
                        //     <td class="tooltip-inner">${comp}</td>
                        // </tr>

            tooltip.innerHTML = `
                <div  style="padding:10px; radius:8px;"><pstyle="margin:0; padding:10px; font-size:14px; font-weight:bold; background:#f1f3f5;">추가 정보</p>
				<table class="table table-sm table-bordered mb-0" style="font-size:12px; width:300px;border:1px solid #dee2e6; background:white;">
                    <thead class="table-light text-center">
                        <tr style="background:#f1f3f5;"><th style="width:40%; padding:5px;">항목</th><th style="width:60%; padding:5px;">내용</th></tr>
                    </thead>
                    <tbody>
						<tr>
							<td class="tooltip-inner">수금 일자</td>
                            <td class="tooltip-inner">${receive_date}</td>
                        </tr>
						<tr>
							<td class="tooltip-inner">수금 금액</td>
                            <td class="tooltip-inner">${receive_amount}</td>
                        </tr>						
						<tr>
							<td class="tooltip-inner">수금 계좌</td>
                            <td class="tooltip-inner">${receive_bankname}<br/>${receive_bankaccount}</td>
                        </tr>
						<tr>
							<td class="tooltip-inner">수금 차액</td>
                            <td class="tooltip-inner">${remain_amount}</td>
                        </tr>
						<tr>
							<td class="tooltip-inner">수금 차액 사유</td>
                            <td class="tooltip-inner">${receive_memo}</td>
                        </tr>
                    </tbody>
                </table></div>`;
            
            // 마우스 위치 기준으로 좌표 설정
            const tooltipHeight = tooltip.offsetHeight;
            const tooltipWidth = tooltip.offsetWidth;
            
            // 마우스 커서에서 위로 15px, 왼쪽으로 절반 위치시켜 중앙 정렬
            tooltip.style.left = (e.clientX - (tooltipWidth / 2)) + 'px';
            tooltip.style.top = (e.clientY - tooltipHeight - 15) + 'px';

            // 화면 왼쪽/오른쪽 끝을 벗어나지 않게 보정
            const leftPos = parseFloat(tooltip.style.left);
            if (leftPos < 10) tooltip.style.left = '10px';
            if (leftPos + tooltipWidth > window.innerWidth) tooltip.style.left = (window.innerWidth - tooltipWidth - 10) + 'px';
        });
        
        row.addEventListener('mouseleave', function(e) {
            const tooltip = document.querySelector('.custom-tooltip');
            if (tooltip) tooltip.remove();
        });
    });
});

@endif


// 계산서 발행일 표시를 위한 달력 설정
const defaultday = "{{ request('day') }}";

const day = new Litepicker({
    element: document.querySelector('#day'),
    singleMode: true,
    firstDay: 0,
    format: 'YYYY-MM-DD',
    lang: "ko-KR",
    numberOfColumns: 1,
    numberOfMonths: 1,
    tooltipText: {
        other: "일"
    },
    dropdowns: {
        minYear: 2023,
        maxYear: new Date().getFullYear() + 6,
        months: true,
        years: true
    },
    setup: (picker) => {
        if (defaultday) {
            picker.setDate(defaultday);
        }
    },
});

// 수금일 표시를 위한 달력 설정
const defaultreceive_date = "{{ request('receive_date') }}";

const receive_date = new Litepicker({
    element: document.querySelector('#receive_date'),
    singleMode: true,
    firstDay: 0,
    format: 'YYYY-MM-DD',
    lang: "ko-KR",
    numberOfColumns: 1,
    numberOfMonths: 1,
    tooltipText: {
        other: "일"
    },
    dropdowns: {
        minYear: 2023,
        maxYear: new Date().getFullYear() + 6,
        months: true,
        years: true
    },
    setup: (picker) => {
        if (defaultreceive_date) {
            picker.setDate(defaultreceive_date);
        }
    },
});

// 자동 계산 함수들
// 매출, 매출 총액 계산
function calculateSales() {
    const quantity = parseFloat($('#companysales_save-modal #quantity').val()) || 0;
    const unitprice = parseFloat($('#companysales_save-modal #unitprice').val()) || 0;
    const sales = quantity * unitprice;
    
    $('#companysales_save-modal #sales').val(sales.toFixed(0));
    calculateTotalSales();
}

// 매출 변경시 총액 재계산
function calculateTotalSales() {
    const sales = parseFloat($('#companysales_save-modal #sales').val()) || 0;
    const totalSales = Math.round(sales * 1.1);
    
    $('#companysales_save-modal #total_sales').val(totalSales.toFixed(0));
    calculateVATAndSupplyPrice();
    calculateReceiveRemain();
}

// 매출 변경시 공급가, 부가세 재계산
function calculateVATAndSupplyPrice() {
    const sales = parseFloat($('#companysales_save-modal #sales').val()) || 0;
    const vat = Math.round(sales * 0.1);
    const supplyprice = sales;
    
    $('#companysales_save-modal #vat').val(vat.toFixed(0));
    $('#companysales_save-modal #supplyprice').val(supplyprice.toFixed(0));
}

// 공급가 변경시 부가세 재계산
function calculateVATFromSupplyPrice() {
    const supplyprice = parseFloat($('#companysales_save-modal #supplyprice').val()) || 0;
    const totalSales = parseFloat($('#companysales_save-modal #total_sales').val()) || 0;
    const vat = totalSales - supplyprice;

    $('#companysales_save-modal #vat').val(vat.toFixed(0));
}

// 매출 총액에서 수금된 금액의 차액을 계산
function calculateReceiveRemain() {
    const totalSales = parseFloat($('#companysales_save-modal #total_sales').val()) || 0;
    const receiveAmount = parseFloat($('#companysales_save-modal #receive_amount').val()) || 0;
    const remain = totalSales - receiveAmount;
    
    $('#companysales_save-modal #receive_remain').val(remain.toFixed(0));
    
    // 수금 상태 자동 변경
    if (remain <= 0) {
        $('#companysales_save-modal #receive_status').val('1'); // 수금 완료
    } else {
        $('#companysales_save-modal #receive_status').val('2'); // 수금 미완료
    }
}

// 수량, 단가 입력시 매출 계산 실행
$(document).on('input', '#companysales_save-modal #quantity, #companysales_save-modal #unitprice', function() {
    calculateSales();
});

// 매출 변경시 총액 재계산 실행
$(document).on('input', '#companysales_save-modal #sales', function() {
    calculateTotalSales();
});

// 공급가 변경시 부가세, 잔액 재계산 실행
$(document).on('input', '#companysales_save-modal #supplyprice', function() {
    calculateVATFromSupplyPrice();
    calculateReceiveRemain();
});

// 수금액 입력시 차액 계산 실행
$(document).on('input', '#companysales_save-modal #receive_amount', function() {
    calculateReceiveRemain();
});


function resetSalesModal() {
 	$('#companysales_save-modal #sales_idx').val('');
    $('#companysales_save-modal #tgt_year').val('');
    $('#companysales_save-modal #tgt_month').val('');
    $('#companysales_save-modal #supplier').val('');
    $('#companysales_save-modal #buyer').val('');
    $('#companysales_save-modal #day').val('');
    $('#companysales_save-modal #type').val('');
    $('#companysales_save-modal #title').val('');
    $('#companysales_save-modal #quantity').val('');
    $('#companysales_save-modal #unitprice').val('');
    $('#companysales_save-modal #sales').val('');
    $('#companysales_save-modal #supplyprice').val('');
    $('#companysales_save-modal #vat').val('');
    $('#companysales_save-modal #memo').val('');
	
	@if(auth()->user()->hasPerm('companysales.receive'))
    $('#companysales_save-modal #receive_status').val('');
    $('#companysales_save-modal #receive_date').val('');
    $('#companysales_save-modal #receive_bank').val('');
    $('#companysales_save-modal #receive_amount').val('');
    $('#companysales_save-modal #receive_memo').val('');
    @endif

	$('#companysales_save-modal #group_idx').val('');
    $('#companysales_save-modal #comp_idx').val('');

	$('#btnSalesDelete').hide();
	$('#companysales_save-modal .modal-header h2').text('매출 저장');
}


// 매출 등록 모달
$(document).on('click', '#btnSalesReg', function () {
    // 폼 초기화
    resetSalesModal();

    // 모달 제목 변경
    $('#companysales_save-modal .modal-header h2').text('매출 저장');
    
    modalOpen('companysales_save-modal');
});

// 매출 저장
$(document).on('click', '#btnSalesSave', function () {
    // 필수 필드 검증
    const tgt_year = $('#companysales_save-modal #tgt_year').val();
    const tgt_month = $('#companysales_save-modal #tgt_month').val();
    const type = $('#companysales_save-modal #type').val();
    const day = $('#companysales_save-modal #day').val();
    const sales = $('#companysales_save-modal #sales').val();
    const group_idx = $('#companysales_save-modal #group_idx').val();
    const comp_idx = $('#companysales_save-modal #comp_idx').val();

    if (!tgt_year || tgt_year === '') { showToast('대상 년도를 선택해주세요.', 'error'); return; }
    if (!tgt_month || tgt_month === '') { showToast('대상 월을 선택해주세요.', 'error'); return; }
    if (!day || day === '') { showToast('계산서 발행일을 입력해주세요.', 'error'); return; }
    if (!type || type === '') { showToast('계산서 매출 구분을 선택해주세요.', 'error'); return; }
    if (!sales || sales === '') { showToast('매출을 입력해주세요.', 'error'); return; }

    // 폼 데이터 수집
    const formData = {
        sales_idx: $('#companysales_save-modal #sales_idx').val() || '',
        tgt_year: tgt_year,
        tgt_month: tgt_month,
        type: type,
        supplier: $('#companysales_save-modal #supplier').val() || '',
        buyer: $('#companysales_save-modal #buyer').val() || '',
        day: day,
        title: $('#companysales_save-modal #title').val() || '',
        quantity: $('#companysales_save-modal #quantity').val() || '',
        unitprice: $('#companysales_save-modal #unitprice').val() || '',
        sales: sales,
        supplyprice: $('#companysales_save-modal #supplyprice').val() || '',
        vat: $('#companysales_save-modal #vat').val() || '',
        memo: $('#companysales_save-modal #memo').val() || '',
		@if(auth()->user()->hasPerm('companysales.create'))
        receive_status: $('#companysales_save-modal #receive_status').val() || '',
        receive_date: $('#companysales_save-modal #receive_date').val() || '',
        receive_bank: $('#companysales_save-modal #receive_bank').val() || 0,
        receive_amount: $('#companysales_save-modal #receive_amount').val() || '',
        receive_memo: $('#companysales_save-modal #receive_memo').val() || '',
		@endif
        'group_idx[]': group_idx,
        'comp_idx[]': comp_idx,
    };

    // AJAX 요청
    $.ajax({
        url: '/admin/companysales/save',
        type: 'POST',
        data: formData,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            if (res.status === 'success') {
                showToast(res.message, 'success');
                modalClose('companysales_save-modal');
                location.reload();
            } else {
                showToast(res.message || '저장 실패', 'error');
            }
        },
        error: function(xhr) {
            console.error(xhr);
            showToast('저장 중 오류가 발생했습니다.', 'error');
        }
    });
});
								
// 매출 수정 행 클릭
$(document).on('click', 'tbody tr.btn-sales-edit', function() {
	const idx = $(this).data('idx');
    const tgt_year = $(this).data('tgt_year');
    const tgt_month = $(this).data('tgt_month');
    const group_idx = $(this).data('group_idx');
    const comp_idx = $(this).data('comp_idx');
	const supplier = $(this).data('supplier');
    const buyer = $(this).data('buyer');
    const day = $(this).data('day');
    const type = $(this).data('type');
    const title = $(this).data('title');
    const quantity = $(this).data('quantity');
    const unitprice = $(this).data('unitprice');
    const sales = $(this).data('sales');
    const supplyprice = $(this).data('supplyprice');
    const vat = $(this).data('vat');
    const memo = $(this).data('memo');
	@if(auth()->user()->hasPerm('companysales.create'))
    const receive_status = $(this).data('receive_status');
    const receive_date = $(this).data('receive_date');
    const receive_bank = $(this).data('receive_bank');
    const receive_amount = $(this).data('receive_amount');
    const receive_memo = $(this).data('receive_memo');
	@endif

	// 폼 데이터 초기화
	resetSalesModal();

    // 폼에 데이터 설정
    $('#companysales_save-modal #sales_idx').val(idx);
    $('#companysales_save-modal #tgt_year').val(tgt_year);
    $('#companysales_save-modal #tgt_month').val(tgt_month);
    $('#companysales_save-modal #supplier').val(supplier);
    $('#companysales_save-modal #buyer').val(buyer);
    $('#companysales_save-modal #day').val(day);
    $('#companysales_save-modal #type').val(type);
    $('#companysales_save-modal #title').val(title);
    $('#companysales_save-modal #quantity').val(quantity);
    $('#companysales_save-modal #unitprice').val(unitprice);
    $('#companysales_save-modal #sales').val(sales);
    $('#companysales_save-modal #supplyprice').val(supplyprice);
    $('#companysales_save-modal #vat').val(vat);
    $('#companysales_save-modal #memo').val(memo);

	@if(auth()->user()->hasPerm('companysales.receive'))
    $('#companysales_save-modal #receive_status').val(receive_status);
    $('#companysales_save-modal #receive_date').val(receive_date);
    $('#companysales_save-modal #receive_bank').val(receive_bank);
    $('#companysales_save-modal #receive_amount').val(receive_amount);
    $('#companysales_save-modal #receive_memo').val(receive_memo);
	@endif

    // SPC와 발전소 선택 해제 후 다시 선택
	$('#companysales_save-modal #group_idx').val((group_idx && group_idx !== "") ? String(group_idx).split(',') : []);
    $('#companysales_save-modal #comp_idx').val((comp_idx && comp_idx !== "") ? String(comp_idx).split(',') : []);

    // 모달 제목 변경
    $('#companysales_save-modal .modal-header h2').text('매출 수정');
    
    // 삭제 버튼 표시
    $('#btnSalesDelete').show();
    
    // 기존 데이터 로드 후 계산 수행
    setTimeout(() => {
        calculateTotalSales();
        calculateReceiveRemain();
    }, 100);
    
    // 모달 열기
    modalOpen('companysales_save-modal');
});

// 매출 삭제
$(document).on('click', '#btnSalesDelete', function() {
    const idx = $('#companysales_save-modal #sales_idx').val();
    
    if (!idx || idx === '') {
        showToast('삭제할 항목을 찾을 수 없습니다.', 'error');
        return;
    }

    if (!confirm('정말로 이 매출 정보를 삭제하시겠습니까?')) {
        return;
    }

    $.ajax({
        url: '/admin/companysales/delete/' + idx,
        type: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            if (res.status === 'success') {
                showToast(res.message, 'success');
                modalClose('companysales_save-modal');
                location.reload();
            } else {
                showToast(res.message || '삭제 실패', 'error');
            }
        },
        error: function(xhr) {
            console.error(xhr);
            showToast('삭제 중 오류가 발생했습니다.', 'error');
        }
    });
});

</script>