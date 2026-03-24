@php 
	$header_skip = false; 
	$depth1_num = '1'; 
	$depth2_num = '1'; 
@endphp 

@include('admin.header._header')
<div class="mt-6 px-5">
  <div class="topbar_admin">
	  <h3 class="intro-y text-xl font-bold">대시보드</h3>
	  <button id="refreshNowBtn" type="button" class="refresh-btn">새로고침(10:00)</button>
</div>

  <main>
    <!-- Summary Cards -->
    <section class="cards" aria-label="요약 카드">
      <article class="card summary-card" data-text="제안 건수" data-summary="proposal">
        <div class="card__label">제안 건수</div>
        <div class="card__value">{{ number_format($summary['total']) }}건 <br/>{{ number_format($summary['total_kw']) }}kW</div>
      </article>

      <article class="card summary-card" data-text="계약 건수" data-summary="contract">
        <div class="card__label">계약 건수</div>
        <div class="card__value">{{ number_format($summary['contract']) }}건 <br/>{{ number_format($summary['contract_kw']) }}kW</div>
      </article>

      <article class="card card--info summary-card" data-text="인허가 진행 중" data-summary="permit">
        <div class="card__label">인허가 건수</div>
        <div class="card__value">{{ number_format($summary['process_confirm']) }}건 <br/>{{ number_format($summary['process_confirm_kw']) }}kW</div>
      </article>

      <article class="card card--info summary-card" data-text="사용전 검사" data-summary="preuse">
        <div class="card__label">사용전검사 건수</div>
        <div class="card__value">{{ number_format($summary['process_ready']) }}건 <br/>{{ number_format($summary['process_ready_kw']) }}kW</div>
      </article>
    </section>
    <section class="cards2" aria-label="요약 카드">
      <article class="card card--danger summary-card" data-text="이슈 협의 난항" data-summary="issue_red">
        <div class="card__label">이슈 협의 난항</div>
        <div class="card__value">{{ number_format($summary['issue_red']) }}건 <br/>{{ number_format($summary['issue_red_kw']) }}kW</div>
      </article>

      <article class="card card--warning summary-card" data-text="이슈 협의 중" data-summary="issue_yellow">
        <div class="card__label">이슈 협의 중</div>
        <div class="card__value">{{ number_format($summary['issue_yellow']) }}건 <br/>{{ number_format($summary['issue_yellow_kw']) }}kW</div>
      </article>

      <article class="card card--management summary-card" data-text="운영 여부">
        <div class="card__label">운영 여부</div>
        <div class="card__value">{{ number_format($summary['management']) }}건 <br/>{{ number_format($summary['management_kw']) }}kW</div>
      </article>

      <!-- <article class="card card--success summary-card" data-text="원활">
        <div class="card__label">원활</div>
        <div class="card__value">{{ number_format($summary['issue_green']) }}건 <br/>{{ number_format($summary['issue_green_kw']) }}kW</div>
      </article>

      <article class="card card--muted summary-card" data-text="완공">
        <div class="card__label">완공 건수</div>
        <div class="card__value">{{ number_format($summary['completed']) }}건 <br/>{{ number_format($summary['completed_kw']) }}kW</div>
      </article> -->
    </section>

    <!-- Table -->
    <section class="panel">
      <div class="panel__header">
        <div class="panel__tools">
            <input id="searchInput" class="input" type="search" placeholder="프로젝트명" />
            <button id="searchBtn" class="btn searchbtn">검색</button>          
            <button id="resetBtn" class="btn searchbtn">초기화</button>
        </div>
      </div>

      <div class="table-wrap" role="region" aria-label="프로젝트 테이블" tabindex="0">
        <table id="mainTable" class="table">
          <thead>
            <tr>
                @foreach($header as $row)
                  <th class="sortable" data-col="{{ $row }}" data-column="{{ $row }}">{{ $row?? '' }}</th>
                @endforeach
            </tr>
          </thead>
          <tbody>
                @foreach($items as $row)
                <tr>
                    @foreach($header as $col)
                      @php
                        $issue = $row[$col] ?? '';
                        $cellClass = match($issue) {
                            '협의 난항' => 'redcolor',
                            '협의 중'   => 'orangecolor',
                            default     => ''
                        };

                        $celltext = match($issue) {
                            '협의 난항' => '이슈 협의 난항',
                            '협의 중'   => '이슈 협의 중',
                            default     => $issue
                        };                        
                      @endphp
                      <td data-col="{{ $col }}" class="{{ $cellClass }}">{{ $celltext ?? '' }}</td>
                    @endforeach
                </tr>
                @endforeach
          </tbody>
        </table>
      </div>
    </section>
  </main>

