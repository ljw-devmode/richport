@php 
	$header_skip = false; 
	$depth1_num = '3'; 
	$depth2_num = '1'; 
@endphp 
@include('admin.header._header')
<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">포트폴리오</h3>

	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/portfolio">
			<div class="flex items-end gap-3 flex-wrap">
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
					<p class="mb-1">형태</p>
					<select id="form_idx" name="form_idx" class="form-select">
						<option value="">전체</option>
						@foreach ($forms as $item)
							<option value="{{$item->idx}}" {{ request('form_idx') == $item->idx ? 'selected' : '' }}>{{$item->subject}}</option>
						@endforeach
					</select>
				</div>				
				<div>
					<p class="mb-1">블로그</p>
					<select id="is_blog" name="is_blog" class="form-select w-36">
						<option value="" {{ request('is_blog') === null || request('is_blog') === '' ? 'selected' : '' }}>전체</option>
			            <option value="1" {{ request('is_blog') == '1' ? 'selected' : '' }}>있음</option>
			            <option value="0" {{ request('is_blog') == '0' ? 'selected' : '' }}>없음</option>
					</select>
				</div>
				<div>
					<p class="mb-1">유튜브</p>
					<select id="is_url" name="is_url" class="form-select w-36">
						<option value="" {{ request('is_url') === null || request('is_url') === '' ? 'selected' : '' }}>전체</option>
			            <option value="1" {{ request('is_url') == '1' ? 'selected' : '' }}>있음</option>
			            <option value="0" {{ request('is_url') == '0' ? 'selected' : '' }}>없음</option>
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
					<p class="mb-1">사업소개 추천</p>
					<select id="introduce_recommend" name="introduce_recommend" class="form-select w-36">
						<option value="" {{ request('introduce_recommend') === null || request('introduce_recommend') === '' ? 'selected' : '' }}>전체</option>
			            <option value="1" {{ request('introduce_recommend') == '1' ? 'selected' : '' }}>추천</option>
			            <option value="0" {{ request('introduce_recommend') == '0' ? 'selected' : '' }}>미추천</option>
					</select>
				</div>
				<div>
					<p class="mb-1">포트폴리오 추천</p>
					<select id="portfolio_recommend" name="portfolio_recommend" class="form-select w-36">
						<option value="" {{ request('portfolio_recommend') === null || request('portfolio_recommend') === '' ? 'selected' : '' }}>전체</option>
			            <option value="1" {{ request('portfolio_recommend') == '1' ? 'selected' : '' }}>추천</option>
			            <option value="0" {{ request('portfolio_recommend') == '0' ? 'selected' : '' }}>미추천</option>
					</select>
				</div>
				<div>
					<p class="mb-1">지역</p>
					<select id="regions_idx" name="regions_idx" class="form-select w-36">
						<option value="">전체</option>
						@foreach ($regions as $item)
						<option value="{{$item->idx}}" {{ request('regions_idx') == $item->idx ? 'selected' : '' }}>{{$item->name_kr}}</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">도시</p>
					<select id="cities_idx" name="cities_idx" class="form-select w-36">
						<option value="">전체</option>
					</select>
				</div>
				<div>
					<p class="mb-1">준공일</p>
					<div class="relative w-56 mx-auto">
						<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
							<i data-lucide="calendar" class="w-4 h-4"></i>
						</div>
						<input type="text" id="completion_date" name="completion_date" class="form-control pl-12" data-single-mode="true" value="{{ request('completion_date') }}">
					</div>
				</div>
				<div>
					<p class="mb-1">발전소명</p>
					<input type="text" id="station_name" name="station_name" class="form-control w-72" placeholder="발전소명을 입력해주세요" value="{{ request('station_name') }}" >
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">총 {{ $data->total() }}개</div>
			@if(auth()->user()->hasPerm('portfolio.create') || auth()->user()->hasPerm('portfolio.excel'))
			<div class="flex items-center gap-2">
				@if(auth()->user()->hasPerm('portfolio.create'))
				<a href="javascript:;" class="btn btn-dark w-20" onclick="modalOpen('portfolio_regist-modal')">등록</a>
				@endif
				@if(auth()->user()->hasPerm('portfolio.excel'))
				<button type="button" class="btn border-[#1b6c40] bg-[#1b6c40] text-white px-4" onclick="window.location.href='{{ route('admin.portfolio.export', request()->query()) }}'">엑셀 다운로드</button>
				@endif
			</div>
			@endif
		</div>

		<div class="p-6">
			<table class="table table-sm table-bordered table-hover">
				<colgroup>
					<col width="4%">
					<col width="12%">
					<col width="8%">
					<col width="4%">
					<col width="4%">
					<col width="8%">
					<col width="8%">
					<col width="12%">
					<col width="6%">
					<col width="9%">
					<col width="4%">
					<col width="4%">
					<col width="6%">
					<col width="12%">
				</colgroup>
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="whitespace-nowrap">번호</th>
						<th class="whitespace-nowrap">구분</th>
						<th class="whitespace-nowrap">형태</th>
						<th class="whitespace-nowrap">블로그</th>
						<th class="whitespace-nowrap">유튜브</th>
						<th class="whitespace-nowrap">지역</th>
						<th class="whitespace-nowrap">도시</th>
						<th class="whitespace-nowrap">발전소명</th>
						<th class="whitespace-nowrap">용량(kw)</th>
						<th class="whitespace-nowrap">준공일</th>
						<th class="whitespace-nowrap">홈</th>
						<th class="whitespace-nowrap">사업소개</th>
						<th class="whitespace-nowrap">포트폴리오</th>
						<th class="whitespace-nowrap">등록일</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $index => $item)
						@php
					        $number = ($data->currentPage() - 1) * $data->perPage() + ($index + 1);
					    @endphp
						<tr
						@if(auth()->user()->hasPerm('portfolio.update') || auth()->user()->hasPerm('portfolio.delete')) 
							onclick="setModifyForm('{{ $item->idx }}')"
							style="cursor: pointer;" 
							class="hover:bg-slate-100"
						@else 
							style="cursor: default;" 
						@endif
						>
							<td>{{ $number }}</td>
							<td>{{ $item->types->subject }}</td>
							<td>{{ $item->formtypes->subject }}</td>
							<td>{{ $item->is_blog }}</td>
							<td>{{ $item->is_url }}</td>
							<td>{{ $item->regions->name_kr }}</td>
							<td>{{ $item->cities->name_kr }}</td>
							<td>{{ $item->station_name }}</td>
							<td>{{ number_format($item->volume) }}</td>
							<td>{{ $item->completion_date }}</td>
							<td>{{ $item->home_recommend_text }}</td>
							<td>{{ $item->introduce_recommend_text }}</td>
							<td>{{ $item->portfolio_recommend_text }}</td>
							<td>{{ $item->created_at }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="13" class="text-center">등록된 콘텐츠가 없습니다.</td>
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

	<!-- 포트폴리오 등록 모달 -->
	<div id="portfolio_regist-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">포트폴리오 등록</h2>
					<button onclick="modalClose('portfolio_regist-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<h3 class="mb-1 text-base font-semibold">발전소 정보</h3>
					<div class="flex items-center gap-10">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">년도 <span class="text-danger">*</span></p>
                            <select id="regist_year" name="regist_year" class="form-select">
                                <option value="">년도를 선택해주세요.</option>
                                @for ($year = date('Y'); $year >= 2015; $year--)
									<option value="{{ $year }}">{{ $year }}</option>
								@endfor
                            </select>
                        </div>
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">구분 <span class="text-danger">*</span></p>
                            <select id="regist_types_idx" name="regist_types_idx" class="form-select">
                                <option value="">구분을 선택해주세요.</option>
                                @foreach ($types as $item)
									<option value="{{$item->idx}}">{{$item->subject}}</option>
								@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center gap-10 mt-3">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">형태 <span class="text-danger">*</span></p>
                            <select id="regist_form_idx" name="regist_form_idx" class="form-select">
                                <option value="">형태를 선택해주세요.</option>
								@foreach ($forms as $item)
									<option value="{{$item->idx}}">{{$item->subject}}</option>
								@endforeach
                            </select>
                        </div>
                        <div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">준공일</p>
							<div class="relative w-full">
								<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
									<i data-lucide="calendar" class="w-4 h-4"></i>
								</div>
								<input type="text" id="regist_completion_date" name="regist_completion_date" class="datepicker form-control pl-12" data-single-mode="true">
							</div>
						</div>
                    </div>
                    <div class="flex items-center gap-10 mt-3">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium ">지역 <span class="text-danger">*</span></p>
                            <select id="regist_regions_idx" name="regist_regions_idx" class="form-select">
                            	<option value="">지역을 선택해주세요</option>
								@foreach ($regions as $item)
								<option value="{{$item->idx}}">{{$item->name_kr}}</option>
								@endforeach
							</select>
                        </div>
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium ">도시 <span class="text-danger">*</span></p>
                            <select id="regist_cities_idx" name="regist_cities_idx" class="form-select">
								<option value="">도시를 선택해주세요</option>
							</select>
                        </div>
                    </div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">용량(kW) <span class="text-danger">*</span></p>
							<input type="text" id="regist_volume" name="regist_volume" class="form-control">
						</div>
						<div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">금액(ko 단위기재) <span class="text-danger">*</span></p>
                            <input type="text" id="regist_price" name="regist_price" class="form-control">
                        </div>
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">금액/억(en 단위기재) <span class="text-danger">*</span></p>
							<input type="text" id="regist_price_en" name="regist_price_en" class="form-control">
						</div>
						<div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">금액/억(cn 단위기재) <span class="text-danger">*</span></p>
                            <input type="text" id="regist_price_cn" name="regist_price_cn" class="form-control">
                        </div>
					</div>					
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">발전소명(ko) <span class="text-danger">*</span></p>
						<input type="text" id="regist_station_name" name="regist_station_name" class="form-control" placeholder="한국어 발전소명을 입력해주세요">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">발전소명(en) <span class="text-danger">*</span></p>
						<input type="text" id="regist_station_name_en" name="regist_station_name_en" class="form-control" placeholder="영어 발전소명을 입력해주세요">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">발전소명(cn) <span class="text-danger">*</span></p>
						<input type="text" id="regist_station_name_cn" name="regist_station_name_cn" class="form-control" placeholder="중국어 발전소명을 입력해주세요">
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
					
					<h3 class="mt-5 mb-1 text-base font-semibold" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">블로그</h3>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">블로그 URL</p>
						<input type="text" id="regist_blog" name="regist_blog" class="form-control" placeholder="블로그 URL을 입력해주세요">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">블로그 내용(ko)</p>
						<textarea id="regist_blog_contents" name="regist_blog_contents" class="form-control h-32" placeholder="한국어 블로그 내용을 입력하세요."></textarea>
					</div>
					<!-- div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">블로그 내용(en)</p>
						<textarea id="regist_blog_contents_en" name="regist_blog_contents_en" class="form-control h-32" placeholder="영어 블로그 내용을 입력하세요."></textarea>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">블로그 내용(cn)</p>
						<textarea id="regist_blog_contents_cn" name="regist_blog_contents_cn" class="form-control h-32" placeholder="중국어 블로그 내용을 입력하세요."></textarea>
					</div -->
					<input type="hidden" id="regist_blog_contents_en" name="regist_blog_contents_en" value=""/>
					<input type="hidden" id="regist_blog_contents_cn" name="regist_blog_contents_cn" value=""/>
					
					<h3 class="mt-5 mb-1 text-base font-semibold" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">유튜브</h3>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 URL</p>
						<input type="text" id="regist_url" name="regist_url" class="form-control" placeholder="유튜브 영상 URL을 입력해주세요">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 iframe</p>
						<input type="text" id="regist_youtube_iframe" name="regist_youtube_iframe" class="form-control" placeholder="유튜브 iframe을 입력하세요.">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 제목(ko)</p>
						<input type="text" id="regist_youtube_subject" name="regist_youtube_subject" class="form-control" placeholder="한국어 영상제목을 입력하세요.">
					</div>
					<!--div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 제목(en)</p>
						<input type="text" id="regist_youtube_subject_en" name="regist_youtube_subject_en" class="form-control" placeholder="영어 유튜브 제목을 입력하세요.">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 제목(cn)</p>
						<input type="text" id="regist_youtube_subject_cn" name="regist_youtube_subject_cn" class="form-control" placeholder="중국어 유튜브 제목을 입력하세요.">
					</div-->
					<input type="hidden" id="regist_youtube_subject_en" name="regist_youtube_subject_en" value=""/>
					<input type="hidden" id="regist_youtube_subject_cn" name="regist_youtube_subject_cn" value=""/>

					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 내용(ko)</p>
						<textarea id="regist_youtube_contents" name="regist_youtube_contents" class="form-control h-32" placeholder="한국어 유튜브 내용을 입력하세요."></textarea>
					</div>
					<!--div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 내용(en)</p>
						<textarea id="regist_youtube_contents_en" name="regist_youtube_contents_en" class="form-control h-32" placeholder="영어 유튜브 내용을 입력하세요."></textarea>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 내용(cn)</p>
						<textarea id="regist_youtube_contents_cn" name="regist_youtube_contents_cn" class="form-control h-32" placeholder="중국어 유튜브 내용을 입력하세요."></textarea>
					</div-->
					<input type="hidden" id="regist_youtube_contents_en" name="regist_youtube_contents_en" value=""/>
					<input type="hidden" id="regist_youtube_contents_cn" name="regist_youtube_contents_cn" value=""/>

					<h3 class="mt-5 mb-1 font-medium" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">시공 이미지 <span class="text-danger">*</span></h3>
					<input type="file" id="regist_portfolioFiles" name="regist_portfolioFiles" class="filepond filepond-media" multiple />
					
					<h3 class="mt-5 mb-1 text-lg font-semibold">추천 포트폴리오</h3>
					<div class="flex items-center gap-2 mt-3">
						<div class="flex-1 form-check">
							<input id="regist_home_recommend" name="regist_home_recommend"  class="form-check-input" type="checkbox" value="">
							<label class="form-check-label" for="regist_home_recommend">홈</label>
						</div>
						<div class="flex-1 form-check">
							<input id="regist_introduce_recommend" name="regist_introduce_recommend"  class="form-check-input" type="checkbox" value="">
							<label class="form-check-label" for="regist_introduce_recommend">사업소개</label>
						</div>
						<div class="flex-1 form-check">
							<input id="regist_portfolio_recommend" name="regist_portfolio_recommend"  class="form-check-input" type="checkbox" value="">
							<label class="form-check-label" for="regist_portfolio_recommend">포트폴리오</label>
						</div>
					</div>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('portfolio_regist-modal')">취소</button>
					@if(auth()->user()->hasPerm('portfolio.create'))
					<button type="button" class="btn btn-primary w-20" onclick="regist()">등록</button>
					@endif
				</div>
			</div>
		</div>
	</div>

	<!-- 포트폴리오 수정 모달 -->
	<div id="portfolio_modify-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">포트폴리오 수정</h2>
					<button onclick="modalClose('portfolio_modify-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<h3 class="mb-1 text-base font-semibold">발전소 정보</h3>
					<input type="hidden" id="modify_idx" name="modify_idx">
					<div class="flex items-center gap-10">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">년도 <span class="text-danger">*</span></p>
                            <select id="modify_year" name="modify_year" class="form-select">
                                <option value="">년도를 선택해주세요.</option>
                                @for ($year = date('Y'); $year >= 2015; $year--)
									<option value="{{ $year }}">{{ $year }}</option>
								@endfor
                            </select>
                        </div>
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">구분 <span class="text-danger">*</span></p>
                            <select id="modify_types_idx" name="modify_types_idx" class="form-select">
                                <option value="">구분을 선택해주세요.</option>
                                @foreach ($types as $item)
									<option value="{{$item->idx}}">{{$item->subject}}</option>
								@endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-10 mt-3">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">형태 <span class="text-danger">*</span></p>
                            <select id="modify_form_idx" name="modify_form_idx" class="form-select">
                                <option value="">형태를 선택해주세요.</option>
                                @foreach ($forms as $item)
									<option value="{{$item->idx}}">{{$item->subject}}</option>
								@endforeach
                            </select>
                        </div>
                        <div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">준공일</p>
							<div class="relative w-full">
								<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
									<i data-lucide="calendar" class="w-4 h-4"></i>
								</div>
								<input type="text" id="modify_completion_date" name="modify_completion_date" class="datepicker form-control pl-12" data-single-mode="true">
							</div>
						</div>
                    </div>
                    
                    <div class="flex items-center gap-10 mt-3">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium ">지역 <span class="text-danger">*</span></p>
                             <select id="modify_regions_idx" name="modify_regions_idx" class="form-select">
                            	<option value="">지역을 선택해주세요</option>
								@foreach ($regions as $item)
								<option value="{{$item->idx}}">{{$item->name_kr}}</option>
								@endforeach
							</select>
                        </div>
                        <div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">도시 <span class="text-danger">*</span></p>
                            <select id="modify_cities_idx" name="modify_cities_idx" class="form-select">
								<option value="">도시를 선택해주세요</option>
							</select>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">용량(kW) <span class="text-danger">*</span></p>
							<input type="text" id="modify_volume" name="modify_volume" class="form-control" placeholder="">
						</div>
						<div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">금액(ko 단위기재) <span class="text-danger">*</span></p>
                            <input type="text" id="modify_price" name="modify_price" class="form-control">
                        </div>
					</div>
                    <div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">금액(en 단위기재) <span class="text-danger">*</span></p>
							<input type="text" id="modify_price_en" name="modify_price_en" class="form-control" placeholder="">
						</div>
						<div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">금액(cn 단위기재) <span class="text-danger">*</span></p>
                            <input type="text" id="modify_price_cn" name="modify_price_cn" class="form-control">
                        </div>
					</div>
                    
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">발전소명(ko) <span class="text-danger">*</span></p>
						<input type="text" id="modify_station_name" name="modify_station_name" class="form-control" placeholder="">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">발전소명(en) <span class="text-danger">*</span></p>
						<input type="text" id="modify_station_name_en" name="modify_station_name_en" class="form-control" placeholder="">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">발전소명(cn) <span class="text-danger">*</span></p>
						<input type="text" id="modify_station_name_cn" name="modify_station_name_cn" class="form-control" placeholder="">
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
					
					<h3 class="mt-5 mb-1 text-base font-semibold" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">블로그</h3>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">블로그 URL</p>
						<input type="text" id="modify_blog" name="modify_blog" class="form-control" placeholder="블로그 URL을 입력해주세요">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">블로그 내용(ko)</p>
						<textarea id="modify_blog_contents" name="modify_blog_contents" class="form-control h-32" placeholder="블로그 내용을 입력하세요."></textarea>
					</div>
					<!--div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">블로그 내용(en)</p>
						<textarea id="modify_blog_contents_en" name="modify_blog_contents_en" class="form-control h-32" placeholder="블로그 내용을 입력하세요."></textarea>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">블로그 내용(cn)</p>
						<textarea id="modify_blog_contents_cn" name="modify_blog_contents_cn" class="form-control h-32" placeholder="블로그 내용을 입력하세요."></textarea>
					</div-->
					<input type="hidden" id="modify_blog_contents_en" name="modify_blog_contents_en" value=""/>
					<input type="hidden" id="modify_blog_contents_cn" name="modify_blog_contents_cn" value=""/>
					
					<h3 class="mt-5 mb-1 text-base font-semibold" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">유튜브</h3>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 URL</p>
						<input type="text" id="modify_url" name="modify_url" class="form-control" placeholder="유튜브 영상 URL을 입력해주세요">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 iframe</p>
						<input type="text" id="modify_youtube_iframe" name="modify_youtube_iframe" class="form-control" placeholder="유튜브 iframe을 입력하세요.">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 제목(ko)</p>
						<input type="text" id="modify_youtube_subject" name="modify_youtube_subject" class="form-control" placeholder="유튜브 제목을 입력하세요.">
					</div>
					<!--div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 제목(en)</p>
						<input type="text" id="modify_youtube_subject_en" name="modify_youtube_subject_en" class="form-control" placeholder="유튜브 제목을 입력하세요.">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 제목(cn)</p>
						<input type="text" id="modify_youtube_subject_cn" name="modify_youtube_subject_cn" class="form-control" placeholder="유튜브 제목을 입력하세요.">
					</div-->
					<input type="hidden" id="modify_youtube_subject_en" name="modify_youtube_subject_en" value=""/>
					<input type="hidden" id="modify_youtube_subject_cn" name="modify_youtube_subject_cn" value=""/>

					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 내용(ko)</p>
						<textarea id="modify_youtube_contents" name="modify_youtube_contents" class="form-control h-32" placeholder="유튜브 내용을 입력하세요."></textarea>
					</div>
					<!--div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 내용(en)</p>
						<textarea id="modify_youtube_contents_en" name="modify_youtube_contents_en" class="form-control h-32" placeholder="유튜브 내용을 입력하세요."></textarea>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">유튜브 내용(cn)</p>
						<textarea id="modify_youtube_contents_cn" name="modify_youtube_contents_cn" class="form-control h-32" placeholder="유튜브 내용을 입력하세요."></textarea>
					</div-->
					<input type="hidden" id="modify_youtube_contents_en" name="modify_youtube_contents_en" value=""/>
					<input type="hidden" id="modify_youtube_contents_cn" name="modify_youtube_contents_cn" value=""/>

					<h3 class="mt-5 mb-1 font-medium" style="border-top: 1px solid rgb(var(--color-slate-200)); padding-top: 15px;">시공 이미지 <span class="text-danger">*</span></h3>
					<input type="file" id="modify_portfolioFiles" name="modify_portfolioFiles" class="filepond filepond-media" multiple />
					
					<h3 class="mt-5 mb-1 text-lg font-semibold">추천 포트폴리오</h3>
					<div class="flex items-center gap-2 mt-3">
						<div class="flex-1 form-check">
							<input id="modify_home_recommend" name="modify_home_recommend"  class="form-check-input" type="checkbox">
							<label class="form-check-label" for="modify_home_recommend">홈</label>
						</div>
						<div class="flex-1 form-check">
							<input id="modify_introduce_recommend" name="modify_introduce_recommend"  class="form-check-input" type="checkbox">
							<label class="form-check-label" for="modify_introduce_recommend">사업소개</label>
						</div>
						<div class="flex-1 form-check">
							<input id="modify_portfolio_recommend" name="modify_portfolio_recommend"  class="form-check-input" type="checkbox">
							<label class="form-check-label" for="modify_portfolio_recommend">포트폴리오</label>
						</div>
					</div>

				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="javascript:window.location.reload()">취소</button>
					@if(auth()->user()->hasPerm('portfolio.update'))
					<button type="button" class="btn btn-primary w-20" onclick="modify()">수정</button>
					@endif
					@if(auth()->user()->hasPerm('portfolio.delete'))
					<button type="button" class="btn btn-outline-danger w-20" onclick="deletePortfolio()">삭제</button>
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

const defaultCompletionDate = "{{ request('completion_date') }}";

const completion_date = new Litepicker({
    element: document.querySelector('#completion_date'),
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
        minYear: 2016,
        maxYear: new Date().getFullYear() + 6,
        months: true,
        years: true
    },
    setup: (picker) => {
        if (defaultCompletionDate) {
            picker.setDate(defaultCompletionDate);
        }
    },
});


