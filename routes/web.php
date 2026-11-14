<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AiApiController;
use App\Http\Controllers\CheckListController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\RipsController;
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
		
		// admin.log : 등록 / 수정 / 삭제 요청을 관리자 로그로 기록
		Route::middleware(['web','auth:admin','admin.log'])->group(function () {
			/* 공통 */
			Route::post('/editor-image/upload', [EditorController::class, 'upload'])->name('editor.image.upload');
			Route::get('/districts/{cities_idx}', [AdminController::class, 'getDistricts']);

			// 파일 다운로드
			Route::get('/download/{ori_name}/{path}', [AdminController::class, 'downloadFile'])->where('path', '.*')->name('file.download');
			// 이미지 보기
			Route::get('/view/{path}', [AdminController::class, 'viewFile'])->where('path', '.*')->name('file.view');

			/* 대시보드 */
			Route::get('/', [AdminController::class, 'getDashboard'])->name('dashboard');
			Route::get('/dashboard', [AdminController::class, 'getDashboard'])->name('dashboard');

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

			/* RIPS > 도입사례 */
			Route::middleware(['can.admin:portfolio.view'])->group(function () {
				Route::get('/portfolio', [AdminController::class, 'getPortfolioList'])->name('portfolio');
				Route::get('/portfolio/list', [AdminController::class, 'getPortfolioList'])->name('portfolio.list');
				Route::get('/portfolio/export', [AdminController::class, 'getPortfolioExport'])->name('portfolio.export');
				Route::post('/portfolio/regist', [AdminController::class, 'setPortfolioRegist'])->name('portfolio.regist');
				Route::post('/portfolio/detailForm/{idx}', [AdminController::class, 'getPortfolioDetailForm'])->name('portfolio.detailForm');
				Route::post('/portfolio/modify/{idx}', [AdminController::class, 'setPortfolioModify'])->name('portfolio.modify');
				Route::delete('/portfolio/delete/{idx}', [AdminController::class, 'setPortfolioDelete'])->name('portfolio.delete');
			});

			/* 파트너 */
			Route::middleware(['can.admin:partner.view'])->group(function () {
				Route::get('/partner', [AdminController::class, 'getPartnerList'])->name('partner');
				Route::get('/partner/list', [AdminController::class, 'getPartnerList'])->name('partner.list');
				Route::get('/partner/export', [AdminController::class, 'getPartnerExport'])->name('partner.export');
				Route::post('/partner/regist', [AdminController::class, 'setPartnerRegist'])->name('partner.regist');
				Route::post('/partner/detailForm/{idx}', [AdminController::class, 'getPartnerDetailForm'])->name('partner.detailForm');
				Route::post('/partner/modify/{idx}', [AdminController::class, 'setPartnerModify'])->name('partner.modify');
				Route::delete('/partner/delete/{idx}', [AdminController::class, 'setPartnerDelete'])->name('partner.delete');
			});

			/* RIPS 관리 > RIPS 업체 (업체별 카테고리/상품 매핑, 발주금액) */
			Route::middleware(['can.admin:rips.view'])->group(function () {
				Route::get('/ripsPartner', [AdminController::class, 'getRipsPartnerList'])->name('ripsPartner');
				// RIPS 업체 등록 / 업체 정보 (RIPS > 설정 > 내 정보 수정 과 같은 항목)
				Route::get('/ripsPartner/create', [AdminController::class, 'getRipsPartnerCreate'])->name('ripsPartner.create');
				Route::post('/ripsPartner/regist', [AdminController::class, 'setRipsPartnerRegist'])->name('ripsPartner.regist');
				Route::get('/ripsPartner/{idx}/info', [AdminController::class, 'getPartnerRips'])->whereNumber('idx')->defaults('tab', 'info')->name('ripsPartner.info');
				Route::post('/ripsPartner/{idx}/info', [AdminController::class, 'setRipsPartnerModify'])->whereNumber('idx')->name('ripsPartner.info.save');
				Route::get('/ripsPartner/{idx}', [AdminController::class, 'getPartnerRips'])->whereNumber('idx')->name('ripsPartner.category');
				Route::get('/ripsPartner/{idx}/products', [AdminController::class, 'getPartnerRips'])->whereNumber('idx')->defaults('tab', 'product')->name('ripsPartner.products');
				Route::get('/ripsPartner/{idx}/stock', [AdminController::class, 'getPartnerRips'])->whereNumber('idx')->defaults('tab', 'stock')->name('ripsPartner.stock');
				Route::get('/ripsPartner/{idx}/sales', [AdminController::class, 'getPartnerRips'])->whereNumber('idx')->defaults('tab', 'sales')->name('ripsPartner.sales');
				Route::get('/ripsPartner/{idx}/orders', [AdminController::class, 'getPartnerRips'])->whereNumber('idx')->defaults('tab', 'orders')->name('ripsPartner.orders');
				Route::get('/ripsPartner/{idx}/data', [AdminController::class, 'getPartnerRipsData'])->whereNumber('idx')->name('ripsPartner.data');
				Route::post('/ripsPartner/{idx}/category/{cidx}', [AdminController::class, 'setPartnerRipsCategory'])->whereNumber(['idx', 'cidx'])->name('ripsPartner.category.save');
				Route::post('/ripsPartner/{idx}/product/{pidx}', [AdminController::class, 'setPartnerRipsProduct'])->whereNumber(['idx', 'pidx'])->name('ripsPartner.product.save');
			});

			/* RIPS 관리 > 발주 관리 (진행상태 / 물류·통관·입고 일정 / 리치포트 발주 정보) */
			Route::middleware(['can.admin:ripsOrder.view'])->group(function () {
				Route::get('/ripsOrder', [AdminController::class, 'getRipsOrderList'])->name('ripsOrder');
				Route::get('/ripsOrder/{idx}', [AdminController::class, 'getRipsOrderDetail'])->whereNumber('idx')->name('ripsOrder.detail');
				Route::post('/ripsOrder/{idx}/status', [AdminController::class, 'setRipsOrderStatus'])->whereNumber('idx')->name('ripsOrder.status');
				Route::post('/ripsOrder/{idx}/save', [AdminController::class, 'setRipsOrderSave'])->whereNumber('idx')->name('ripsOrder.save');
			});

			/* RIPS 관리 > 판매 관리 (월별 : 리치포트 상품 기준 / 일별 : 업체 상품 기준) */
			Route::middleware(['can.admin:ripsSales.view'])->group(function () {
				Route::get('/ripsSales', [AdminController::class, 'getRipsSalesMonthly'])->name('ripsSales');
				Route::get('/ripsSales/monthly/{idx}', [AdminController::class, 'getRipsSalesMonthlyDetail'])->whereNumber('idx')->name('ripsSales.monthly.detail');
				Route::get('/ripsSales/daily', [AdminController::class, 'getRipsSalesDaily'])->name('ripsSales.daily');
			});

			/* RIPS 관리 > AI 연동 (인증키, 예측값 사용 방식, 수신 현황) */
			Route::middleware(['can.admin:aiApi.view'])->group(function () {
				Route::get('/aiApi', [AdminController::class, 'getAiApi'])->name('aiApi');
				Route::post('/aiApi/save', [AdminController::class, 'setAiApiSave'])->name('aiApi.save');
				Route::post('/aiApi/regenerate', [AdminController::class, 'setAiApiRegenerate'])->name('aiApi.regenerate');
			});

			/* RIPS 관리 > 통관 이슈 */
			Route::middleware(['can.admin:customsIssue.view'])->group(function () {
				Route::get('/customsIssue', [AdminController::class, 'getCustomsIssueList'])->name('customsIssue');
				Route::post('/customsIssue/regist', [AdminController::class, 'setCustomsIssueRegist'])->name('customsIssue.regist');
				Route::post('/customsIssue/detailForm/{idx}', [AdminController::class, 'getCustomsIssueDetailForm'])->whereNumber('idx')->name('customsIssue.detailForm');
				Route::post('/customsIssue/modify/{idx}', [AdminController::class, 'setCustomsIssueModify'])->whereNumber('idx')->name('customsIssue.modify');
				Route::delete('/customsIssue/delete/{idx}', [AdminController::class, 'setCustomsIssueDelete'])->whereNumber('idx')->name('customsIssue.delete');
				Route::get('/customsIssue/download/{idx}', [AdminController::class, 'getCustomsIssueDownload'])->whereNumber('idx')->name('customsIssue.download');
			});

			/* 상품 */
			Route::middleware(['can.admin:product.view'])->group(function () {
				Route::get('/product', [AdminController::class, 'getProductList'])->name('product');
				Route::get('/product/list', [AdminController::class, 'getProductList'])->name('product.list');
				Route::get('/product/export', [AdminController::class, 'getProductExport'])->name('product.export');
				Route::post('/product/regist', [AdminController::class, 'setProductRegist'])->name('product.regist');
				Route::post('/product/detailForm/{idx}', [AdminController::class, 'getProductDetailForm'])->name('product.detailForm');
				Route::post('/product/modify/{idx}', [AdminController::class, 'setProductModify'])->name('product.modify');
				Route::delete('/product/delete/{idx}', [AdminController::class, 'setProductDelete'])->name('product.delete');
			});

			/* 사업관리 > 카테고리 관리 (form_types 1~4단계) */
			Route::middleware(['can.admin:formCategory.view'])->group(function () {
				Route::get('/formCategory', [AdminController::class, 'getFormCategory'])->name('formCategory');
				Route::get('/formCategory/list', [AdminController::class, 'getFormCategoryList'])->name('formCategory.list');
				Route::post('/formCategory/regist', [AdminController::class, 'setFormCategoryRegist'])->name('formCategory.regist');
				Route::post('/formCategory/modify/{idx}', [AdminController::class, 'setFormCategoryModify'])->whereNumber('idx')->name('formCategory.modify');
				Route::post('/formCategory/sort/{idx}', [AdminController::class, 'setFormCategorySort'])->whereNumber('idx')->name('formCategory.sort');
				Route::delete('/formCategory/delete/{idx}', [AdminController::class, 'setFormCategoryDelete'])->whereNumber('idx')->name('formCategory.delete');
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
			});

			/* 설문조사 > 설문조사 결과 관리 (테스트베드 상품별 목록 > 요약 > 개별 설문) */
			Route::middleware(['can.admin:checkresult.view'])->group(function () {
				Route::get('/checkResultList', [AdminController::class, 'checkResultList'])->name('check.result');
				Route::get('/checkResult/{idx}', [AdminController::class, 'checkResultDetail'])->whereNumber('idx')->name('check.result.detail');
				Route::get('/checkResultResponse/{idx}', [AdminController::class, 'checkResultResponse'])->whereNumber('idx')->name('check.result.response');
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

			/* 고객문의 > RIPS문의관리 */
			Route::middleware(['can.admin:inquireRips.view'])->group(function () {
				Route::get('/inquireRips', [AdminController::class, 'getInquireRipsList'])->name('inquireRips');
				Route::get('/inquireRips/list', [AdminController::class, 'getInquireRipsList'])->name('inquireRips.list');
				Route::get('/inquireRips/export', [AdminController::class, 'getInquireRipsExport'])->name('inquireRips.export');
				Route::post('/inquireRips/detailForm/{idx}', [AdminController::class, 'getInquireRipsDetailForm'])->name('inquireRips.detailForm');
				Route::post('/inquireRips/modify/{idx}', [AdminController::class, 'setInquireRipsModify'])->name('inquireRips.modify');
				Route::delete('/inquireRips/delete/{idx}', [AdminController::class, 'setInquireRipsDelete'])->name('inquireRips.delete');
			});

			/* 고객문의 > TESTBED문의관리 */
			Route::middleware(['can.admin:inquireTestbed.view'])->group(function () {
				Route::get('/inquireTestbed', [AdminController::class, 'getInquireTestbedList'])->name('inquireTestbed');
				Route::get('/inquireTestbed/list', [AdminController::class, 'getInquireTestbedList'])->name('inquireTestbed.list');
				Route::get('/inquireTestbed/export', [AdminController::class, 'getInquireTestbedExport'])->name('inquireTestbed.export');
				Route::post('/inquireTestbed/detailForm/{idx}', [AdminController::class, 'getInquireTestbedDetailForm'])->name('inquireTestbed.detailForm');
				Route::post('/inquireTestbed/modify/{idx}', [AdminController::class, 'setInquireTestbedModify'])->name('inquireTestbed.modify');
				Route::delete('/inquireTestbed/delete/{idx}', [AdminController::class, 'setInquireTestbedDelete'])->name('inquireTestbed.delete');
			});

			/* 고객문의 > FAQ (사용자 RIPS > FAQ) */
			Route::middleware(['can.admin:faq.view'])->group(function () {
				Route::get('/faq', [AdminController::class, 'getFaqList'])->name('faq');
				Route::post('/faq/regist', [AdminController::class, 'setFaqRegist'])->name('faq.regist');
				Route::post('/faq/detailForm/{idx}', [AdminController::class, 'getFaqDetailForm'])->name('faq.detailForm');
				Route::post('/faq/modify/{idx}', [AdminController::class, 'setFaqModify'])->name('faq.modify');
				Route::delete('/faq/delete/{idx}', [AdminController::class, 'setFaqDelete'])->name('faq.delete');
			});

			/* 서비스 관리 > 관리자 로그 (등록 / 수정 / 삭제 기록 조회) */
			Route::middleware(['can.admin:adminLog.view'])->group(function () {
				Route::get('/adminLog', [AdminController::class, 'getAdminLogList'])->name('adminLog');
			});

			/* 서비스 관리 > 리치포트 직원 관리 */
			Route::middleware(['can.admin:user.view'])->group(function () {
				Route::get('/user', [AdminController::class, 'getUserList'])->name('user');
				Route::post('/user/regist', [AdminController::class, 'setUserRegist'])->name('user.regist');
				Route::get('/user/list', [AdminController::class, 'getUserList'])->name('user.list');
				Route::post('/user/detailForm/{idx}', [AdminController::class, 'getUserDetailForm'])->name('user.detailForm');
				Route::post('/user/modify/{idx}', [AdminController::class, 'setUserModify'])->name('user.modify');
				Route::delete('/user/delete/{idx}', [AdminController::class, 'setUserDelete'])->name('user.delete');
				Route::get('/createId', [AdminController::class, 'setAccountRegist']);			
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


/* RIPS 서비스 (시작) */
Route::middleware('web')->group(function () {
	Route::prefix('rips')->name('rips.')->group(function () {
		Route::get('/', [RipsController::class, 'index'])->name('index');
		Route::get('login', [RipsController::class, 'loginGet'])->name('login');
		Route::post('login', [RipsController::class, 'loginPost']);
		Route::post('logout', [RipsController::class, 'logout'])->name('logout');

		// 업체별 화면 : /rips/{업체ID}
		Route::prefix('{rid}')->where(['rid' => '[A-Za-z0-9_\-]+'])->middleware('rips.partner')->group(function () {
			/* 대시보드 */
			Route::get('/', [RipsController::class, 'getDashboard'])->name('dashboard');

			/* 카테고리 */
			Route::get('/category', [RipsController::class, 'getCategory'])->name('category');
			Route::get('/category/list', [RipsController::class, 'getCategoryList'])->name('category.list');
			Route::post('/category/regist', [RipsController::class, 'setCategoryRegist'])->name('category.regist');
			Route::post('/category/modify/{idx}', [RipsController::class, 'setCategoryModify'])->name('category.modify');
			Route::post('/category/sort', [RipsController::class, 'setCategorySort'])->name('category.sort');
			Route::delete('/category/delete/{idx}', [RipsController::class, 'setCategoryDelete'])->name('category.delete');

			/* 상품 */
			Route::get('/product', [RipsController::class, 'getProductList'])->name('product');
			Route::post('/product/regist', [RipsController::class, 'setProductRegist'])->name('product.regist');
			Route::post('/product/detailForm/{idx}', [RipsController::class, 'getProductDetailForm'])->name('product.detailForm');
			Route::post('/product/modify/{idx}', [RipsController::class, 'setProductModify'])->name('product.modify');
			Route::delete('/product/delete/{idx}', [RipsController::class, 'setProductDelete'])->name('product.delete');

			/* 판매 */
			Route::get('/sales', [RipsController::class, 'getSalesList'])->name('sales');
			Route::post('/sales/regist', [RipsController::class, 'setSalesRegist'])->name('sales.regist');
			Route::post('/sales/detailForm/{idx}', [RipsController::class, 'getSalesDetailForm'])->name('sales.detailForm');
			Route::post('/sales/modify/{idx}', [RipsController::class, 'setSalesModify'])->name('sales.modify');
			Route::delete('/sales/delete/{idx}', [RipsController::class, 'setSalesDelete'])->name('sales.delete');
			Route::get('/sales/template', [RipsController::class, 'getSalesTemplate'])->name('sales.template');
			Route::post('/sales/upload', [RipsController::class, 'setSalesUpload'])->name('sales.upload');
			Route::post('/sales/setting', [RipsController::class, 'setSalesSetting'])->name('sales.setting');

			/* 발주 */
			Route::get('/order', [RipsController::class, 'getOrderList'])->name('order');
			Route::get('/order/create', [RipsController::class, 'getOrderForm'])->name('order.create');
			Route::post('/order/regist', [RipsController::class, 'setOrderRegist'])->name('order.regist');
			Route::get('/order/{idx}', [RipsController::class, 'getOrderDetail'])->whereNumber('idx')->name('order.detail');
			Route::get('/order/{idx}/edit', [RipsController::class, 'getOrderForm'])->whereNumber('idx')->name('order.edit');
			Route::post('/order/{idx}/modify', [RipsController::class, 'setOrderModify'])->whereNumber('idx')->name('order.modify');
			Route::post('/order/{idx}/cancel', [RipsController::class, 'setOrderCancel'])->whereNumber('idx')->name('order.cancel');

			/* 리치포트 상품 > 리치포트 상품 (목록/상세, 신상품 테스트베드 신청) */
			Route::get('/richport', [RipsController::class, 'getRichportProductList'])->name('richport');
			Route::post('/richport/{idx}', [RipsController::class, 'getRichportProductDetail'])->whereNumber('idx')->name('richport.detail');
			Route::post('/richport/testbed', [RipsController::class, 'setRichportTestbedRegist'])->name('richport.testbed');

			/* 리치포트 상품 > 테스트베드 신청 (신청 내역/상태) */
			Route::get('/testbed', [RipsController::class, 'getTestbedList'])->name('testbed');
			Route::post('/testbed/{idx}', [RipsController::class, 'getTestbedDetail'])->whereNumber('idx')->name('testbed.detail');

			/* 리치포트 상품 > 설문조사 결과 (테스트베드 진행완료 상품) */
			Route::get('/survey', [RipsController::class, 'getSurveyList'])->name('survey');
			Route::get('/survey/{idx}', [RipsController::class, 'getSurveyDetail'])->whereNumber('idx')->name('survey.detail');
			Route::get('/survey/{idx}/response/{listIdx}', [RipsController::class, 'getSurveyResponse'])->whereNumber(['idx', 'listIdx'])->name('survey.response');

			/* 설정 > 내 정보 수정 */
			Route::get('/profile', [RipsController::class, 'getProfile'])->name('profile');
			Route::post('/profile', [RipsController::class, 'setProfile'])->name('profile.save');
			Route::post('/profile/password', [RipsController::class, 'setProfilePassword'])->name('profile.password');

			/* 통관 이슈 (대시보드에서 조회) */
			Route::post('/customs/{idx}', [RipsController::class, 'getCustomsIssueDetail'])->whereNumber('idx')->name('customs.detail');
			Route::get('/customs/{idx}/download', [RipsController::class, 'getCustomsIssueDownload'])->whereNumber('idx')->name('customs.download');
		});
	});

	// 판매 데이터 API 수신 (헤더 X-RIPS-KEY 로 인증)
	Route::post('/rips-api/sales', [RipsController::class, 'setSalesApi'])->name('rips.api.sales');

	// AI 재고 예측 연동 API (Authorization: Bearer {인증키}, 관리자 > RIPS 관리 > AI 연동) - 문서 : API 연동가이드
	Route::prefix('ai-api/v1')->name('ai.api.')->middleware(['ai.api', 'throttle:120,1'])->group(function () {
		Route::get('/ping', [AiApiController::class, 'ping'])->name('ping');
		Route::get('/partners', [AiApiController::class, 'partners'])->name('partners');
		Route::get('/products', [AiApiController::class, 'products'])->name('products');
		Route::get('/sales', [AiApiController::class, 'sales'])->name('sales');
		Route::get('/pending-orders', [AiApiController::class, 'pendingOrders'])->name('pendingOrders');
		Route::post('/forecasts', [AiApiController::class, 'storeForecasts'])->name('forecasts.store');
		Route::post('/forecasts/query', [AiApiController::class, 'queryForecasts'])->name('forecasts.query');
	});
});
/* RIPS 서비스 (끝) */


/* 테스트베드 설문조사 (로그인 없이 설문 URL 로 답변) */
Route::middleware('web')->group(function () {
	Route::get('/survey/{token}', [CheckListController::class, 'getSurvey'])->where('token', '[A-Za-z0-9]+')->name('survey.show');
	Route::post('/survey/{token}', [CheckListController::class, 'setSurveyAnswer'])->where('token', '[A-Za-z0-9]+')->middleware('throttle:30,1')->name('survey.answer');
});


/* 체크리스트 (시작) */
// 인증(로그인) 페이지 및 처리 (미들웨어 제외 영역)
Route::middleware('web')->group(function () {
	Route::prefix('/proposal')->group(function () {
		// 샘플
		Route::get('/sample', [ProposalController::class, 'sample'])->name('sample');
	});
});


/* 사용자페이지 (시작) : /ko, /en, /vn 동일 구성, 뷰는 resources/views/user/{언어} */
foreach (['ko', 'en', 'vn'] as $lang) {
	Route::middleware(['web', 'user.locale:'.$lang])->prefix($lang)->name($lang.'.')->group(function () use ($lang) {
		// 홈
		Route::get('/', [UserController::class, 'index']); // 메인화면

		// 회사소개 > 회사소개
		Route::get('/company', [UserController::class, 'company_about']);
		Route::get('/company/about', [UserController::class, 'company_about']);

		// 회사소개 > 오시는길
		Route::get('/company/contact', [UserController::class, 'company_contact']);

		// 회사소개 > 크루소개
		Route::get('/company/crew', [UserController::class, 'company_crew']);

		// 회사소개 > 연혁
		Route::get('/company/history', [UserController::class, 'company_history']);


		// RIPS > 주요특징
		Route::get('/rips', [UserController::class, 'rips_about']);
		Route::get('/rips/about', [UserController::class, 'rips_about']);

		// RIPS > 안전재고예측
		Route::get('/rips/inventory', [UserController::class, 'rips_inventory']);

		// RIPS > 발주예측
		Route::get('/rips/order', [UserController::class, 'rips_order']);

		// RIPS > 왜 필요한가?
		Route::get('/rips/why', [UserController::class, 'rips_why']);

		// RIPS > 도입사례
		Route::get('/rips/portpolio', [UserController::class, 'rips_portpolio']);

		// RIPS > 가격정책
		Route::get('/rips/price', [UserController::class, 'rips_price']);

		// RIPS > FAQ
		Route::get('/rips/check', [UserController::class, 'rips_check']);

		// 문의하기
		Route::get('/rips/inquire', [UserController::class, 'rips_inquire']);


		// Partner > 베트남
		Route::get('/partner/vietnam', [UserController::class, 'partner_vietnam']);

		// Partner > 한국
		Route::get('/partner/korea', [UserController::class, 'partner_korea']);


		// 상품소개 > 상품등록
		Route::get('/product/congbo', [UserController::class, 'product_congbo']);

		// 상품소개 > 신상품
		Route::get('/product/new', [UserController::class, 'product_new']);

		// 상품소개 > 신상품 상세
		Route::post('/product/detail/{idx}', [UserController::class, 'product_detail']);


		// 테스트베드 > 소개
		Route::get('/testbed/about', [UserController::class, 'testbed_about']);


		// 문의하기
		Route::post('/setInquireRegist', [UserController::class, 'setInquireRegist']);

		// rips 문의하기
		Route::post('/setRipsInquireRegist', [UserController::class, 'setRipsInquireRegist']);

		// testbed 문의하기
		Route::post('/setTestbedInquireRegist', [UserController::class, 'setTestbedInquireRegist']);


		// 약관보기
		Route::get('/policy', [UserController::class, 'policy']);

		// 파일 다운로드
		Route::get('/download/{ori_name}/{path}', [UserController::class, 'downloadFile'])->where('path', '.*')->name('file.download');
		// 이미지 보기
		Route::get('/view/{path}', [UserController::class, 'viewFile'])->where('path', '.*')->name('file.view');


		Route::fallback(function () use ($lang) {
			return redirect('/'.$lang);
		});
	});
}
/* 사용자페이지 (끝) */

/* 리포트 */
Route::middleware('web')->group(function () {
	Route::get('/spc/monthly', [UserController::class, 'getSpcMonthlyReport'])->name('spc.monthly');
	Route::post('/spc/monthly', [UserController::class, 'getSpcMonthlyReport'])->name('spc.monthly');

});

Route::fallback(function () {
			return redirect('/ko');
});
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	