@php
	$header_skip = false;
	$depth1_num = '1';
	$depth2_num = '1';
@endphp

@include('admin.header._header')
<style>
	.ds-grid {display:grid; grid-template-columns:repeat(12, minmax(0, 1fr)); gap:16px; }
	.ds-col-3 {grid-column:span 3; }
	.ds-col-4 {grid-column:span 4; }
	.ds-col-6 {grid-column:span 6; }
	.ds-col-8 {grid-column:span 8; }
	.ds-col-12 {grid-column:span 12; }
	.ds-box {display:flex; flex-direction:column; height:100%; padding:20px; border:1px solid #e2e8f0; border-radius:8px; background:#fff; }
	.ds-box-head {display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
	.ds-box-head h4 {font-size:15px; font-weight:700; }
	.ds-more {font-size:12px; color:#64748b; white-space:nowrap; }
	.ds-more:hover {color:rgb(var(--color-primary)); }
	.ds-stats {display:flex; gap:12px; }
	.ds-stat {flex:1; min-width:0; }
	.ds-stat + .ds-stat {padding-left:12px; border-left:1px solid #e2e8f0; }
	.ds-stat .label {font-size:12px; color:#64748b; }
	.ds-stat .value {margin-top:4px; font-size:24px; font-weight:700; line-height:1.2; overflow-wrap:anywhere; }
	.ds-stat .value small {margin-left:2px; font-size:12px; font-weight:500; color:#64748b; }
	.ds-stat .sub {margin-top:4px; font-size:12px; color:#64748b; }
	.ds-stat .sub b {color:rgb(var(--color-primary)); font-weight:600; }
	.ds-recent {margin-top:16px; }
	.ds-table {width:100%; font-size:13px; }
	.ds-table th {padding:8px; background:#f8fafc; color:#475569; font-weight:600; white-space:nowrap; text-align:center; }
	.ds-table td {padding:9px 8px; border-top:1px solid #f1f5f9; text-align:center; }
	.ds-table td.left {max-width:0; width:100%; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; text-align:left; }
	.ds-table td.right {text-align:right; }
	.ds-table td.nowrap {white-space:nowrap; }
	.ds-table tbody tr[onclick] {cursor:pointer; }
	.ds-table tbody tr[onclick]:hover {background:#f8fafc; }
	.ds-empty {padding:24px 8px !important; color:#94a3b8; }
	.ds-issue-title {display:flex; align-items:center; gap:6px; min-width:0; }
	.ds-issue-title span {overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
	.ds-badge {display:inline-block; padding:2px 9px; border-radius:999px; font-size:12px; font-weight:600; white-space:nowrap; background:#f1f5f9; color:#475569; }
	.ds-badge.ordered, .ds-badge.inquire-1   {background:#e0f2fe; color:#0369a1; }
	.ds-badge.preparing, .ds-badge.inquire-2 {background:#fef3c7; color:#b45309; }
	.ds-badge.shipping  {background:#ede9fe; color:#6d28d9; }
	.ds-badge.customs   {background:#fce7f3; color:#be185d; }
	.ds-badge.received, .ds-badge.inquire-3  {background:#dcfce7; color:#15803d; }
	.ds-badge.inquire-4 {background:#ede9fe; color:#6d28d9; }
	.ds-badge.inquire-5 {background:#ccfbf1; color:#0f766e; }
	.ds-badge.soldout, .ds-badge.urgent {background:#fee2e2; color:#dc2626; }
	.ds-badge.soon      {background:#dbeafe; color:#2563eb; }
	.ds-badge.off       {background:#f8fafc; color:#cbd5e1; }
	.ds-badge.canceled  {background:#f1f5f9; color:#94a3b8; text-decoration:line-through; }
</style>
<div class="mt-6 px-5 pb-6">
  <div class="topbar_admin">
	  <h3 class="intro-y text-xl font-bold">대시보드</h3>
</div>

  <main class="intro-y mt-6">
    <div class="ds-grid">

      @if($partners !== null)
      <div class="{{ $products !== null ? 'ds-col-6' : 'ds-col-12' }}">
        <div class="ds-box">
          <div class="ds-box-head">
            <h4>파트너 현황</h4>
            <a href="/admin/partner" class="ds-more">더보기</a>
          </div>
          <div class="ds-stats">
            @foreach (['korea' => '한국', 'vietnam' => '베트남'] as $country => $label)
            <div class="ds-stat">
              <p class="label">{{ $label }}</p>
              <p class="value">{{ number_format($partners[$country]->total ?? 0) }}<small>개</small></p>
              <p class="sub">이번달 등록 <b>{{ number_format($partners[$country]->month_count ?? 0) }}</b></p>
            </div>
            @endforeach
          </div>
        </div>
      </div>
      @endif

      @if($products !== null)
      <div class="{{ $partners !== null ? 'ds-col-3' : 'ds-col-6' }}">
        <div class="ds-box">
          <div class="ds-box-head">
            <h4>등록 상품</h4>
            <a href="/admin/product" class="ds-more">더보기</a>
          </div>
          <div class="ds-stats">
            <div class="ds-stat">
              <p class="label">전체</p>
              <p class="value">{{ number_format($products->total) }}<small>개</small></p>
              <p class="sub">이번달 등록 <b>{{ number_format($products->month_count) }}</b></p>
            </div>
          </div>
        </div>
      </div>
      <div class="{{ $partners !== null ? 'ds-col-3' : 'ds-col-6' }}">
        <div class="ds-box">
          <div class="ds-box-head">
            <h4>CongBo</h4>
            <a href="/admin/product" class="ds-more">더보기</a>
          </div>
          <div class="ds-stats">
            <div class="ds-stat">
              <p class="label">전체</p>
              <p class="value">{{ number_format($products->congbo_total) }}<small>개</small></p>
              <p class="sub">이번달 등록 <b>{{ number_format($products->congbo_month_count) }}</b></p>
            </div>
          </div>
        </div>
      </div>
      @endif

      @if($topSales !== null)
      <div class="{{ $orderAlerts !== null ? 'ds-col-6' : 'ds-col-12' }}">
        <div class="ds-box">
          <div class="ds-box-head">
            <h4>이번달 판매 순위 <span class="ds-more">{{ now()->format('Y년 n월') }}, 리치포트 상품 기준</span></h4>
            <a href="/admin/ripsSales?ym={{ now()->format('Y-m') }}" class="ds-more">더보기</a>
          </div>
          <table class="ds-table">
            <thead>
              <tr>
                <th>순위</th>
                <th style="text-align:left;">카테고리</th>
                <th style="text-align:left;">상품</th>
                <th>판매수량</th>
                <th>판매금액</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($topSales as $i => $row)
              <tr onclick="location.href='/admin/ripsSales/monthly/{{ $row->idx }}?ym={{ now()->format('Y-m') }}'">
                <td class="nowrap">{{ $i + 1 }}</td>
                <td class="left" style="width:40%;" title="{{ $row->category }}">{{ $row->category }}</td>
                <td class="left" style="width:60%;" title="{{ $row->product_name }}">{{ $row->product_name }}</td>
                <td class="right nowrap">{{ number_format($row->qty) }}</td>
                <td class="right nowrap">{{ number_format($row->amount) }}</td>
              </tr>
              @empty
              <tr><td colspan="5" class="ds-empty">이번달 판매 데이터가 없습니다.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @endif

      @if($orderAlerts !== null)
      <div class="{{ $topSales !== null ? 'ds-col-6' : 'ds-col-12' }}">
        <div class="ds-box">
          <div class="ds-box-head">
            <h4>발주 필요 업체 <span class="ds-more">재고소진 · 즉시발주 · 발주필요(소진 {{ \App\Services\RipsForecast::ALERT_DAYS }}일 이내)</span></h4>
            <a href="/admin/ripsPartner" class="ds-more">더보기</a>
          </div>
          <table class="ds-table">
            <thead>
              <tr>
                <th style="text-align:left;">업체</th>
                @foreach (\App\Services\RipsForecast::ALERT_TYPES as $label)
                <th>{{ $label }}</th>
                @endforeach
                <th>상품 수</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($orderAlerts->take(7) as $row)
              <tr onclick="location.href='/admin/ripsPartner/{{ $row->partner->idx }}/stock'">
                <td class="left" title="{{ $row->partner->partner_name }}">{{ $row->partner->partner_name }}@if($row->partner->store_name) <span class="ds-more">· {{ $row->partner->store_name }}</span>@endif</td>
                @foreach (\App\Services\RipsForecast::ALERT_TYPES as $type => $label)
                @php $cnt = $row->counts[$type] ?? 0; @endphp
                <td class="nowrap"><span class="ds-badge {{ $cnt ? $type : 'off' }}">{{ $cnt }}</span></td>
                @endforeach
                <td class="nowrap"><b>{{ number_format($row->total) }}</b>개</td>
              </tr>
              @empty
              <tr><td colspan="5" class="ds-empty">발주가 필요한 업체가 없습니다.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @endif

      @if($orders !== null)
      <div class="{{ $customsIssues !== null ? 'ds-col-8' : 'ds-col-12' }}">
        <div class="ds-box">
          <div class="ds-box-head">
            <h4>발주 현황 <span class="ds-more">이번달, 취소 제외</span></h4>
            <a href="/admin/ripsOrder" class="ds-more">더보기</a>
          </div>
          <div class="ds-stats">
            <div class="ds-stat">
              <p class="label">발주 건수</p>
              <p class="value">{{ number_format($orders['count']) }}<small>건</small></p>
            </div>
            <div class="ds-stat">
              <p class="label">발주 총액</p>
              <p class="value">{{ number_format($orders['amount']) }}</p>
            </div>
            <div class="ds-stat">
              <p class="label">업체 수</p>
              <p class="value">{{ number_format($orders['partners']) }}<small>개</small></p>
            </div>
            <div class="ds-stat">
              <p class="label">상품 수</p>
              <p class="value">{{ number_format($orders['products']) }}<small>개</small></p>
            </div>
          </div>
          <div class="ds-recent">
            <table class="ds-table">
              <thead>
                <tr>
                  <th>발주일</th>
                  <th>발주번호</th>
                  <th style="text-align:left;">업체</th>
                  <th>발주총액</th>
                  <th>상태</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($recentOrders as $order)
                <tr onclick="location.href='/admin/ripsOrder/{{ $order->idx }}'">
                  <td class="nowrap">{{ $order->order_date->format('Y-m-d') }}</td>
                  <td class="nowrap">{{ $order->order_no }}</td>
                  <td class="left" title="{{ $order->partner->partner_name ?? '' }}">{{ $order->partner->partner_name ?? '-' }}</td>
                  <td class="right nowrap">{{ number_format($order->total_amount) }}</td>
                  <td class="nowrap"><span class="ds-badge {{ $order->status }}">{{ $order->status_text }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="ds-empty">발주 내역이 없습니다.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @endif

      @if($customsIssues !== null)
      <div class="{{ $orders !== null ? 'ds-col-4' : 'ds-col-12' }}">
        <div class="ds-box">
          <div class="ds-box-head">
            <h4>통관 이슈</h4>
            <a href="/admin/customsIssue" class="ds-more">더보기</a>
          </div>
          <table class="ds-table">
            <tbody>
              @forelse ($customsIssues as $issue)
              <tr onclick="openCustomsIssueDetail('{{ $issue->idx }}')">
                <td class="left">
                  <div class="ds-issue-title">
                    <span title="{{ $issue->title }}">{{ $issue->title }}</span>
                    @if($issue->ori_name)
                    <i data-lucide="paperclip" class="w-3.5 h-3.5 shrink-0"></i>
                    @endif
                  </div>
                </td>
              </tr>
              @empty
              <tr><td class="ds-empty">등록된 통관 이슈가 없습니다.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @endif

      @if($ripsInquires !== null)
      <div class="{{ $testbedInquires !== null ? 'ds-col-6' : 'ds-col-12' }}">
        <div class="ds-box">
          <div class="ds-box-head">
            <h4>RIPS 신청 현황</h4>
            <a href="/admin/inquireRips" class="ds-more">더보기</a>
          </div>
          <table class="ds-table">
            <thead>
              <tr>
                <th style="text-align:left;">업체명</th>
                <th>진행상태</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($ripsInquires as $inquire)
              <tr onclick="location.href='/admin/inquireRips'">
                <td class="left" title="{{ $inquire->inquire_company }}">{{ $inquire->inquire_company ?: '-' }}</td>
                <td class="nowrap"><span class="ds-badge inquire-{{ $inquire->status }}">{{ $inquire->status_kr }}</span></td>
              </tr>
              @empty
              <tr><td colspan="2" class="ds-empty">신청 내역이 없습니다.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @endif

      @if($testbedInquires !== null)
      <div class="{{ $ripsInquires !== null ? 'ds-col-6' : 'ds-col-12' }}">
        <div class="ds-box">
          <div class="ds-box-head">
            <h4>테스트베드 현황</h4>
            <a href="/admin/inquireTestbed" class="ds-more">더보기</a>
          </div>
          <table class="ds-table">
            <thead>
              <tr>
                <th style="text-align:left;">업체명</th>
                <th style="text-align:left;">상품</th>
                <th>상태</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($testbedInquires as $inquire)
              <tr onclick="location.href='/admin/inquireTestbed'">
                <td class="left" style="width:45%;" title="{{ $inquire->inquire_company }}">{{ $inquire->inquire_company ?: '-' }}</td>
                <td class="left" style="width:55%;" title="{{ $inquire->product->product_name ?? '' }}">{{ $inquire->product->product_name ?? '-' }}</td>
                <td class="nowrap"><span class="ds-badge inquire-{{ $inquire->status }}">{{ $inquire->status_kr }}</span></td>
              </tr>
              @empty
              <tr><td colspan="3" class="ds-empty">신청 내역이 없습니다.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @endif

    </div>

    @if($topSales === null && $orderAlerts === null && $partners === null && $products === null && $orders === null && $customsIssues === null && $ripsInquires === null && $testbedInquires === null)
    <p class="ds-empty text-center">표시할 수 있는 항목이 없습니다.</p>
    @endif
  </main>

  @if($customsIssues !== null)
    @include('admin.customsIssueDetail')
  @endif
</div>
</div>
<!-- END: Content -->
</div>
@include('admin.footer._footer')
