{{--
	업체 상품 목록 (RIPS > 상품, 관리자 > RIPS 업체 상세 > 상품 현황 공통)
	$data        : 상품 paginator (RipsForecast::productList)
	$categoryMap : 업체 카테고리 경로
	$orderRid    : 값이 있으면 [발주하기] 버튼 표시 (RIPS 업체 아이디), 관리자는 null
	$rowClick    : true 면 행 클릭 시 openProductModal(idx) (RIPS 상품 수정)
--}}
@php
	$orderRid = $orderRid ?? null;
	$rowClick = $rowClick ?? false;
	$today    = \Carbon\Carbon::today();
	$cols     = $orderRid ? 11 : 10;
@endphp
<div class="rp-table-wrap">
	<table class="table table-bordered">
		<thead class="bg-slate-50 text-center">
			<tr>
				<th class="rp-nowrap">번호</th>
				<th class="rp-nowrap">카테고리</th>
				<th class="rp-nowrap">업체명</th>
				<th class="rp-nowrap">상품명</th>
				<th class="rp-nowrap">바코드</th>
				<th class="rp-nowrap">잔여수량</th>
				<th class="rp-nowrap">잔여소진 예상일</th>
				<th class="rp-nowrap">추천 발주일</th>
				<th class="rp-nowrap">추천수량</th>
				<th class="rp-nowrap">예약 입고 소요기간</th>
				@if ($orderRid)<th class="rp-nowrap">발주</th>@endif
			</tr>
		</thead>
		<tbody class="text-center font-medium">
			@forelse ($data as $index => $item)
				@php
					$tip = $item->forecast_type === 'calc'
						? '일평균 판매 '.number_format($item->daily_avg, 1).'개 · 입고 대기 '.number_format($item->pending_qty).'개'
						: ($item->forecast_type === 'ai'
							? 'AI 예측값 · 기준일 '.$item->forecast_base_date?->format('Y-m-d').' · 모델 '.$item->forecast_model.($item->daily_avg !== null ? ' · 예측 일평균 '.number_format($item->daily_avg, 1).'개' : '').' · 입고 대기 '.number_format($item->pending_qty).'개'
							: '최근 판매 데이터가 없어 계산할 수 없습니다.');
					$alertType = $item->alert['type'] ?? null;
				@endphp
				<tr @if ($rowClick) onclick="openProductModal({{ $item->idx }})" class="hover:bg-slate-100" style="cursor:pointer;" @endif>
					<td>{{ $data->total() - (($data->currentPage() - 1) * $data->perPage() + $index) }}</td>
					<td class="text-left">{{ $categoryMap[$item->category_idx] ?? '-' }}</td>
					<td>{{ $item->company_name }}</td>
					<td class="text-left">{{ $item->product_name }}</td>
					<td class="rp-nowrap">@if ($item->barcode){{ $item->barcode }}@else<span class="rp-muted">미입력</span>@endif</td>
					<td class="rp-num {{ $item->stock_qty <= 0 ? 'text-danger' : '' }}">
						{{ number_format($item->stock_qty) }}
						@if ($alertType === 'soldout')<div class="text-danger text-xs">재고소진</div>@endif
					</td>
					<td class="rp-nowrap" title="{{ $tip }}">
						@if ($item->expect_out_date)
							<span class="{{ $item->expect_out_date->lte($today) ? 'text-danger' : '' }}">{{ $item->expect_out_date->format('Y-m-d') }}</span>@if ($item->forecast_type === 'ai')<span class="rp-ai-tag">AI</span>@endif
							@if ($item->expect_out_date->lte($today) && $alertType !== 'soldout')<div class="text-danger text-xs">소진</div>@endif
						@else
							<span class="rp-muted">-</span>
						@endif
					</td>
					<td class="rp-nowrap" title="{{ $tip }}">
						@if ($item->recommend_order_date && $item->recommend_qty > 0)
							<span class="{{ $item->recommend_order_date->lte($today) ? 'text-danger' : '' }}">{{ $item->recommend_order_date->format('Y-m-d') }}</span>
							@if ($alertType === 'urgent')
								<div class="text-danger text-xs">즉시 발주</div>
							@elseif ($alertType === 'soon')
								<div class="rp-recommend text-xs">발주추천</div>
							@endif
						@elseif ($item->recommend_order_date)
							<span class="rp-muted">발주 불필요</span>
						@else
							<span class="rp-muted">-</span>
						@endif
					</td>
					<td class="rp-num" title="{{ $tip }}">{!! $item->recommend_qty !== null ? number_format($item->recommend_qty) : '<span class="rp-muted">-</span>' !!}</td>
					<td>{{ $item->lead_days !== null ? $item->lead_days.'일' : '-' }}</td>
					@if ($orderRid)
						<td onclick="event.stopPropagation();">
							<a href="{{ route('rips.order.create', ['rid' => $orderRid, 'product' => $item->idx]) }}" class="btn btn-sm btn-primary rp-nowrap">발주하기</a>
						</td>
					@endif
				</tr>
			@empty
				<tr>
					<td colspan="{{ $cols }}" class="rp-muted">
						{{ request('status') ? '해당하는 상품이 없습니다.' : '등록된 상품이 없습니다.' }}
						@if (!$categoryMap && $orderRid)
							<br>먼저 <a href="{{ route('rips.category', ['rid' => $orderRid]) }}" class="text-primary underline">카테고리</a>를 등록해주세요.
						@endif
					</td>
				</tr>
			@endforelse
		</tbody>
	</table>
</div>
@include('rips.common._pagination', ['data' => $data])