document.addEventListener('DOMContentLoaded', function () {

    let reqRegion  = "{{ request('regions_idx') }}";
    let reqCity    = "{{ request('cities_idx') }}";

    // 지역이 선택된 상태라면 → 도시 목록 자동 로드 + select 유지
    if (reqRegion) {

        $.ajax({
            url: '/admin/cities/' + reqRegion,
            method: 'GET',
            success: function (res) {
                const $citySelect = $('#cities_idx');

                // 초기화
                $citySelect.empty().append('<option value="">전체</option>');

                // 옵션 채우기
                res.forEach(function(city) {
                    $citySelect.append(
                        `<option value="${city.idx}" ${reqCity == city.idx ? 'selected' : ''}>
                            ${city.name_kr}
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
        const registEl = document.querySelector('input[name="regist_portfolioFiles"]');
        const modifyEl = document.querySelector('input[name="modify_portfolioFiles"]');

        // 등록용
        if (registEl) {
            window.pondRegist = FilePond.create(registEl, {
                allowMultiple: true,
                allowReorder: true,
                acceptedFileTypes: ['image/*'],
                maxFileSize: '20MB',
                labelIdle: '시공 이미지를 여기에 드래그하거나 <span class="filepond--label-action">클릭하여 업로드</span>하세요.',
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
                labelIdle: '시공 이미지를 여기에 드래그하거나 <span class="filepond--label-action">클릭하여 업로드</span>하세요.',
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
        $('input[name="regist_portfolioFiles"]').attr('accept', 'image/*');
        $('input[name="modify_portfolioFiles"]').attr('accept', 'image/*');

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

/* $('input[name="regist_portfolioFiles"]').attr('accept', 'image/*');
$('input[name="modify_portfolioFiles"]').attr('accept', 'image/*'); */

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
 *  지역 / 도시 연동
 * =============================== */
function bindRegionCity(regionSelector, citySelector, defaultText) {
    $(regionSelector).on('change', function() {
        let regionsIdx = $(this).val();
        let $citySelect = $(citySelector);

        // 초기화
        $citySelect.empty().append(`<option value="">${defaultText}</option>`);

        if (!regionsIdx) return;

        $.ajax({
            url: '/admin/cities/' + regionsIdx,
            method: 'GET',
            success: function (res) {
                res.forEach(function(city) {
                    $citySelect.append(
                        `<option value="${city.idx}">${city.name_kr}</option>`
                    );
                });
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                showToast('도시 목록을 불러오지 못했습니다.','error');
            }
        });
    });
}

$(function () {
    bindRegionCity('#regions_idx', '#cities_idx', '전체');
    bindRegionCity('#regist_regions_idx', '#regist_cities_idx', '도시를 선택해주세요.');
    bindRegionCity('#modify_regions_idx', '#modify_cities_idx', '도시를 선택해주세요.');
});

/* ===============================
 *  포트폴리오 등록
 * =============================== */

function regist() {
	const year					= $('#regist_year').val();
  	const types_idx				= $('#regist_types_idx').val();
  	const form_idx				= $('#regist_form_idx').val();
  	const completion_date		= $('#regist_completion_date').val();
  	const regions_idx			= $('#regist_regions_idx').val();
  	const cities_idx			= $('#regist_cities_idx').val();
  	const volume				= $('#regist_volume').val().trim();
  	const price					= $('#regist_price').val().trim();
  	const price_en				= $('#regist_price_en').val().trim();
  	const price_cn				= $('#regist_price_cn').val().trim();
  	const station_name			= $('#regist_station_name').val().trim();
  	const station_name_en		= $('#regist_station_name_en').val().trim();
  	const station_name_cn		= $('#regist_station_name_cn').val().trim();
  	const url					= $('#regist_url').val();
  	const blog					= $('#regist_blog').val();
  	const youtube_iframe		= $('#regist_youtube_iframe').val().trim();
  	const youtube_subject		= $('#regist_youtube_subject').val().trim();
  	const youtube_subject_en	= $('#regist_youtube_subject_en').val().trim();
  	const youtube_subject_cn	= $('#regist_youtube_subject_cn').val().trim();
  	const youtube_contents		= $('#regist_youtube_contents').val();
  	const youtube_contents_en	= $('#regist_youtube_contents_en').val();
  	const youtube_contents_cn	= $('#regist_youtube_contents_cn').val();
  	const blog_contents			= $('#regist_blog_contents').val();
  	const blog_contents_en		= $('#regist_blog_contents_en').val();
  	const blog_contents_cn		= $('#regist_blog_contents_cn').val();
  	const pcThumbInput 			= $('input[name="regist_pc_thumb"]')[0];
  	const foThumbInput 			= $('input[name="regist_fo_thumb"]')[0];
  	const moThumbInput 			= $('input[name="regist_mo_thumb"]')[0];
  	const home_recommend		= $('#regist_home_recommend').is(':checked') ? 1 : 0;
  	const introduce_recommend	= $('#regist_introduce_recommend').is(':checked') ? 1 : 0;
  	const portfolio_recommend	= $('#regist_portfolio_recommend').is(':checked') ? 1 : 0;

  	if (!year)					return showToast('년도를 선택해주세요.','error');
  	if (!types_idx)				return showToast('구분을 선택해주세요.','error');
  	if (!form_idx)				return showToast('형태를 선택해주세요.','error');
	if (!regions_idx)			return showToast('지역을 선택해주세요.','error');
	if (!cities_idx)			return showToast('도시를 선택해주세요.','error');
	if (!volume)				return showToast('용량을 입력해주세요.','error');
	if (isNaN(Number(volume)))	return showToast('용량은 숫자만 입력해주세요.','error');
	if (!price)					return showToast('금액(한국어)을 입력해주세요.','error');
	if (!price_en)				return showToast('금액(영어)을 입력해주세요.','error');
	if (!price_cn)				return showToast('금액(중국어)을 입력해주세요.','error');
	if (!station_name)			return showToast('발전소명(한국어)을 입력해주세요.','error');
	if (!station_name_en)		return showToast('발전소명(영어)을 입력해주세요.','error');
	if (!station_name_cn)		return showToast('발전소명(중국어)을 입력해주세요.','error');
	
	
	const fd = new FormData();
	fd.append('year', year);
	fd.append('types_idx', types_idx);
	fd.append('form_idx', form_idx);
	fd.append('completion_date', completion_date);
	fd.append('regions_idx', regions_idx);
	fd.append('cities_idx', cities_idx);
	fd.append('volume', volume);
	fd.append('price', price);
	fd.append('price_en', price_en);
	fd.append('price_cn', price_cn);
	fd.append('station_name', station_name);
	fd.append('station_name_en', station_name_en);
	fd.append('station_name_cn', station_name_cn);
	fd.append('url', url);
	fd.append('blog', blog);
	fd.append('youtube_iframe', youtube_iframe);
	fd.append('youtube_subject', youtube_subject);
	fd.append('youtube_subject_en', youtube_subject_en);
	fd.append('youtube_subject_cn', youtube_subject_cn);
	fd.append('youtube_contents', youtube_contents);
	fd.append('youtube_contents_en', youtube_contents_en);
	fd.append('youtube_contents_cn', youtube_contents_cn);
	fd.append('blog_contents', blog_contents);
	fd.append('blog_contents_en', blog_contents_en);
	fd.append('blog_contents_cn', blog_contents_cn);
	fd.append('home_recommend', home_recommend);
	fd.append('introduce_recommend', introduce_recommend);
	fd.append('portfolio_recommend', portfolio_recommend);


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

	const portfolioFiles = window.pondRegist?.getFiles?.() || [];
	if (!portfolioFiles.length) return showToast('시공이미지를 한 개 이상 업로드해주세요.','error');
	{
		const invalid=[], tooLarge=[];
		portfolioFiles.forEach(({file})=>{
			const img=isImage(file);
			if(!img) invalid.push(file.name);
			if(img && file.size>MAX_IMG) tooLarge.push(`${file.name} (이미지 > 20MB)`);
		});
		if(invalid.length) return showToast(`시공이미지 형식 오류:\n- ${invalid.join('\n- ')}`,'error');
		if(tooLarge.length) return showToast(`시공이미지 용량 초과:\n- ${tooLarge.join('\n- ')}`,'error');
	}
	portfolioFiles.forEach(({file})=> fd.append('portfolioFiles[]', file, file.name));
	
	if (!confirm('포트폴리오를 등록하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/portfolio/regist',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	showToast('포트폴리오가 저장되었습니다.','success');
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
    	url: '/admin/portfolio/detailForm/'+idx,
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
			$('#modify_year').val(d.year);
			$('#modify_types_idx').val(d.types_idx);
			$('#modify_form_idx').val(d.form_idx);
			$('#modify_regions_idx').val(d.regions_idx);
			$('#modify_cities_idx').val(d.cities_idx);
			$('#modify_station_name').val(d.station_name);
			$('#modify_station_name_en').val(d.station_name_en);
			$('#modify_station_name_cn').val(d.station_name_cn);
			$('#modify_volume').val(d.volume);
			$('#modify_price').val(d.price);
			$('#modify_price_en').val(d.price_en);
			$('#modify_price_cn').val(d.price_cn);
			$('#modify_completion_date').val(d.completion_date);
			$('#modify_url').val(d.url);
			$('#modify_blog').val(d.blog);
			$('#modify_youtube_iframe').val(d.youtube_iframe);
			$('#modify_youtube_subject').val(d.youtube_subject);
			$('#modify_youtube_subject_en').val(d.youtube_subject_en);
			$('#modify_youtube_subject_cn').val(d.youtube_subject_cn);
			$('#modify_youtube_contents').val(d.youtube_contents);
			$('#modify_youtube_contents_en').val(d.youtube_contents_en);
			$('#modify_youtube_contents_cn').val(d.youtube_contents_cn);
			$('#modify_blog_contents').val(d.blog_contents);
			$('#modify_blog_contents_en').val(d.blog_contents_en);
			$('#modify_blog_contents_cn').val(d.blog_contents_cn);
			$('#modify_pc_thumb_name').text(d.pc_ori_name);
			$('#modify_fo_thumb_name').text(d.fo_ori_name);
			$('#modify_mo_thumb_name').text(d.mo_ori_name);

			$('#modify_pc_thumb').attr('data-up_name',d.pc_up_name);
			$('#modify_fo_thumb').attr('data-up_name',d.fo_up_name);
			$('#modify_mo_thumb').attr('data-up_name',d.mo_up_name);

			$('#modify_home_recommend').prop('checked', d.home_recommend == 1);
			$('#modify_introduce_recommend').prop('checked', d.introduce_recommend == 1);
			$('#modify_portfolio_recommend').prop('checked', d.portfolio_recommend == 1);
			
			$('#modify_regions_idx').val(d.regions_idx || '');

            const $citySelect = $('#modify_cities_idx');
            $citySelect.empty().append('<option value="">도시를 선택해주세요</option>');

            if (d.regions_idx) {
                $.ajax({
                    url: '/admin/cities/' + d.regions_idx,
                    method: 'GET',
                    success: function (cities) {
                        cities.forEach(function (city) {
                            $citySelect.append(
                                `<option value="${city.idx}">${city.name_kr}</option>`
                            );
                        });

                        if (d.cities_idx) {
                            $citySelect.val(d.cities_idx);
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        showToast('도시 목록을 불러오지 못했습니다.','error');
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

			modalOpen('portfolio_modify-modal');
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
	const year					= $('#modify_year').val();
  	const types_idx				= $('#modify_types_idx').val();
  	const form_idx				= $('#modify_form_idx').val();
  	const completion_date		= $('#modify_completion_date').val();
  	const regions_idx			= $('#modify_regions_idx').val();
  	const cities_idx			= $('#modify_cities_idx').val();
  	const volume				= $('#modify_volume').val().trim();
  	const price					= $('#modify_price').val().trim();
  	const price_en				= $('#modify_price_en').val().trim();
  	const price_cn				= $('#modify_price_cn').val().trim();
  	const station_name			= $('#modify_station_name').val().trim();
  	const station_name_en		= $('#modify_station_name_en').val().trim();
  	const station_name_cn		= $('#modify_station_name_cn').val().trim();
  	const url					= $('#modify_url').val();
  	const blog					= $('#modify_blog').val();
  	const youtube_iframe		= $('#modify_youtube_iframe').val().trim();
  	const youtube_subject		= $('#modify_youtube_subject').val().trim();
  	const youtube_subject_en	= $('#modify_youtube_subject_en').val().trim();
  	const youtube_subject_cn	= $('#modify_youtube_subject_cn').val().trim();
  	const youtube_contents		= $('#modify_youtube_contents').val();
  	const youtube_contents_en	= $('#modify_youtube_contents_en').val();
  	const youtube_contents_cn	= $('#modify_youtube_contents_cn').val();
  	const blog_contents			= $('#modify_blog_contents').val();
  	const blog_contents_en		= $('#modify_blog_contents_en').val();
  	const blog_contents_cn		= $('#modify_blog_contents_cn').val();
  	const pcThumbInput 			= $('input[name="modify_pc_thumb"]')[0];
  	const foThumbInput 			= $('input[name="modify_fo_thumb"]')[0];
  	const moThumbInput 			= $('input[name="modify_mo_thumb"]')[0];
  	const home_recommend		= $('#modify_home_recommend').is(':checked') ? 1 : 0;
  	const introduce_recommend	= $('#modify_introduce_recommend').is(':checked') ? 1 : 0;
  	const portfolio_recommend	= $('#modify_portfolio_recommend').is(':checked') ? 1 : 0;

  	if (!year)					return showToast('년도를 선택해주세요.','error');
  	if (!types_idx)				return showToast('구분을 선택해주세요.','error');
  	if (!form_idx)				return showToast('형태를 선택해주세요.','error');
	if (!regions_idx)			return showToast('지역을 선택해주세요.','error');
	if (!cities_idx)			return showToast('도시를 선택해주세요.','error');
	if (!volume)				return showToast('용량을 입력해주세요.','error');
	if (isNaN(Number(volume)))	return showToast('용량은 숫자만 입력해주세요.','error');
	if (!price)					return showToast('금액(한국어)을 입력해주세요.','error');
	if (!price_en)				return showToast('금액(영어)을 입력해주세요.','error');
	if (!price_cn)				return showToast('금액(중국어)을 입력해주세요.','error');
	if (!station_name)			return showToast('발전소명(한국어)을 입력해주세요.','error');
	if (!station_name_en)		return showToast('발전소명(영어)을 입력해주세요.','error');
	if (!station_name_cn)		return showToast('발전소명(중국어)을 입력해주세요.','error');

	const fd = new FormData();
	fd.append('year', year);
	fd.append('types_idx', types_idx);
	fd.append('form_idx', form_idx);
	fd.append('completion_date', completion_date);
	fd.append('regions_idx', regions_idx);
	fd.append('cities_idx', cities_idx);
	fd.append('volume', volume);
	fd.append('price', price);
	fd.append('price_en', price_en);
	fd.append('price_cn', price_cn);
	fd.append('station_name', station_name);
	fd.append('station_name_en', station_name_en);
	fd.append('station_name_cn', station_name_cn);
	fd.append('url', url);
	fd.append('blog', blog);
	fd.append('youtube_iframe', youtube_iframe);
	fd.append('youtube_subject', youtube_subject);
	fd.append('youtube_subject_en', youtube_subject_en);
	fd.append('youtube_subject_cn', youtube_subject_cn);
	fd.append('youtube_contents', youtube_contents);
	fd.append('youtube_contents_en', youtube_contents_en);
	fd.append('youtube_contents_cn', youtube_contents_cn);
	fd.append('blog_contents', blog_contents);
	fd.append('blog_contents_en', blog_contents_en);
	fd.append('blog_contents_cn', blog_contents_cn);
	fd.append('home_recommend', home_recommend);
	fd.append('introduce_recommend', introduce_recommend);
	fd.append('portfolio_recommend', portfolio_recommend);


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
	if (!files.length) return showToast('시공이미지를 한 개 이상 업로드해주세요.','error');
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
		if(invalid.length) return showToast(`시공이미지 형식 오류:\n- ${invalid.join('\n- ')}`,'error');
		if(tooLarge.length) return showToast(`시공이미지 용량 초과:\n- ${tooLarge.join('\n- ')}`,'error');
	}
	
	fd.append('removedFileIdxs', JSON.stringify(removedFileIdxs));
	fd.append('existingFileOrder', JSON.stringify(existingFileOrder));

	if (!confirm('포트폴리오를 수정하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/portfolio/modify/'+idx,
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('포트폴리오가 수정되었습니다.','success');
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

function deletePortfolio() {
	const idx = $('#modify_idx').val();
  	if (!idx) return showToast('오류가 발생하였습니다.','error');
	
	if (!confirm('정말로 해당 포트폴리오를 삭제하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/portfolio/delete/'+idx,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('포트폴리오가 삭제되었습니다.','success');
                location.href = '/admin/portfolio';
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
