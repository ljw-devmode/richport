<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">    
  <meta name="color-scheme" content="light only"/>
	<meta name="prefers-color-scheme" content="light only"/>
	<meta name="supported-color-schemes" content="light"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no, address=no, email=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

	<title>솔라테크 월별 리포트</title>
	<link rel="canonical" href="https://solarteq.co.kr/monthreport">
	    
  <link rel="icon" href="{{ asset('user_assets/img/favicon.svg') }}" type="image/svg+xml">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('user_assets/img/appleicon.png') }}">
	
	<meta name="Keywords" content="{{ $meta['keywords'] }}">
	<meta name="description" content="{{ $meta['description'] }}">
	<meta property="og:title" content="{{ $meta['og:title'] }}"/>
  <meta property="og:image" content="https://solarteq.co.kr/og_image.png"/>
  <meta property="og:description" content="{{ $meta['og:description'] }}"/>
  <meta property="twitter:title" content="{{ $meta['twitter:title'] }}"/>
  <meta property="twitter:image" content="https://solarteq.co.kr/og_image.png"/>
  <meta property="twitter:description" content="{{ $meta['twitter:description'] }}"/>
  <meta property="twitter:card" content="{{ $meta['twitter:card'] }}"/>

  <!-- css -->
  <link rel="stylesheet" href="{{ asset('user_assets/css/monthreport.css') }}" />
  <link rel="stylesheet" href="{{ asset('user_assets/css/font.css') }}" />
  <link rel="stylesheet" href="{{ asset('user_assets/css/reset.css') }}" />
  <link rel="stylesheet" href="{{ asset('user_assets/css/swiper-bundle.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('user_assets/css/fullpage_report.css') }}" />

  <!-- js -->
  <script src="{{ asset('user_assets/js/jquery-1.12.4.min.js') }}"></script>
  <script src="{{ asset('user_assets/js/lenis.min.js') }}"></script>
  <script src="{{ asset('user_assets/js/aos.js') }}"></script>
  <script src="{{ asset('user_assets/js/scrolloverflow.min.js') }}"></script>
  <script src="{{ asset('user_assets/js/fullpage.js') }}"></script>
  <script src="{{ asset('user_assets/js/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('user_assets/js/common.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head> 

