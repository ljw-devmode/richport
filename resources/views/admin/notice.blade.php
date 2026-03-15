@php 
	$header_skip = false; 
	$depth1_num = '2'; 
	$depth2_num = '2'; 
@endphp 
@include('admin.header._header')

<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">회사소식</h3>

	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/notice">
			<div class="flex items-end gap-3">
				<div class="w-1/5">
					<p class="mb-1">구분</p>
					<select id="gubun" name="gubun" class="form-select">
						<option value="">전체</option>
						<option value="1" {{ request('gubun') == '1' ? 'selected' : '' }}>언론보도</option>
						<option value="2" {{ request('gubun') == '2' ? 'selected' : '' }}>MOU</option>
						<option value="3" {{ request('gubun') == '3' ? 'selected' : '' }}>봉사</option>
						<option value="4" {{ request('gubun') == '4' ? 'selected' : '' }}>인증</option>
					</select>
				</div>
				<div class="w-4/5">
					<p class="mb-1">제목</p>
					<input type="text" id="subject" name="subject" class="form-control" placeholder="제목을 입력해주세요" value="{{ request('subject') }}">
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">총 {{ $data->total() }}개</div>
			<div class="flex items-center gap-2">
				<a href="javascript:;" class="btn btn-dark px-4" onclick="modalOpen('notice_regist-modal')">회사소식 등록</a>
				<button type="button" class="btn border-[#1b6c40] bg-[#1b6c40] text-white px-4" onclick="window.location.href='{{ route('admin.notice.export', request()->query()) }}'">엑셀 다운로드</button>
			</div>
		</div>

		<div class="p-6">
			<table class="table table-bordered table-hover">
				<colgroup>
					<col width="10%">
					<col width="15%">
					<col width="*">
					<col width="*">
					<col width="*">
					<col width="20%">
				</colgroup>
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="whitespace-nowrap">번호</th>
						<th class="whitespace-nowrap">구분</th>
						<th class="whitespace-nowrap">제목(ko)</th>
						<th class="whitespace-nowrap">제목(en)</th>
						<th class="whitespace-nowrap">제목(cn)</th>
						<th class="whitespace-nowrap">등록일</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $index => $item)
						@php
					        $number = ($data->currentPage() - 1) * $data->perPage() + ($index + 1);
					    @endphp
						<tr onclick="setModifyForm('{{ $item->idx }}')">
							<td>{{ $number }}</td>
							<td>{{ $item->gubun_kr }}</td>
							<td>{{ $item->subject }}</td>
							<td>{{ $item->subject_en }}</td>
							<td>{{ $item->subject_cn }}</td>
							<td>{{ $item->writed_at }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="4" class="text-center">등록된 콘텐츠가 없습니다.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
			@php
                $currentPage = $data->currentPage();
                $lastPage = $data->lastPage();
                
                $blockSize = 10;                               
			    $currentBlock = (int) ceil($currentPage / $blockSize);
			
			    $start = ($currentBlock - 1) * $blockSize + 1; 
			    $end = min($start + $blockSize - 1, $lastPage); 
            @endphp
			<nav class="w-full mt-5">
				<ul class="pagination justify-center">
					{{-- 첫 페이지 --}}
					<li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
						<a class="page-link" href="{{ $data->url(1) }}"> <i data-lucide="chevrons-left" class="w-4 h-4"></i></a>
					</li> 
					{{-- 이전 페이지 --}}
					<li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
						<a class="page-link" href="{{ $data->previousPageUrl() }}"> <i data-lucide="chevron-left" class="w-4 h-4"></i></a>
					</li> 
					{{-- 페이지 번호 --}} 
					@for ($i = $start; $i <= $end; $i++)
						<li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
							<a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
						</li>
					@endfor 
					{{-- 다음 페이지 --}}
					<li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
						<a class="page-link" href="{{ $data->nextPageUrl() }}"> <i data-lucide="chevron-right" class="w-4 h-4"></i></a>
					</li> 
					{{-- 마지막 페이지 --}}
					<li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
						<a class="page-link" href="{{ $data->url($lastPage) }}"> <i data-lucide="chevrons-right" class="w-4 h-4"></i></a>
					</li>
				</ul>
			</nav>	
		</div>
	</div>

	<!-- 회사소식 등록 모달 -->
	<div id="notice_regist-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">회사소식 등록</h2>
					<button onclick="modalClose('notice_regist-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<h3 class="mb-1 text-base font-semibold">소식 정보</h3>
					<div class="flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">제목(ko) <span class="text-danger">*</span></p>
						<input type="text" id="regist_subject" name="regist_subject" class="form-control" placeholder="">
					</div>
					<div class="flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">제목(en) <span class="text-danger">*</span></p>
						<input type="text" id="regist_subject_en" name="regist_subject_en" class="form-control" placeholder="">
					</div>
					<div class="flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">제목(cn) <span class="text-danger">*</span></p>
						<input type="text" id="regist_subject_cn" name="regist_subject_cn" class="form-control" placeholder="">
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">구분 <span class="text-danger">*</span></p>
                            <select id="regist_gubun" name="regist_gubun" class="form-select">
                                <option value="">구분을 선택해주세요.</option>
								<option value="1">언론보도</option>
								<option value="2">MOU</option>
								<option value="3">봉사</option>
								<option value="4">인증</option>
                            </select>
                        </div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">등록일 <span class="text-danger">*</span></p>
							<div class="relative w-full">
								<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
									<i data-lucide="calendar" class="w-4 h-4"></i>
								</div>
								<input type="text" id="regist_writed_at" name="regist_writed_at" class="datepicker form-control pl-12" data-single-mode="true">
							</div>
						</div>
					</div>
					<div class="flex items-start gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium pt-2">설명글(ko) <span class="text-danger">*</span></p>
						<textarea id="regist_contents" name="regist_contents" class="form-control h-32"></textarea>
					</div>
					<div class="flex items-start gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium pt-2">설명글(en) <span class="text-danger">*</span></p>
						<textarea id="regist_contents_en" name="regist_contents_en" class="form-control h-32"></textarea>
					</div>
					<div class="flex items-start gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium pt-2">설명글(cn) <span class="text-danger">*</span></p>
						<textarea id="regist_contents_cn" name="regist_contents_cn" class="form-control h-32"></textarea>
					</div>

					<h3 class="mt-5 mb-1 font-medium">소식 이미지 <span class="text-danger">*</span> <span class="text-xs">(첫번째 이미지는 목록에 썸네일로 표시됩니다.)</span></h3>
					<input type="file" id="regist_files" name="regist_files" class="filepond filepond-media" multiple />
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('notice_regist-modal')">취소</button>
					<button type="button" class="btn btn-primary w-20" onclick="regist()">등록</button>
				</div>
			</div>
		</div>
	</div>

	<!-- 회사소식 수정 모달 -->
	<div id="notice_modify-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">회사소식 수정</h2>
					<button onclick="modalClose('notice_modify-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<h3 class="mb-1 text-base font-semibold">소식 정보</h3>
					<input type="hidden" id="modify_idx" name="modify_idx">
					<div class="flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">제목(ko) <span class="text-danger">*</span></p>
						<input type="text" id="modify_subject" name="modify_subject" class="form-control" placeholder="">
					</div>
					<div class="flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">제목(en) <span class="text-danger">*</span></p>
						<input type="text" id="modify_subject_en" name="modify_subject_en" class="form-control" placeholder="">
					</div>
					<div class="flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">제목(cn) <span class="text-danger">*</span></p>
						<input type="text" id="modify_subject_cn" name="modify_subject_cn" class="form-control" placeholder="">
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
                            <p class="shrink-0 w-32 font-medium">구분 <span class="text-danger">*</span></p>
                            <select id="modify_gubun" name="modify_gubun" class="form-select">
                                <option value="">구분을 선택해주세요.</option>
								<option value="1">언론보도</option>
								<option value="2">MOU</option>
								<option value="3">봉사</option>
								<option value="4">인증</option>
                            </select>
                        </div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">등록일 <span class="text-danger">*</span></p>
							<div class="relative w-full">
								<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
									<i data-lucide="calendar" class="w-4 h-4"></i>
								</div>
								<input type="text" id="modify_writed_at" name="modify_writed_at" class="datepicker form-control pl-12" data-single-mode="true">
							</div>
						</div>
					</div>
					<div class="flex items-start gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium pt-2">설명글(ko) <span class="text-danger">*</span></p>
						<textarea id="modify_contents" name="modify_contents" class="form-control h-32"></textarea>
					</div>
					<div class="flex items-start gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium pt-2">설명글(en) <span class="text-danger">*</span></p>
						<textarea id="modify_contents_en" name="modify_contents_en" class="form-control h-32"></textarea>
					</div>
					<div class="flex items-start gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium pt-2">설명글(cn) <span class="text-danger">*</span></p>
						<textarea id="modify_contents_cn" name="modify_contents_cn" class="form-control h-32"></textarea>
					</div>

					<h3 class="mt-5 mb-1 font-medium">소식 이미지 <span class="text-danger">*</span> <span class="text-xs">(첫번째 이미지는 목록에 썸네일로 표시됩니다.)</span></h3>
					<input type="file" id="modify_files" name="modify_files" class="filepond filepond-media" name="photoUpload" multiple />
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="javascript:window.location.reload()">취소</button>
					<button type="button" class="btn btn-primary w-20" onclick="modify()">수정</button>
					<button type="button" class="btn btn-outline-danger w-20" onclick="deleteNotice()">삭제</button>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- END: Content -->
</div>
@include('admin.footer._footer')
<script>

let removedFileIdxs = [];
let existingFiles = [];

function initFilePond() {
    try {
        if (!window.__fp_reg) {
            FilePond.registerPlugin(
                FilePondPluginImagePreview,
                FilePondPluginFileValidateSize,
                FilePondPluginFileValidateType
            );
            window.__fp_reg = true;
        }

        window.pondRegist = null;
        window.pondModify = null;
        const registEl = document.querySelector('input[name="regist_files"]');
        const modifyEl = document.querySelector('input[name="modify_files"]');

        // 등록용
        if (registEl) {
            window.pondRegist = FilePond.create(registEl, {
                allowMultiple: true,
                allowReorder: true,
                acceptedFileTypes: ['image/*'],
                maxFileSize: '256MB',
                labelIdle: '소식 이미지를 여기에 드래그하거나 <span class="filepond--label-action">클릭하여 업로드</span>하세요.',
                imagePreviewHeight: 150,
                credits: false,
            });
        }

        // 수정용
        if (modifyEl) {
            window.pondModify = FilePond.create(modifyEl, {
                allowMultiple: true,
                allowReorder: true,
                acceptedFileTypes: ['image/*', 'video/*'],
                maxFileSize: '256MB',
                labelIdle: '소식 이미지를 여기에 드래그하거나 <span class="filepond--label-action">클릭하여 업로드</span>하세요.',
                imagePreviewHeight: 150,
                credits: false,
                server: {
                    // files 옵션에서 넘겨준 source를 받아 실제 File 객체로 변환
                    load: (source, load, error) => {
                        const { url, name, type } = source;

                        fetch(url)
                            .then(res => {
                                if (!res.ok) throw new Error('Fetch failed');
                                return res.blob();
                            })
                            .then(blob => {
                                const file = new File(
                                    [blob],
                                    name || 'file',
                                    {
                                        type: type || blob.type,
                                        lastModified: new Date().getTime()
                                    }
                                );
                                load(file);
                            })
                            .catch(err => {
                                console.error('파일 로드 실패:', err);
                                error(err.message);
                            });
                    }
                },
                files: existingFiles,
                onremovefile: (error, file) => {
                    const fileId = file.getMetadata('idx');
                    if (fileId) {
                    	removedFileIdxs.push(fileId);
                    }
                }
            });
        }

        // input accept 속성
        $('input[name="regist_files"]').attr('accept', 'image/*');
        $('input[name="modify_files"]').attr('accept', 'image/*');

    } catch (err) {
        console.error('FilePond 초기화 오류:', err);
    }
}

// DOM 준비 상태에 따라 실행
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFilePond);
} else {
    initFilePond();
}

