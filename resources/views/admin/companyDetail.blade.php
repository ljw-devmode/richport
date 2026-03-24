@php 
	$header_skip = false; 
	$depth1_num = '3'; 
	$depth2_num = '21'; 
@endphp 
@include('admin.header._header')

	<div class="mt-6 px-5 tabrelative">
		<h3 class="intro-y text-xl font-bold">발전소 상세</h3>
		@if(auth()->user()->hasPerm('company.report'))
		<button type="button" id="createReport" class="btn btn-danger btnreg" data-idx="{{ $data->idx }}">리포트 생성</button>
		@endif
			
		<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
			<div class="modal-body">
				<p class="mb-1 font-medium " id="modalCompanyCount">발전소 정보</p>

				<div class="flex items-center gap-2 mt-3">
					<p class="shrink-0 w-32 font-medium ">발전소명 <span class="text-danger">*</span></p>
					<input type="text" id="modify_comp_name" name="modify_comp_name" class="form-control" placeholder="발전소명을 입력해주세요" value="{{ $data->comp_name }}">
				</div>

				<div class="flex items-center gap-10 mt-3">
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">지역(도/시) <span class="text-danger">*</span></p>
						<select id="modify_regions_idx" name="modify_regions_idx" class="form-select">
							<option value="">지역을 선택해주세요</option>
							@foreach ($regions as $item)
								<option value="{{$item->idx}}"  {{ $data->regions_idx == $item->idx ? 'selected' : '' }}>{{$item->name_kr}}</option>
							@endforeach
						</select>
					</div>
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">도시(시/구/군) <span class="text-danger">*</span></p>
						<select id="modify_cities_idx" name="modify_cities_idx" class="form-select">
							<option value="">도시를 선택해주세요</option>
						</select>
					</div>
				</div>

				<div class="flex items-center gap-10 mt-3">
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium">용량(kW) <span class="text-danger">*</span></p>
						<input type="text" id="modify_volume" name="modify_volume" class="form-control" placeholder="용량을 입력해주세요" value="{{ $data->volume_formatted }}">
					</div>
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium">상업운전 개시일</p>
						<div class="relative w-full">
							<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
								<i data-lucide="calendar" class="w-4 h-4"></i>
							</div>
							<input type="text" id="modify_start_date" name="modify_start_date" class="datepicker form-control pl-12" data-single-mode="true" value="{{$data->start_date}}">
						</div>
					</div>
				</div>

				<div class="flex items-center gap-10 mt-3">
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium">일사량 관측소 <span class="text-danger">*</span></p>
						<div class="relative w-full">
							<select id="modify_kma_idx" name="modify_kma_idx" class="form-select">
								<option value="">일사량 관측소를 선택해주세요</option>
								@foreach ($kmaobv as $item)
								<option value="{{ $item->idx }}" {{ $data->kma_idx == $item->idx ? 'selected' : '' }}>{{ $item->stn_name }} ({{ $item->idx }})</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium">소속 SPC</p>
						<div class="relative w-full">
							<select id="modify_group_idx" name="modify_group_idx" class="form-select">
								<option value="0">SPC 미선택</option>
								@foreach ($companygroup as $item)
								<option value="{{ $item->idx }}" {{ $data->group_idx == $item->idx ? 'selected' : '' }}>{{ $item->group_name }}</option>
								@endforeach
							</select>
						</div>
					</div>						
				</div>

				<div class="flex items-center gap-10 mt-3">
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">목표 발전시간(h) </p>
					<input type="text" id="modify_tgt_kwh" name="modify_tgt_kwh" class="form-control" placeholder="목표 발전시간을 입력해주세요" value="{{$data->tgt_kwh}}">
					</div>
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">목표 발전효율(%) </p>
					<input type="text" id="modify_tgt_rate" name="modify_tgt_rate" class="form-control" placeholder="목표 발전효율을 입력해주세요" value="{{$data->tgt_rate}}">
					</div>						
				</div>

				<input type="hidden" id="modify_idx" name="modify_idx" value="{{ $data->idx }}">
			</div>
		</div>

		<!-- 저장, 목록, 삭제 버튼 -->
		<div class="modal-footer flex items-center justify-center gap-3 ">
			<button type="button" class="btn btn-outline-pending w-20 " onclick="goList();">목록</button>
			@if(auth()->user()->hasPerm('company.update'))
			<button type="button" class="btn btn-primary w-20" onclick="modify()">저장</button>
			@endif
			@if(auth()->user()->hasPerm('company.delete'))
			<button type="button" id="btnDeleteGroup" class="btn btn-outline-danger w-20" onclick="deletekwh()">삭제</button>
			@endif
		</div>

		@if(auth()->user()->hasPerm('company.saleview') || 
		auth()->user()->hasPerm('company.kwhview') || 
		auth()->user()->hasPerm('company.yearkwhview') || 
		auth()->user()->hasPerm('company.yearkwhhoursview') || 
		auth()->user()->hasPerm('company.checkview') || 
		auth()->user()->hasPerm('company.emergencyview') || 
		auth()->user()->hasPerm('company.changeview') || 
		auth()->user()->hasPerm('company.issueview'))
		<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
			<!-- 탭 영역 -->
			<div class="flex items-center gap-10 border-b border-slate-200 py-3 px-6">
				<!-- <button type="button" class="detail-tab-btn active text-primary" data-tab="tab-sales">현황</button> -->
				@if(auth()->user()->hasPerm('company.saleview'))				 
				<button type="button" class="detail-tab-btn active text-primary" data-tab="tab-sales">매출</button>
				@endif
				@if(auth()->user()->hasPerm('company.kwhview'))				 
				<button type="button" class="detail-tab-btn" data-tab="tab-kwh">발전량</button>
				@endif
				@if(auth()->user()->hasPerm('company.yearkwhview'))				 
				<button type="button" class="detail-tab-btn" data-tab="tab-yearkwh">목표 발전량</button>
				@endif
				@if(auth()->user()->hasPerm('company.yearkwhhoursview'))				 
				<button type="button" class="detail-tab-btn" data-tab="tab-yearkwhhours">목표 발전시간</button>
				@endif
				@if(auth()->user()->hasPerm('company.checkview'))				 
				<button type="button" class="detail-tab-btn" data-tab="tab-check">정기 점검</button>
				@endif
				@if(auth()->user()->hasPerm('company.emergencyview'))				 
				<button type="button" class="detail-tab-btn" data-tab="tab-emergency">긴급 출동</button>
				@endif
				@if(auth()->user()->hasPerm('company.changeview'))				 
				<button type="button" class="detail-tab-btn" data-tab="tab-change">소모품 교체</button>
				@endif
				@if(auth()->user()->hasPerm('company.issueview'))				 
				<button type="button" class="detail-tab-btn" data-tab="tab-issue">이슈</button>
				@endif
			</div>


			<div class="detail-tabs-wrap">
				<div class="detail-tab-panels tabrelative">
					<!-- 현황 -->
					<div id="tab-summary" class="detail-tab-panel">
						<div class="text-base mb-2">총 {{ $data->companysales->count() }}개월</div>
						<div id="summaryTableArea">
						</div>
					</div>

					<!-- 매출 -->
					@if(auth()->user()->hasPerm('company.saleview'))
					<div id="tab-sales" class="detail-tab-panel active">
						<!-- 매출 등록 -->
						@if(auth()->user()->hasPerm('company.salecreate'))
						<div class="btnreg"><button type="button" id="btnSalesReg" name="btnSalesReg" class="btn btn-primary">매출 등록</button></div>
						@endif
						<!-- 매출 하단 목록 -->
						<div id="salesTableArea"></div>
					</div>
					@endif					

					<!-- 발전량 -->
					@if(auth()->user()->hasPerm('company.kwhview'))
					<div id="tab-kwh" class="detail-tab-panel">
						<div class="text-base mb-2">총 {{ $data->companykwh->count() }}개월</div>
						<div id="kwhTableArea">
						</div>
					</div>
					@endif	

					<!-- 목표 발전량 -->
					@if(auth()->user()->hasPerm('company.kwhview'))
					<div id="tab-yearkwh" class="detail-tab-panel">
						<div class="text-base mb-2">총 {{ $data->companyyearkwh->count() }}년차</div>
						<div id="yearkwhTableArea">
						</div>
					</div>
					@endif

					<!-- 목표 발전시간 -->
					@if(auth()->user()->hasPerm('company.kwhview'))
					<div id="tab-yearkwhhours" class="detail-tab-panel">
						<div class="text-base mb-2">총 {{ $data->companyyearkwhhours->count() }}개월</div>
						<div id="yearkwhhoursTableArea">
						</div>
					</div>					
					@endif

					<!-- 정기점검 -->
					@if(auth()->user()->hasPerm('company.kwhview'))
					<div id="tab-check" class="detail-tab-panel">
						<!-- 정기점검 등록 -->
						@if(auth()->user()->hasPerm('company.checkcreate') || auth()->user()->hasPerm('company.checkupdate')) 
						<div class="card-body">
							<input type="hidden" id="check_idx">
							<input type="hidden" id="check_type" name="check_type" value="정기 점검"/>

							<div class="om gap-2">
								<div class="col-md-2 w-40">
									<p class="mb-1">날짜</p>
									<div class="relative mx-auto">
										<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
											<i data-lucide="calendar" class="w-4 h-4"></i>
										</div>
										<input type="text" id="check_date" name="check_date" class="datepicker form-control pl-12" data-single-mode="true" value="{{$data->start_date}}">									
									</div>
								</div>

								<div class="col-md-3 w-35p">
									<p class="mb-1">점검 내용</p>
									<input type="text" id="check_content" name="check_content" class="form-control" placeholder="점검 내용을 입력해주세요">
								</div>
								<div class="col-md-3 w-35p">
									<p class="mb-1">점검 결과</p>
									<input type="text" id="check_result" name="check_result" class="form-control" placeholder="점검 결과를 입력해주세요">
								</div>
								<input type="hidden" id="check_count" name="check_count" class="form-control">

								<div class="col-md-1 d-flex gap-1">
									<button type="button" id="btnCheckSave" class="btn btn-primary">저장</button>
									<button type="button" id="btnCheckCancel" class="btn btn-secondary">취소</button>
								</div>
							</div>
						</div>
						@endif

						<!-- 정기점검 내역 -->
						<div id="checkTableArea"></div>
					</div>
					@endif

					<!-- 긴급출동 -->
					@if(auth()->user()->hasPerm('company.kwhview'))
					<div id="tab-emergency" class="detail-tab-panel">
						@if(auth()->user()->hasPerm('company.emergencycreate') || auth()->user()->hasPerm('company.emergencyupdate')) 
						<!-- 긴급출동 등록 -->
						<div class="card-body">
							<input type="hidden" id="emergency_idx">
							<input type="hidden" id="emergency_type" name="emergency_type" value="긴급 출동"/>

							<div class="om gap-2">
								<div class="col-md-2 w-40">
									<p class="mb-1">날짜</p>
									<div class="relative mx-auto">
										<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
											<i data-lucide="calendar" class="w-4 h-4"></i>
										</div>
										<input type="text" id="emergency_date" name="emergency_date" class="datepicker form-control pl-12" data-single-mode="true" value="{{$data->start_date}}">									
									</div>
								</div>

								<div class="col-md-3 w-35p">
									<p class="mb-1">출동 내용</p>
									<input type="text" id="emergency_content" name="emergency_content" class="form-control" placeholder="출동 내용을 입력해주세요">
								</div>
								<div class="col-md-3 w-35p">
									<p class="mb-1">출동 결과</p>
									<input type="text" id="emergency_result" name="emergency_result" class="form-control" placeholder="출동 결과를 입력해주세요">
								</div>
								<input type="hidden" id="emergency_count" name="emergency_count" class="form-control">

								<div class="col-md-1 d-flex gap-1">
									<button type="button" id="btnEmergencySave" class="btn btn-primary">저장</button>
									<button type="button" id="btnEmergencyCancel" class="btn btn-secondary">취소</button>
								</div>
							</div>
						</div>
						@endif

						<!-- 긴급출동 내역 -->
						<div id="emergencyTableArea"></div>
					</div>
					@endif
					
					<!-- 소모품 교체 -->
					@if(auth()->user()->hasPerm('company.kwhview'))
					<div id="tab-change" class="detail-tab-panel">
						<!-- 소모품 교체 등록 -->						
						@if(auth()->user()->hasPerm('company.changecreate') || auth()->user()->hasPerm('company.changeupdate')) 
						<div class="card-body">
							<input type="hidden" id="change_idx">
							<input type="hidden" id="change_type" name="change_type" value="소모품 교체"/>

							<div class="om gap-2">
								<div class="col-md-2 w-40">
									<p class="mb-1">날짜</p>
									<div class="relative mx-auto">
										<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
											<i data-lucide="calendar" class="w-4 h-4"></i>
										</div>
										<input type="text" id="change_date" name="change_date" class="datepicker form-control pl-12" data-single-mode="true" value="{{$data->start_date}}">									
									</div>
								</div>

								<div class="col-md-3 w-50p">
									<p class="mb-1">교체 내용</p>
									<input type="text" id="change_content" name="change_content" class="form-control" placeholder="교체 내용을 입력해주세요">
								</div>
								<input type="hidden" id="change_result" name="change_result" class="form-control">	
								<div class="col-md-3 w-15p">
									<p class="mb-1">교체 수량</p>
									<input type="number" id="change_count" name="change_count" class="form-control" pattern="\d*" inputmode="numeric" placeholder="숫자만 입력해주세요">
								</div>

								<div class="col-md-1 d-flex gap-1">
									<button type="button" id="btnChangeSave" class="btn btn-primary">저장</button>
									<button type="button" id="btnChangeCancel" class="btn btn-secondary">취소</button>
								</div>
							</div>
						</div>
						@endif

						<!-- 소모품 교체 내역 -->
						<div id="changeTableArea"></div>
					</div>					
					@endif

					<!-- 이슈 -->
					@if(auth()->user()->hasPerm('company.kwhview'))
					<div id="tab-issue" class="detail-tab-panel">
						<!-- 이슈 등록 -->
						@if(auth()->user()->hasPerm('company.issuecreate') || auth()->user()->hasPerm('company.issueupdate')) 
						<div class="card-body">
							<input type="hidden" id="issue_idx">
							<input type="hidden" id="issue_type" name="issue_type" value="이슈">

							<div class="om gap-2">
								<div class="col-md-2 w-40">
									<p class="mb-1">날짜</p>
									<div class="relative mx-auto">
										<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
											<i data-lucide="calendar" class="w-4 h-4"></i>
										</div>
										<input type="text" id="issue_date" name="issue_date" class="datepicker form-control pl-12" data-single-mode="true" value="{{$data->start_date}}">									
									</div>
								</div>

								<div class="col-md-5 w-70p">
									<p class="mb-1">이슈 내용</p>
									<input type="text" id="issue_content" name="issue_content" class="form-control" placeholder="이슈 내용을 입력해주세요">
								</div>
								<input type="hidden" id="issue_result" name="issue_result" class="form-control" >
								<input type="hidden" id="issue_count" name="issue_count" class="form-control">

								<div class="col-md-1 d-flex gap-1">
									<button type="button" id="btnIssueSave" class="btn btn-primary">저장</button>
									<button type="button" id="btnIssueCancel" class="btn btn-secondary">취소</button>
								</div>
							</div>
						</div>
						@endif

						<!-- 이슈 하단 목록 -->
						<div id="issueTableArea"></div>
					</div>
					@endif
				</div>
			</div>
		</div>
		@endif
	</div>



	<!-- 매출 모달 -->
	@if(auth()->user()->hasPerm('company.salecreate') || auth()->user()->hasPerm('company.saleupdate')) 
	<div id="company_sales-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">매출 저장</h2>
					<button onclick="modalClose('company_sales-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">발전 년도<span class="text-danger">*</span></p>
							<select id="sales_tgt_year" name="sales_tgt_year" class="form-select">
								<option value="">년도 선택</option>
								<option value="2026">2026</option>
								<option value="2025">2025</option>
								<option value="2024">2024</option>
							</select>
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">발전 월<span class="text-danger">*</span></p>
							<select id="sales_tgt_month" name="sales_tgt_month" class="form-select">
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
							<p class="shrink-0 w-32 font-medium ">매출 귀속<span class="text-danger">*</span></p>
						<input type="text" id="sales_supplier" name="sales_supplier" class="form-control" placeholder="매출 귀속을 입력해주세요">
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">공급 받는 자<span class="text-danger">*</span></p>
						<input type="text" id="sales_buyer" name="sales_buyer" class="form-control" placeholder="공급 받는 자를 입력해주세요">
						</div>
					</div>					
					<div class="flex items-center gap-10 mt-3">						
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">계산서 발행일<span class="text-danger">*</span></p>
							<div class="relative w-full">
								<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
									<i data-lucide="calendar" class="w-4 h-4"></i>
								</div>
								<input type="text" id="sales_day" name="sales_day" class="datepicker form-control pl-12" data-single-mode="true">
							</div>
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">매출 구분<span class="text-danger">*</span></p>
							<select id="sales_type" name="sales_type" class="form-select">
								<option value="REC" selected>REC</option>
								<option value="SMP">SMP</option>
								<option value="운영비">운영비</option>
							</select>
						</div>
					</div>	
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">계산서 품목명 </p>
						<input type="text" id="sales_title" name="sales_title" class="form-control" placeholder="계산서 품목명을 입력해주세요">
						</div>
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">수량 </p>
						<input type="text" id="sales_quantity" name="sales_quantity" class="form-control" placeholder="수량을 입력해주세요">
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">단가 </p>
						<input type="text" id="sales_unitprice" name="sales_unitprice" class="form-control" placeholder="단가를 입력해주세요">
						</div>						
					</div>	
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">매출<span class="text-danger">*</span></p>
						<input type="text" id="sales_sales" name="sales_sales" class="form-control" placeholder="매출을 입력해주세요">
						</div>
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">공급가 </p>
						<input type="text" id="sales_supplyprice" name="sales_supplyprice" class="form-control" placeholder="공급가를 입력해주세요">
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">부가세 </p>
						<input type="text" id="sales_vat" name="sales_vat" class="form-control" placeholder="부가세를 입력해주세요">
						</div>						
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">비고 </p>
						<input type="text" id="sales_memo" name="sales_memo" class="form-control" placeholder="비고를 입력해주세요">
						</div>
					</div>														
				</div>

				<input type="hidden" id="sales_idx" name="sales_idx" value="">

				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" id="btnSaleCancel" name="btnSaleCancel" class="btn btn-outline-secondary w-20 " onclick="modalClose('company_sales-modal')">취소</button>
					<button type="button" id="btnSalesSave" name="btnSalesSave"  class="btn btn-primary w-20" >저장</button>
				</div>
			</div>
		</div>
	</div>
	@endif

	<!-- 발전량 모달 -->
	@if(auth()->user()->hasPerm('company.kwhcreate') || auth()->user()->hasPerm('company.kwhupdate')) 
	<div id="company_kwh-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">발전량 저장</h2>
					<button onclick="modalClose('company_kwh-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<p class="mb-1 font-medium " id="kwhModalTitle"></p>	
				
					<div class="flex items-center gap-2 mt-3" id="ori_kwh_text">
						<p class="shrink-0 w-32 font-medium ">기존 발전량 </p>
						<input type="text" id="ori_kwh" name="ori_kwh" class="form-control" placeholder="발전소명을 입력해주세요" value="" readonly="readonly">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">발전량 <span class="text-danger">*</span></p>
						<input type="text" id="inputKwh" name="inputKwh" value="" class="form-control" placeholder="발전량을 입력해주세요">
					</div>

					<input type="hidden" id="kwh_idx">
					<input type="hidden" id="kwh_comp_idx">
					<input type="hidden" id="kwh_year">
					<input type="hidden" id="kwh_month">
				</div>
				
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20 " onclick="modalClose('company_kwh-modal')">취소</button>
					<button type="button" class="btn btn-primary w-20" onclick="savekwh()">저장</button>
					<button type="button" id="btnDeleteGroup" class="btn btn-outline-danger w-20" onclick="deletekwh()">삭제</button>
				</div>
			</div>
		</div>
	</div>
	@endif

	<!-- 목표 발전량 모달 -->
	@if(auth()->user()->hasPerm('company.yearkwhcreate') || auth()->user()->hasPerm('company.yearkwhupdate')) 
	<div id="company_yearkwh-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">목표 발전량 저장</h2>
					<button onclick="modalClose('company_yearkwh-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<p class="mb-1 font-medium " id="yearkwhModalTitle"></p>	
				
					<div class="flex items-center gap-2 mt-3" id="ori_kwh_text">
						<p class="shrink-0 w-32 font-medium ">기존 목표 발전량 </p>
						<input type="text" id="ori_yearkwh" name="ori_yearkwh" class="form-control" value="" readonly="readonly">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">목표 발전량 <span class="text-danger">*</span></p>
						<input type="text" id="inputyearKwh" name="inputyearKwh" value="" class="form-control" placeholder="발전량을 입력해주세요">
					</div>

					<input type="hidden" id="yearkwh_idx">
					<input type="hidden" id="yearkwh_comp_idx">
					<input type="hidden" id="yearkwh_period">
					<input type="hidden" id="yearkwh_start_year">
				</div>
		
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20 " onclick="modalClose('company_yearkwh-modal')">취소</button>
					<button type="button" class="btn btn-primary w-20" onclick="saveyearkwh()">저장</button>
					<button type="button" id="btnDeleteGroup" class="btn btn-outline-danger w-20" onclick="deleteyearkwh()">삭제</button>
				</div>
			</div>
		</div>
	</div>
	@endif

	<!-- 목표 발전 시간 모달 -->
	@if(auth()->user()->hasPerm('company.yearkwhhourscreate') || auth()->user()->hasPerm('company.yearkwhhoursupdate')) 
	<div id="company_yearkwhhours-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">발전량 저장</h2>
					<button onclick="modalClose('company_yearkwhhours-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<p class="mb-1 font-medium " id="yearkwhhoursModalTitle"></p>	
				
					<div class="flex items-center gap-2 mt-3" id="ori_yearkwhhours_text">
						<p class="shrink-0 w-32 font-medium ">기존 목표 발전시간 </p>
						<input type="text" id="ori_yearkwhhours" name="ori_yearkwhhours" class="form-control" placeholder="발전소명을 입력해주세요" value="" readonly="readonly">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">목표 발전시간 <span class="text-danger">*</span></p>
						<input type="text" id="inputyearkwhhours" name="inputyearkwhhours" value="" class="form-control" placeholder="발전량을 입력해주세요">
					</div>

					<input type="hidden" id="yearkwhhours_idx">
					<input type="hidden" id="yearkwhhours_comp_idx">
					<input type="hidden" id="yearkwhhours_year">
					<input type="hidden" id="yearkwhhours_month">
				</div>
				
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20 " onclick="modalClose('company_yearkwhhours-modal')">취소</button>
					<button type="button" class="btn btn-primary w-20" onclick="saveyearkwhhours()">저장</button>
					<button type="button" id="btnDeleteGroup" class="btn btn-outline-danger w-20" onclick="deleteyearkwhhours()">삭제</button>
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
$(document).on('click', '.detail-tab-btn', function () {
    const tabId = $(this).data('tab');

    $('.detail-tab-btn').removeClass('active text-primary');
	$(this).addClass('active text-primary');

    $('.detail-tab-panel').removeClass('active').hide();
    $('#' + tabId).addClass('active').show();
});

