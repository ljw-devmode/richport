<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Storage;
use App\Models\Inquire;

// 테스트 라우트
Route::get('/test/check-cookie', [TestController::class, 'checkCookie']);

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
			/* 대시보드 */
			// Route::get('/', [AdminController::class, 'getDashBoardList'])->name('dashboard');
			Route::get('/', [AdminController::class, 'getInquireList']);
			// Route::get('/dashboard', [AdminController::class, 'getDashBoardList'])->name('dashboard');
			Route::get('/dashboard', [AdminController::class, 'getInquireList'])->name('dashboard');
			
			Route::post('/editor-image/upload', [EditorController::class, 'upload'])->name('editor.image.upload');
			Route::get('/cities/{regions_idx}', [AdminController::class, 'getCities']);
			Route::get('/createId', [AdminController::class, 'setAccountRegist']);
			
			/* 회사소개 */
			Route::get('/history', [AdminController::class, 'getHistoryList'])->name('history');
			Route::get('/history/list', [AdminController::class, 'getHistoryList'])->name('history.list');
			Route::get('/history/export', [AdminController::class, 'getHistoryExport'])->name('history.export');
			Route::post('/history/regist', [AdminController::class, 'setHistoryRegist'])->name('history.regist');
			Route::post('/history/detailForm/{idx}', [AdminController::class, 'getHistoryDetailForm'])->name('history.detailForm');
			Route::post('/history/modify/{idx}', [AdminController::class, 'setHistoryModify'])->name('history.modify');
			Route::delete('/history/delete/{idx}', [AdminController::class, 'setHistoryDelete'])->name('history.delete');
			/* 사업소개 */
			Route::get('/portfolio', [AdminController::class, 'getPortfolioList'])->name('portfolio');
			Route::get('/portfolio/list', [AdminController::class, 'getPortfolioList'])->name('portfolio.list');
			Route::get('/portfolio/export', [AdminController::class, 'getPortfolioExport'])->name('portfolio.export');
			Route::post('/portfolio/regist', [AdminController::class, 'setPortfolioRegist'])->name('portfolio.regist');
			Route::post('/portfolio/detailForm/{idx}', [AdminController::class, 'getPortfolioDetailForm'])->name('portfolio.detailForm');
			Route::post('/portfolio/modify/{idx}', [AdminController::class, 'setPortfolioModify'])->name('portfolio.modify');
			Route::delete('/portfolio/delete/{idx}', [AdminController::class, 'setPortfolioDelete'])->name('portfolio.delete');
			/* 솔라테크소식 */
			Route::get('/notice', [AdminController::class, 'getNoticeList'])->name('notice');
			Route::get('/notice/list', [AdminController::class, 'getNoticeList'])->name('notice.list');
			Route::get('/notice/export', [AdminController::class, 'getNoticeExport'])->name('notice.export');
			Route::post('/notice/regist', [AdminController::class, 'setNoticeRegist'])->name('notice.regist');
			Route::post('/notice/detailForm/{idx}', [AdminController::class, 'getNoticeDetailForm'])->name('notice.detailForm');
			Route::post('/notice/modify/{idx}', [AdminController::class, 'setNoticeModify'])->name('notice.modify');
			Route::delete('/notice/delete/{idx}', [AdminController::class, 'setNoticeDelete'])->name('notice.delete');
			/* 문의관리 */
			Route::get('/inquire', [AdminController::class, 'getInquireList'])->name('inquire');
			Route::get('/inquire/list', [AdminController::class, 'getInquireList'])->name('inquire.list');
			Route::get('/inquire/export', [AdminController::class, 'getInquireExport'])->name('inquire.export');
			Route::post('/inquire/detailForm/{idx}', [AdminController::class, 'getInquireDetailForm'])->name('inquire.detailForm');
			Route::post('/inquire/modify/{idx}', [AdminController::class, 'setInquireModify'])->name('inquire.modify');
			Route::delete('/inquire/delete/{idx}', [AdminController::class, 'setInquireDelete'])->name('inquire.delete');
			
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

/* 사용자페이지_한국어 (시작) */

Route::middleware('web')->group(function () {
	Route::prefix('ko')->name('ko.')->group(function () {
		Route::get('/', [UserController::class, 'index_ko']); // 메인화면

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

		Route::fallback(function () {
			return redirect('/ko');
		});
	});
});


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


Route::middleware('web')->group(function () {
	Route::prefix('cn')->name('cn.')->group(function () {
		Route::get('/', [UserController::class, 'index_cn']); // 메인화면

		Route::post('/inquire', [UserController::class, 'setInquireRegist_cn']);

		Route::get('/policy', [UserController::class, 'policy_cn']);

		Route::get('/company', [UserController::class, 'company_about_cn']);
		Route::get('/company/about', [UserController::class, 'company_about_cn']);
		Route::get('/company/history', [UserController::class, 'company_history_cn']);
		Route::get('/company/patent', [UserController::class, 'company_patent_cn']);

		Route::get('/business/index', [UserController::class, 'business_index_cn']);
		Route::get('/business/solar', [UserController::class, 'business_solar_cn']);
		Route::get('/business/rps', [UserController::class, 'business_rps_cn']);
		Route::get('/business/lease', [UserController::class, 'business_lease_cn']);
		Route::get('/business/qna', [UserController::class, 'business_qna_cn']);

		Route::get('/smartfactory/automation', [UserController::class, 'smartfactory_automation_cn']);
		Route::get('/smartfactory/mes', [UserController::class, 'smartfactory_mes_cn']);
		Route::get('/smartfactory/fems', [UserController::class, 'smartfactory_fems_cn']);

		Route::get('/portfolio', [UserController::class, 'portfolio_cn']);
		Route::get('/portfolio/detail/{idx}', [UserController::class, 'portfolio_detail_cn']);

		Route::get('/news', [UserController::class, 'solarteq_news_cn']);
		Route::post('/news/detailForm/{idx}', [UserController::class, 'getNoticeDetailForm_cn']);

		Route::get('/culture', [UserController::class, 'solarteq_culture_cn']);
		Route::get('/download/ci', function () {
			if (!Storage::disk('public')->exists('ci/solarteq_ci.zip')) {
				abort(404, '文件不存在');
			}
			
			// 파일 다운로드
			return Storage::disk('public')->download('ci/solarteq_ci.zip', 'solarteq_ci.zip');
		})->where('path', '.*') 
		->name('download.ci');

		Route::get('/gov/building', function() { return redirect('/cn'); });
		Route::get('/gov/finance', function() { return redirect('/cn'); });
		Route::get('/gov/house', function() { return redirect('/cn'); });
		Route::get('/gov/carbon', function() { return redirect('/cn'); });		

		Route::fallback(function () {
			return redirect('/cn');
		});
	});
});

Route::fallback(function () {
			return redirect('/ko');
});
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	