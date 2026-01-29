// 메뉴 접었다 펴기
// document.addEventListener("DOMContentLoaded", function () {
//   function toggleSideNav() {
//     const sideNav = document.querySelector(".side-nav");
//     sideNav.classList.toggle("fold");
//   }

//   const foldBtn = document.querySelector(".fold_btn");
//   foldBtn.addEventListener("click", toggleSideNav);
// });

//=======================================================
//   화면 줄어들면 메뉴 접기
//=======================================================
const sidenavFold = () => {
    const wsize = window.innerWidth;
    if (wsize < 1280) {
        if(document.querySelector(".side-nav") == null) return null;
        // document.querySelector(".side-nav").classList.add("fold");
    } else {
        return false;
    }
};

window.addEventListener("resize", sidenavFold);
window.addEventListener("load", sidenavFold);


//=======================================================
//   검색 영역 - 초기화
//=======================================================
const formResetHandle = () => {
    const form = document.querySelector("form.search_form");
    form.reset();
};



//=======================================================
//   공통 - 모달
//=======================================================
const modalOpen = (item) => {
    const modal = document.querySelector(`#${item}`);
    modal.classList.add("show", "overflow-y-auto");
    modal.style.marginTop = "0px";
    modal.style.marginLeft = "0px";
    modal.style.paddingLeft = "0px";
    modal.style.zIndex = "10000";
};

// 모달 닫기
const modalClose = (item)=>{
    const modal = document.querySelector(`#${item}`);
    modal.classList.remove('show');
    modal.style.marginTop = "-10000px";
    modal.style.marginLeft = "-10000px";
    modal.style.paddingLeft = "0";
    modal.style.zIndex = "0";
}



// ========================================
// 모든 모달 배경 클릭 및 ESC 닫힘 방지 (jQuery 버전)
// ========================================
$('.modal').each(function() {
	$(this).on('click', function(e) {
		if (!$(this).hasClass('show')) return;
		const dialog = $(this).find('.modal-dialog')[0];
		if (dialog && !dialog.contains(e.target)) {
			e.stopImmediatePropagation();
			e.preventDefault();
		}
	});
});

// ESC 키로 닫히지 않도록 방지
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape' || e.keyCode === 27) {
    const opened = document.querySelector('.modal.show');
    if (opened) {
      e.preventDefault();
      e.stopImmediatePropagation();
    }
  }
}, true);

//=======================================================
//   페이지 로딩
//=======================================================
// 페이지 로딩이 시작될 때 실행할 함수
function showLoadingSvg() {
    const loadingContainer = document.getElementById('loadingContainer');
        if(loadingContainer == null) return;
        loadingContainer.style.display = 'flex';
    }

    // 페이지 로딩이 완료된 후 실행할 함수
    function hideLoadingSvg() {
    const loadingContainer = document.getElementById('loadingContainer');
        if(loadingContainer == null) return;
        loadingContainer.style.display = 'none';
    }

    // 페이지 로딩 시작 시 showLoadingSvg() 함수 호출
    window.addEventListener('load', function() {
        showLoadingSvg(); // 로딩 시작 시 바로 SVG 표시

        // 3초 후에 hideLoadingSvg() 함수 호출
        setTimeout(hideLoadingSvg, 500); // 3초(3000ms)로 설정
});


//=======================================================
//   토스트모달
//=======================================================
function showToast(message, type = 'default', duration = 3000) {
  const container = document.getElementById('toast-container');

  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.textContent = message;

  container.appendChild(toast);

  setTimeout(() => {
    toast.remove();
  }, duration);
}


//=======================================================
//   FilePond
//=======================================================
/*$(window).on('load',function(){

    // FilePond 플러그인 등록
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType
    );

    // FilePond 초기화 함수
    function initFilePond(input) {
        if (!input || !window.FilePond) return;

        const pond = FilePond.create(input, {
            maxFiles: 10,
            allowMultiple: true,
            allowReorder: true,
            acceptedFileTypes: ['image/png', 'image/jpeg', 'image/gif'],
            maxFileSize: '10MB',
            labelIdle: '사진을 여기에 드래그하거나 <span class="filepond--label-action">클릭하여 업로드</span>하세요.',
            allowImagePreview: true,
            imageCropAspectRatio: '1',
            imagePreviewHeight: 150,
            styleButtonRemoveItemPosition: 'right',
            
        });
    }

    // 초기 FilePond 요소들 초기화
    document.querySelectorAll('.filepond-media').forEach(input => {
        initFilePond(input);
    });
    
})*/

//=======================================================
//   파일 업로드 커스텀
//=======================================================
(function() {
    // 페이지 로드 완료 후 실행
    document.addEventListener('DOMContentLoaded', function() {
        initFileUploads();
    });

    // 파일 업로드 초기화 함수
    function initFileUploads() {
        document.querySelectorAll('.input-file').forEach((fileInput, index) => {
            // 고유 ID 부여
            const uniqueId = fileInput.id || `inputFile_${index + 1}`;
            fileInput.id = uniqueId;
            
            // label의 for 속성 설정
            const label = fileInput.nextElementSibling;
            if (label && label.tagName === 'LABEL') {
                label.setAttribute('for', uniqueId);
            }
			
            // 파일 선택 이벤트
            fileInput.addEventListener('change', handleFileSelect);
        });
    }

    // 파일 선택 핸들러
    function handleFileSelect(e) {
        const fileName = e.target.files[0]?.name || '';
        const fileNameDiv = e.target.parentElement.querySelector('.file-name');
        if (fileNameDiv) {
            fileNameDiv.textContent = fileName;
        }
    }

    // 전역 함수로 노출 (필요한 경우 재초기화용)
    window.initFileUploads = initFileUploads;
})();