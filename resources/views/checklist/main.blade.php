<?php
$header_skip = true;
?>
@include('checklist.header._header')
@include('checklist.header._top')

    <div class="tabrelative">
		
		<div class="intro-y border bg-white">
			<!-- 탭 영역 -->
			<div class="flex spaceevenly items-center gap-10 border-b border-slate-200 py-3 px-6">
				<button type="button" id="checklist" name="checklist" class="detail-tab-btn active text-primary" data-tab="tab-list">체크리스트</button>
				<button type="button" id="checkresult" name="checkresult" class="detail-tab-btn" data-tab="tab-result">결과</button>
			</div>


			<div class="detail-tabs-wrap">
				<div class="detail-tab-panels tabrelative">
					<!-- 체크리스트 -->
					<div id="tab-list" class="detail-tab-panel active">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th class="pd2 sortable1">체크리스트</th>
                                    <th class="pd2">점검</th>
                                </tr>
                            </thead>
                            <tbody>    
                            @foreach($checklist as $item)
                                <tr>
                                    <th class="text-center pd2">{{ $item->check_name }}</th>
                                    <th class="text-center pd2"><button class="btn pd2 btn-primary" onclick="location.href='/check/execution/{{$item->idx}}'">점검</button></th>
                                </tr>
                            @endforeach
                            </body>
                        </table>                        
					</div>

					<!-- 결과 -->
					<div id="tab-result" class="detail-tab-panel">
						<div class="mb-4 flex items-center gap-2">
                            <input id="project-filter" type="text" class="border rounded px-2 py-1" placeholder="체크리스트, 프로젝트 검색">
                            <button type="button" class="btn btn-primary" onclick="filterResults()">검색</button>
                        </div>

                        <table id="resulttable" name="resulttable" class="mt-6 table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th class="pd2 sortable2">년월일</th>
                                    <th class="pd2 sortable2">체크리스트</th>
                                    <th class="pd2 sortable2">프로젝트</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($result as $item)
                                <tr onclick="viewDetail('{{ $item->idx }}')" class="">
                                    <th class="text-center pd2">{{ $item->created_at?->format('Y-m-d') }}</th>
                                    <th class="text-center pd2">{{ $item->checklist->check_name }}</th>
                                    <th class="text-center pd2">{{ $item->project_name }}</th>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- END: Content -->
</div>
</div>
@include('checklist.footer._footer')

<script>
// 탭 컨트롤
$(document).on('click', '.detail-tab-btn', function () {
    const tabId = $(this).data('tab');

    $('.detail-tab-btn').removeClass('active text-primary');
	$(this).addClass('active text-primary');

    $('.detail-tab-panel').removeClass('active').hide();
    $('#' + tabId).addClass('active').show();
});

// 화면 표시시 탭(2) 결과 파라미터가 있으면 결과 탭 표시
$(document).ready(function() {
    // URL에서 tab 파라미터 가져오기
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab');

    if (activeTab === 'checklist') {
        $('#checklist').trigger('click');        
    } else if (activeTab === 'result') {
       $('#checkresult').trigger('click');        
    }
});

// 프로젝트명 검색 결과
function filterResults() {
    const filterText = document.getElementById('project-filter').value.trim().toLowerCase();

    document.querySelectorAll('#resulttable tbody tr ').forEach((row) => {
        const cells = row.querySelectorAll('th');
        const checkName = cells[1]?.textContent.trim().toLowerCase() || '';
        const projectName = cells[2]?.textContent.trim().toLowerCase() || '';
        row.style.display = (checkName.includes(filterText) || projectName.includes(filterText)) ? '' : 'none';
    });
}



// 체크 결과 상세
function viewDetail(resultIdx) {
    if (!resultIdx) {
        alert('체크리스트 ID가 없습니다.');
        return;
    }
    location.href = '/check/result/' + resultIdx;
}
</script>