<div id="detailModal" class="reportmodal">
  <div class="reportmodal-content">
    <span class="reportmodal-close">&times;</span>

    <h3 class="reportmodal-title" id="modalTitle"></h3>

    <ul class="detail-list" id="detailList"></ul>

    <hr class="reporthr">

    <!-- 입력/수정 -->
    <div class="memo-editor">
      <textarea id="memoText" class="memo-textarea" placeholder="메모를 입력하세요"></textarea>

      <div class="memo-actions">
        <button id="saveMemoBtn" class="reportbtn">등록</button>
        <button id="cancelEditBtn" class="reportbtn btn--light" style="display:none;">취소</button>
      </div>
    </div>

    <!-- 목록 -->
    <ul id="memoList" class="memo-list"></ul>


  </div>
</div>  

<div id="loadingOverlay" class="loading-overlay" aria-hidden="true">
  <div class="loading-box">
    <div class="loading-bar">
      <div class="loading-bar-fill"></div>
    </div>
    <div class="loading-text" id="loadingText">처리중입니다!!</div>
  </div>
</div>
</div>
</div>
<!-- END: Content -->
</div>
@include('admin.footer._footer')

<script>
// 초기 상태 저장 (초기화 시 원본 순서로 복원하기 위함)
const ori_table = document.getElementById("mainTable");
const ori_tbody = ori_table.querySelector("tbody");
const rows        = ori_tbody.querySelectorAll("tr")
const summaryCards = document.querySelectorAll('.summary-card');
let originalRows = Array.from(ori_tbody.querySelectorAll("tr"));


/* 컬럼 순서 변경 기능 영역 ***************************************************/
// 컬럼 이동을 위한 변수 선언
const mainTable = document.getElementById('mainTable');
const tableheadRow = mainTable.querySelector('thead tr');
const tablebody = mainTable.querySelector('tbody');

// 초기 컬럼 순서 저장(초기화용)
const originalCols = Array.from(tableheadRow.querySelectorAll('th'))
  .map(th => th.dataset.col || th. textContent.trim());

// 컬럼 순서 재배치 함수
function setColumnOrder(order) {
  // thead 재배치
  const thMap = new Map(
    Array.from(tableheadRow.querySelectorAll('th')).map(th => [th.dataset.col || th. textContent.trim(), th])
  );
  order.forEach(key => {
    const th = thMap.get(key);
    if (th) tableheadRow.appendChild(th);
  });

  // tbody 재배치
  Array.from(tablebody.querySelectorAll('tr')).forEach(tr => {
    const tdMap = new Map(
      Array.from(tr.querySelectorAll('td')).map(td => [td.dataset.col, td])
    );
    order.forEach(key => {
      const td = tdMap.get(key);
      if (td) tr.appendChild(td);
    });
  });
}

// 특정 컬럼을 앞으로 이동시키는 함수
function moveColumnToFront(colKey) {
  const current = Array.from(tableheadRow.querySelectorAll('th'))
    .map(th => th.dataset.col || th. textContent.trim());

  if (!current.includes(colKey)) return;

  const nextOrder = [colKey, ...current.filter(c => c !== colKey)];
  setColumnOrder(nextOrder);
}

// 컬럼 순서 초기화 함수
function resetColumns() {
  setColumnOrder(originalCols);
}

