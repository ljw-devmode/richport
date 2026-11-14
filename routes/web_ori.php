<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CheckListController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DaouController;
use Illuminate\Support\Facades\Storage;
use App\Models\Inquire;

Route::get('/login', function () {
	return redirect()->route('admin.login');
})->name('login');

/* 관리자페이지 (시작) */
Route::middleware('web')->group(function () {
	Route::prefix('admin')->name('admin.')->group(function () {
		Route::get('login', [AdminController::class, 'loginGet'])->name('login');
		Route::post('login', [AdminController::class, 'loginPost']);
		Route::post('logout', [AdminController::class, 'logout'])->name('logout');
		
		Route::middleware(['web','auth:admin'])->group(function () {
			/* 공통 */
			Route::post('/editor-image/upload', [EditorController::class, 'upload'])->name('editor.image.upload');
			Route::get('/cities/{regions_idx}', [AdminController::class, 'getCities']);

			// 파일 다운로드
			Route::get('/download/{ori_name}/{path}', [AdminController::class, 'downloadFile'])->where('path', '.*')->name('file.download');
			// 이미지 보기
			Route::get('/view/{path}', [AdminController::class, 'viewFile'])->where('path', '.*')->name('file.view');

			/* 대시보드 */
			Route::get('/', [AdminController::class, 'getDashboard'])->name('dashboard');
			Route::get('/dashboard', [AdminController::class, 'getDashboard'])->name('dashboard');
			Route::post('/memo/store', [AdminController::class, 'dashboardMemoSave']);
			Route::get('/memo/list/{name}', [AdminController::class, 'dashboardMemoList']);
			Route::delete('/memo/delete/{idx}', [AdminController::class, 'dashboardMemoDelete']);
			Route::put('/memo/update/{idx}', [AdminController::class, 'dashboardMemoUpdate']);

			Route::get('/kma/syncYearlyKma', [AdminController::class, 'syncYearlyKma'])->name('kma.sync'); // 일사량 동기화 (기상정 -> DB)
			
			/* 회사소개 > 연혁 */
			Route::middleware(['can.admin:history.view'])->group(function () {
				Route::get('/history', [AdminController::class, 'getHistoryList'])->name('history');
				Route::get('/history/list', [AdminController::class, 'getHistoryList'])->name('history.list');
				Route::get('/history/export', [AdminController::class, 'getHistoryExport'])->name('history.export');
				Route::post('/history/regist', [AdminController::class, 'setHistoryRegist'])->name('history.regist');
				Route::post('/history/detailForm/{idx}', [AdminController::class, 'getHistoryDetailForm'])->name('history.detailForm');
				Route::post('/history/modify/{idx}', [AdminController::class, 'setHistoryModify'])->name('history.modify');
				Route::delete('/history/delete/{idx}', [AdminController::class, 'setHistoryDelete'])->name('history.delete');
			});
				
			/* 회사소개 > 솔라테크소식 */
			Route::middleware(['can.admin:notice.view'])->group(function () {
				Route::get('/notice', [AdminController::class, 'getNoticeList'])->name('notice');
				Route::get('/notice/list', [AdminController::class, 'getNoticeList'])->name('notice.list');
				Route::get('/notice/export', [AdminController::class, 'getNoticeExport'])->name('notice.export');
				Route::post('/notice/regist', [AdminController::class, 'setNoticeRegist'])->name('notice.regist');
				Route::post('/notice/detailForm/{idx}', [AdminController::class, 'getNoticeDetailForm'])->name('notice.detailForm');
				Route::post('/notice/modify/{idx}', [AdminController::class, 'setNoticeModify'])->name('notice.modify');
				Route::delete('/notice/delete/{idx}', [AdminController::class, 'setNoticeDelete'])->name('notice.delete');
			});

			/* 프로젝트 관리 > 프로젝트 목록 */
			Route::middleware(['can.admin:project.view'])->group(function () {
				Route::get('/project', [AdminController::class, 'getProjectList'])->name('project');
				Route::get('/project/list', [AdminController::class, 'getProjectList'])->name('project.list');
				Route::get('/project/export', [AdminController::class, 'getProjectExport'])->name('project.export');
				Route::post('/project/save', [AdminController::class, 'setProjectSave'])->name('project.save');
				Route::post('/project/detailForm/{idx}', [AdminController::class, 'getProjectDetailForm'])->name('project.detailForm');
				Route::post('/project/modify/{idx}', [AdminController::class, 'setProjectModify'])->name('project.modify');
				Route::delete('/project/delete/{idx}', [AdminController::class, 'setProjectDelete'])->name('project.delete');
			});
			
			/* 프로젝트 관리 > 원가관리 목록 */
			Route::middleware(['can.admin:proposalprd.view'])->group(function () {
				Route::get('/proposalprd', [AdminController::class, 'getProposalprdList'])->name('proposalprd');
				Route::get('/proposalprd/list', [AdminController::class, 'getProposalprdList'])->name('proposalprd.list');
				Route::get('/proposalprd/export', [AdminController::class, 'getProposalprdExport'])->name('proposalprd.export');
				Route::post('/proposalprd/save', [AdminController::class, 'setProposalprdSave'])->name('proposalprd.save');
				Route::post('/proposalprd/detailForm/{idx}', [AdminController::class, 'getProposalprdDetailForm'])->name('proposalprd.detailForm');
				Route::post('/proposalprd/modify/{idx}', [AdminController::class, 'setProposalprdModify'])->name('proposalprd.modify');
				Route::delete('/proposalprd/delete/{idx}', [AdminController::class, 'setProposalprdDelete'])->name('proposalprd.delete');
			});	
			
			/* 프로젝트 관리 > 견적서 목록 */
			Route::middleware(['can.admin:project.view'])->group(function () {
				Route::get('/estimate', [AdminController::class, 'getEstimateList'])->name('estimate');
				Route::get('/estimate/list', [AdminController::class, 'getEstimateList'])->name('estimate.list');
				Route::get('/estimate/export', [AdminController::class, 'getEstimateExport'])->name('estimate.export');
				Route::post('/estimate/save', [AdminController::class, 'setEstimateSave'])->name('estimate.save');
				Route::post('/estimate/detailForm/{idx}', [AdminController::class, 'getEstimateDetailForm'])->name('estimate.detailForm');
				Route::post('/estimate/modify/{idx}', [AdminController::class, 'setEstimateModify'])->name('estimate.modify');
				Route::delete('/estimate/delete/{idx}', [AdminController::class, 'setEstimateDelete'])->name('estimate.delete');
			});				

			/* 사업관리 > 포트폴리오 */
			Route::middleware(['can.admin:portfolio.view'])->group(function () {
				Route::get('/portfolio', [AdminController::class, 'getPortfolioList'])->name('portfolio');
				Route::get('/portfolio/list', [AdminController::class, 'getPortfolioList'])->name('portfolio.list');
				Route::get('/portfolio/export', [AdminController::class, 'getPortfolioExport'])->name('portfolio.export');
				Route::post('/portfolio/regist', [AdminController::class, 'setPortfolioRegist'])->name('portfolio.regist');
				Route::post('/portfolio/detailForm/{idx}', [AdminController::class, 'getPortfolioDetailForm'])->name('portfolio.detailForm');
				Route::post('/portfolio/modify/{idx}', [AdminController::class, 'setPortfolioModify'])->name('portfolio.modify');
				Route::delete('/portfolio/delete/{idx}', [AdminController::class, 'setPortfolioDelete'])->name('portfolio.delete');
			});

			/* 사업관리 > 발전소 관리 */
			Route::middleware(['can.admin:company.view'])->group(function () {
				Route::get('/company', [AdminController::class, 'getCompanyList'])->name('company'); 
				Route::get('/company/list', [AdminController::class, 'getCompanyList'])->name('company.list');
				Route::get('/company/export', [AdminController::class, 'getCompanyExport'])->name('company.export');
				Route::post('/company/regist', [AdminController::class, 'setCompanyRegist'])->name('company.regist');
				Route::get('/company/detail/{idx}', [AdminController::class, 'getCompanyDetail'])->name('company.detail');
				Route::post('/company/modify/{idx}', [AdminController::class, 'setCompanyModify'])->name('company.modify');
				Route::delete('/company/delete/{idx}', [AdminController::class, 'setCompanyDelete'])->name('company.delete');

				/* 사업관리 > 발전소 관리 > 상세 > 매출 */
				Route::post('/company/sales/save', [AdminController::class, 'saveCompanySales'])->name('sales.save');
				Route::post('/company/sales/delete', [AdminController::class, 'deleteCompanySales'])->name('sales.delete');
				Route::post('/company/sales/{idx}', [AdminController::class, 'getCompanySales'])->name('sales.detail');

				/* 사업관리 > 발전소 관리 > 상세 > 발전량 */
				Route::post('/company/kwh/save', [AdminController::class, 'saveCompanyKwh'])->name('kwh.save');
				Route::post('/company/kwh/delete', [AdminController::class, 'deleteCompanyKwh'])->name('kwh.delete');
				Route::post('/company/kwh/{idx}', [AdminController::class, 'getCompanyKwh'])->name('kwh.detail');
	
				// CSV 양식 다운로드 (GET 방식)
				Route::get('/kwh/downloadtemplate', [AdminController::class, 'downloadKwhTemp'])->name('kwh.downloadtemp');
				// CSV 파일 업로드 및 저장 (POST 방식)
				Route::post('/kwh/upload', [AdminController::class, 'uploadKwhTemp'])->name('kwh.uploadtemp');

				/* 사업관리 > 발전소 관리 > 상세 > 목표 발전량 */
				Route::post('/company/yearkwh/save', [AdminController::class, 'saveCompanyYearKwh'])->name('yearkwh.save');
				Route::post('/company/yearkwh/delete', [AdminController::class, 'deleteCompanyYearKwh'])->name('yearkwh.delete');
				Route::post('/company/yearkwh/{idx}', [AdminController::class, 'getCompanyYearKwh'])->name('yearkwh.detail');
		
				/* 사업관리 > 발전소 관리 > 상세 > 목표 발전시간 */
				Route::post('/company/yearkwhhours/save', [AdminController::class, 'saveCompanyYearKwhHours'])->name('yearkwhhours.save');
				Route::post('/company/yearkwhhours/delete', [AdminController::class, 'deleteCompanyYearKwhHours'])->name('yearkwhhours.delete');
				Route::post('/company/yearkwhhours/{idx}', [AdminController::class, 'getCompanyYearKwhHours'])->name('yearkwhhours.detail');
			
				/* 사업관리 > 발전소 관리 > 상세 > O&M, 이슈 */
				Route::post('/company/manage/save', [AdminController::class, 'saveCompanyManage'])->name('manage.save');
				Route::post('/company/manage/delete', [AdminController::class, 'deleteCompanyManage'])->name('manage.save');
				Route::post('/company/manage/{idx}', [AdminController::class, 'getCompanyManage'])->name('manage.save');

				/* 사업관리 > 발전소 관리 > 상세 > SMP */
				Route::post('/company/smp/save', [AdminController::class, 'saveCompanySmp'])->name('smp.save');
				Route::post('/company/smp/delete', [AdminController::class, 'deleteCompanySmp'])->name('smp.delete');
				Route::post('/company/smp/{idx}', [AdminController::class, 'getCompanySmp'])->name('smp.detail');
				
				/* 사업관리 > 발전소 관리 > 상세 > REC */
				Route::post('/company/rec/save', [AdminController::class, 'saveCompanyRec'])->name('rec.save');
				Route::post('/company/rec/delete', [AdminController::class, 'deleteCompanyRec'])->name('rec.delete');
				Route::post('/company/rec/{idx}', [AdminController::class, 'getCompanyRec'])->name('rec.detail');
								
			});

			/* 사업관리 > 발전소 매출관리 */
			Route::middleware(['can.admin:companysales.view'])->group(function () {
				Route::get('/companysales', [AdminController::class, 'getCompanySalesList'])->name('companysales'); // 매출 관리
				Route::get('/companysales/list', [AdminController::class, 'getCompanySalesList'])->name('companysales.list');
				Route::post('/companysales/save', [AdminController::class, 'setCompanySalesSave'])->name('companysales.regist');
				Route::post('/companysales/detailForm/{idx}', [AdminController::class, 'getCompanySalesDetailForm'])->name('companysales.detailForm');
				Route::delete('/companysales/delete/{idx}', [AdminController::class, 'setCompanySalesDelete'])->name('companysales.delete');
				Route::get('/companysales/export', [AdminController::class, 'companySalesExport'])->name('companysales.export');

			});

			/* 사업관리 > SPC 관리 */
			Route::middleware(['can.admin:spc.view'])->group(function () {
				Route::get('/spc', [AdminController::class, 'getCompanySpcList'])->name('spc'); // spc 관리
				Route::get('/spc/list', [AdminController::class, 'getCompanySpcList'])->name('spc.list');
				Route::post('/spc/regist', [AdminController::class, 'setCompanySpcRegist'])->name('spc.regist');
				Route::post('/spc/detailForm/{idx}', [AdminController::class, 'getCompanySpcDetailForm'])->name('spc.detailForm');
				Route::post('/spc/modify/{idx}', [AdminController::class, 'setCompanySpcModify'])->name('spc.modify');
				Route::delete('/spc/delete/{idx}', [AdminController::class, 'setCompanySpcDelete'])->name('spc.delete');
			});

			/* 사업관리 > SPC 리포트 */
			Route::middleware(['can.admin:spc_report.view'])->group(function () {
				Route::get('/report/spc', [AdminController::class, 'getReportSpcList'])->name('reportSpc'); // Company 관리
				Route::get('/report/spc/list', [AdminController::class, 'getReportSpcList'])->name('reportSpc.list');
				// Route::get('/report/spc/detail/{idx}', [AdminController::class, 'getReportSpcDetail'])->name('reportSpc.detail');
				Route::get('/report/spc/monthly', [AdminController::class, 'getSpcMonthlyReport'])->name('reportSpc.monthly');
				Route::post('/report/spc/monthly', [AdminController::class, 'getSpcMonthlyReport'])->name('reportSpc.monthly');
				Route::get('/report/spc/{idx}/download-pdf', [ReportController::class, 'downloadPdf']);
			});
			
			/* 사업관리 > SPC 리포트 생성 */
			Route::middleware(['can.admin:company.report'])->group(function () {
				Route::post('/report/company/reportgenerater/{idx}', [AdminController::class, 'generateReport'])->name('reportCompany.reportgenerater');			
			});	

			/* 사업관리 > 체크리스트 관리 */
			Route::middleware(['can.admin:checklist.view'])->group(function () {
				Route::get('/checklist', [AdminController::class, 'checkList'])->name('check.list');
				Route::get('/checkregist', [AdminController::class, 'checkRegistForm'])->name('check.regist.form');
				Route::post('/checkregist', [AdminController::class, 'checkRegist'])->name('check.regist');
				Route::get('/checkmodify/{idx}', [AdminController::class, 'checkModifyForm'])->name('check.modify.form');
				Route::post('/checkmodify/{idx}', [AdminController::class, 'checkModify'])->name('check.modify');
				Route::post('/checkcopy/{idx}', [AdminController::class, 'checkCopy'])->name('check.copy');
				Route::post('/checkdelete/{idx}', [AdminController::class, 'checkDelete'])->name('check.delete');
				Route::get('/checkResultList', [AdminController::class, 'checkResultList'])->name('check.result');
			});
			
			/* 사업관리 > 체크리스트 결과 관리 */
			Route::middleware(['can.admin:checkresult.view'])->group(function () {
				Route::get('/checkResultList', [AdminController::class, 'checkResultList'])->name('check.result');
				Route::get('/checkResultView/{idx}', [AdminController::class, 'checkResultView'])->name('check.resultview');
			});

			/* 사업관리 > 일사량 전년·전월 비교 */
			Route::middleware(['can.admin:kma_monthly.view'])->group(function () {
				Route::get('/kma', [AdminController::class, 'getCompareMonthly'])->name('kma'); // 일사량 조회
				Route::get('/kma/monthly', [AdminController::class, 'getCompareMonthly'])->name('kma.monthly'); // 일사량 조회
				Route::get('/kma/monthly/export', [AdminController::class, 'kmaCompareMonthlyExport'])->name('kma.monthlyexport');
			});

			/* 사업관리 > 일사량 기간조회 */
			Route::middleware(['can.admin:kma_db.view'])->group(function () {
				Route::get('/kma/db', [AdminController::class, 'getCompareMonthlyDB'])->name('kma.db'); // 일사량 기간 조회
				Route::get('/kma/db/export', [AdminController::class, 'kmaDbExport'])->name('kma.dbexport');
				Route::post('/kma/syncYearlyKma', [AdminController::class, 'syncYearlyKma'])->name('kma.sync'); // 일사량 동기화 (기상정 -> DB)
			});

			/* 고객문의 > 문의관리 */
			Route::middleware(['can.admin:inquire.view'])->group(function () {
				Route::get('/inquire', [AdminController::class, 'getInquireList'])->name('inquire');
				Route::get('/inquire/list', [AdminController::class, 'getInquireList'])->name('inquire.list');
				Route::get('/inquire/export', [AdminController::class, 'getInquireExport'])->name('inquire.export');
				Route::post('/inquire/detailForm/{idx}', [AdminController::class, 'getInquireDetailForm'])->name('inquire.detailForm');
				Route::post('/inquire/modify/{idx}', [AdminController::class, 'setInquireModify'])->name('inquire.modify');
				Route::delete('/inquire/delete/{idx}', [AdminController::class, 'setInquireDelete'])->name('inquire.delete');
			});

			/* 서비스 관리 > 사용자 관리 */
			Route::middleware(['can.admin:user.view'])->group(function () {
				Route::get('/user', [AdminController::class, 'getUserList'])->name('user');
				Route::post('/user/regist', [AdminController::class, 'setUserRegist'])->name('user.regist');
				Route::get('/user/list', [AdminController::class, 'getUserList'])->name('user.list');
				Route::post('/user/detailForm/{idx}', [AdminController::class, 'getUserDetailForm'])->name('user.detailForm');
				Route::post('/user/modify/{idx}', [AdminController::class, 'setUserModify'])->name('user.modify');
				Route::delete('/user/delete/{idx}', [AdminController::class, 'setUserDelete'])->name('user.delete');
				Route::get('/createId', [AdminController::class, 'setAccountRegist']);			
			});

			/* 서비스 관리 > 부서 관리 */
			Route::middleware(['can.admin:department.view'])->group(function () {
				Route::get('/department', [AdminController::class, 'getDepartmentList'])->name('department');
				Route::get('/department/list', [AdminController::class, 'getDepartmentList'])->name('department.list');
				Route::post('/department/regist', [AdminController::class, 'setDepartmentRegist'])->name('department.regist');
				Route::post('/department/detailForm/{idx}', [AdminController::class, 'getDepartmentDetailForm'])->name('department.detailForm');
				Route::post('/department/modify/{idx}', [AdminController::class, 'setDepartmentModify'])->name('department.modify');
				Route::delete('/department/delete/{idx}', [AdminController::class, 'setDepartmentDelete'])->name('department.delete');
			});	

			/* 서비스 관리 > 외주사 관리 */
			Route::middleware(['can.admin:outcompany.view'])->group(function () {
				Route::get('/outcompany', [AdminController::class, 'getOutCompanyList'])->name('outcompany');
				Route::get('/outcompany/list', [AdminController::class, 'getOutCompanyList'])->name('outcompany.list');
				Route::post('/outcompany/regist', [AdminController::class, 'setOutCompanyRegist'])->name('outcompany.regist');
				Route::get('/outcompany/detail/{idx}', [AdminController::class, 'getOutCompanyDetail'])->name('outcompany.detail');
				Route::post('/outcompany/modify/{idx}', [AdminController::class, 'setOutCompanyModify'])->name('outcompany.modify');
				Route::delete('/outcompany/delete/{idx}', [AdminController::class, 'setOutCompanyDelete'])->name('outcompany.delete');
			});			

			/* 서비스 관리 > 외주사 > 발전소 관리 */
			Route::middleware(['can.admin:outcompany.view'])->group(function () {
				Route::post('/outcompany/companylist/{idx}', [AdminController::class, 'getOutCompanyCompanyList'])->name('outuser.companylist');
				Route::post('/outcompany/companysave', [AdminController::class, 'setOutCompanyCompanySave'])->name('outuser.companysave');
				Route::post('/outcompany/companydelete', [AdminController::class, 'setOutCompanyCompanyDelete'])->name('outuser.companydelete');
			});	

			/* 서비스 관리 > 외주사 > 담당자 관리 */
			Route::middleware(['can.admin:outcompany.view'])->group(function () {
				Route::post('/outcompany/userlist/{idx}', [AdminController::class, 'getOutCompanyUserList'])->name('outuser.userlist');
				Route::post('/outcompany/usersave', [AdminController::class, 'setOutCompanyUserSave'])->name('outuser.usersave');
				Route::post('/outcompany/userdelete', [AdminController::class, 'setOutCompanyUserDelete'])->name('outuser.userdelete');
			});	

			/* 서비스 관리 > 은행 계좌 관리 */
			Route::middleware(['can.admin:bankAccount.view'])->group(function () {
				Route::get('/bankAccount', [AdminController::class, 'getBankAccountList'])->name('bankAccount'); // 은행 계좌 관리
				Route::get('/bankAccount/list', [AdminController::class, 'getBankAccountList'])->name('bankAccount.list');
				Route::post('/bankAccount/regist', [AdminController::class, 'setBankAccountRegist'])->name('bankAccount.regist');
				Route::get('/bankAccount/detail/{idx}', [AdminController::class, 'getBankAccountDetail'])->name('bankAccount.detail');
				Route::put('/bankAccount/modify/{idx}', [AdminController::class, 'setBankAccountModify'])->name('bankAccount.modify');
				Route::delete('/bankAccount/delete/{idx}', [AdminController::class, 'setBankAccountDelete'])->name('bankAccount.delete');
			});

			// 파일 다운로드
			Route::get('/file/download/{path}', function (string $path) {
				if (!Storage::disk('public')->exists($path)) {
					abort(404, '파일이 존재하지 않습니다.');
				}
				
				// DB에서 ori_name 찾기 (up_name이 정확히 path 컬럼과 동일하게 저장되어 있다고 가정)
				$inquire = Inquire::where('up_name', $path)->first();
				$downloadName = $inquire?->ori_name ?? basename($path); // ori_name이 있으면 사용
				
				// 파일 다운로드
				return Storage::disk('public')->download($path, $downloadName);
			})->where('path', '.*') 
			->name('file.download');
			
			Route::fallback(function () {
				return redirect('/admin');
			});

			/* API 라우트 */
			Route::post('/api/set-language', function (Illuminate\Http\Request $request) {
				$lang = $request->input('lang');
		
				// 유효한 언어 확인
				$validLangs = ['ko', 'en', 'zh-cn'];
		
				if (!in_array($lang, $validLangs)) {
					return response()->json(['status' => 'error', 'message' => 'Invalid language'], 400);
				}
		
				// 쿠키 설정 (30일 유효)
				$response = response()->json(['status' => 'success', 'message' => 'Language cookie set']);
				$response->cookie('selectedLang', $lang, 30 * 24 * 60, '/', null, false, false);
				return $response;
			});
		});
	});
});
/* 관리자페이지 (끝) */