<body>
<!-- fullpage wrapper -->
  <div id="fullpage">

    <!-- 1) 표지 -->
    <section class="section page">
      <div class="cover page-inner">
        <header class="cover-header">
          <div class="logo">
            <img src="{{ asset('user_assets/img/logo.svg') }}" alt="">
            <div class="tagline">당신의 에너지 자산 파트너</div>
          </div>
          <div class="divider"></div>
        </header>

        <section class="cover-title">
          <p class="report-date">{{ $data['year'] }}년 {{ $data['month'] }}월 월간보고서</p>
          <h1>{{ number_format($data['totalVolume'] / 1000, 3) }} MWp</h1>
          <h3>솔라테크에너지 태양광발전설비</h3>
        </section>

        <footer class="cover-footer">
          <p class="doc-date">{{ $data['today'] }}</p>
          <p class="company">솔라테크에너지</p>
          <p class="logo-bottom"><img src="{{ asset('user_assets/img/logo.svg') }}" alt=""></p>
        </footer>
      </div>
    </section>

    <!-- 2) 요약 -->
    <section class="section page">
      <div class="coversub page-inner">
        <!-- 페이지 헤더 -->
        <div class="page-top">
          <div class="top-title">관리 운영 월간보고서</div>
          <div class="top-line"></div>
        </div>

        <div class="page-center-title">요 약</div>

        <!-- 발전 현황 -->
        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>발전 현황</span>
          </div>

          <div class="table-wrap">
            <table class="report-table">
              <thead>
                <tr>
                  <th>발전소명</th>
                  @foreach($companies as $c)
                      <th>{{ $c->comp_name }}</th>
                  @endforeach
                </tr>
              </thead>

              <tbody>
                <tr>
                  <th class="th-left">용량(kW)</th>
                  @foreach($companies as $c)
                    <td>{{ number_format($c->volume, 2) }}</td>
                  @endforeach
                </tr>

                <tr>
                  <th class="th-left">발전량(실적)</th>
                  @foreach($companies as $c)
                    <td>{{ number_format(optional($reports[$c->idx] ?? null)->act_kwh ?? 0) }}</td>
                  @endforeach
                </tr>

                <tr>
                  <th class="th-left">목표(년)</th>
                  @foreach($companies as $c)
                    <td>{{ number_format(optional($reports[$c->idx] ?? null)->tgt_hours ?? 0, 2) }}</td>
                  @endforeach
                </tr>

                <tr>
                  <th class="th-left">평균(월)</th>
                  @foreach($companies as $c)
                    <td>{{ number_format(optional($reports[$c->idx] ?? null)->act_hours ?? 0, 2) }}</td>
                  @endforeach
                </tr>

                <tr class="row-achv">
                  <th class="th-left">달성률</th>
                  @foreach($companies as $c)
                     @php
                      $report = $reports[$c->idx] ?? null;

                      $act = $report->act_hours ?? 0;
                      $tgt = $report->tgt_hours ?? 0;

                      $rate = $tgt > 0 ? ($act / $tgt) * 100 : 0;
                    @endphp
                    <td>{{ number_format($rate, 2) }}%</td>
                  @endforeach
                </tr>

                <tr>
                  <th class="th-left">PR 목표(년)</th>
                  @foreach($companies as $c)
                    <td>83.00%</td>
                  @endforeach                  
                </tr>

                <tr>
                  <th class="th-left">PR 종합효율</th>
                  @foreach($companies as $c)
                    <td>{{ number_format(optional($reports[$c->idx] ?? null)->total_rate * 100 ?? 0, 2) }}%</td>
                  @endforeach
                </tr>

                <tr>
                  <th class="th-left">PR 달성률</th>
                  @foreach($companies as $c)
                    <td>{{ number_format(optional($reports[$c->idx] ?? null)->act_rate * 100 ?? 0, 2) }}%</td>
                  @endforeach
                </tr>
              </tbody>
            </table>
          </div>

          <div class="note">
            ※ 매출 산정은 모니터링 시스템의 인버터 발전 데이터를 근거로 산출한 금액임
          </div>
        </div>

        <!-- 운영 현황 -->
        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>운영 현황</span>
          </div>

          <ul class="dot-list">
            <li>태양광 모듈 열화 측정</li>
            <li>구조물 상태확인</li>
            <li>전기실 내 소화기 상태 확인 및 가연성 물질 정리</li>
            <li>기타 부대설비 점검계획</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- 3) Overview -->
    <section class="section page">
      <div class="coversub page-inner">
        <div class="page-top">
          <div class="top-title">Overview</div>
          <div class="top-line"></div>
        </div>

        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>솔라테크에너지</span>
          </div>

          <!-- 사업정보 상단 테이블 -->
          <div class="overviewdesc">
            <span>설비 용량 : {{ number_format($data['totalVolume'] / 1000, 3) }} MWp</span>
            <spcn>발전소 : {{ $data['companiesCount'] }} 개소</span>
          </div>
        </div>

        <!-- 년차별 테이블 -->
        @foreach($periods as $period)
        <div>
          <div class="table-wrap">
            <table class="report-table">
              <thead>
                <tr>
                  <th>발전소명</th>
                  <th>목표발전량({{ $period }}차년도)</th>
                  <th>실적발전량(누적)</th>
                  <th class="th-achv">달성율(%)</th>
                </tr>
              </thead>
              <tbody>

              @foreach($companies as $c)
                @php
                    $row = $summarydata[$period][$c->idx] ?? null;

                    $tgt_yearkwh = $row->tgt_yearkwh ?? 0;
                    $act = $row->sum_act_kwh ?? 0;
                    $start_ym = $row->start_ym ?? '';
                    $end_ym = $row->end_ym ?? '';

                    $rate = ($tgt_yearkwh > 0) ? ($act / $tgt_yearkwh * 100) : 0;
                @endphp

                <tr>
                  <th class="th-left">{{ $c->comp_name }} ({{ $start_ym }}~{{ $end_ym }}) </th>
                  <td>{{ number_format($tgt_yearkwh) }}</td>
                  <td>{{ number_format($act) }}</td>
                  <td class="td-achv">{{ number_format($rate, 2) }}%</td>
                </tr>
              @endforeach

              </tbody>
            </table>
          </div>
        </div>
        @endforeach

        <!-- 하단 주석 -->
        <div class="footnotes mt-4">
          <p>※ 전력거래소(KPX) &amp; 모니터링시스템 발전데이터 기준으로 작성</p>
          <p>※ 상업운전개시일</p>
          <ul>
          @foreach($companies as $c)
            <li>
              {{ $c->comp_name }} : {{ \Carbon\Carbon::parse($c->start_date)->format('Y-m-d') }}
            </li>
          @endforeach
          </ul>
        </div>
      </div>
    </section>

    <!-- 4) Overview - 발전 데이터 -->
    <section class="section page">
      <div class="coversub page-inner">
        <div class="page-top">
          <div class="top-title">Overview</div>
          <div class="top-line"></div>
        </div>

        <!-- 블록 1: 발전 데이터 + 그래프 -->
        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>발전 데이터</span>
          </div>

          <div class="chart-card">
            <div class="mt-2">{{ $data['year'] }}년 {{ $data['month'] }}월 발전시간</div>
            <div class="mt-2">
              <canvas id="genHoursChart" class="chart-height"></canvas>
            </div>
          </div>
        </div>

        <!-- 블록 2: 기상청 일사 데이터 -->
        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>기상청 일사 데이터</span>
          </div>

          <div class="table-wrap">
            <table class="report-table meteo">
              <thead>
                <tr>
                  <th rowspan="2" class="th-left">구분</th>
                  <th colspan="3">기상청 일사데이터 분석 (kWh/㎡)</th>
                  <th colspan="2">운영 실적 비교</th>
                </tr>
                <tr>
                  <th>전년({{ $data['lastYearSameMonth'] }}) </th>
                  <th>전월({{ $data['lastMonth'] }})</th>
                  <th>당월({{ $data['year'] }}-{{ $data['month'] }})</th>
                  <th>전년 대비</th>
                  <th>전월 대비</th>
                </tr>
              </thead>
              <tbody>
              @foreach($companies as $c)
                @php
                  $r = $reports[$c->idx] ?? null;

                  $preYear = $r->pre_year_kma ?? 0;
                  $preMonth = $r->pre_month_kma ?? 0;
                  $curMonth = $r->cur_month_kma ?? 0;

                  // 전년 대비
                  $vsYear = ($preYear > 0) ? (($curMonth - $preYear) / $preYear * 100) : 0;

                  // 전월 대비
                  $vsMonth = ($preMonth > 0) ? (($curMonth - $preMonth) / $preMonth * 100) : 0;

                  $preYearkwh = $preYear * $mjToKwh;
                  $preMonthkwh = $preMonth * $mjToKwh;
                  $curMonthkwh = $curMonth * $mjToKwh;
                @endphp

                <tr>
                  <th class="th-left">{{ $c->comp_name }}</th>
                  <td>{{ number_format($preYearkwh, 2) }}</td>
                  <td>{{ number_format($preMonthkwh, 2) }}</td>
                  <td>{{ number_format($curMonthkwh, 2) }}</td>
                  <td>{{ number_format($vsYear, 2) }}%</td>
                  <td>{{ number_format($vsMonth, 2) }}%</td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <!-- 5) Operating Management - PV 정기점검 내역 -->
    <section class="section page">
      <div class="coversub page-inner">
        <div class="page-top">
          <div class="top-title">Operating Management</div>
          <div class="top-line"></div>
        </div>

        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>O&M 현황</span>
          </div>

          <!-- 테이블 -->
          <div class="table-wrap pv-wrap">
            <table class="report-table pv-table">
              <thead>
                <tr>
                  <th rowspan="2" class="w-10">월</th>
                  <th colspan="{{ $companies->count() }}">정기 점검</th>
                  <th rowspan="2">긴급 출동</th>
                  <!-- <th rowspan="2">소모품 교체</th> -->
                </tr>
                <tr>
                  @foreach($companies as $c)
                      <th>{{ $c->comp_name }}</th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @foreach($yearManageData as $row)
                <tr>
                  <th class="th-left">{{ $row['month'] }}</th>

                  @foreach($companies as $company)
                    <td>{{ $row[$company->idx] }}</td>
                  @endforeach
  
                  <td>{{ $row['monthly_emergency'] }}</td>
                  <!-- <td>{{ $row['monthly_change'] }}</td> -->
                </tr>
                @endforeach

                <tr class="sum-row">
                  <th class="th-left">합 계</th>
                  
                  @foreach($companies as $company)
                      <td>{{ $yearManageSummary[$company->idx] }}</td>
                  @endforeach

                  <td>{{ $yearManageSummary['total_emergency'] }}</td>
                  <!-- <td>{{ $yearManageSummary['total_change'] }}</td> -->
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Performance Management -->
        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>Performance Management</span>
          </div>

          <div class="table-wrap">
            <table class="report-table op-table">
              <thead>
                <tr>
                  <th class="w-48">점검 예정</th>
                  <th>업무 내역</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{ $data['next1Month'] }}</td>
                  <td class="td-left">정기 점검 및 전기안전 관련 설비 점검</td>
                </tr>
                <tr>
                  <td>{{ $data['next2Month'] }}</td>
                  <td class="td-left">정기 점검 및 전기안전 관련 설비 점검</td>
                </tr>
                <tr>
                  <td>{{ $data['next3Month'] }}</td>
                  <td class="td-left">정기 점검 및 전기안전 관련 설비 점검</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>


    <!-- 6) Operating Management -->
    <section class="section page">
      <div class="coversub page-inner">
        <div class="page-top">
          <div class="top-title">Operating Management</div>
          <div class="top-line"></div>
        </div>

        <!-- 1) 주요 업무 내역 -->
        <!-- <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>정기 점검</span>
          </div>

          <div class="table-wrap">
            <table class="report-table op-table">
              <thead>
                <tr>
                  <th>날짜</th>
                  <th>발전소 명</th>
                  <th>점검 내역</th>
                  <th>점검 결과</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{ $data['start_date'] }} ~ {{ $data['end_date'] }}</td>
                  <td class="td-left">전기안전 관리자 정기 점검</td>
                </tr>
                @forelse($manageData['정기 점검'] as $row)
                  <tr>
                      <td>{{ $row['days'] }}</td>
                      <td>{{ $row['comp_name'] }}</td>
                      <td>{{ $row['manage'] ?? '정기 점검' }}</td>
                      <td>{{ $row['result'] ?? '이상 없음' }}</td>
                  </tr>
                @empty
                  <tr><td>-</td><td>-</td><td>-</td><td>-</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div> -->

        <!-- 1) 발전소 정기 점검 내역 -->
        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>정기 점검</span>
          </div>

          <div class="table-wrap">
            <table class="report-table op-table">
              <thead>
                <tr>
                  <th>날짜</th>
                  <th>발전소 명</th>
                  <th>점검 내역</th>
                  <th>점검 결과</th>
                </tr>
              </thead>
              <tbody>
                @forelse($checkData as $row)
                  <tr>
                      <td>{{ $row['day'] }}</td>
                      <td>{{ $row['comp_name'] }}</td>
                      <td>{{ $row['manage'] ?? '정기 점검' }}</td>
                      <td>{{ $row['result'] ?? '이상 없음' }}</td>
                  </tr>
                @empty
                  <tr><td>-</td><td>-</td><td>-</td><td>-</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- 2) 발전소 긴급출동 내역 -->
        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>긴급 출동</span>
          </div>

          <div class="table-wrap">
            <table class="report-table op-table">
              <thead>
                <tr>
                  <th>날짜</th>
                  <th>발전소 명</th>
                  <th>출동 내역</th>
                  <th>출동 결과</th>
                </tr>
              </thead>
              <tbody>
                @forelse($emergencyData as $row)
                  <tr>
                      <td>{{ $row['day'] }}</td>
                      <td>{{ $row['comp_name'] }}</td>
                      <td>{{ $row['manage'] ?? '비 정기 점검' }}</td>
                      <td>{{ $row['result'] ?? '이상 없음' }}</td>
                  </tr>
                @empty
                  <tr><td>-</td><td>-</td><td>-</td><td>-</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>


    <!-- 7) Operating Management - Performance / 이슈사항 -->
    <section class="section page">
      <div class="coversub page-inner">
        <div class="page-top">
          <div class="top-title">Operating Management</div>
          <div class="top-line"></div>
        </div>

        <!-- 2) 소모품 교체 관리 -->
        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>소모품 교체</span>
          </div>

          <div class="table-wrap">
            <table class="report-table op-table">
              <thead>
                <tr>
                  <th>날짜</th>
                  <th>발전소 명</th>
                  <th>교체 내역</th>
                  <th>교체 수량</th>
                </tr>
              </thead>
              <tbody>
              @forelse($changeData as $row)
                <tr>
                    <td>{{ $row['day'] }}</td>
                    <td>{{ $row['comp_name'] }}</td>
                    <td>{{ $row['manage'] ?? '소모품 확인' }}</td>
                    <td>{{ $row['count'] ?? '교체 없음' }}</td>
                </tr>
              @empty
                <tr><td>-</td><td>-</td><td>-</td><td>-</td></tr>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- 이슈사항 -->
        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>이슈</span>
          </div>

          <div class="table-wrap">
            <table class="report-table op-table">
              <thead>
                <tr>
                  <th style="width: 160px;">날짜</th>
                  <th>발전소명</th>
                  <th>이슈 내용</th>
                </tr>
              </thead>
              <tbody>
              @forelse($issueData as $row)
                <tr>
                    <td>{{ $row['day'] }}</td>
                    <td>{{ $row['comp_name'] }}</td>
                    <td>{{ $row['manage'] ?? '특이사항 없음' }}</td>
                </tr>
              @empty
                <tr>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </section>

  </div>

  <script>
  // fullpage
  $('#fullpage').fullpage({
      scrollingSpeed:1000,
      autoScrolling:true,
      normalScrollElements: '.in_scroll'
  });
  $(window).on('resize', function() {
      $.fn.fullpage.reBuild();
  });


  // 4페이지 막대그래프: 2월 발전시간
  // (수치는 예시값이야. 이미지/원본 데이터에 맞춰 바꿔주면 됨)
  const labels = @json($labels);
  const values = @json($values);

  console.log("labels " + labels);
  console.log("values " + values);

  const ctx = document.getElementById('genHoursChart');
  if (ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: `{{ $data['month'] }}월 발전시간`,
          data: values,
          borderWidth: 0,
          // 이미지처럼 “같은 계열 색상”로 그라데이션 느낌을 주기 위해 개별 색상 배열
          backgroundColor: [
            '#74380a', '#a65a20', '#b76422', '#c76b23', '#d87324', '#eaa58c', '#f0b8a3', '#f4d0c2' 
          ]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }, // 아래 커스텀 범례를 쓰기 위해 숨김
          title: { display: false }
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { font: { size: 12 } }
          },
          y: {
            min: 0,
            max: 5,
            beginAtZero: true,
            ticks: { stepSize: 1, autoSkip: false, font: { size: 16 } },
            grid: { color: '#e6e6e6' }
          }
        }
      }
    });
  }   
    

  let fpInstance = null;

  document.addEventListener('DOMContentLoaded', function () {
      fpInstance = new fullpage('#fullpage', {
          licenseKey: 'gplv3-license',
          autoScrolling: true,
          fitToSection: true,
          navigation: true,
          scrollingSpeed: 700
      });
  });

  function beforePrintHandler() {
      if (window.fullpage_api) {
          window.fullpage_api.setAutoScrolling(false);
          window.fullpage_api.setFitToSection(false);
          window.fullpage_api.setKeyboardScrolling(false);
      }
  }

  function afterPrintHandler() {
      if (window.fullpage_api) {
          window.fullpage_api.setAutoScrolling(true);
          window.fullpage_api.setFitToSection(true);
          window.fullpage_api.setKeyboardScrolling(true);
      }
  }

  window.addEventListener('beforeprint', beforePrintHandler);
  window.addEventListener('afterprint', afterPrintHandler);
  
  </script>

</body>
</html>