// 카드 클릭 시 해당 조건에 맞는 행만 보이도록 필터링 + 컬럼 이동
function applyCardColumnMove(datatext) {
  // 기본: 컬럼 변경 없음
  // 필요하면 카드 클릭 시 항상 resetColumns() 후 이동하도록 할 수도 있음.
  resetColumns();

  if (datatext === '제안 건수') {
    moveColumnToFront('제안 일자');
  } else if (datatext === '계약 건수') {
    moveColumnToFront('계약 날인');
  } else if (datatext === '인허가 진행 중') {
    moveColumnToFront('공사 단계');
  } else if (datatext === '사용전 검사') {
    moveColumnToFront('사용전 검사');
  } else if (datatext === '이슈 협의 난항' || datatext === '이슈 협의 중') {
    moveColumnToFront('이슈');
  } else if (datatext === '운영 여부') {
    moveColumnToFront('운영 여부');
  }
}


/* 검색 영역 ***************************************************/
// 검색 및 필터링 요소
const searchInput = document.getElementById('searchInput');
const searchBtn   = document.getElementById('searchBtn');
const resetBtn    = document.getElementById('resetBtn');

// 문자열 정규화 함수 (공백 제거, 소문자 변환)
function normalize(s) {
  return (s ?? '').toString().trim().toLowerCase();
}

// 검색 함수
function search() {
  const keyword = normalize(searchInput.value);

  if(keyword === '') {
    showToast('프로젝트명을 입력하세요.','error');
    return;
  }

  // 프로그래시스바
  runWithLoading(() => {
    // 컬럼 순서 초기화
    resetColumns();

    // 테이블 행 필터링
    rows.forEach(row => {
      const cells = row.querySelectorAll('td');
      const keyword = cells[0]?. textContent;
      row.style.display = keyword.includes(searchInput.value) ? '' : 'none';    
    });

    // 검색된 프로젝트명을 기준으로 정렬 표시
    let th = table.querySelector('thead th[data-column="프로젝트명"], thead th[data-col="프로젝트명"]');
    if (th) {
      th.click();
    }
  }, '처리 중입니다!!');
}

// 검색 버튼 클릭 또는 엔터키 입력 시 검색 함수 실행
searchBtn.addEventListener('click', search);

// 엔터키 입력 감지
searchInput.addEventListener('keydown', (e) => {
  if (e.key === 'Enter') search();
});

/* 초기화 버튼 영역 ***************************************************/
// 초기화 버튼 클릭 시 모든 필터 및 검색 초기화
resetBtn.addEventListener('click', function() {
  summaryCards.forEach(card => card.classList.remove("active"));

  // 프로그래시스바
  runWithLoading(() => {
    // 컬럼 순서 초기화
    resetColumns();

    // 정렬 표시 제거
    document.querySelectorAll(".sortable").forEach(th => {
        th.classList.remove("asc", "desc");
    });

    // 검색어 초기화
    searchInput.value = '';
    
    // 저장된 원본 순서대로 다시 붙이기
    originalRows.forEach(row => {
        row.style.display = '';
        ori_tbody.appendChild(row);
    });

    // 정렬 표시 제거
    document.querySelectorAll(".sortable").forEach(th => {
        th.classList.remove("asc", "desc");
    });
  }, '처리 중입니다!!');    
});

/* 카드 클릭 시 필터 처리 영역 ***************************************************/
// 각 행의 "프로젝트명" 열 인덱스
const PROJECT_COL_INDEX = 0;
// 각 행의 "계약 날인" 열 인덱스
const CONTRACT_COL_INDEX = 5;
// 각 행의 "인허가, 완공" 열 인덱스
const PROECSS_COL_INDEX = 8;
// 각 행의 "사용전 검사" 열 인덱스
const PREUSE_COL_INDEX = 9;
// 각 행의 "이슈" 열 인덱스
const ISSUE_COL_INDEX = 10;
// 각 행의 "운영 여부" 열 인덱스
const MANAGEMENT_COL_INDEX = 11;

