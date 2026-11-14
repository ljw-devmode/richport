<?php
$header_skip = true;
?>
@include('checklist.header._header')
@include('checklist.header._top')

    <div class="tabrelative">
		
		<div class="intro-y bg-white">
			<!-- 탭 영역 -->
			<div class="flex gap-2 justify-between items-center border-b border-slate-200 py-3 px-6">
				<p class="text font-bold keepall">{{ $list->check_name }}</p>
                <p class="text font-bold keepall">{{ $user['out_name'] }} | {{ $user['user_name'] }} | {{ $user['user_phone'] }}</p>
			</div>
        </div>

        <div class="p-3 bg-white border-b ">
            <form id="execute-form">
                <div class="flex items-center">
                    <input type="hidden" id="project_name" name="project_name" value="">
                    <span class="text-slate-500 text-sm w-24">프로젝트 선택</span>
                    @if($companylist->isNotEmpty())
                    <select id="project_idx" name="project_idx" >
                        <option value="">프로젝트 선택</option>
                        @foreach($companylist as $company)
                            <option value="{{ $company->idx }}">{{ $company->comp_name }}</option>
                        @endforeach
                    </select>
                    @else
                    <div class="text-slate-500 font-medium">할당된 프로젝트가 없습니다.</div>
                    @endif
                    <button type="button" class="btn btn-dark ml-1" onclick="location.href='/check/main'">취소</button>
                </div>
            
                <input type="hidden" name="list_idx" value="{{ $list->idx }}">
                <input type="hidden" name="user_comp" value="{{ $user['out_name'] }}">
                <input type="hidden" name="user_name" value="{{ $user['user_name'] }}">
                <input type="hidden" name="user_phone" value="{{ $user['user_phone'] }}">  
                <input type="hidden" name="user_lat" id="user_lat">
                <input type="hidden" name="user_lng" id="user_lng">         
                
                @foreach($list->checksection as $section)
                    <div class="mt-4 mb-10">
                        <h3 class="bg-slate-800 py-2 font-bold">
                            {{ $section->section_name }}
                        </h3>
                        <table class="table table-bordered w-full text-sm">
                            <thead class="bg-slate-100 text-center">
                                <tr>
                                    @if($section->layout_type != '1')<th>구분</th>@endif
                                    @if($section->layout_type == '3')<th>항목</th>@endif
                                    <th>확인 사항</th>
                                    @if($section->layout_type == '1')<th class="w-70p">확인 결과</th>@endif
                                    @if($section->layout_type == '2')<th class="w-55p">확인 결과</th>@endif
                                    @if($section->layout_type == '3')<th class="w-40p">확인 결과</th>@endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($section->checkitem as $item)
                                <tr>
                                    @if($section->layout_type != '1')
                                    <td class="bg-slate-50 font-medium text-center">{{ $item->d2_name }}</td>
                                    @endif
                                    @if($section->layout_type == '3')
                                    <td class="bg-slate-50 font-medium text-center">{{ $item->d3_name }}</td>
                                    @endif                                    
                                    <td class="p-3">{{ $item->item_name }}</td>
                                    <td class="p-3">
                                        @if($item->input_type == 'radio' || $item->input_type == 'checkbox')
                                            <div class="flex flex-wrap gap-3">
                                                @foreach($item->checkoption as $opt)
                                                    <label class="flex items-center gap-1 cursor-pointer">
                                                        <input type="{{ $item->input_type }}" 
                                                            name="results[{{ $item->idx }}]" 
                                                            value="{{ $opt->idx }}">
                                                        <span>{{ $opt->option_name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>

                                            <!-- 비고란 추가 표시 -->
                                            @if($item->has_text == 'Y')
                                                <div class="mt-2">
                                                    <input type="text" name="text_results[{{ $item->idx }}]" class="form-control w-full text-xs" placeholder="참고 사항 입력">
                                                </div>
                                            @endif                                            
                                        @elseif($item->input_type == 'input')
                                            <input type="text" name="results[{{ $item->idx }}]" class="form-control w-full" placeholder="확인 결과를 입력해주세요.">
                                        @endif
                                        <input type="hidden" id="input_type" name="input_type" value="{{ $item->idx }}"/>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach

                <!-- 서명 및 하단 정보 (나중에 구현) -->

                
            <div class="relative w-full h-[400px] mt-6 py-4 border-t border-slate-200">
                <div class="flex gap-2 items-center mb-2">
                    <span class="text-slate-500 text-sm w-20">점검자 서명</span>
                    <button type="button" onclick="clearSignature()" class="btn btn-sm btn-outline-secondary flex items-center gap-1">
                        <i data-lucide="refresh-ccw" class="w-3 h-3"></i> 서명 지우기
                    </button>
                </div>
                <canvas class="border" id="signature-pad"></canvas>
    
                <div class="mt-2">
                    <p class="text-sm text-slate-500">* 위 상자속에 서명해 주세요.</p>
                    <p class="text-sm text-slate-500">* 점검자의 위치정보는 자동으로 저장됩니다.</p>
                </div>
            </div>

                <div class="mt-4 text-center">
                    <button type="button" onclick="submitCheck()" class="btn btn-primary btn-lg px-20 font-bold text-xl ">
                        점검 완료 및 저장
                    </button>
                </div>
            </form>
        </div>
	</div>


<!-- END: Content -->
</div>
</div>
@include('checklist.footer._footer')

<script>
// 사용자 좌표 획득
$(document).ready(function() {
    getLocation();
});

// 서명 캔버스 초기화
const canvas = document.getElementById('signature-pad');
const signaturePad = new SignaturePad(canvas, {
    minWidth: 2,
    maxWidth: 3,
    penColor: "rgb(0, 0, 0)"
});

// 서명 초기화 함수
function clearSignature() {
    if (confirm('작성하신 서명을 지우시겠습니까?')) {
        signaturePad.clear(); // 캔버스를 깨끗하게 비움
    }
}

// 프로젝트 변경시 프로젝트명 자동 입력
$('#project_idx').on('change', function() {
    const project_name = $(this).find('option:selected').text();
    $('#project_name').val(project_name);
});

function getLocation() {
    if ("geolocation" in navigator) {
        const options = {
            enableHighAccuracy: true, // 높은 정확도 사용
            timeout: 5000,            // 5초 이내 응답 없으면 중단
            maximumAge: 0             // 캐시된 위치 사용 안 함
        };

        navigator.geolocation.getCurrentPosition(function(position) {
            $('#user_lat').val(position.coords.latitude);
            $('#user_lng').val(position.coords.longitude);
            console.log("위치 정보 획득 완료");
        }, function(error) {
            console.warn("위치 정보 획득 실패: " + error.message);
        }, options);
    }
}

// 체크 결과 저장
function submitCheck() {
    if (signaturePad.isEmpty()) {
        alert("점검자 서명을 입력해 주세요.");
        return;
    }

    // 필수 체크 (프로젝트명 등)
    if ($('#project_idx').val() == '') {
        alert("프로젝트를 선택해주세요.");
        $('#project_idx').focus();
        return;
    }

    if (!$('#user_lat').val() || !$('#user_lng').val()) {
        // 위경도 재확보 시도
        getLocation(); 
        
        // 재확보 실패시
        if (!$('#user_lat').val() || !$('#user_lng').val()) {
            if(!confirm("위치 정보를 가져오지 못했습니다. 그대로 제출하시겠습니까?")) return;
        } else {
            if (!confirm("점검 결과를 제출하시겠습니까?")) return;    
        }
    } else {
        if (!confirm("점검 결과를 제출하시겠습니까?")) return;
    }

    // 서명을 Base64 이미지 데이터로 변환
    const signatureImage = signaturePad.toDataURL();
    
    // 폼 데이터 수집
    const formData = new FormData($('#execute-form')[0]);
    formData.append('user_sign', signatureImage); // 서명 데이터 추가


    $.ajax({
        url: '/check/executeSave', // 서버 저장 라우트
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            if(res.status === 'success') {
                alert('점검이 완료되었습니다.');
                location.href="/check/main?tab=result";
            } else {
                alert(res.message);
            }
        }
    });
}

</script>

