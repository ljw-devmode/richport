{{-- spcMonthReport.blade.php (수정본) --}}
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>SPC 월간 보고서</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h2>SPC 월간 보고서</h2>
<p>기준: {{ $year }}년 {{ $month }}월</p>

<table>
    <thead>
        <tr>
            <th>발전소</th>
            <th>년차</th>
            <th>목표 발전량</th>
            <th>실제 발전량</th>
            <th>목표 시간</th>
            <th>실제 시간</th>
            <th>PR</th>
            <th>달성율</th>
            <th>일사량</th>
            <th>매출</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $r)
        <tr>
            <td>{{ $r->company->name ?? '-' }}</td>
            <td>{{ $r->period }}</td>
            <td>{{ number_format($r->tgt_kwh) }}</td>
            <td>{{ number_format($r->act_kwh) }}</td>
            <td>{{ number_format($r->tgt_hours,2) }}</td>
            <td>{{ number_format($r->act_hours,2) }}</td>
            <td>{{ number_format($r->total_rate,2) }}</td>
            <td>{{ number_format($r->act_rate,2) }}</td>
            <td>{{ number_format($r->cur_month_kma,2) }}</td>
            <td>{{ number_format($r->total_sales) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