// 요약 카드 클릭 시 해당 조건에 맞는 행만 보이도록 필터링
summaryCards.forEach(card => {
  card.addEventListener('click', function() {
    const datatext = this.dataset.text;
    
    // 프로그래시스바
    runWithLoading(() => {
      // 🔥 모든 카드에서 active 제거
      summaryCards.forEach(c => c.classList.remove("active"));        
      this.classList.add("active");

      // 컬럼 순서 초기화
      resetColumns();

      rows.forEach(row => {
          const tds = row.querySelectorAll('td');

          if(datatext === '제안 건수') {
              row.style.display = '';
          } else if(datatext === '계약 건수') {
              const contractText = tds[CONTRACT_COL_INDEX]?.textContent;
              row.style.display = contractText !== '' ? '' : 'none';
          } else if(datatext === '인허가 진행 중' || datatext === '완공') {
              const processText = tds[PROECSS_COL_INDEX]?.textContent ;
              row.style.display = (processText === datatext) ? '' : 'none';
          } else if(datatext === '이슈 협의 난항' || datatext === '이슈 협의 중') {
              const issueText = tds[ISSUE_COL_INDEX]?.textContent ;
              row.style.display = (issueText === datatext) ? '' : 'none';
          } else if(datatext === '사용전 검사') {
              const contractText = tds[PREUSE_COL_INDEX]?.textContent;
              row.style.display = contractText !== '' ? '' : 'none';
          } else if(datatext === '운영 여부') {
              const managementText = tds[MANAGEMENT_COL_INDEX]?.textContent ;
              row.style.display = managementText ? '' : 'none';              
          } else {
              row.style.display = 'none';
          }
      });        

      // 검색어 초기화
      searchInput.value = '';

      // ✅ 컬럼 이동 추가 (필터 적용과 함께 동작)
      applyCardColumnMove(datatext);

      // 첫 번째 열(프로젝트명) 기준으로 정렬 표시 초기화
      const htTh = document.querySelector('#mainTable thead th');
      if (htTh) {
        // 기존 정렬방식 모두 제거
        htTh.classList.remove("asc", "desc");
        // 토글 방식이므로 정렬 과정에서 내림->오름, 오름->내림으로 변경되기 때문에 
        htTh.classList.add("asc");
        htTh.click();
      }
    }, '처리 중입니다!!');   
  });
});

/* 테이블 해더 클릭시 정렬 처리 영역 ***************************************************/
// 숫자 변환 함수 (용량 정렬을 위해)
function toNumber(text){
  const cleaned = normalize(text).replace(/[^0-9.\-]/g,''); // 콤마/단위 제거
  if (!cleaned || cleaned === '-' || cleaned === '.' || cleaned === '-.') return NaN;
  return Number(cleaned);
}

// 테이블 헤더 클릭 시 정렬
document.querySelectorAll(".sortable").forEach(header => {
  header.addEventListener("click", function () {

      const columnName = this.dataset.column;
      const table = this.closest("table");
      const tbody = table.querySelector("tbody");
      const headers = table.querySelectorAll(".sortable");
      const rows = Array.from(tbody.querySelectorAll("tr"));

      // 다른 헤더 상태 제거
      headers.forEach(h => {
        if (h !== this) {
          h.classList.remove("asc", "desc");
        }
      });

      // 현재 헤더 정렬 방향 토글
      const isAsc = !this.classList.contains("asc");
      this.classList.toggle("asc", isAsc);
      this.classList.toggle("desc", !isAsc);

      // 클릭한 해더 확인하여 해당 열 인덱스 찾기
      const columnIndex = Array.from(
        table.querySelectorAll("thead th")
      ).findIndex(th => th.dataset.column === columnName);

      // 열 인덱스에 따라 정렬 방식 결정
      rows.sort((a, b) => {
        let aText = a.children[columnIndex]. textContent.trim();
        let bText = b.children[columnIndex]. textContent.trim();

        const aHas = aText !== '';
        const bHas = bText !== '';

        // 값 있는 것 먼저 정렬, 값 없는 것은 뒤에 표시
        if (aHas && !bHas) {
          return -1;
        } else if (!aHas && bHas) {
          return 1;
        } else if (aHas && bHas) {
          // 숫자 정렬 (용량)
          if (columnName === '용량(kW)') {
            const aNum = toNumber(aText);
            const bNum = toNumber(bText);
            if (isNaN(aNum) && isNaN(bNum)) return 0;
            if (isNaN(aNum)) return 1;
            if (isNaN(bNum)) return -1;
            return isAsc ? aNum - bNum : bNum - aNum;

          // 날짜 정렬 (제안 일자, 계약 날인, 착공일, 사용전 검사)
          } else if (columnName === '제안 일자' || columnName === '계약 날인' || columnName === '착공일' || columnName === '사용전 검사') {
            return isAsc
              ? new Date(aText) - new Date(bText)
              : new Date(bText) - new Date(aText);

          // 문자열 정렬 (프로젝트명, 사업 구도, 사업기획팀, EPC, 공사 단계, 이슈, 운영여부)
          } else {
            return isAsc
              ? aText.localeCompare(bText)
              : bText.localeCompare(aText);
          }
        } else {
          const aProj = (a.children[PROJECT_COL_INDEX]?. textContent ?? '').trim();
          const bProj = (b.children[PROJECT_COL_INDEX]?. textContent ?? '').trim();
          const cmpProj = aProj.localeCompare(bProj);
          if (cmpProj !== 0) return cmpProj;

          // 프로젝트명도 같으면 원본순서
          return Number(a.dataset.index) - Number(b.dataset.index);
        }
      });

      // 정렬된 행을 tbody에 다시 추가
      rows.forEach(row => tbody.appendChild(row));
  });
});

