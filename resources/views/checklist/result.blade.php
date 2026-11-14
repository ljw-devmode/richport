<?php
$header_skip = true;
?>
@include('checklist.header._header')
@include('checklist.header._top')

    <div class="intro-y p-4 bg-white">
        <div class="flex justify-between items-center py-3">
            <h3 class="text-xl font-bold">점검 결과</h3>
            
            <div class="gap-10 border-slate-200">
                <button onclick="location.href='/check/main?tab=result'" class="btn btn-dark">목록으로</button>
                <button onclick="if(confirm('체크 결과를 수정하시겠습니까?')) location.href='{{ route('check.execute.copy', $execution->idx) }}'" class="btn btn-primary">수정하기</button>
                <button onclick="window.print()" class="btn btn-outline-secondary">인쇄하기</button>
            </div>
        </div>

        <div class="flex justify-between items-center mb-10 bg-slate-50 p-4 border gap-3">
            <div>
                <p class="font-bold text-primary keepall">{{ $execution->checklist->check_name }}</p>
                <p class="mt-1">{{ $execution->project_name }}</p>
            </div>
            <div>
                <p class="font-bold">{{ $execution->user_name }} / {{ $execution->user_phone }}</p>
                <p class="mt-1" class="">{{ $execution->created_at?->format('Y-m-d') }}</p>

            </div>
        </div>

        <!-- [본문] 점검 항목 결과 -->
        <div class="space-y-10">
            @foreach($execution->checklist->checksection as $section)
                <div class="section-group mt-4">
                    <h4 class="text-lg font-bold mb-3">{{ $section->section_name }}</h4>
                    <table class="mt-1 table">
                        <thead>
                            <tr>
                                @if($section->layout_type == '1')
                                <th class="py-2">세부 확인사항</th>
                                <th class="w-56 py-2">결과</th>
                                @endif
                                @if($section->layout_type == '2')
                                <th class="py-2">항목</th>
                                <th class="py-2">세부 확인사항</th>
                                <th class="w-56 py-2">결과</th>
                                @endif                                
                                @if($section->layout_type == '3')
                                <th class="py-2">구분</th>
                                <th class="py-2">항목</th>
                                <th class="py-2">세부 확인사항</th>
                                <th class="w-56 py-2">결과</th>
                                @endif                                
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // 섹션 하위 답변 필터링
                                $results = $execution->checkresult->where('section_idx', $section->idx);
                            @endphp

                            @forelse($section->checkitem as $item)
                                @php
                                    $ans = $results->where('item_idx', $item->idx)->first();
                                    
                                @endphp
                                <tr class="border-b hover:bg-slate-50">
                                    @if($section->layout_type != '1')
                                    <td class="text-center bg-slate-50/50 text-sm">{{ $item->d2_name }}</td>
                                    @endif
                                    @if($section->layout_type == '3')
                                    <td class="text-center bg-slate-50/50 text-sm">{{ $item->d3_name }}</td>
                                    @endif
                                    <td class="p-3">{{ $item->item_name }}</td>
                                    <td class="p-3 text-center font-bold">
                                        @if($item->input_type == 'radio' || $item->input_type == 'checkbox')
                                            <span class="text-slate-700">
                                                {{ $ans->checkoption->option_name ?? '미선택' }}
                                                @if($item->has_text == 'Y' && $ans?->result_value != null)
                                                    <br/>
                                                    비고: {{ $ans->result_value ?? '-' }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-slate-700">{{ $ans->result_value ?? '-' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-slate-400">데이터가 없습니다.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>

        <!-- [하단] 위치 및 서명 -->
        <div class="mt-7">
            <!-- 위치 정보 -->
            <h4 class="font-bold mb-4 flex items-center gap-2">점검 위치</h4>
            <div class="mt-1 bg-slate-100 p-4 rounded-lg flex items-center gap-3 bg-slate-50 p-4 rounded-lg border ">
                @php 
                    // 1. 값이 존재하고 비어있지 않은지 확인 (Null Coalescing Operator 사용)
                    $user_lat = $execution->user_lat ?? null;
                    $user_lng = $execution->user_lng ?? null;

                    // 화면 표시용 변수 설정
                    $lat = $user_lat ?: '미획득'; 
                    $lng = $user_lng ?: '미획득'; 
                @endphp 
                <p>위도: {{ $lat }} <br/> 경도: {{ $lng }}</p>
                @php 
                    // 2. lat와 lng 값이 모두 있을 때만 URL 생성 및 버튼 출력
                    if ($user_lat && $user_lng) {
                        $url = "https://map.naver.com?lat=". $user_lat . "&lng=" . $user_lng . "&title=" . $execution->user_comp . " | " . $execution->user_name . " | " . $execution->user_phone;
                        echo '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-outline-primary bg-white w-20 h-10">위치 보기</a>';
                    } else {
                        // 값 없으면 빈 버튼이나 안내 메시지 출력 (선택 사항)
                        echo '<button class="btn btn-sm btn-outline-secondary w-20 h-10" disabled>위치 없음</button>';
                    }
                @endphp 

            </div>
        </div>

        <!-- 서명 정보 -->
        <div class="mt-4">
            <h4 class="font-bold mb-4 flex items-center gap-2">점검자 서명</h4>
            <div class="mt-1 bg-slate-100 p-4 rounded-lg flex items-center gap-3 bg-slate-50 p-4 rounded-lg border ">
                @if($execution->user_sign)
                    <img src="{{ asset('storage/' . $execution->user_sign) }}" class="max-h-full" alt="Signature">
                @else
                    <span class="text-slate-400 italic">등록된 서명이 없습니다.</span>
                @endif
            </div>
        </div>
    </div>



  
<!-- END: Content -->
</div>
</div>
@include('checklist.footer._footer')

<script>


</script>