document.addEventListener('DOMContentLoaded', function () {
    let reqRegion  = "{{ $data->regions_idx }}";
    let reqCity    = "{{ $data->cities_idx }}";

	// 지역이 선택된 상태라면 → 도시 목록 자동 로드 + select 유지
    if (reqRegion) {
		const $citySelect = $('#modify_cities_idx');
		$citySelect.empty().append('<option value="">도시를 선택해주세요</option>');

		$.ajax({
			url: '/admin/cities/' + reqRegion,
			method: 'GET',
			success: function (cities) {
				cities.forEach(function (city) {
					$citySelect.append(
						`<option value="${city.idx}">${city.name_kr}</option>`
					);
				});

				if (reqCity) {
					$citySelect.val(reqCity);
				}
			},
			error: function (xhr) {
				console.error(xhr.responseText);
				showToast('도시 목록을 불러오지 못했습니다.','error');
			}
		});
    }

	// 현황 표시
	//setSummaryTable(@json($data), @json($sales));

	// 매출 표시
	setSalesTable(@json($sales));

	// 발전량 표시
	setKwhTable(@json($data), @json($kwh));
	
	// 목표 발전량 표시
	setYearKwhTable(@json($data), @json($yearkwh));
	
	// 목표 발전시간 표시
	setYearKwhHoursTable(@json($data), @json($yearkwhhours));
	
	// 정기점검 표시
	setCheckTable(@json($ckeck));

	// 긴급출동 표시
	setEmergencyTable(@json($emergency));

	// 소모품교체 표시
	setChangeTable(@json($change));

	// 이슈 표시
	setIssueTable(@json($issue));
});