/* 테이블 클릭시 상세 모달 처리 영역 ***************************************************/
// 행 클릭 시 상세 모달 열기
const tbody = document.querySelector('#mainTable tbody');  
const modal = document.getElementById('detailModal');
const detailTable = document.getElementById('detailTable');
const memoList = document.getElementById('memoList');
const memoText = document.getElementById('memoText');
const saveBtn = document.getElementById('saveMemoBtn');
const cancelBtn = document.getElementById('cancelEditBtn');
const closeBtn = document.querySelector('.reportmodal-close');

// 현재 선택된 프로젝트명 저장 (메모 등록/수정 시 필요)
let currentProjectName = "";
let editingMemoId = null; // 수정 모드이면 id 저장

// 테이블 행 클릭 이벤트
tbody.addEventListener('click', function(e) {
  const tr = e.target.closest('tr');

  // tbody 밖 클릭 방지
  if (!tr || !tbody.contains(tr)) return;

  // 클릭한 행의 각 셀과 헤더 가져오기
  const cells = tr.querySelectorAll('td');
  const headers = document.querySelectorAll('#mainTable thead th');
  const detailList = document.getElementById('detailList');

  // [수정 포인트] data-col 속성을 이용해 프로젝트명을 정확히 타겟팅
  const projectTd = tr.querySelector('td[data-col="프로젝트명"]');
  const currentProjectName = projectTd ? projectTd.textContent.trim() : '이름 없음';  

  // 상세 정보 리스트 초기화 후 각 셀의 데이터로 리스트 아이템 생성
  detailList.innerHTML = '';
  headers.forEach((th, index) => {
    const li = document.createElement('li');
    li.innerHTML = `
      <div class="label">${th. textContent}</div>
      <div class="value">${cells[index]?. textContent ?? ''}</div>
    `;
    detailList.appendChild(li);
  });
  
  // 모달 타이틀 설정 및 메모 로드
  modalTitle.textContent = currentProjectName;
  memoList.innerHTML = '';
  loadMemo(currentProjectName);

  modal.style.display = 'flex';
});

// 모달 닫기
closeBtn.onclick = () => modal.style.display='none';


/* 상세 모달에서 메모 처리 영역 ***************************************************/
//특수문자 제거
function escapeHtml(s){
  return (s ?? '').toString()
    .replaceAll('&','&amp;')
    .replaceAll('<','&lt;')
    .replaceAll('>','&gt;')
    .replaceAll('"','&quot;')
    .replaceAll("'","&#039;");
}

