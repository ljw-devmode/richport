<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
  <meta name="color-scheme" content="light only"/>
	<meta name="prefers-color-scheme" content="light only"/>
	<meta name="supported-color-schemes" content="light"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no, address=no, email=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <title>기상청 일사량 조회</title>

  <link rel="stylesheet" href="{{ asset('user_assets/css/compare.css') }}" />
  <link rel="canonical" href="{{ $meta['canonical'] }}">
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
</head>
<body>
  <header class="topbar_front">
    <div class="container">
      <h1 class="topbar__title">기상청 일사량 조회</h1>
    </div>
  </header>
  
  <section class="panel">
    <form method="GET" action="/ko/kma" class="toolbar">
      <label>
        관측소
        <select name="stnId" onchange="this.form.submit()">
          @foreach($stations as $s)
            <option value="{{ $s->stn_code }}" {{ (int)$stnId === (int)$s->stn_code ? 'selected' : '' }}>
              {{ $s->stn_name }} ({{ $s->stn_code }})
            </option>
          @endforeach
        </select>
      </label>

      <label>
        년도
        <select name="year" onchange="this.form.submit()">
          @for($y=2024; $y<=2027; $y++)
            <option value="{{ $y }}" {{ (int)$year === $y ? 'selected' : '' }}>{{ $y }}년</option>
          @endfor
        </select>
      </label>

      <label>
        월
        <select name="month" onchange="this.form.submit()">
          @for($m=1; $m<=12; $m++)
            <option value="{{ $m }}" {{ (int)$month === $m ? 'selected' : '' }}>{{ $m }}월</option>
          @endfor
        </select>
      </label>

      <noscript><button type="submit">조회</button></noscript>
    </form>
  </section>

<!-- <div class="small">
  선택월: {{ $target->format('Y-m') }}
  @if($isCurrentMonth)
    (현재월: 1일 ~ {{ $targetEndDay }}일(어제)까지만 조회)
  @else
    (1일 ~ 말일 조회)
  @endif
</div> -->

  <table>
    <thead>
      <tr>
        <th rowspan="2">일</th>
        <th colspan="2">{{ $prevYear->format('Y-m') }}</th>
        <th colspan="2">{{ $prevMonth->format('Y-m') }}</th>
        <th colspan="2">{{ $target->format('Y-m') }}</th>
      </tr>
      <tr>
        <th>일사량</th><th>kWh/m²</th>
        <th>일사량</th><th>kWh/m²</th>
        <th>일사량</th><th>kWh/m²</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rows as $r)
        @php
          $py_mj = $r['prev_year'];
          $pm_mj = $r['prev_month'];
          $tg_mj = $r['target'];

          $py_kwh = $py_mj !== null ? $py_mj * $mjToKwh : null;
          $pm_kwh = $pm_mj !== null ? $pm_mj * $mjToKwh : null;
          $tg_kwh = $tg_mj !== null ? $tg_mj * $mjToKwh : null;
        @endphp

        <tr>
          <td>{{ $r['day'] }}</td>

          <td>{{ $py_mj !== null ? number_format($py_mj, 2) : '' }}</td>
          <td>{{ $py_kwh !== null ? number_format($py_kwh, 2) : '' }}</td>

          <td>{{ $pm_mj !== null ? number_format($pm_mj, 2) : '' }}</td>
          <td>{{ $pm_kwh !== null ? number_format($pm_kwh, 2) : '' }}</td>

          <td>{{ $tg_mj !== null ? number_format($tg_mj, 2) : '' }}</td>
          <td>{{ $tg_kwh !== null ? number_format($tg_kwh, 2) : '' }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <th>월 합계</th>
        <th>{{ number_format($sumPrevYear, 2) }}</th>
        <th class="small">{{ number_format($sumPrevYear * $mjToKwh, 2) }}kWh/m²</th>
        <th>{{ number_format($sumPrevMon, 2) }}</th>
        <th class="small">{{ number_format($sumPrevMon * $mjToKwh, 2) }}kWh/m²</th>
        <th>{{ number_format($sumTarget, 2) }}</th>
        <th class="small">{{ number_format($sumTarget * $mjToKwh, 2) }}kWh/m²</th>
      </tr>
    </tfoot>
  </table>

</body>
</html>