function modify() {
	const idx			= $('#modify_idx').val();
	const comp_name		= $('#modify_comp_name').val().trim();
	const regions_idx	= $('#modify_regions_idx').val();
	const cities_idx	= $('#modify_cities_idx').val();
	const volume		= toNumber($('#modify_volume').val().trim());
	const start_date	= $('#modify_start_date').val();
	const kma_idx		= $('#modify_kma_idx').val();
	const group_idx		= $('#modify_group_idx').val();
	const tgt_kwh		= $('#modify_tgt_kwh').val().trim();
	const tgt_rate		= $('#modify_tgt_rate').val().trim();

  	if (!comp_name)		return showToast('발전소명을 입력해주세요.','error');
  	if (!regions_idx)	return showToast('지역(도/시)을 선택해주세요.','error');
  	if (!cities_idx)	return showToast('도시(시/구/군) 선택해주세요.','error');
  	if (!volume)		return showToast('용량을 입력해주세요.','error');
  	// if (!start_date)	return showToast('상업운전 개시일을 선택해주세요.','error');
  	if (!kma_idx)		return showToast('일사량 관측소를 선택해주세요.','error');
  	// if (!group_idx)		return showToast('소속 SPC를 선택해주세요.','error');
  	// if (!tgt_kwh)		return showToast('년간 목표 발전 시간을 입력해주세요.','error');
  	// if (!tgt_rate)		return showToast('년간 목표 발전 효율을 입력해주세요.','error');

	const fd = new FormData();
	fd.append('comp_name', comp_name);
	fd.append('regions_idx', regions_idx);
	fd.append('cities_idx', cities_idx);
	fd.append('volume', volume);
	fd.append('kma_idx', kma_idx);
	fd.append('start_date', start_date);
	fd.append('group_idx', group_idx);
	fd.append('tgt_kwh', tgt_kwh);
	fd.append('tgt_rate', tgt_rate);
	
	if (!confirm('발전소 정보를 수정하시겠습니까?|' + idx + '|')) return;

    $.ajax({
    	url: '/admin/company/modify/'+idx,
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('발전소가 수정되었습니다.','success');
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

function deleteCompany() {
	const idx		= $('#modify_idx').val();
  	if (!idx)		return showToast('오류가 발생하였습니다.','error');
	
	if (!confirm('정말로 발전소를 삭제하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/company/delete/'+idx,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('발전소가 삭제되었습니다.','success');
                location.href = '/admin/company';
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


/*******************************  현황  *******************************/
// 현황 세팅
function setSummaryTable(data, sales) {
	console.log(data);
	console.log(sales);
	let html = `<table class="table">
			<thead>
				<tr>
					<th>년도</th>
					<th>구분</th>`;
					// 1~12월 header
					for(let m=1; m<=12; m++){ html += `<th>${m}월</th>`; }
	html += `</tr></thead><tbody>`;

	if(sales && Object.keys(sales).length > 0){
		// year 목록 추출
		let years = Object.keys(sales).map(year => Number(year));

		// 마지막 year + 1 추가
		const maxYear = Math.max(...years);
		years.push(maxYear + 1);

		years.forEach(function(year){
			const items = sales[year] || [];
			html += `<tr>
					<td rowspan="5">${year}</td>
						<td>SMP</td>`;
			for(let month=1; month<=12; month++){
				const found = items.find(i => Number(i.month) === month);
				html += `<td class="sales-cell" 
						data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
						${smprec ? smprec : ''}
					</td>`;
			}
			html += `</tr>`;
			
			html += `<tr>
						<td>REC</td>`;
			for(let month=1; month<=12; month++){
				const found = items.find(i => Number(i.month) === month);
				html += `<td class="sales-cell" 
						data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
						${found ? Number(found.quantity).toLocaleString() : ''}
					</td>`;
			}
			html += `</tr>`;
			
			html += `<tr>
						<td>운영비</td>`;
			for(let month=1; month<=12; month++){
				const found = items.find(i => Number(i.month) === month);
				html += `<td class="sales-cell" 
						data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
						${found ? Number(found.unitprice).toLocaleString() : ''}
					</td>`;
			}
			html += `</tr>`;
		});

		$('#btnDeleteGroup').hide();
	}else{
		const currentYear = new Date().getFullYear();

		for (let year = currentYear; year <= currentYear + 1; year++) {
			html += `<tr>`;
			html += `<td>${year}</td>
					<td></td>`;

			for (let month = 1; month <= 12; month++) {
				html += `
					<td class="sales-cell"
						data-idx="" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="" data-quantity="" data-unitprice="" data-sales="" data-supplyprice="" data-vat="">						
					</td>
				`;
			}

			html += `</tr>`;
		}
	}
	html += `</tbody></table>`;

	$('#summaryTableArea').html(html);
}


// 현황 변경사항 반영
function renderSummaryTable(idx) {
    $.ajax({
    	url: '/admin/company/sales/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				
				let html = `<table class="table">
						<thead>
							<tr>
								<th>년도</th>
								<th>구분</th>`;
								// 1~12월 header
								for(let m=1; m<=12; m++){ html += `<th>${m}월</th>`; }
				html += `</tr></thead><tbody>`;

				if(res.sales && Object.keys(res.sales).length > 0){
					// year 목록 추출
					let years = Object.keys(res.sales).map(year => Number(year));

					// 마지막 year + 1 추가
					const maxYear = Math.max(...years);
					years.push(maxYear + 1);

					years.forEach(function(year){
						const items = res.sales[year] || [];
						html += `<tr>
								<td rowspan="5">${year}</td>
									<td>SMP/REC</td>`;
						for(let month=1; month<=12; month++){
							const found = items.find(i => Number(i.month) === month);
							html += `<td class="sales-cell" 
									data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
									${smprec ? smprec : ''}
								</td>`;
						}
						html += `</tr>`;
						
						html += `<tr>
									<td>수량</td>`;
						for(let month=1; month<=12; month++){
							const found = items.find(i => Number(i.month) === month);
							html += `<td class="sales-cell" 
									data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
									${found ? Number(found.quantity).toLocaleString() : ''}
								</td>`;
						}
						html += `</tr>`;
						
						html += `<tr>
									<td>단가</td>`;
						for(let month=1; month<=12; month++){
							const found = items.find(i => Number(i.month) === month);
							html += `<td class="sales-cell" 
									data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
									${found ? Number(found.unitprice).toLocaleString() : ''}
								</td>`;
						}
						html += `</tr>`;
						
						html += `<tr>
									<td>매출</td>`;
						for(let month=1; month<=12; month++){
							const found = items.find(i => Number(i.month) === month);
							html += `<td class="sales-cell" 
									data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
									${found ? Number(found.sales).toLocaleString() : ''}
								</td>`;
						}
						html += `</tr>`;
						
						html += `<tr>
									<td>공급가</td>`;
						for(let month=1; month<=12; month++){
							const found = items.find(i => Number(i.month) === month);
							html += `<td class="sales-cell" 
									data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
									${found ? Number(found.supplyprice).toLocaleString() : ''}
								</td>`;
						}
						html += `</tr>`;
						
						html += `<tr>
									<td>부가세</td>`;
						for(let month=1; month<=12; month++){
							const found = items.find(i => Number(i.month) === month);
							html += `<td class="sales-cell" 
									data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
									${found ? Number(found.vat).toLocaleString() : ''}
								</td>`;
						}
						html += `</tr>`;			
					});

					$('#btnDeleteGroup').hide();


				}else{
					const currentYear = new Date().getFullYear();

					for (let year = currentYear; year <= currentYear + 1; year++) {
						html += `<tr>`;
						html += `<td>${year}</td>`;

						for (let month = 1; month <= 12; month++) {
							html += `
								<td class="kwh-cell"
									data-idx="" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">						
								</td>
							`;
						}

						html += `</tr>`;
					}
				}
				html += `</tbody></table>`;

				$('#summaryTableArea').html(html);
			
            } else {
            	showToast(res.message || '불러오기 실패','error');
                window.location.reload();
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}


/*******************************  매출  *******************************/
// 매출, 공급가, 부가세 자동 계산
document.addEventListener('DOMContentLoaded', function () {
  const quantityEl = document.getElementById('sales_quantity');
  const unitPriceEl = document.getElementById('sales_unitprice');
  const salesEl = document.getElementById('sales_sales');
  const supplyPriceEl = document.getElementById('sales_supplyprice');
  const vatEl = document.getElementById('sales_vat');

  function updateSalesFromQtyPrice() {
    const quantity = toNumber(quantityEl?.value);
    const unitPrice = toNumber(unitPriceEl?.value);
    const sales = quantity * unitPrice;

    if (salesEl) {
      salesEl.value =  Number(Math.round(formatNumber(sales))).toLocaleString();
    }

	quantityEl.value = Number(quantity).toLocaleString();
    unitPriceEl.value = Number(unitPrice).toLocaleString();

	updateSupplyAndVatFromSales();
  }

  function updateSupplyAndVatFromSales() {
    const sales = toNumber(salesEl?.value);

    const supplyPrice = Math.round(sales / 1.1);
    const vat = sales - supplyPrice;

    if (supplyPriceEl) {
      supplyPriceEl.value = Number(Math.round(formatNumber(supplyPrice))).toLocaleString();
    }

    if (vatEl) {
      vatEl.value = Number(Math.round(formatNumber(vat))).toLocaleString();
    }

	salesEl.value = Number(sales).toLocaleString();
  }

  function updateVatFromSupply() {
	const sales = toNumber(salesEl?.value);
    const supplyPrice = toNumber(supplyPriceEl?.value);

    const vat = sales - supplyPrice;

    if (vatEl) {
      vatEl.value = Number(Math.round(formatNumber(vat))).toLocaleString();
    }

	supplyPriceEl.value = Number(supplyPrice).toLocaleString();
  }

  if (quantityEl) {
    quantityEl.addEventListener('input', updateSalesFromQtyPrice);
    quantityEl.addEventListener('change', updateSalesFromQtyPrice);
  }

  if (unitPriceEl) {
    unitPriceEl.addEventListener('input', updateSalesFromQtyPrice);
    unitPriceEl.addEventListener('change', updateSalesFromQtyPrice);
  }

  if (salesEl) {
    salesEl.addEventListener('input', updateSupplyAndVatFromSales);
    salesEl.addEventListener('change', updateSupplyAndVatFromSales);
  }

  if (supplyPriceEl) {
    supplyPriceEl.addEventListener('input', updateVatFromSupply);
    supplyPriceEl.addEventListener('change', updateVatFromSupply);
  }

  // 페이지 로드시 1회 계산
  updateSalesFromQtyPrice();
});

// 매출 등록 모달
$(document).on('click', '#btnSalesReg', function () {
	const idx = $(this).data('idx') || '';
	const now = new Date(); 

	$('#sales_idx').val(idx);
	$('#sales_tgt_year').val('');
	$('#sales_tgt_month').val('');
	$('#sales_supplier').val('');
	$('#sales_buyer').val('');
	$('#sales_type').val('REC');
	$('#sales_title').val('');	
	$('#sales_quantity').val('0');
	$('#sales_unitprice').val('0');
	$('#sales_sales').val('0');
	$('#sales_supplyprice').val('0');
	$('#sales_vat').val('0');
	$('#sales_memo').val('');

    if (idx) {
        // 수정 모드
        $('#btnSalesDelete').show();
    } else {
        // 등록 모드
        $('#btnSalesDelete').hide();
    }

    modalOpen2('company_sales-modal');
});

// 매출 수정 모달
$(document).on('click', '.btn-sales-edit', function () {
	const idx = $(this).data('idx') || '';
	const tgt_year = $(this).data('tgt_year') || '';
	const tgt_month = $(this).data('tgt_month') || '';
	const supplier = $(this).data('supplier') || '';
	const buyer = $(this).data('buyer') || '';
	const day = $(this).data('day') || '';
	const type = $(this).data('type') || '';
	const title = $(this).data('title') || '';
	const quantity = $(this).data('quantity') || '';
	const unitprice = $(this).data('unitprice') || '';
	const sales = $(this).data('sales') || '';
	const supplyprice = $(this).data('supplyprice') || '';
	const vat = $(this).data('vat') || '';
	const memo = $(this).data('memo') || '';

	$('#sales_idx').val(idx);
	$('#sales_tgt_year').val(tgt_year);
	$('#sales_tgt_month').val(tgt_month);
	$('#sales_supplier').val(supplier);
	$('#sales_buyer').val(buyer);
	$('#sales_day').val(day);
	$('#sales_type').val(type);
	$('#sales_title').val(title);	
	$('#sales_quantity').val(quantity);
	$('#sales_unitprice').val(unitprice);
	$('#sales_sales').val(sales);
	$('#sales_supplyprice').val(supplyprice);
	$('#sales_vat').val(vat);
	$('#sales_memo').val(memo);

    if (idx) {
        // 수정 모드
        $('#btnSalesDelete').show();
    } else {
        // 등록 모드
        $('#btnSalesDelete').hide();
    }

    modalOpen2('company_sales-modal');
});


// 매출 저장
$(document).on('click', '#btnSalesSave', function () {
	if (!$('#sales_day').val())		return showToast('계산서 발행일를 선택해주세요.','error');
	if (!$('#sales_supplier').val())		return showToast('매출귀속을 입력해주세요.','error');
	if (!$('#sales_buyer').val())		return showToast('공급 받는 자를 입력해주세요.','error');
	if (!$('#sales_type').val())	return showToast('매출구분을 선택해주세요.','error');
	if (!$('#sales_tgt_year').val() || $('#sales_tgt_year').val() === '')	return showToast('발전 년도를 선택해주세요.','error');
	if (!$('#sales_tgt_month').val() || $('#sales_tgt_month').val() === '')	return showToast('발전 월을 선택해주세요.','error');
	if (!$('#sales_sales').val())	return showToast('매출을 입력해주세요.','error');

    $.ajax({
        url: '/admin/company/sales/save',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
			idx: $('#sales_idx').val(),
            comp_idx: "{{ $data->idx }}",
			tgt_year: $('#sales_tgt_year').val(),
           	tgt_month: $('#sales_tgt_month').val(),
			supplier: $('#sales_supplier').val(),
			buyer: $('#sales_buyer').val(),
			day: $('#sales_day').val(),
			type: $('#sales_type').val(),
			title: $('#sales_title').val(),
           	quantity: String($('#sales_quantity').val()).replace(/,/g, '').trim(),
           	unitprice: String($('#sales_unitprice').val()).replace(/,/g, '').trim(),
           	sales: String($('#sales_sales').val()).replace(/,/g, '').trim(),
           	supplyprice: String($('#sales_supplyprice').val()).replace(/,/g, '').trim(),
           	vat: String($('#sales_vat').val()).replace(/,/g, '').trim(),
			memo: $('#sales_memo').val()
        },
        success: function (res) {
            if (res.status === 'success') {
				showToast('매출이 등록되었습니다.', 'success');
                modalClose('company_sales-modal');
				renderSalesTable("{{ $data->idx }}");
            } else {
                showToast('오류가 발생했습니다.','error');
            }
        }
    });
});

// 매출 삭제
$(document).on('click', '.btn-sales-delete', function () {
    const idx = $(this).data('idx');
	
    if (!confirm('삭제하시겠습니까?')) return;

    $.ajax({
        url: '/admin/company/sales/delete',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            idx: idx,
            comp_idx: "{{ $data->idx }}"
        },
        success: function (res) {
            if (res.status === 'success') {
				showToast('매출이 삭제되었습니다.', 'success');
                renderSalesTable("{{ $data->idx }}");
            } else {
                showToast('오류가 발생했습니다.','error');
            }
        }
    });
});


// 현재 사용자의 매출 수정/삭제 권한 선언
const CAN_SALE_UPDATE = {{ auth()->user()->hasPerm('company.saleupdate') ? 'true' : 'false' }};
const CAN_SALE_DELETE = {{ auth()->user()->hasPerm('company.saledelete') ? 'true' : 'false' }};


// 매출 세팅
function setSalesTable(salesList) {
	const showActionHeader = CAN_SALE_UPDATE || CAN_SALE_DELETE;	
	
	let html = `
		<div class="text-base mt-2 mb-2">매출 내역</div>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
					<th>발전 년월</th>
					<th>매출 귀속</th>
					<th>공급 받는 자</th>
                    <th>계산서 발행일</th>
                    <th>구분</th>
					<th>계산서 품목명</th>
					<th>수량</th>
					<th>단가</th>
                    <th>매출</th>
					<th>공급가</th>
					<th>부가세</th>
					<th>비고</th>
					${showActionHeader ? '<th>수정·삭제</th>' : ''}
				</tr>
            </thead>
            <tbody>
    `;

    if (salesList && salesList.length > 0) {
        salesList.forEach(function(item, index) {
            html += `
                <tr>
					<td>${item.tgt_year ?? ''}-${item.tgt_month ?? ''}</td>
					<td>${item.supplier ?? ''}</td>
					<td>${item.buyer ?? ''}</td>
                    <td>${item.day}</td>
                    <td>${item.type ?? ''}</td>
                    <td>${item.title ?? ''}</td>
					<td>${Number(item.quantity).toLocaleString() ?? ''}</td>
					<td>${Number(item.unitprice).toLocaleString() ?? ''}</td>
					<td>${Number(item.sales).toLocaleString() ?? ''}</td>
					<td>${Number(item.supplyprice).toLocaleString() ?? ''}</td>
					<td>${Number(item.vat).toLocaleString() ?? ''}</td>
					<td>${item.memo ?? ''}</td>	

					${showActionHeader ? `
                        <td>
                            ${CAN_SALE_UPDATE ? `
                               <button type="button"
									class="btn btn-sm btn-outline-primary btn-sales-edit"
									data-idx="${item.idx}"
									data-tgt_year="${item.tgt_year ?? ''}"
									data-tgt_month="${item.tgt_month ?? ''}"
									data-supplier="${item.supplier ?? ''}"
									data-buyer="${item.buyer ?? ''}"
									data-day="${item.day ?? ''}"
									data-type="${item.type ?? ''}"
									data-title="${item.title ?? ''}"							
									data-quantity="${item.quantity ?? ''}"
									data-unitprice="${item.unitprice ?? ''}"
									data-sales="${item.sales ?? ''}"
									data-supplyprice="${item.supplyprice ?? ''}"
									data-vat="${item.vat ?? ''}"
									data-memo="${item.memo ?? ''}">
									수정
								</button>
                            ` : ''}
                            ${CAN_SALE_DELETE ? `
                                	<button type="button"
										class="btn btn-sm btn-outline-danger btn-sales-delete"
										data-idx="${item.idx}">
										삭제
									</button>
                            ` : ''}
                        </td>
                    ` : ''}
				</tr>
            `;
        });
    } else {
        html += `<tr><td colspan="13">매출 내역이 없습니다.</td></tr>`;
    }

    html += `</tbody></table>`;
    $('#salesTableArea').html(html);
}

// 매출 수정사항 반영
function renderSalesTable(idx) {
	const showActionHeader = CAN_SALE_UPDATE || CAN_SALE_DELETE;
	
    $.ajax({
    	url: '/admin/company/sales/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				const salesList = res.sales;

				let html = `
					<div class="text-base mt-2 mb-2">매출 내역</div>
					<table class="table table-bordered text-center">
						<thead>
							<tr>
								<th>발전 년월</th>
								<th>매출 귀속</th>
								<th>공급 받는 자</th>
								<th>계산서 발행일</th>
								<th>구분</th>
								<th>계산서 품목명</th>
								<th>수량</th>
								<th>단가</th>
								<th>매출</th>
								<th>공급가</th>
								<th>부가세</th>
								<th>비고</th>
								<th>수정·삭제</th>
							</tr>
						</thead>
						<tbody>
				`;

				if (salesList && salesList.length > 0) {
					salesList.forEach(function(item, index) {
						html += `
							<tr>
								<td>${item.tgt_year ?? ''}-${item.tgt_month ?? ''}</td>
								<td>${item.supplier ?? ''}</td>
								<td>${item.buyer ?? ''}</td>
								<td>${item.day}</td>
								<td>${item.type ?? ''}</td>
								<td>${item.title ?? ''}</td>								
								<td>${Number(item.quantity).toLocaleString() ?? ''}</td>
								<td>${Number(item.unitprice).toLocaleString() ?? ''}</td>
								<td>${Number(item.sales).toLocaleString() ?? ''}</td>
								<td>${Number(item.supplyprice).toLocaleString() ?? ''}</td>
								<td>${Number(item.vat).toLocaleString() ?? ''}</td>
								<td>${item.memo ?? ''}</td>
								${showActionHeader ? `
								<td>
									${CAN_SALE_UPDATE ? `
									<button type="button"
											class="btn btn-sm btn-outline-primary btn-sales-edit"
											data-idx="${item.idx}"
											data-tgt_year="${item.tgt_year ?? ''}"
											data-tgt_month="${item.tgt_month ?? ''}"
											data-supplier="${item.supplier ?? ''}"
											data-buyer="${item.buyer ?? ''}"
											data-day="${item.day ?? ''}"
											data-type="${item.type ?? ''}"
											data-title="${item.title ?? ''}"							
											data-quantity="${item.quantity ?? ''}"
											data-unitprice="${item.unitprice ?? ''}"
											data-sales="${item.sales ?? ''}"
											data-supplyprice="${item.supplyprice ?? ''}"
											data-vat="${item.vat ?? ''}"
											data-memo="${item.memo ?? ''}">
											수정
										</button>
									` : ''}
									${CAN_SALE_DELETE ? `
											<button type="button"
												class="btn btn-sm btn-outline-danger btn-sales-delete"
												data-idx="${item.idx}">
												삭제
											</button>
									` : ''}
								</td>
							` : ''}
							</tr>
						`;
					});
				} else {
					html += `<tr><td colspan="13">매출 내역이 없습니다.</td></tr>`;
				}

				html += `</tbody></table>`;
				$('#salesTableArea').html(html);

            } else {
            	showToast(res.message || '불러오기 실패','error');
                window.location.reload();
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}



/*******************************  발전량  *******************************/
// 수정 모달에서 TD를 클릭해서 발전량 등록,수정 모달 표시
$(document).on('click', '.kwh-cell', function () {
    const idx = $(this).data('idx') || '';
    const compIdx = $(this).data('comp') || '';
    const compName = $(this).data('comp-name') || '';
    const year = $(this).data('year') || '';
    const month = $(this).data('month') || '';
    const kwh = $(this).data('kwh') || '';

    $('#kwh_idx').val(idx);
    $('#kwh_comp_idx').val(compIdx);
    $('#kwh_year').val(year);
    $('#kwh_month').val(month);

    $('#kwhModalTitle').text(`${compName} / ${year}년 ${month}월`);

	if(kwh) {
		$('#ori_kwh_text').show();
		$('#ori_kwh').val(Number(kwh).toLocaleString());
		$('#inputKwh').val('');
	} else {
		$('#ori_kwh_text').hide();
		$('#ori_kwh').val('');
		$('#inputKwh').val('');
	}

    if (idx) {
        // 수정 모드
        $('#btnKwhDelete').show();
    } else {
        // 등록 모드
        $('#btnKwhDelete').hide();
    }

    modalOpen2('company_kwh-modal');
});

// 발전량 저장
function savekwh() {
    const idx 		= $('#kwh_idx').val();
    const comp_idx 	= $('#kwh_comp_idx').val();
    const year 		= $('#kwh_year').val();
    const month 	= $('#kwh_month').val();
    const kwh 		= $('#inputKwh').val();

    if (kwh === '') {
		showToast('발전량을 입력해주세요.','error');
        return;
    }

	const fd = new FormData();
	fd.append('idx', idx);
	fd.append('comp_idx', comp_idx);
	fd.append('year', year);
	fd.append('month', month);
	fd.append('kwh', kwh);

    $.ajax({
        url: '/admin/company/kwh/save',
        type: 'POST',
        data: fd,
		processData: false,
        contentType: false,
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
			 if (res.status) {
            	showToast('발전량이 저장되었습니다.','success');
            	modalClose('company_kwh-modal');

				// 발전량 변경사항 반영
				renderKwhTable(comp_idx);
            } else {
            	showToast(res.message || '저장 실패','error');
            }
        },
        error: function () {
            showToast('오류가 발생했습니다.','error');  
        }
    });
}

// 발전량 삭제
function deletekwh() {
    const idx = $('#kwh_idx').val();
	const comp_idx 	= $('#kwh_comp_idx').val();

    if (!idx) return;

    if (!confirm('해당 발전량 데이터를 삭제하시겠습니까?')) {
        return;
    }

	const fd = new FormData();
	fd.append('idx', idx);	

    $.ajax({
        url: '/admin/company/kwh/delete',
        type: 'POST',
        data: fd,
		processData: false,
        contentType: false,
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },		
        success: function (res) {
			 if (res.status) {
            	showToast('발전량이 삭제되었습니다.','success');
            	modalClose('company_kwh-modal');

				// 발전량 변경사항 반영
				renderKwhTable(comp_idx);
            } else {
            	showToast(res.message || '삭제 실패','error');
            }
        },
        error: function () {
            showToast('오류가 발생했습니다.','error');
        }
    });
}


// 발전량 변경사항 반영
function setKwhTable(data, kwh) {

	let html = `<table class="table">
			<thead>
				<tr>
					<th>년도</th>`;
					// 1~12월 header
					for(let m=1; m<=12; m++){ html += `<th>${m}월</th>`; }
	html += `</tr></thead><tbody>`;

	if(kwh && Object.keys(kwh).length > 0){
		// year 목록 추출
		let years = Object.keys(kwh).map(year => Number(year));

		// 마지막 year + 1 추가
		const maxYear = Math.max(...years);
		years.push(maxYear + 1);

		years.forEach(function(year){
			const items = kwh[year] || [];
			html += `<tr>`;
			html += `<td>${year}</td>`;

			// 1~12월 반복
			for(let month=1; month<=12; month++){
				const found = items.find(i => Number(i.month) === month);
				html += `<td class="kwh-cell" 
						data-idx="${found ? found.idx : ''}" 
						data-comp-name="${ data.comp_name }" 
						data-comp="${ data.idx }" 
						data-year="${year}" 
						data-month="${month}"
						data-kwh="${found ? Number(found.act_kwh) : ''}">
						${found ? Number(found.act_kwh).toLocaleString() : ''}
					</td>`;
			}

			html += `</tr>`;
		});

		$('#btnDeleteGroup').hide();
	}else{
		const currentYear = new Date().getFullYear();

		for (let year = currentYear; year <= currentYear + 1; year++) {
			html += `<tr>`;
			html += `<td>${year}</td>`;

			for (let month = 1; month <= 12; month++) {
				html += `
					<td class="kwh-cell"
						data-idx=""
						data-comp-name="${data.comp_name}"
						data-comp="${data.idx}"
						data-year="${year}"
						data-month="${month}"
						data-kwh="">
					</td>
				`;
			}

			html += `</tr>`;
		}
	}
	html += `</tbody></table>`;

	$('#kwhTableArea').html(html);
}


// 발전량 변경사항 반영
function renderKwhTable(idx) {
    $.ajax({
    	url: '/admin/company/kwh/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				// kwh 랜더링
				let html = `<table class="table">
						<thead>
							<tr>
								<th>년도</th>`;
								// 1~12월 header
								for(let m=1; m<=12; m++){ html += `<th>${m}월</th>`; }
				html += `</tr></thead><tbody>`;

				if(res.kwh && Object.keys(res.kwh).length > 0){
					// year 목록 추출
					let years = Object.keys(res.kwh).map(year => Number(year));

					// 마지막 year + 1 추가
					const maxYear = Math.max(...years);
					years.push(maxYear + 1);

					years.forEach(function(year){
						const items = res.kwh[year] || [];
						html += `<tr>`;
						html += `<td>${year}</td>`;

						// 1~12월 반복
						for(let month=1; month<=12; month++){
							const found = items.find(i => Number(i.month) === month);
							html += `<td class="kwh-cell" 
									data-idx="${found ? found.idx : ''}" 
									data-comp-name="${res.data.comp_name}"
									data-comp="${res.data.idx}" 
									data-year="${year}" 
									data-month="${month}"
									data-kwh="${found ? Number(found.act_kwh) : ''}">
									${found ? Number(found.act_kwh).toLocaleString() : ''}
								</td>`;
						}

						html += `</tr>`;
					});

					$('#btnDeleteGroup').hide();
				}else{
					const currentYear = new Date().getFullYear();

					for (let year = currentYear; year <= currentYear + 1; year++) {
						html += `<tr>`;
						html += `<td>${year}</td>`;

						for (let month = 1; month <= 12; month++) {
							html += `
								<td class="kwh-cell"
									data-idx=""
									data-comp-name="${res.data.comp_name}"
									data-comp="${res.data.idx}"
									data-year="${year}"
									data-month="${month}"
									data-kwh="">
								</td>
							`;
						}

						html += `</tr>`;
					}
				}
				html += `</tbody></table>`;

				$('#kwhTableArea').html(html);

            } else {
            	showToast(res.message || '불러오기 실패','error');
                window.location.reload();
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}


/*******************************  목표 발전량  *******************************/
// 수정 모달에서 TD를 클릭해서 목표 발전량 등록,수정 모달 표시
$(document).on('click', '.yearkwh-cell', function () {
    const idx = $(this).data('idx') || '';
    const compIdx = $(this).data('comp') || '';
    const compName = $(this).data('comp-name') || '';
    const period = $(this).data('period') || '';
	const start_year = $(this).data('start_year') || '';
    const year_kwh = $(this).data('year_kwh') || '';

    $('#yearkwh_idx').val(idx);
    $('#yearkwh_comp_idx').val(compIdx);
    $('#yearkwh_period').val(period);
	$('#yearkwh_start_year').val(start_year);
    $('#yearkwhModalTitle').text(`${compName} / 년차: ${period} / 시작월: ${start_year}`);

	if(year_kwh) {
		$('#ori_yearkwh_text').show();
		$('#ori_yearkwh').val(Number(year_kwh).toLocaleString());
		$('#inputyearKwh').val('');
	} else {
		$('#ori_yearkwh_text').hide();
		$('#ori_yearkwh').val('');
		$('#inputyearKwh').val('');
	}

    if (idx) {
        // 수정 모드
        $('#btnyearKwhDelete').show();
    } else {
        // 등록 모드
        $('#btnyearKwhDelete').hide();
    }

    modalOpen2('company_yearkwh-modal');
});

// 목표 발전량 저장
function saveyearkwh() {
    const idx 		= $('#yearkwh_idx').val();
    const comp_idx 	= $('#yearkwh_comp_idx').val();
    const period 		= $('#yearkwh_period').val();
	const start_year 		= $('#yearkwh_start_year').val();
    const year_kwh 		= $('#inputyearKwh').val();

    if (year_kwh === '') {
		showToast('목표 발전량을 입력해주세요.','error');
        return;
    }

	const fd = new FormData();
	fd.append('idx', idx);
	fd.append('comp_idx', comp_idx);
	fd.append('period', period);
	fd.append('start_year', start_year);
	fd.append('year_kwh', year_kwh);

    $.ajax({
        url: '/admin/company/yearkwh/save',
        type: 'POST',
        data: fd,
		processData: false,
        contentType: false,
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
			 if (res.status) {
            	showToast('목표 발전량이 저장되었습니다.','success');
            	modalClose('company_yearkwh-modal');

				// 목표 발전량 변경사항 반영
				renderyearKwhTable(comp_idx);
            } else {
            	showToast(res.message || '저장 실패','error');
            }
        },
        error: function () {
            showToast('오류가 발생했습니다.','error');
        }
    });
}

// 목표 발전량 삭제
function deleteyearkwh() {
    const idx = $('#yearkwh_idx').val();
	const comp_idx 	= $('#yearkwh_comp_idx').val();

    if (!idx) return;

    if (!confirm('해당 목표 발전량 데이터를 삭제하시겠습니까?')) {
        return;
    }

	const fd = new FormData();
	fd.append('idx', idx);	

    $.ajax({
        url: '/admin/company/yearkwh/delete',
        type: 'POST',
        data: fd,
		processData: false,
        contentType: false,
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },		
        success: function (res) {
			 if (res.status) {
            	showToast('목표 발전량이 삭제되었습니다.','success');
            	modalClose('company_yearkwh-modal');

				// 목표 발전량 변경사항 반영
				renderyearKwhTable(comp_idx);
            } else {
            	showToast(res.message || '삭제 실패','error');
            }
        },
        error: function () {
            showToast('오류가 발생했습니다.','error');
        }
    });
}


// 목표 발전량 변경사항 반영
function setYearKwhTable(data, yearkwh) {
	let html = '';

	// 발전 시작일
	const startDate = data.start_date || '2026-01-01';

	const startYear = Number(startDate.substring(0,4));
	const startMonth = Number(startDate.substring(5,7));

	// 연차별 start_year 생성
	function getStartYear(period){
		const year = startYear + (period - 1);
		return `${year}${String(startMonth).padStart(2,'0')}`;
	}

	// 데이터
	const items = yearkwh || [];

	// period → map 생성 (조회 속도 개선)
	const map = {};
	items.forEach(i => {
		map[Number(i.period)] = i;
	});

	// 10년씩 표시
	const ranges = [
		{start:1, end:10},
		{start:11, end:20}
	];

	ranges.forEach(range => {

		html += `<table class="table">
			<thead>
				<tr>
					<th class="w-10p">년차</th>`;

		for(let y=range.start; y<=range.end; y++){
			html += `<th class="w-9p">${y}년차</th>`;
		}

		html += `</tr>
			</thead>
			<tbody>
				<tr>
					<td>목표 발전량</td>`;

		for(let y=range.start; y<=range.end; y++){

			const found = map[y];

			const start_year = found 
				? found.start_year 
				: getStartYear(y);

			html += `<td class="yearkwh-cell"
					data-idx="${found ? found.idx : ''}"
					data-comp-name="${data.comp_name}"
					data-comp="${data.idx}"
					data-period="${y}"
					data-start_year="${start_year}"
					data-year_kwh="${found ? found.year_kwh : ''}">
					${found ? Number(found.year_kwh).toLocaleString() : ''}
				</td>`;
		}

		html += `</tr>
			</tbody>
		</table>`;
	});

	$('#yearkwhTableArea').html(html);
}


// 목표 발전량 변경사항 반영
function renderyearKwhTable(idx) {
    $.ajax({
    	url: '/admin/company/yearkwh/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				let html = '';

				// 발전 시작일
				const startDate = res.data.start_date || '2026-01-01';

				const startYear = Number(startDate.substring(0,4));
				const startMonth = Number(startDate.substring(5,7));

				// 연차별 start_year 생성
				function getStartYear(period){
					const year = startYear + (period - 1);
					return `${year}${String(startMonth).padStart(2,'0')}`;
				}

				// 데이터
				const items = res.yearkwh || [];

				// period → map 생성 (조회 속도 개선)
				const map = {};
				items.forEach(i => {
					map[Number(i.period)] = i;
				});

				// 10년씩 표시
				const ranges = [
					{start:1, end:10},
					{start:11, end:20}
				];

				ranges.forEach(range => {

					html += `<table class="table">
						<thead>
							<tr>
								<th class="w-10p">년차</th>`;

					for(let y=range.start; y<=range.end; y++){
						html += `<th class="w-9p">${y}년차</th>`;
					}

					html += `</tr>
						</thead>
						<tbody>
							<tr>
								<td>목표 발전량</td>`;

					for(let y=range.start; y<=range.end; y++){

						const found = map[y];

						const start_year = found 
							? found.start_year 
							: getStartYear(y);

						html += `<td class="yearkwh-cell"
								data-idx="${found ? found.idx : ''}"
								data-comp-name="${res.data.comp_name}"
								data-comp="${res.data.idx}"
								data-period="${y}"
								data-start_year="${start_year}"
								data-year_kwh="${found ? found.year_kwh : ''}">
								${found ? Number(found.year_kwh).toLocaleString() : ''}
							</td>`;
					}

					html += `</tr>
						</tbody>
					</table>`;
				});

				$('#yearkwhTableArea').html(html);

            } else {
            	showToast(res.message || '불러오기 실패','error');
                window.location.reload();
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}



/*******************************  목표 발전 시간  *******************************/
// 수정 모달에서 TD를 클릭해서 발전량 등록,수정 모달 표시
$(document).on('click', '.yearkwhhours-cell', function () {
    const idx = $(this).data('idx') || '';
    const compIdx = $(this).data('comp') || '';
    const compName = $(this).data('comp-name') || '';
    const year = $(this).data('year') || '';
    const month = $(this).data('month') || '';
    const yearkwhhours = $(this).data('yearkwhhours') || '';

    $('#yearkwhhours_idx').val(idx);
    $('#yearkwhhours_comp_idx').val(compIdx);
    $('#yearkwhhours_year').val(year);
    $('#yearkwhhours_month').val(month);

    $('#yearkwhhoursModalTitle').text(`${compName} / ${year}년 ${month}월`);

	if(yearkwhhours) {
		$('#ori_yearkwhhours_text').show();
		$('#ori_yearkwhhours').val(Number(yearkwhhours).toLocaleString());
		$('#inputyearkwhhours').val('');
	} else {
		$('#ori_yearkwhhours_text').hide();
		$('#ori_yearkwhhours').val('');
		$('#inputyearkwhhours').val('');
	}

    if (idx) {
        // 수정 모드
        $('#btnyearkwhhoursDelete').show();
    } else {
        // 등록 모드
        $('#btnyearkwhhoursDelete').hide();
    }

    modalOpen2('company_yearkwhhours-modal');
});

// 목표 발전시간 저장
function saveyearkwhhours() {
    const idx 		= $('#yearkwhhours_idx').val();
    const comp_idx 	= $('#yearkwhhours_comp_idx').val();
    const year 		= $('#yearkwhhours_year').val();
    const month 	= $('#yearkwhhours_month').val();
    const yearkwhhours 		= $('#inputyearkwhhours').val();

    if (yearkwhhours === '') {
        showToast('발전량을 입력해주세요.','error');
        return;
    }

	const fd = new FormData();
	fd.append('idx', idx);
	fd.append('comp_idx', comp_idx);
	fd.append('year', year);
	fd.append('month', month);
	fd.append('tgt_hours', yearkwhhours);

    $.ajax({
        url: '/admin/company/yearkwhhours/save',
        type: 'POST',
        data: fd,
		processData: false,
        contentType: false,
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
			 if (res.status) {
            	showToast('목표 발전시간이 저장되었습니다.','success');
            	modalClose('company_yearkwhhours-modal');

				// 발전량 변경사항 반영
				renderYearKwhHoursTable(comp_idx);
            } else {
            	showToast(res.message || '저장 실패','error');
            }
        },
        error: function () {
        	showToast('오류가 발생했습니다.','error');
        }
    });
}

// 목표 발전시간 삭제
function deleteyearkwhhours() {
    const idx = $('#yearkwhhours_idx').val();
	const comp_idx 	= $('#yearkwhhours_comp_idx').val();

    if (!idx) return;

    if (!confirm('해당 발전량 데이터를 삭제하시겠습니까?')) {
        return;
    }

	const fd = new FormData();
	fd.append('idx', idx);	

    $.ajax({
        url: '/admin/company/yearkwhhours/delete',
        type: 'POST',
        data: fd,
		processData: false,
        contentType: false,
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },		
        success: function (res) {
			 if (res.status) {
            	showToast('목표 발전시간이 삭제되었습니다.','success');
            	modalClose('company_yearkwhhours-modal');

				// 발전량 변경사항 반영
				renderYearKwhHoursTable(comp_idx);
            } else {
            	showToast(res.message || '삭제 실패','error');
            }
        },
        error: function () {
            showToast('오류가 발생했습니다.','error');
        }
    });
}


// 목표 발전시간 변경사항 반영
function setYearKwhHoursTable(data, yearkwhhours) {

	let html = `<table class="table">
			<thead>
				<tr>
					<th>년도</th>`;
					// 1~12월 header
					for(let m=1; m<=12; m++){ html += `<th>${m}월</th>`; }
	html += `</tr></thead><tbody>`;

	if(yearkwhhours && Object.keys(yearkwhhours).length > 0){
		// year 목록 추출
		let years = Object.keys(yearkwhhours).map(year => Number(year));

		// 이전, 이후 year 각각 추가
		const maxYear = Math.max(...years);
		const minYear = Math.min(...years);
		years.push(maxYear + 1); // 다음년도
		years.unshift(minYear - 1); // 이전년도
		
		// 오름차순으로 재정렬
		years = [...new Set(years)].sort((a,b) => a - b);

		years.forEach(function(year){
			const items = yearkwhhours[year] || [];
			html += `<tr>`;
			html += `<td>${year}</td>`;

			// 1~12월 반복
			for(let month=1; month<=12; month++){
				const found = items.find(i => Number(i.month) === month);
				html += `<td class="yearkwhhours-cell" 
						data-idx="${found ? found.idx : ''}" 
						data-comp-name="${ data.comp_name }" 
						data-comp="${ data.idx }" 
						data-year="${year}" 
						data-month="${month}"
						data-tgt_hours="${found ? Number(found.tgt_hours) : ''}">
						${found ? Number(found.tgt_hours).toLocaleString() : ''}
					</td>`;
			}

			html += `</tr>`;
		});

		$('#btnDeleteGroup').hide();
	}else{
		const currentYear = new Date().getFullYear();

		for (let year = currentYear - 1; year <= currentYear + 1; year++) {
			html += `<tr>`;
			html += `<td>${year}</td>`;

			for (let month = 1; month <= 12; month++) {
				html += `
					<td class="yearkwhhours-cell"
						data-idx=""
						data-comp-name="${data.comp_name}"
						data-comp="${data.idx}"
						data-year="${year}"
						data-month="${month}"
						data-tgt_hours="">
					</td>
				`;
			}

			html += `</tr>`;
		}
	}
	html += `</tbody></table>`;

	$('#yearkwhhoursTableArea').html(html);
}


// 목표 발전시간 변경사항 반영
function renderYearKwhHoursTable(idx) {
    $.ajax({
    	url: '/admin/company/yearkwhhours/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				// kwh 랜더링
				let html = `<table class="table">
						<thead>
							<tr>
								<th>년도</th>`;
								// 1~12월 header
								for(let m=1; m<=12; m++){ html += `<th>${m}월</th>`; }
				html += `</tr></thead><tbody>`;

				if(res.yearkwhhours && Object.keys(res.yearkwhhours).length > 0){
					// year 목록 추출
					let years = Object.keys(res.yearkwhhours).map(year => Number(year));

					// 이전, 이후 year 각각 추가
					const maxYear = Math.max(...years);
					const minYear = Math.min(...years);
					years.push(maxYear + 1); // 다음년도
					years.unshift(minYear - 1); // 이전년도
					
					// 오름차순으로 재정렬
					years = [...new Set(years)].sort((a,b) => a - b);

					years.forEach(function(year){
						const items = res.yearkwhhours[year] || [];
						html += `<tr>`;
						html += `<td>${year}</td>`;

						// 1~12월 반복
						for(let month=1; month<=12; month++){
							const found = items.find(i => Number(i.month) === month);
							html += `<td class="yearkwhhours-cell" 
									data-idx="${found ? found.idx : ''}" 
									data-comp-name="${res.data.comp_name}"
									data-comp="${res.data.idx}" 
									data-year="${year}" 
									data-month="${month}"
									data-tgt_hours="${found ? Number(found.tgt_hours) : ''}">
									${found ? Number(found.tgt_hours).toLocaleString() : ''}
								</td>`;
						}

						html += `</tr>`;
					});

					$('#btnDeleteGroup').hide();
				}else{
					const currentYear = new Date().getFullYear();

					for (let year = currentYear - 1; year <= currentYear + 1; year++) {
						html += `<tr>`;
						html += `<td>${year}</td>`;

						for (let month = 1; month <= 12; month++) {
							html += `
								<td class="yearkwhhours-cell"
									data-idx=""
									data-comp-name="${res.data.comp_name}"
									data-comp="${res.data.idx}"
									data-year="${year}"
									data-month="${month}"
									data-tgt_hours="">
								</td>
							`;
						}

						html += `</tr>`;
					}
				}
				html += `</tbody></table>`;

				$('#yearkwhhoursTableArea').html(html);

            } else {
            	showToast(res.message || '불러오기 실패','error');
                window.location.reload();
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}



/*******************************  정기점검  *******************************/
// 정기점검 수정버튼 클릭
$(document).on('click', '.btn-check-edit', function () {
    $('#check_idx').val($(this).data('idx'));
    $('#check_date').val($(this).data('day'));
    $('#check_type').val($(this).data('type'));
    $('#check_content').val($(this).data('content'));
	$('#check_result').val($(this).data('result'));
});

// 정기점검 수정취소 클릭
$(document).on('click', '#btnCheckCancel', function () {
    $('#check_idx').val('');
    $('#check_date').val('');
    $('#check_type').val('');
    $('#check_content').val('');
	$('#check_result').val('');
});

// 정기점검 저장
$(document).on('click', '#btnCheckSave', function () {
	if (!$('#check_content').val())		return showToast('내용을 입력해주세요.','error');

    $.ajax({
        url: '/admin/company/manage/save',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            idx: $('#check_idx').val(),
            comp_idx: "{{ $data->idx }}",
            day: $('#check_date').val(),
            type: $('#check_type').val(),
            manage: $('#check_content').val(),
			result: $('#check_result').val()
        },
        success: function (res) {
            if (res.status === 'success') {
				showToast('정기 점검 내역이 저장되었습니다.','success');
                renderCheckTable("{{ $data->idx }}");
                $('#btnCheckCancel').click();
            } else {
                showToast('오류가 발생했습니다.','error');
            }
        }
    });
});

// 정기점검 삭제
$(document).on('click', '.btn-check-delete', function () {
    const idx = $(this).data('idx');

    if (!confirm('삭제하시겠습니까?')) return;

    $.ajax({
        url: '/admin/company/manage/delete',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            idx: idx,
            comp_idx: "{{ $data->idx }}"
        },
        success: function (res) {
            if (res.status === 'success') {
				showToast('정기 점검 내역이 삭제되었습니다.','success');
                renderCheckTable("{{ $data->idx }}");
            } else {
                showToast('오류가 발생했습니다.','error');
            }
        }
    });
});

// 정기점검 세팅
function setCheckTable(checkList) {
    let html = `
		<div class="text-base mt-5 mb-2">정기 점검 내역</div>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>날짜</th>
                    <th>점검 내용</th>
					<th>점검 결과</th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
    `;

    if (checkList && checkList.length > 0) {
        checkList.forEach(function(item) {
            html += `
                <tr>
                    <td>${item.day}</td>
                    <td>${item.manage ?? ''}</td>
					<td>${item.result ?? ''}</td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-primary btn-check-edit"
                            data-idx="${item.idx}"
                            data-day="${item.day}"
                            data-type="${item.type ?? ''}"
                            data-content="${item.manage ?? ''}"
							data-result="${item.result ?? ''}">
                            수정
                        </button>
                    </td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger btn-check-delete"
                            data-idx="${item.idx}">
                            삭제
                        </button>
                    </td>
                </tr>
            `;
        });
    } else {
        html += `<tr><td colspan="7">정기 점검 내역이 없습니다.</td></tr>`;
    }

    html += `</tbody></table>`;

    $('#checkTableArea').html(html);
}

// 정기점검 수정사항 반영
function renderCheckTable(idx) {
	$.ajax({
    	url: '/admin/company/manage/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				const checkList = res.checkList;

				let html = `
					<div class="text-base mt-5 mb-2">정기 점검 내역</div>
					<table class="table table-bordered text-center">
						<thead>
							<tr>
								<th>날짜</th>
								<th>점검 내용</th>
								<th>점검 결과</th>
								<th>수정</th>
								<th>삭제</th>
							</tr>
						</thead>
						<tbody>
				`;

				if (checkList && checkList.length > 0) {
					checkList.forEach(function(item) {
						html += `
							<tr>
								<td>${item.day}</td>
								<td>${item.manage ?? ''}</td>
								<td>${item.result ?? ''}</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-primary btn-check-edit"
										data-idx="${item.idx}"
										data-day="${item.day}"
										data-type="${item.type ?? ''}"
										data-content="${item.manage ?? ''}"
										data-result="${item.result ?? ''}">
										수정
									</button>
								</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-danger btn-check-delete"
										data-idx="${item.idx}">
										삭제
									</button>
								</td>
							</tr>
						`;
					});
				} else {
					html += `<tr><td colspan="7">정기 점검 내역이 없습니다.</td></tr>`;
				}

				html += `</tbody></table>`;

				$('#checkTableArea').html(html);

            } else {
            	showToast(res.message || '불러오기 실패','error');
                window.location.reload();
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}



/*******************************  긴급출동  *******************************/
// 긴급출동 수정버튼 클릭
$(document).on('click', '.btn-emergency-edit', function () {
    $('#emergency_idx').val($(this).data('idx'));
    $('#emergency_date').val($(this).data('day'));
    $('#emergency_type').val($(this).data('type'));
    $('#emergency_content').val($(this).data('content'));
	$('#emergency_result').val($(this).data('result'));
});

// 긴급출동 수정취소 클릭
$(document).on('click', '#btnEmergencyCancel', function () {
    $('#emergency_idx').val('');
    $('#emergency_date').val('');
    $('#emergency_type').val('');
    $('#emergency_content').val('');
	$('#emergency_result').val('');
});

// 긴급출동 저장
$(document).on('click', '#btnEmergencySave', function () {
	if (!$('#emergency_content').val())		return showToast('내용을 입력해주세요.','error');

    $.ajax({
        url: '/admin/company/manage/save',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            idx: $('#emergency_idx').val(),
            comp_idx: "{{ $data->idx }}",
            day: $('#emergency_date').val(),
            type: $('#emergency_type').val(),
            manage: $('#emergency_content').val(),
			result: $('#emergency_result').val()
        },
        success: function (res) {
            if (res.status === 'success') {
				showToast('긴급 출동 내역이 저장되었습니다.','success');
                renderEmergencyTable("{{ $data->idx }}");
                $('#btnEmergencyCancel').click();
            } else {
                showToast('오류가 발생했습니다.','error');
            }
        }
    });
});

// 긴급출동 삭제
$(document).on('click', '.btn-emergency-delete', function () {
    const idx = $(this).data('idx');

    if (!confirm('삭제하시겠습니까?')) return;

    $.ajax({
        url: '/admin/company/manage/delete',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            idx: idx,
            comp_idx: "{{ $data->idx }}"
        },
        success: function (res) {
            if (res.status === 'success') {
				showToast('긴급 출동 내역이 삭제되었습니다.','success');
                renderEmergencyTable("{{ $data->idx }}");
            } else {
                showToast('오류가 발생했습니다.','error');
            }
        }
    });
});

// 긴급출동 세팅
function setEmergencyTable(emergencyList) {
    let html = `
		<div class="text-base mt-5 mb-2">긴급 출동 내역</div>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>날짜</th>
                    <th>긴급 출동 내용</th>
                    <th>긴급 출동 결과</th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
    `;

    if (emergencyList && emergencyList.length > 0) {
        emergencyList.forEach(function(item) {
            html += `
                <tr>
                    <td>${item.day}</td>
					<td>${item.manage ?? ''}</td>
					<td>${item.result ?? ''}</td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-primary btn-emergency-edit"
                            data-idx="${item.idx}"
                            data-day="${item.day}"
                            data-type="${item.type ?? ''}"
                            data-content="${item.manage ?? ''}"
							data-result="${item.result ?? ''}">
                            수정
                        </button>
                    </td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger btn-emergency-delete"
                            data-idx="${item.idx}">
                            삭제
                        </button>
                    </td>
                </tr>
            `;
        });
    } else {
        html += `<tr><td colspan="7">긴급 출동 내역이 없습니다.</td></tr>`;
    }

    html += `</tbody></table>`;

    $('#emergencyTableArea').html(html);
}

// 긴급출동 수정사항 반영
function renderEmergencyTable(idx) {
	$.ajax({
    	url: '/admin/company/manage/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				const emergencyList = res.emergencyList;					

				let html = `
					<div class="text-base mt-5 mb-2">긴급 출동 내역</div>
					<table class="table table-bordered text-center">
						<thead>
							<tr>
								<th>날짜</th>
								<th>긴급 출동 내용</th>
                    			<th>긴급 출동 결과</th>
								<th>수정</th>
								<th>삭제</th>
							</tr>
						</thead>
						<tbody>
				`;

				if (emergencyList && emergencyList.length > 0) {
					emergencyList.forEach(function(item) {
						html += `
							<tr>
								<td>${item.day}</td>
								<td>${item.manage ?? ''}</td>
								<td>${item.result ?? ''}</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-primary btn-emergency-edit"
										data-idx="${item.idx}"
										data-day="${item.day}"
										data-type="${item.type ?? ''}"
										data-content="${item.manage ?? ''}"
										data-result="${item.result ?? ''}">
										수정
									</button>
								</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-danger btn-emergency-delete"
										data-idx="${item.idx}">
										삭제
									</button>
								</td>
							</tr>
						`;
					});
				} else {
					html += `<tr><td colspan="7">긴급 출동 내역이 없습니다.</td></tr>`;
				}

				html += `</tbody></table>`;

				$('#emergencyTableArea').html(html);
            } else {
            	showToast(res.message || '불러오기 실패','error');
                window.location.reload();
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}


/*******************************  소모품교체  *******************************/
// 소모품교체 수정버튼 클릭
$(document).on('click', '.btn-change-edit', function () {
    $('#change_idx').val($(this).data('idx'));
    $('#change_date').val($(this).data('day'));
    $('#change_type').val($(this).data('type'));
    $('#change_content').val($(this).data('content'));
	$('#change_count').val($(this).data('count'));
});

// 소모품교체 수정취소 클릭
$(document).on('click', '#btnChangeCancel', function () {
    $('#change_idx').val('');
    $('#change_date').val('');
    $('#change_type').val('');
    $('#change_content').val('');
	$('#change_count').val('');
});

// 소모품교체 저장
$(document).on('click', '#btnChangeSave', function () {
	if (!$('#change_content').val())		return showToast('내용을 입력해주세요.','error');

    $.ajax({
        url: '/admin/company/manage/save',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            idx: $('#change_idx').val(),
            comp_idx: "{{ $data->idx }}",
            day: $('#change_date').val(),
            type: $('#change_type').val(),
            manage: $('#change_content').val(),
			count: $('#change_count').val()
        },
        success: function (res) {
            if (res.status === 'success') {
				showToast('소모품 교체 내역이 저장되었습니다.','success');
                renderChangeTable("{{ $data->idx }}");
                $('#btnChangeCancel').click();
            } else {
                showToast('오류가 발생했습니다.','error');
            }
        }
    });
});

// 소모품교체 삭제
$(document).on('click', '.btn-change-delete', function () {
    const idx = $(this).data('idx');

    if (!confirm('삭제하시겠습니까?')) return;

    $.ajax({
        url: '/admin/company/manage/delete',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            idx: idx,
            comp_idx: "{{ $data->idx }}"
        },
        success: function (res) {
            if (res.status === 'success') {
				showToast('소모품 교체 내역이 삭제되었습니다.','success');
                renderChangeTable("{{ $data->idx }}");
            } else {
                showToast('오류가 발생했습니다.','error');
            }
        }
    });
});

// 소모품교체 세팅
function setChangeTable(changeList) {
    let html = `
		<div class="text-base mt-5 mb-2">소모품 교체 내역</div>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>날짜</th>
					<th>교체 내용</th>
					<th>교체 수량</th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
    `;

    if (changeList && changeList.length > 0) {
        changeList.forEach(function(item) {
            html += `
                <tr>
                    <td>${item.day}</td>
					<td>${item.manage ?? ''}</td>
					<td>${item.count ?? ''}</td>					
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-primary btn-change-edit"
                            data-idx="${item.idx}"
                            data-day="${item.day}"
                            data-type="${item.type ?? ''}"
                            data-content="${item.manage ?? ''}">
                            수정
                        </button>
                    </td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger btn-change-delete"
                            data-idx="${item.idx}">
                            삭제
                        </button>
                    </td>
                </tr>
            `;
        });
    } else {
        html += `<tr><td colspan="7">소모품 교체 내역이 없습니다.</td></tr>`;
    }

    html += `</tbody></table>`;

	$('#changeTableArea').html(html);
}

// 소모품교체 수정사항 반영
function renderChangeTable(idx) {
	$.ajax({
    	url: '/admin/company/manage/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				const changeList = res.changeList;					

				let html = `
					<div class="text-base mt-5 mb-2">소모품 교체 내역</div>
					<table class="table table-bordered text-center">
						<thead>
							<tr>
								<th>날짜</th>
								<th>교체 내용</th>
								<th>교체 수량</th>
								<th>수정</th>
								<th>삭제</th>
							</tr>
						</thead>
						<tbody>
				`;

				if (changeList && changeList.length > 0) {
					changeList.forEach(function(item) {
						html += `
							<tr>
								<td>${item.day}</td>
								<td>${item.manage ?? ''}</td>
								<td>${item.count ?? ''}</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-primary btn-change-edit"
										data-idx="${item.idx}"
										data-day="${item.day}"
										data-type="${item.type ?? ''}"
										data-content="${item.manage ?? ''}"
										data-count="${item.count ?? ''}">
										수정
									</button>
								</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-danger btn-change-delete"
										data-idx="${item.idx}">
										삭제
									</button>
								</td>
							</tr>
						`;
					});
				} else {
					html += `<tr><td colspan="7">소모품 교체 내역이 없습니다.</td></tr>`;
				}

				html += `</tbody></table>`;

				$('#changeTableArea').html(html);

            } else {
            	showToast(res.message || '불러오기 실패','error');
                window.location.reload();
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}


/*******************************  이슈  *******************************/
// 이슈 수정버튼 클릭
$(document).on('click', '.btn-issue-edit', function () {
    $('#issue_idx').val($(this).data('idx'));
    $('#issue_day').val($(this).data('day'));
    $('#issue_content').val($(this).data('content'));
});

// 이슈 취소버튼 클릭
$(document).on('click', '#btnIssueCancel', function () {
    $('#issue_idx').val('');
    $('#issue_day').val('');
    $('#issue_content').val('');
});

// 이슈 저장
$(document).on('click', '#btnIssueSave', function () {
	if (!$('#issue_content').val())		return showToast('내용을 입력해주세요.','error');

    $.ajax({
        url: '/admin/company/manage/save',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            idx: $('#issue_idx').val(),
            comp_idx: "{{ $data->idx }}",
            day: $('#issue_date').val(),
           	type: '이슈',			
            manage: $('#issue_content').val()
        },
        success: function (res) {
            if (res.status === 'success') {
				showToast('이슈 내역이 저장되었습니다.','success');
                renderIssueTable("{{ $data->idx }}");
                $('#btnIssueCancel').click();
            } else {
                showToast('오류가 발생했습니다.','error');
            }
        }
    });
});

// 이슈 삭제
$(document).on('click', '.btn-issue-delete', function () {
    const idx = $(this).data('idx');

    if (!confirm('삭제하시겠습니까?')) return;

    $.ajax({
        url: '/admin/company/manage/delete',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            idx: idx,
            comp_idx: "{{ $data->idx }}"
        },
        success: function (res) {
            if (res.status === 'success') {
				showToast('이슈 내역이 삭제되었습니다.','success');
                renderIssueTable("{{ $data->idx }}");
            } else {
                showToast('오류가 발생했습니다.','error');
            }
        }
    });
});

// 이슈 세팅
function setIssueTable(issueList) {
    let html = `
		<div class="text-base mt-5 mb-2">이슈 내역</div>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>날짜</th>
                    <th>내용</th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
    `;

    if (issueList && issueList.length > 0) {
        issueList.forEach(function(item) {
            html += `
                <tr>
                    <td>${item.day}</td>
                    <td>${item.manage ?? ''}</td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-primary btn-issue-edit"
                            data-idx="${item.idx}"
                            data-day="${item.day}"
                            data-content="${item.manage ?? ''}">
                            수정
                        </button>
                    </td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger btn-issue-delete"
                            data-idx="${item.idx}">
                            삭제
                        </button>
                    </td>
                </tr>
            `;
        });
    } else {
        html += `<tr><td colspan="6">이슈 내역이 없습니다.</td></tr>`;
    }

    html += `</tbody></table>`;
    $('#issueTableArea').html(html);
}

// 이슈 수정사항 반영
function renderIssueTable(idx) {
    $.ajax({
    	url: '/admin/company/manage/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				const issueList = res.issueList;

				let html = `
					<div class="text-base mt-5 mb-2">이슈 내역</div>
					<table class="table table-bordered text-center">
						<thead>
							<tr>
								<th>날짜</th>
								<th>내용</th>
								<th>수정</th>
								<th>삭제</th>
							</tr>
						</thead>
						<tbody>
				`;

				if (issueList && issueList.length > 0) {
					issueList.forEach(function(item) {
						html += `
							<tr>
								<td>${item.day}</td>
								<td>${item.manage ?? ''}</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-primary btn-issue-edit"
										data-idx="${item.idx}"
										data-day="${item.day}"
										data-content="${item.manage ?? ''}">
										수정
									</button>
								</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-danger btn-issue-delete"
										data-idx="${item.idx}">
										삭제
									</button>
								</td>
							</tr>
						`;
					});
				} else {
					html += `<tr><td colspan="6">이슈 내역이 없습니다.</td></tr>`;
				}

				html += `</tbody></table>`;
				$('#issueTableArea').html(html);

            } else {
            	showToast(res.message || '불러오기 실패','error');
                window.location.reload();
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}


// 해당 발전소 리포트 생성
$(document).on('click', '#createReport', function () {
	const idx = $(this).data('idx');

	$.ajax({
		url: '/admin/report/company/reportgenerater/'+idx,
		method: 'POST',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		success: function (res) {
			if (res.status === 'success') {
            	showToast('리포트 생성 완료', 'success');
        	} else {
				showToast(res.message || '저장 실패','error');
			}
		},
		error: function (xhr) {
			showToast('오류 발생','error');
			console.error(xhr.responseText);
		}
	});	

});

function goList() {
	location.href="/admin/company";
}

function toNumber(value) {
	const num = parseFloat(String(value ?? '').replace(/,/g, '').trim());
	return isNaN(num) ? 0 : num;
}

function formatNumber(value) {
	return String(value);
}


</script>