// 메모 목록 로드 함수
function loadMemo(name){
  fetch('/ko/memo/list/' + encodeURIComponent(name))
    .then(res => res.json())
    .then(list => {
      memoList.innerHTML = '';

      // 등록된 메모가 없을 때 안내 메시지 표시
      if (!list || list.length === 0) {
        memoList.innerHTML = `<li class="memo-item"><div class="memo-body" style="color:#6b7280;">등록된 메모가 없습니다.</div></li>`;
        return;
      }


      // 메모 항목 생성
      list.forEach(item => {
        const li = document.createElement('li');
        li.className = 'memo-item';
        li.dataset.id = item.idx;

        li.innerHTML = `
          <div class="memo-head">
            <div class="memo-date">${escapeHtml(item.created_at)}</div>
            <div class="memo-btns">
              <button class="memo-btn" data-action="edit" data-id="${item.idx}">수정</button>
              <button class="memo-btn memo-btn--danger" data-action="delete" data-id="${item.idx}">삭제</button>
            </div>
          </div>
          <div class="memo-body">${escapeHtml(item.memo)}</div>
        `;

        memoList.appendChild(li);
      });
    });
}

// 메모 목록 업데이트 함수 (등록/수정/삭제 후 새로고침)
function updateMemo(list){
    memoList.innerHTML = '';

    // 등록된 메모가 없을 때 안내 메시지 표시
    if (!list || list.length === 0) {
      memoList.innerHTML = `<li class="memo-item"><div class="memo-body" style="color:#6b7280;">등록된 메모가 없습니다.</div></li>`;
      return;
    }

    // 메모 항목 생성
    list.forEach(item => {
      const li = document.createElement('li');
      li.className = 'memo-item';
      li.dataset.id = item.idx;

      li.innerHTML = `
        <div class="memo-head">
          <div class="memo-date">${escapeHtml(item.created_at)}</div>
          <div class="memo-btns">
            <button class="memo-btn" data-action="edit" data-id="${item.idx}">수정</button>
            <button class="memo-btn memo-btn--danger" data-action="delete" data-id="${item.idx}">삭제</button>
          </div>
        </div>
        <div class="memo-body">${escapeHtml(item.memo)}</div>
      `;

      memoList.appendChild(li);
    });
}

// 메모 버튼 클릭 이벤트 (수정/삭제)
memoList.addEventListener('click', function(e){
  const btn = e.target.closest('button');
  if (!btn) return;

  const action = btn.dataset.action;
  const id = btn.dataset.id;

  // 액션에 따라 수정 또는 삭제 함수 호출
  if (action === 'edit') {
    startEdit(id);
  } else if (action === 'delete') {
    deleteMemo(id);
  }
});

// 수정 모드 시작 함수
function startEdit(id){
  // 해당 li에서 memo 내용 가져오기
  const li = memoList.querySelector(`li[data-id="${id}"]`);
  const text = li?.querySelector('.memo-body')?. textContent ?? '';

  editingMemoId = id;
  memoText.value = text;

  saveBtn.textContent = '수정';
  cancelBtn.style.display = '';
}

// 수정 취소 버튼 클릭 이벤트
cancelBtn.addEventListener('click', function(){
  editingMemoId = null;
  memoText.value = '';
  saveBtn.textContent = '등록';
  cancelBtn.style.display = 'none';
});

// 메모 저장 버튼 클릭 이벤트 (등록/수정)
saveBtn.addEventListener('click', function(){
  const memo = (memoText.value ?? '').trim();
  if (!memo) {
    showToast('메모를 입력하세요.','error');
    return;
  }
  if (!currentProjectName) {
    showToast('프로젝트를 먼저 선택하세요.','error');
    return;
  }

  // 수정 모드면 update, 아니면 store
  const url = editingMemoId ? `/ko/memo/update/${editingMemoId}` : '/ko/memo/store';
  const method = editingMemoId ? 'PUT' : 'POST';

  // 프로그래시스바
  runWithLoading(() => {
    // API 호출
    fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        name: currentProjectName,
        memo
      })
    })
    .then(res => res.json())
    .then(list => {
      // 저장 후 목록 새로고침
      updateMemo(list);

      // 입력 초기화 + 상태 리셋
      editingMemoId = null;
      memoText.value = '';
      saveBtn.textContent = '등록';
      cancelBtn.style.display = 'none';
    });
  }, '처리 중입니다!!'); 
});