/* 체크리스트 (시작) */
// 인증(로그인) 페이지 및 처리 (미들웨어 제외 영역)
Route::middleware('web')->group(function () {
	Route::prefix('/check')->group(function () {
		// 로그인 화면 호출
		Route::get('', [CheckListController::class, 'checkLoginView'])->name('check');
		Route::get('/login', [CheckListController::class, 'checkLoginView'])->name('check.login');
		Route::post('/auth', [CheckListController::class, 'checkAuth'])->name('check.auth');
		Route::get('/usercheck', [CheckListController::class, 'userCheck'])->name('check.usercheck');
		Route::post('/userauth', [CheckListController::class, 'userAuth'])->name('check.userauth');
		Route::get('/logout', [CheckListController::class, 'checkLogout'])->name('check.logout');

		// 메인 
		Route::get('/main', [CheckListController::class, 'checkMainView'])->name('check.main');

		// 체크화면
		Route::get('/execution/{idx}', [CheckListController::class, 'checkExecuteForm'])->name('check.execute.form');
		Route::post('/executeSave', [CheckListController::class, 'checkExecuteSave'])->name('check.execute.save');

		// 체크결과
		Route::get('/result/{idx}', [CheckListController::class, 'checkResultView'])->name('check.result');
		
		// 수정 시 사본 생성 후 사본의 체크 수정 화면으로 이동
		Route::get('/executeCopy/{idx}', [CheckListController::class, 'checkExecuteCopy'])->name('check.execute.copy');
		Route::get('/moidfy/{idx}/{exec_idx?}', [CheckListController::class, 'checkModifyForm'])->name('check.modify');
		Route::post('/modifySave', [CheckListController::class, 'checkExecuteUpdate'])->name('check.modify.save');

		// 파일 다운로드
		Route::get('/download/{ori_name}/{path}', [CheckListController::class, 'downloadFile'])->where('path', '.*')->name('file.download');
		// 이미지 보기
		Route::get('/view/{path}', [CheckListController::class, 'viewFile'])->where('path', '.*')->name('file.view');
	});
});


