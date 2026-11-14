{{--
	업체 판매 목록 (RIPS > 판매관리, 관리자 > RIPS 업체 상세 > 판매 현황 공통)
	$data : RipsSales::listFor paginator, $categoryMap : 업체 카테고리 경로, $clickable : 행 클릭 시 수정 모달 (RIPS)
--}}
@use('App\Models\RipsSales')
@php $clickable = $clickable ?? false; @endphp
<div class="rp-table-wrap">
	<table class="table table-bordered">
		<thead class="bg-slate-50 text-center">
			<tr>
				<th class="rp-nowrap">판매일자</th>
				<th class="rp-nowrap">구분</th>
				<th class="rp-nowrap">카테고리</th>
				<th class="rp-nowrap">상품명</th>
				<th class="rp-nowrap">총 판매수량</th>
				<th class="rp-nowrap">총 판매가</th>
				<th class="rp-nowrap">상품별 가격</th>
				<th class="rp-nowrap">잔여수량</th>
				<th class="rp-nowrap">프로모션</th>
				<th class="rp-nowrap">할인율</th>
				<th class="rp-nowrap">판매매대</th>
				<th class="rp-nowrap">등록경로</th>
			</tr>
		</thead>
		<tbody class="text-center font-medium">
			@forelse ($data as $item)
				@if ($item->input_type === 'stock')
				<tr @if ($clickable) onclick="openStockModal({{ $item->idx }})" class="hover:bg-slate-100" style="cursor:pointer;" @endif>
					<td class="rp-nowrap">{{ $item->sales_date->format('Y-m-d') }}</td>
					<td><span class="rp-badge stock">재고등록</span></td>
					<td class="text-left">{{ $categoryMap[$item->category_idx] ?? '-' }}</td>
					<td class="text-left">{{ $item->product->product_name ?? '-' }}</td>
					<td class="rp-muted">-</td>
					<td class="rp-muted">-</td>
					<td class="rp-muted">-</td>
					<td class="rp-num">{{ number_format($item->stock_qty) }}</td>
					<td class="rp-muted">-</td>
					<td class="rp-muted">-</td>
					<td class="rp-muted">-</td>
					<td>{{ RipsSales::SOURCES[$item->source] ?? '-' }}</td>
				</tr>
				@continue
				@endif
				<tr @if ($clickable) onclick="openSalesModal({{ $item->idx }})" class="hover:bg-slate-100" style="cursor:pointer;" @endif>
					<td class="rp-nowrap">{{ $item->sales_date->format('Y-m-d') }}@if($item->sales_time) <span class="rp-muted">{{ substr($item->sales_time, 0, 5) }}</span>@endif</td>
					<td>{{ RipsSales::INPUT_TYPES[$item->input_type] ?? '-' }}</td>
					<td class="text-left">{{ $categoryMap[$item->category_idx] ?? '-' }}</td>
					<td class="text-left">{{ $item->product->product_name ?? '-' }}</td>
					<td class="rp-num">{{ number_format($item->qty) }}</td>
					<td class="rp-num">{{ number_format($item->total_amount) }}</td>
					<td class="rp-num">{{ number_format($item->unit_price) }}</td>
					<td class="rp-num">{{ $item->stock_qty !== null ? number_format($item->stock_qty) : '-' }}</td>
					<td>
						@if ($item->promotion !== 'N')
							<span class="rp-badge promo">{{ RipsSales::PROMOTIONS[$item->promotion] ?? $item->promotion }}</span>
						@else
							<span class="rp-muted">N</span>
						@endif
					</td>
					<td class="rp-num {{ $item->discount_rate === null ? 'rp-muted' : '' }}">{{ $item->discount_rate !== null ? rtrim(rtrim(number_format($item->discount_rate, 2), '0'), '.').'%' : '-' }}</td>
					<td class="{{ $item->display_place === '' ? 'rp-muted' : '' }}">{{ RipsSales::DISPLAY_PLACES[$item->display_place] ?? '-' }}</td>
					<td>{{ RipsSales::SOURCES[$item->source] ?? '-' }}</td>
				</tr>
			@empty
				<tr><td colspan="12" class="rp-muted">등록된 판매 데이터가 없습니다.</td></tr>
			@endforelse
		</tbody>
	</table>
</div>
@include('rips.common._pagination', ['data' => $data])