/* ===============================
 *  이미지 검증 유틸
 * =============================== */
 
const IMG_MIME = ['image/jpeg','image/png','image/webp','image/gif'];
const IMG_EXT  = /\.(jpe?g|png|webp|gif)$/i;
const MAX_IMG = 256 * 1024 * 1024;    // 256MB


function isImage(file) {
	const t = (file.type || '').toLowerCase();
	return (t && IMG_MIME.includes(t)) || IMG_EXT.test(file.name);
}

/* ===============================
 *  솔라테크소식 등록
 * =============================== */

function regist() {
	const subject		= $('#regist_subject').val().trim();
	const subject_en	= $('#regist_subject_en').val().trim();
	const subject_cn	= $('#regist_subject_cn').val().trim();
  	const gubun			= $('#regist_gubun').val();
  	const writed_at		= $('#regist_writed_at').val();
  	const contents		= $('#regist_contents').val().trim();
  	const contents_en	= $('#regist_contents_en').val().trim();
  	const contents_cn	= $('#regist_contents_cn').val().trim();

  	if (!subject)	return showToast('제목(ko)을 입력해주세요.','error');
  	if (!subject_en)	return showToast('제목(en)을 입력해주세요.','error');
  	if (!subject_cn)	return showToast('제목(cn)을 입력해주세요.','error');
  	if (!gubun)		return showToast('구분을 입력해주세요.','error');  
	if (!writed_at)	return showToast('등록일을 입력해주세요.','error');
	if (!contents)	return showToast('설명글(ko)을 입력해주세요.','error');
	if (!contents_en)	return showToast('설명글(en)을 입력해주세요.','error');
	if (!contents_cn)	return showToast('설명글(cn)을 입력해주세요.','error');

	const fd = new FormData();
	fd.append('subject', subject);
	fd.append('subject_en', subject_en);
	fd.append('subject_cn', subject_cn);
	fd.append('gubun', gubun);
	fd.append('writed_at', writed_at);
	fd.append('contents', contents);
	fd.append('contents_en', contents_en);
	fd.append('contents_cn', contents_cn);

	const noticeFiles = window.pondRegist?.getFiles?.() || [];
	if (!noticeFiles.length) return showToast('소식이미지를 한 개 이상 업로드해주세요.','error');
	{
		const invalid=[], tooLarge=[];
		noticeFiles.forEach(({file})=>{
			const img=isImage(file);
			if(!img) invalid.push(file.name);
			if(img && file.size>MAX_IMG) tooLarge.push(`${file.name} (이미지 > 256MB)`);
		});
		if(invalid.length) return showToast(`소식이미지 형식 오류:\n- ${invalid.join('\n- ')}`,'error');
		if(tooLarge.length) return showToast(`소식이미지 용량 초과:\n- ${tooLarge.join('\n- ')}`,'error');
	}
	noticeFiles.forEach(({file})=> fd.append('noticeFiles[]', file, file.name));
	
	if (!confirm('회사소식을 등록하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/notice/regist',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	showToast('회사소식이 저장되었습니다.','success');
            	location.reload();
            } else {
            	showToast(res.message || '저장 실패','error');
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}

function setModifyForm(idx) {
    $.ajax({
    	url: '/admin/notice/detailForm/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
        	if (res.status !== 'success') {
        		showToast(res.message || '불러오기 실패','error');
                return;
            }

            const d = res.data;

            $('#modify_idx').val(d.idx);
			$('#modify_subject').val(d.subject);
			$('#modify_subject_en').val(d.subject_en);
			$('#modify_subject_cn').val(d.subject_cn);
			$('#modify_gubun').val(d.gubun);
			$('#modify_writed_at').val(d.writed_at);
			$('#modify_contents').val(d.contents);
			$('#modify_contents_en').val(d.contents_en);
			$('#modify_contents_cn').val(d.contents_cn);
			
            if (window.pondModify) {
            	removedFileIdxs = [];
            	existingFiles = [];

                (res.files || []).forEach(function (f) {
                    const url = '/storage/' + f.up_name;

                    existingFiles.push({
                        source: {
                            url: url,
                            name: f.ori_name || 'file',
                            type: null,
                        },
                        options: {
                            type: 'local',
                            metadata: {
                                idx: f.idx,
                                orderby: f.orderby
                            }
                        }
                    });
                });

                window.pondModify.removeFiles();
                window.pondModify.setOptions({
                    files: existingFiles
                });
            }

			modalOpen('notice_modify-modal');
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}

const existingFileOrder = [];

function modify() {
	const idx			= $('#modify_idx').val();
	const subject		= $('#modify_subject').val().trim();
	const subject_en	= $('#modify_subject_en').val().trim();
	const subject_cn	= $('#modify_subject_cn').val().trim();
  	const gubun			= $('#modify_gubun').val();
  	const writed_at		= $('#modify_writed_at').val();
  	const contents		= $('#modify_contents').val().trim();
  	const contents_en	= $('#modify_contents_en').val().trim();
  	const contents_cn	= $('#modify_contents_cn').val().trim();

  	if (!subject)	return showToast('제목(ko)을 입력해주세요.','error');
  	if (!subject_en)	return showToast('제목(en)을 입력해주세요.','error');
  	if (!subject_cn)	return showToast('제목(cn)을 입력해주세요.','error');
  	if (!gubun)		return showToast('구분을 입력해주세요.','error');  
	if (!writed_at)	return showToast('등록일을 입력해주세요.','error');
	if (!contents)	return showToast('설명글(ko)을 입력해주세요.','error');
	if (!contents_en)	return showToast('설명글(en)을 입력해주세요.','error');
	if (!contents_cn)	return showToast('설명글(cn)을 입력해주세요.','error');

	const fd = new FormData();
	fd.append('subject', subject);
	fd.append('subject_en', subject_en);
	fd.append('subject_cn', subject_cn);
	fd.append('gubun', gubun);
	fd.append('writed_at', writed_at);
	fd.append('contents', contents);
	fd.append('contents_en', contents_en);
	fd.append('contents_cn', contents_cn);

	// MO 썸네일 - FilePond
	const files = window.pondModify?.getFiles?.() || [];
	if (!files.length) return showToast('소식이미지를 한 개 이상 업로드해주세요.','error');
	{
		const invalid=[], tooLarge=[];
		files.forEach((item, idx) => {
			const files_idx = item.getMetadata('idx');
            const orderby = idx + 1;
            if (files_idx) {
            	existingFileOrder.push({ idx: files_idx, orderby: orderby });
            } else {
            	const img=isImage(item.file);
				if(!img) invalid.push(item.file.name);
				if(img && item.file.size>MAX_IMG) tooLarge.push(`${item.file.name} (이미지 > 256MB)`);
				fd.append('new_file_upload[]', item.file, item.file.name);
				fd.append('new_file_order[]', orderby); // ✅ 순서 일치 시켜 전송
            }
		});
		if(invalid.length) return showToast(`시공이미지 형식 오류:\n- ${invalid.join('\n- ')}`,'error');
		if(tooLarge.length) return showToast(`시공이미지 용량 초과:\n- ${tooLarge.join('\n- ')}`,'error');
	}
	
	fd.append('removedFileIdxs', JSON.stringify(removedFileIdxs));
	fd.append('existingFileOrder', JSON.stringify(existingFileOrder));

	if (!confirm('회사소식을 수정하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/notice/modify/'+idx,
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('회사소식이 수정되었습니다.','success');
            	location.reload();
            } else {
            	showToast(res.message || '저장 실패','error');
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}

function deleteNotice() {
	const idx = $('#modify_idx').val();
  	if (!idx) return showToast('오류가 발생하였습니다.','error');
	
	if (!confirm('정말로 해당 회사소식을 삭제하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/notice/delete/'+idx,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('회사소식이 삭제되었습니다.','success');
                location.href = '/admin/notice';
            } else {
            	showToast(res.message || '저장 실패','error');
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}
</script>