/* 체크리스트 (시작) */
// 인증(로그인) 페이지 및 처리 (미들웨어 제외 영역)
Route::middleware('web')->group(function () {
	Route::prefix('/proposal')->group(function () {
		// 샘플
		Route::get('/sample', [ProposalController::class, 'sample'])->name('sample');
	});
});


/* 사용자페이지_한국어 (시작) */
Route::middleware('web')->group(function () {
	Route::prefix('ko')->name('ko.')->group(function () {
		Route::get('/', [UserController::class, 'index_ko']); // 메인화면
		
		// 파일 다운로드
		Route::get('/download/{ori_name}/{path}', [UserController::class, 'downloadFile'])->where('path', '.*')->name('file.download');
		// 이미지 보기
		Route::get('/view/{path}', [UserController::class, 'viewFile'])->where('path', '.*')->name('file.view');

		Route::post('/inquire', [UserController::class, 'setInquireRegist_ko']);

		Route::get('/policy', [UserController::class, 'policy_ko']);

		Route::get('/company', [UserController::class, 'company_about_ko']);
		Route::get('/company/about', [UserController::class, 'company_about_ko']);
		Route::get('/company/history', [UserController::class, 'company_history_ko']);
		Route::get('/company/patent', [UserController::class, 'company_patent_ko']);

		Route::get('/business/index', [UserController::class, 'business_index_ko']);
		Route::get('/business/solar', [UserController::class, 'business_solar_ko']);
		Route::get('/business/rps', [UserController::class, 'business_rps_ko']);
		Route::get('/business/lease', [UserController::class, 'business_lease_ko']);
		Route::get('/business/qna', [UserController::class, 'business_qna_ko']);

		Route::get('/smartfactory/automation', [UserController::class, 'smartfactory_automation_ko']);
		Route::get('/smartfactory/mes', [UserController::class, 'smartfactory_mes_ko']);
		Route::get('/smartfactory/fems', [UserController::class, 'smartfactory_fems_ko']);

		Route::get('/portfolio', [UserController::class, 'portfolio_ko']);
		Route::get('/portfolio/detail/{idx}', [UserController::class, 'portfolio_detail_ko']);

		Route::get('/news', [UserController::class, 'solarteq_news_ko']);
		Route::post('/news/detailForm/{idx}', [UserController::class, 'getNoticeDetailForm_ko']);

		Route::get('/culture', [UserController::class, 'solarteq_culture_ko']);
		Route::get('/download/ci', function () {
			if (!Storage::disk('public')->exists('ci/solarteq_ci.zip')) {
				abort(404, '파일이 존재하지 않습니다.');
			}
			
			// 파일 다운로드
			return Storage::disk('public')->download('ci/solarteq_ci.zip', 'solarteq_ci.zip');
		})->where('path', '.*') 
		->name('download.ci');

		Route::get('/gov/building', [UserController::class, 'gov_building_ko']);
		Route::get('/gov/finance', [UserController::class, 'gov_finance_ko']);
		Route::get('/gov/house', [UserController::class, 'gov_house_ko']);
		Route::get('/gov/carbon', [UserController::class, 'gov_carbon_ko']);

		Route::get('/report', [UserController::class, 'report']); // 리포트
		Route::post('/memo/store', [UserController::class, 'reportMemoStore']);
		Route::get('/memo/list/{name}', [UserController::class, 'reportMemoList']);
		Route::delete('/memo/delete/{idx}', [UserController::class, 'reportMemoDelete']);
		Route::put('/memo/update/{idx}', [UserController::class, 'reportMemoUpdate']);

		Route::get('/kma', [UserController::class, 'compareMonthly']); // 일사량 조회

		Route::fallback(function () {
			return redirect('/ko');
		});
	});
});

