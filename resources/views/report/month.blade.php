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
          <p class="report-date">2026년 02월 월간보고서</p>
          <h1>4.770 MWp</h1>
          <h3>솔라테크에너지 태양광발전설비</h3>
        </section>

        <footer class="cover-footer">
          <p class="doc-date">2026.03</p>
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
                  <th class="th-left">발전소명</th>
                  <th>대현</th>
                  <th>삼포산업</th>
                  <th>디엠산업</th>
                  <th>라이프산업</th>
                  <th>신일스틸</th>
                  <th>성지산업</th>
                  <th>산수아이앤씨</th>
                </tr>
              </thead>

              <tbody>
                <tr>
                  <th class="th-left">용량(kW)</th>
                  <td>1,435.20</td>
                  <td>1,211.00</td>
                  <td>960.49</td>
                  <td>298.20</td>
                  <td>403.20</td>
                  <td>117.60</td>
                  <td>345.10</td>
                </tr>

                <tr>
                  <th class="th-left">발전량(실적)</th>
                  <td>134,751</td>
                  <td>115,862</td>
                  <td>91,853</td>
                  <td>28,560</td>
                  <td>44,871</td>
                  <td>12,547</td>
                  <td>33,744</td>
                </tr>

                <tr>
                  <th class="th-left">목표(년)</th>
                  <td>3.13</td>
                  <td>3.13</td>
                  <td>3.36</td>
                  <td>3.45</td>
                  <td>3.22</td>
                  <td>3.41</td>
                  <td>3.13</td>
                </tr>

                <tr>
                  <th class="th-left">평균(월)</th>
                  <td>3.35</td>
                  <td>3.42</td>
                  <td>3.42</td>
                  <td>3.42</td>
                  <td>3.97</td>
                  <td>3.81</td>
                  <td>3.49</td>
                </tr>

                <tr class="row-achv">
                  <th class="th-left">달성률</th>
                  <td>107.13%</td>
                  <td>109.17%</td>
                  <td>101.65%</td>
                  <td>99.15%</td>
                  <td>123.43%</td>
                  <td>111.74%</td>
                  <td>111.57%</td>
                </tr>

                <tr>
                  <th class="th-left">PR 목표(년)</th>
                  <td>83.00%</td>
                  <td>83.00%</td>
                  <td>83.00%</td>
                  <td>83.00%</td>
                  <td>83.00%</td>
                  <td>83.00%</td>
                  <td>83.00%</td>
                </tr>

                <tr>
                  <th class="th-left">PR 종합효율</th>
                  <td>100.89%</td>
                  <td>102.81%</td>
                  <td>105.18%</td>
                  <td>101.66%</td>
                  <td>112.06%</td>
                  <td>105.93%</td>
                  <td>105.07%</td>
                </tr>

                <tr>
                  <th class="th-left">PR 달성률</th>
                  <td>121.55%</td>
                  <td>123.86%</td>
                  <td>126.72%</td>
                  <td>122.48%</td>
                  <td>135.01%</td>
                  <td>127.62%</td>
                  <td>126.59%</td>
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
            <span>사업 정보</span>
          </div>

          <!-- 사업정보 상단 테이블 -->
          <div class="mini-table-wrap">
            <table class="mini-table">
              <tbody>
                <tr>
                  <th>Project</th>
                  <td>솔라테크에너지</td>
                </tr>
                <tr>
                  <th>설비 용량</th>
                  <td>4,770.79 [kWp], 발전소 7개소</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 1차년도 테이블 -->
        <div class="block">
          <div class="table-wrap">
            <table class="report-table">
              <thead>
                <tr>
                  <th class="th-left">발전소명</th>
                  <th>목표발전량(년)</th>
                  <th>실적발전량(누적)</th>
                  <th class="th-achv">달성율(%)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th class="th-left">디엠산업(1차년도)</th>
                  <td>1,227,026</td>
                  <td>1,429,480</td>
                  <td class="td-achv">116.50%</td>
                </tr>
                <tr>
                  <th class="th-left">대현(1차년도)</th>
                  <td>1,833,468</td>
                  <td>1,879,497</td>
                  <td class="td-achv">102.51%</td>
                </tr>
                <tr>
                  <th class="th-left">라이프산업(1차년도)</th>
                  <td>380,951</td>
                  <td>392,127</td>
                  <td class="td-achv">102.93%</td>
                </tr>
                <tr>
                  <th class="th-left">성지산업(1차년도)</th>
                  <td>150,234</td>
                  <td>168,593</td>
                  <td class="td-achv">112.22%</td>
                </tr>
                <tr>
                  <th class="th-left">산수아이앤씨(1차년도)</th>
                  <td>440,865</td>
                  <td>475,695</td>
                  <td class="td-achv">107.90%</td>
                </tr>
                <tr>
                  <th class="th-left">신일스틸(1차년도)</th>
                  <td>515,088</td>
                  <td>557,569</td>
                  <td class="td-achv">108.25%</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 2차년도 테이블 -->
        <div class="block">
          <div class="table-wrap">
            <table class="report-table">
              <thead>
                <tr>
                  <th class="th-left">발전소명</th>
                  <th>목표발전량(년)</th>
                  <th>실적발전량(누적)</th>
                  <th>달성율(%)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th class="th-left">대현(2차년도)</th>
                  <td>1,824,301</td>
                  <td>279,371</td>
                  <td>15.31%</td>
                </tr>
                <tr>
                  <th class="th-left">삼포산업</th>
                  <td>1,547,053</td>
                  <td>654,076</td>
                  <td>42.28%</td>
                </tr>
                <tr>
                  <th class="th-left">디엠산업(2차년도)</th>
                  <td>1,220,891</td>
                  <td>512,042</td>
                  <td>26.84%</td>
                </tr>
                <tr>
                  <th class="th-left">라이프산업(2차년도)</th>
                  <td>379,046</td>
                  <td>24,934</td>
                  <td>6.58%</td>
                </tr>
                <tr>
                  <th class="th-left">신일스틸(2차년도)</th>
                  <td>512,513</td>
                  <td>44,871</td>
                  <td>8.76%</td>
                </tr>
                <tr>
                  <th class="th-left">성지산업</th>
                  <td>149,483</td>
                  <td>24,350</td>
                  <td>16.29%</td>
                </tr>
                <tr>
                  <th class="th-left">산수아이앤씨(2차년도)</th>
                  <td>438,661</td>
                  <td>65,477</td>
                  <td>14.93%</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 하단 주석 -->
        <div class="footnotes">
          <p>※ 전력거래소(KPX) &amp; 모니터링시스템 발전데이터 기준으로 작성</p>
          <p>※ 상업운전개시일</p>
          <ul>
            <li>대현 : 2024.12.11</li>
            <li>디엠산업 : 2024.09.30</li>
            <li>라이프산업 : 2024.12.27</li>
            <li>성지산업 : 2025.01.21</li>
            <li>산수아이앤씨 : 2025.01.05</li>
            <li>신일스틸 : 2025.02.19</li>
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
            <div class="chart-title">2월 발전시간</div>
            <div class="chart-area">
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
                  <th>전년</th>
                  <th>전월</th>
                  <th>당월</th>
                  <th>전년 대비</th>
                  <th>전월 대비</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th class="th-left">경주(디엠산업)</th>
                  <td>118.11</td>
                  <td>100.93</td>
                  <td>90.92</td>
                  <td>-23.02%</td>
                  <td>-9.91%</td>
                </tr>
                <tr>
                  <th class="th-left">음성(대현, 삼포, 산수)</th>
                  <td>99.54</td>
                  <td>81.72</td>
                  <td>93.06</td>
                  <td>-6.51%</td>
                  <td>13.87%</td>
                </tr>
                <tr>
                  <th class="th-left">동두천(라이프산업)</th>
                  <td>105.96</td>
                  <td>81.94</td>
                  <td>94.21</td>
                  <td>-11.08%</td>
                  <td>14.98%</td>
                </tr>
                <tr>
                  <th class="th-left">천안(신일스틸)</th>
                  <td>108.02</td>
                  <td>88.94</td>
                  <td>99.31</td>
                  <td>-8.06%</td>
                  <td>11.66%</td>
                </tr>
                <tr>
                  <th class="th-left">화성(성지산업)</th>
                  <td>109.25</td>
                  <td>88.71</td>
                  <td>100.72</td>
                  <td>-7.80%</td>
                  <td>13.54%</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <!-- 5) Operating Management -->
    <section class="section page">
      <div class="coversub page-inner">
        <div class="page-top">
          <div class="top-title">Operating Management</div>
          <div class="top-line"></div>
        </div>

        <!-- 1) 주요 업무 내역 -->
        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>주요 업무 내역</span>
          </div>

          <div class="table-wrap">
            <table class="report-table op-table">
              <thead>
                <tr>
                  <th style="width: 160px;">일 시</th>
                  <th>업무 내역</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>02.01~02.28</td>
                  <td class="td-left">전기안전관리자 - 정기점검</td>
                </tr>
                <tr>
                  <td>02/02, 02/10, 02/19, 02/25</td>
                  <td class="td-left">대현 - 정기점검</td>
                </tr>
                <tr>
                  <td>02/03, 02/09, 02/20, 02/25</td>
                  <td class="td-left">삼포산업 - 정기점검</td>
                </tr>
                <tr>
                  <td>02/03, 02/11, 02/20, 02/25</td>
                  <td class="td-left">디엠산업 - 정기점검</td>
                </tr>
                <tr>
                  <td>02/05</td>
                  <td class="td-left">라이프산업 - 정기점검</td>
                </tr>
                <tr>
                  <td>02/04, 02/20</td>
                  <td class="td-left">신일스틸 - 정기점검</td>
                </tr>
                <tr>
                  <td>02/06</td>
                  <td class="td-left">성지산업 - 정기점검</td>
                </tr>
                <tr>
                  <td>02/04, 02/19</td>
                  <td class="td-left">산수아이앤씨 - 정기점검</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 2) 소모품 교체 관리 -->
        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>소모품 교체 관리</span>
          </div>

          <div class="table-wrap">
            <table class="report-table op-table">
              <thead>
                <tr>
                  <th style="width: 160px;">일 시</th>
                  <th>교체 내역</th>
                  <th style="width: 120px;">수량</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>-</td>
                  <td class="td-left">-</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td>-</td>
                  <td class="td-left">-</td>
                  <td>-</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 3) 발전소 긴급출동 내역 -->
        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>발전소 긴급출동 내역</span>
          </div>

          <div class="table-wrap">
            <table class="report-table op-table">
              <thead>
                <tr>
                  <th style="width: 160px;">일 시</th>
                  <th>처리 내역</th>
                  <th style="width: 140px;">처리 결과</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>-</td>
                  <td class="td-left">-</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td>-</td>
                  <td class="td-left">-</td>
                  <td>-</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>


    <!-- 6) Operating Management - PV 정기점검 내역 -->
    <section class="section page">
      <div class="coversub page-inner">
        <div class="page-top">
          <div class="top-title">Operating Management</div>
          <div class="top-line"></div>
        </div>

        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>PV 정기점검 내역</span>
          </div>

          <!-- 상단 테이블 -->
          <div class="table-wrap pv-wrap">
            <table class="report-table pv-table">
              <thead>
                <tr>
                  <th style="width: 90px;">2026년</th>
                  <th>대현</th>
                  <th>삼포산업</th>
                  <th>디엠산업</th>
                  <th style="width: 110px;">긴급출동</th>
                </tr>
              </thead>
              <tbody>
                <tr><th class="th-left">1월</th><td>06, 13, 19, 26</td><td>05, 13, 19, 26</td><td>06, 12, 22, 28</td><td>0</td></tr>
                <tr><th class="th-left">2월</th><td>02, 10, 19, 25</td><td>03, 11, 20, 25</td><td>03, 11, 20, 25</td><td>0</td></tr>
                <tr><th class="th-left">3월</th><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">4월</th><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">5월</th><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">6월</th><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">7월</th><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">8월</th><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">9월</th><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">10월</th><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">11월</th><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">12월</th><td></td><td></td><td></td><td>0</td></tr>

                <tr class="sum-row">
                  <th class="th-left">합 계</th>
                  <td>4</td>
                  <td>4</td>
                  <td>4</td>
                  <td>0</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- 하단 테이블 -->
          <div class="table-wrap pv-wrap">
            <table class="report-table pv-table">
              <thead>
                <tr>
                  <th style="width: 90px;">2026년</th>
                  <th>라이프산업</th>
                  <th>신일스틸</th>
                  <th>성지산업</th>
                  <th>산수아이앤씨</th>
                  <th style="width: 110px;">긴급출동</th>
                </tr>
              </thead>
              <tbody>
                <tr><th class="th-left">1월</th><td>14</td><td>07, 19</td><td>08</td><td>10, 28</td><td>0</td></tr>
                <tr><th class="th-left">2월</th><td>05</td><td>04, 20</td><td>06</td><td>04, 19</td><td>0</td></tr>
                <tr><th class="th-left">3월</th><td></td><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">4월</th><td></td><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">5월</th><td></td><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">6월</th><td></td><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">7월</th><td></td><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">8월</th><td></td><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">9월</th><td></td><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">10월</th><td></td><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">11월</th><td></td><td></td><td></td><td></td><td>0</td></tr>
                <tr><th class="th-left">12월</th><td></td><td></td><td></td><td></td><td>0</td></tr>

                <tr class="sum-row">
                  <th class="th-left">합 계</th>
                  <td>1</td>
                  <td>2</td>
                  <td>1</td>
                  <td>2</td>
                  <td>0</td>
                </tr>
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
                  <th style="width: 170px;">점검 예정</th>
                  <th>업무 내역</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>2026년 03월</td>
                  <td class="td-left">정기 점검 및 전기안전 관련 설비 점검</td>
                </tr>
                <tr>
                  <td>2026년 04월</td>
                  <td class="td-left">정기 점검 및 전기안전 관련 설비 점검</td>
                </tr>
                <tr>
                  <td>2026년 05월</td>
                  <td class="td-left">정기 점검 및 전기안전 관련 설비 점검</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 이슈사항 -->
        <div class="block">
          <div class="block-title">
            <span class="sq"></span>
            <span>이슈사항</span>
          </div>

          <div class="issue-wrap">
            <table class="report-table issue-table">
              <thead>
                <tr>
                  <th>이슈 사항</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="issue-body">
                    <div class="issue-item">- 26년도 2월 특이사항 X</div>
                  </td>
                </tr>
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
  const labels = ['대현','삼포산업','디엠산업','라이프산업','신일스틸','성지산업','산수아이앤씨'];
  const values = [3.35, 3.42, 3.42, 3.42, 3.97, 3.81, 3.49];

  const ctx = document.getElementById('genHoursChart');
  if (ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: '2월 발전시간',
          data: values,
          borderWidth: 0,
          // 이미지처럼 “같은 계열 색상”로 그라데이션 느낌을 주기 위해 개별 색상 배열
          backgroundColor: [
            '#a65a20', '#b76422', '#c76b23', '#d87324', '#eaa58c', '#f0b8a3', '#f4d0c2'
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