// 메모 삭제 함수
function deleteMemo(id){
    if (!confirm('메모를 삭제하시겠습니까?')) return;

  // 프로그래시스바
  runWithLoading(() => {  
    // API 호출
    fetch(`/ko/memo/delete/${id}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify ({
        name: currentProjectName
      })
    })
    .then(res => res.json())
    .then(list => {
      updateMemo(list);
    });
  }, '처리 중입니다!!');     
}

/* 자동 새로고침 처리 영역 ***************************************************/
(function () {
  // 중복 실행 방지
  if (window.__reportAutoRefreshStarted) return;
  window.__reportAutoRefreshStarted = true;

  const REFRESH_MS = 10 * 60 * 1000; // 10분
  const refreshBtn = document.getElementById('refreshNowBtn');

  // 다음 새로고침 예정 시각
  let nextRefreshAt = Date.now() + REFRESH_MS;

  // 남은 시간 포맷 함수
  function formatRemaining(ms) {
    const totalSec = Math.max(0, Math.floor(ms / 1000));
    const min = String(Math.floor(totalSec / 60)).padStart(2, '0');
    const sec = String(totalSec % 60).padStart(2, '0');
    return `${min}:${sec}`;
  }

  // 모달 열려있는지 확인 함수
  function isModalOpen() {
    const modal = document.getElementById('detailModal');
    if (!modal) return false;
    return getComputedStyle(modal).display !== 'none';
  }

  // 1초마다 카운트다운 갱신 + 시간되면 리프레시(모달 열려있으면 스킵 후 다음 10분)
  setInterval(() => {
    const now = Date.now();
    const remaining = nextRefreshAt - now;

    // 시간 표시
    if (refreshBtn) {
      refreshBtn.textContent = `새로고침 (${formatRemaining(remaining)})`;
    }

    // 시간이 됐으면 처리
    if (remaining <= 0) {
      if (isModalOpen()) {
        // ✅ 모달 열려있으면 이번 주기 건너뛰고 다음 10분으로
        nextRefreshAt = Date.now() + REFRESH_MS;
        if (countdownEl) countdownEl.textContent = `새로고침: ${formatRemaining(REFRESH_MS)}`;
        return;
      }

      window.location.reload(true);
    } 
  }, 1000);

  // 수동 새로고침 버튼
  if (refreshBtn) {
    refreshBtn.addEventListener('click', () => {
      if (isModalOpen()) {
        // 모달 열려있을 때는 실수 방지 (원하면 confirm 제거 가능)
        const ok = confirm('상세창이 열려있습니다. 지금 새로고침하면 입력 중인 내용이 사라질 수 있어요. 새로고침할까요?');
        if (!ok) return;
      }

      window.location.reload(true);
    });
  }
})();

/* 로딩 오버레이 처리 영역 ***************************************************/
const overlay = document.getElementById('loadingOverlay');
const loadingText = document.getElementById('loadingText');

function showLoading(msg = '처리중입니다!!') {
  if (loadingText) loadingText.textContent = msg;
  overlay?.classList.add('show');
  overlay?.setAttribute('aria-hidden', 'false');
}

function hideLoading() {
  overlay?.classList.remove('show');
  overlay?.setAttribute('aria-hidden', 'true');
}

// ✅ 페이지 새로고침/이동(서버 응답 대기) 동안 표시
// window.addEventListener('beforeunload', () => {
//   showLoading('데이터를 불러오는 중입니다!!');
// });

// ✅ 특정 버튼/카드 클릭 시에도 잠깐 표시하고 DOM 처리 끝나면 숨김
function runWithLoading(fn, msg) {

  showLoading(msg);
    setTimeout(() => {
      // 렌더링 보장 (바로 heavy 작업하면 오버레이가 안 보일 수 있음)
      requestAnimationFrame(() => {
      try {
        fn();
      } finally {
        // DOM 반영 한 틱 뒤 숨김
        requestAnimationFrame(() => hideLoading());
      }
    });
  }, 300);

}

</script>