// 영어
Route::middleware('web')->group(function () {
	Route::prefix('en')->name('en.')->group(function () {
		Route::get('/', [UserController::class, 'index_en']); // 메인화면

		Route::post('/inquire', [UserController::class, 'setInquireRegist_en']);

		Route::get('/policy', [UserController::class, 'policy_en']);

		Route::get('/company', [UserController::class, 'company_about_en']);
		Route::get('/company/about', [UserController::class, 'company_about_en']);
		Route::get('/company/history', [UserController::class, 'company_history_en']);
		Route::get('/company/patent', [UserController::class, 'company_patent_en']);

		Route::get('/business/index', [UserController::class, 'business_index_en']);
		Route::get('/business/solar', [UserController::class, 'business_solar_en']);
		Route::get('/business/rps', [UserController::class, 'business_rps_en']);
		Route::get('/business/lease', [UserController::class, 'business_lease_en']);
		Route::get('/business/qna', [UserController::class, 'business_qna_en']);

		Route::get('/smartfactory/automation', [UserController::class, 'smartfactory_automation_en']);
		Route::get('/smartfactory/mes', [UserController::class, 'smartfactory_mes_en']);
		Route::get('/smartfactory/fems', [UserController::class, 'smartfactory_fems_en']);

		Route::get('/portfolio', [UserController::class, 'portfolio_en']);
		Route::get('/portfolio/detail/{idx}', [UserController::class, 'portfolio_detail_en']);

		Route::get('/news', [UserController::class, 'solarteq_news_en']);
		Route::post('/news/detailForm/{idx}', [UserController::class, 'getNoticeDetailForm_en']);

		Route::get('/culture', [UserController::class, 'solarteq_culture_en']);
		Route::get('/download/ci', function () {
			if (!Storage::disk('public')->exists('ci/solarteq_ci.zip')) {
				abort(404, 'The file does not exist.');
			}
			
			// 파일 다운로드
			return Storage::disk('public')->download('ci/solarteq_ci.zip', 'solarteq_ci.zip');
		})->where('path', '.*') 
		->name('download.ci');

		Route::get('/gov/building', function() { return redirect('/en'); });
		Route::get('/gov/finance', function() { return redirect('/en'); });
		Route::get('/gov/house', function() { return redirect('/en'); });
		Route::get('/gov/carbon', function() { return redirect('/en'); });		

		Route::fallback(function () {
			return redirect('/en');
		});
	});
});

