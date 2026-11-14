<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLanguageCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 쿠키에서 선택된 언어 확인
        $selectedLang = $request->cookie('selectedLang');
        
        // 유효한 언어 코드 (저장값 : 사용자 화면 언어 선택에서 저장하는 쿠키 값)
        $validStorageLangs = ['ko', 'en', 'vn'];
        $validUrlLangs = ['ko', 'en', 'vn'];

        // 저장값 → URL 코드 매핑
        $langMap = [
            'ko' => 'ko',
            'en' => 'en',
            'vn' => 'vn'
        ];
        
        // 쿠키에 유효한 언어가 저장되어 있으면
        if ($selectedLang && in_array($selectedLang, $validStorageLangs)) {
            // 저장값을 URL 코드로 변환
            $selectedUrlLang = $langMap[$selectedLang];
            
            // 전체 경로 가져오기
            $fullPath = $request->getPathInfo(); // /ko/company/history 형식
            $pathSegments = array_filter(explode('/', $fullPath));
            $currentUrlLang = reset($pathSegments) ?? null; // 첫 번째 세그먼트
            
            // 현재 URL의 첫 번째 세그먼트가 유효한 언어 코드인지 확인
            if (in_array($currentUrlLang, $validUrlLangs) && $currentUrlLang !== $selectedUrlLang) {
                // 경로를 새 언어로 변경 (첫 번째 세그먼트만 교체)
                $pathSegments = explode('/', trim($fullPath, '/'));
                if (count($pathSegments) > 0) {
                    $pathSegments[0] = $selectedUrlLang;
                }
                $newPath = '/' . implode('/', $pathSegments);
                return redirect($newPath);
            }
        }
        
        return $next($request);
    }
}