// 베트남어
Route::middleware('web')->group(function () {
	Route::prefix('vn')->name('vn.')->group(function () {
		Route::get('/', [UserController::class, 'index_vn']); // 메인화면

		Route::post('/inquire', [UserController::class, 'setInquireRegist_vn']);

		Route::get('/policy', [UserController::class, 'policy_vn']);

		Route::get('/company', [UserController::class, 'company_about_vn']);
		Route::get('/company/about', [UserController::class, 'company_about_vn']);
		Route::get('/company/history', [UserController::class, 'company_history_vn']);
		Route::get('/company/patent', [UserController::class, 'company_patent_vn']);

		Route::get('/business/index', [UserController::class, 'business_index_vn']);
		Route::get('/business/solar', [UserController::class, 'business_solar_vn']);
		Route::get('/business/rps', [UserController::class, 'business_rps_vn']);
		Route::get('/business/lease', [UserController::class, 'business_lease_vn']);
		Route::get('/business/qna', [UserController::class, 'business_qna_vn']);

		Route::get('/smartfactory/automation', [UserController::class, 'smartfactory_automation_vn']);
		Route::get('/smartfactory/mes', [UserController::class, 'smartfactory_mes_vn']);
		Route::get('/smartfactory/fems', [UserController::class, 'smartfactory_fems_vn']);

		Route::get('/portfolio', [UserController::class, 'portfolio_vn']);
		Route::get('/portfolio/detail/{idx}', [UserController::class, 'portfolio_detail_vn']);

		Route::get('/news', [UserController::class, 'solarteq_news_vn']);
		Route::post('/news/detailForm/{idx}', [UserController::class, 'getNoticeDetailForm_vn']);

		Route::get('/culture', [UserController::class, 'solarteq_culture_vn']);
		Route::get('/download/ci', function () {
			if (!Storage::disk('public')->exists('ci/solarteq_ci.zip')) {
				abort(404, '文件不存在');
			}
			
			// 파일 다운로드
			return Storage::disk('public')->download('ci/solarteq_ci.zip', 'solarteq_ci.zip');
		})->where('path', '.*') 
		->name('download.ci');

		Route::get('/gov/building', function() { return redirect('/vn'); });
		Route::get('/gov/finance', function() { return redirect('/vn'); });
		Route::get('/gov/house', function() { return redirect('/vn'); });
		Route::get('/gov/carbon', function() { return redirect('/vn'); });

		Route::fallback(function () {
			return redirect('/vn');
		});
	});
});

/* 리포트 */
Route::middleware('web')->group(function () {
	Route::get('/spc/monthly', [UserController::class, 'getSpcMonthlyReport'])->name('spc.monthly');
	Route::post('/spc/monthly', [UserController::class, 'getSpcMonthlyReport'])->name('spc.monthly');

});

Route::fallback(function () {
			return redirect('/ko');
});
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	