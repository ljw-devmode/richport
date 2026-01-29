<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $path = request()->path();
            $meta = match (true) {
                str_starts_with($path, 'business/index') => [
                    'title' => '솔라테크 SOLARTEQ',
                    'keywords' => '태양광,재생에너지,태양광설치,건물지원,주택지원,금융지원,탄소중립,지붕임대,RPS,태양광발전,RE100,ESG',
                    'description' => '업계최고혜택,안심방수,건축구조기술사 직접설계,구조물 자체생산,안전관리,전문O&M',
                    'og:title' => '솔라테크 SOLARTEQ',
                    'og:description' => '업계최고혜택,안심방수,건축구조기술사 직접설계,구조물 자체생산,안전관리,전문O&M',
                    'twitter:title' => '솔라테크 SOLARTEQ',
                    'twitter:description' => '업계최고혜택,안심방수,건축구조기술사 직접설계,구조물 자체생산,안전관리,전문O&M',
                    'twitter:card' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',
                ],                  
                str_starts_with($path, 'business/solar') => [
                    'title' => '솔라테크 지붕임대태양광',
                    'keywords' => '태양광,재생에너지,지붕임대,임대사업,RE100,ESG',
                    'description' => '유휴 지붕·옥상으로 20년간 수익보장, 지붕임대 태양광, 업계 최고 혜택, 투자비용 0원, 지붕·옥상 무상관리',
                    'og:title' => '솔라테크 지붕임대태양광',
                    'og:description' => '유휴 지붕으로 20년간 수익보장, 지붕임대 태양광, 업계 최고 혜택, 투자비용 0원',
                    'twitter:title' => '솔라테크 지붕임대태양광',
                    'twitter:description' => '유휴 지붕으로 20년간 수익보장, 지붕임대 태양광, 업계 최고 혜택, 투자비용 0원',
                    'twitter:card' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',
                ],   
                str_starts_with($path, 'business/rps') => [
                    'title' => '솔라테크 RPS 태양광발전',
                    'keywords' => '태양광,재생에너지,지붕임대,RE100,ESG',
                    'description' => 'RPS 제도기반 20년간 수익보장, 전력·공급인증서 판매, ESG달성',
                    'og:title' => '솔라테크 RPS 태양광발전',
                    'og:description' => 'RPS 제도기반 20년간 수익보장, 전력·공급인증서 판매, ESG달성',
                    'twitter:title' => '솔라테크 RPS 태양광발전',
                    'twitter:description' => 'RPS 제도기반 20년간 수익보장, 전력·공급인증서 판매, ESG달성',
                    'twitter:card' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',
                ],      
                str_starts_with($path, 'business/lease') => [
                    'title' => '솔라테크 RE100 리스',
                    'keywords' => '태양광,재생에너지,지붕임대,RE100,ESG',
                    'description' => '20년간 고정단가로 전기 구독, 전기료 절감, 탄소감축, RE100 달성, ESG 경영',
                    'og:title' => '솔라테크 RE100 리스',
                    'og:description' => 'RPS 제도기반 20년간 수익보장, 전력·공급인증서 판매, ESG달성',
                    'twitter:title' => '솔라테크 RE100 리스',
                    'twitter:description' => 'RPS 제도기반 20년간 수익보장, 전력·공급인증서 판매, ESG달성',
                    'twitter:card' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',
                ],                                         
                str_starts_with($path, 'gov/building') => [
                    'title' => '솔라테크 건물지원사업',
                    'keywords' => '태양광,건물지원,정부지원사업,태양광지원,RE100,ESG,재생에너지',
                    'description' => '건물지원사업은 솔라테크, 설치비 50% 보조금, 200kW까지, RE100·ESG 달성',
                    'og:title' => '솔라테크 건물지원사업',
                    'og:description' => '건물지원사업은 솔라테크, 설치비 50% 보조금, 200kW까지, RE100·ESG 달성',
                    'twitter:title' => '솔라테크 건물지원사업',
                    'twitter:description' => '건물지원사업은 솔라테크, 설치비 50% 보조금, 200kW까지, RE100·ESG 달성',
                    'twitter:card' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',
                ],
                str_starts_with($path, 'gov/finance') => [
                    'title' => '솔라테크 금융지원사업',
                    'keywords' => '태양광,금융지원,정부지원사업,저금리융자,태양광지원,RE100,ESG,재생에너지',
                    'description' => '금융지원사업은 솔라테크, 태양광 설비비 80% 저금리 융자, 최대 300억원, RE100·ESG 달성',
                    'og:title' => '솔라테크 금융지원사업',
                    'og:description' => '금융지원사업은 솔라테크, 태양광 설비비 80% 저금리 융자, 최대 300억원, RE100·ESG 달성',
                    'twitter:title' => '솔라테크 금융지원사업',
                    'twitter:description' => '금융지원사업은 솔라테크, 태양광 설비비 80% 저금리 융자, 최대 300억원, RE100·ESG 달성',
                    'twitter:card' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',                    
                ],
                str_starts_with($path, 'gov/house') => [
                    'title' => '솔라테크 주택지원사업',
                    'keywords' => '태양광,건물지원,정부지원사업,태양광보조금,태양광지원,RE100,ESG,재생에너지',
                    'description' => '주택지원사업은 솔라테크, 태양광 설치비 50% 지원, 전기료절감',
                    'og:title' => '솔라테크 주택지원사업',
                    'og:description' => '주택지원사업은 솔라테크, 태양광 설치비 50% 지원, 전기료절감',
                    'twitter:title' => '솔라테크 주택지원사업',
                    'twitter:description' => '주택지원사업은 솔라테크, 태양광 설치비 50% 지원, 전기료절감',
                    'twitter:card' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',                     
                ],
                str_starts_with($path, 'gov/carbon') => [
                    'title' => '솔라테크 탄소중립설비 지원사업',
                    'keywords' => '태양광,탄소중립,정부지원사업,태양광보조금,태양광지원,RE100,ESG,재생에너지',
                    'description' => '탄소중립설비 지원사업은 솔라테크, 태양광 설치비 70% 지원, 최대 100억원, RE100·ESG 달성',
                    'og:title' => '솔라테크 탄소중립설비 지원사업',
                    'og:description' => '탄소중립설비 지원사업은 솔라테크, 태양광 설치비 70% 지원, 최대 100억원, RE100·ESG 달성',
                    'twitter:title' => '솔라테크 탄소중립설비 지원사업',
                    'twitter:description' => '탄소중립설비 지원사업은 솔라테크, 태양광 설치비 70% 지원, 최대 100억원, RE100·ESG 달성',
                    'twitter:card' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',                         
                ],
                str_starts_with($path, 'portfolio') => [
                    'title' => '솔라테크 포트폴리오',
                    'keywords' => '태양광,탄소중립,정부지원사업,태양광보조금,태양광지원,RE100,ESG,재생에너지',
                    'description' => '솔라테크의 태양광 시공 사례, 지붕형 태양광, 주차장형 태양광, 대지형 태양광',
                    'og:title' => '솔라테크 탄소중립지원사업',
                    'og:description' => '솔라테크의 태양광 시공 사례, 지붕형 태양광, 주차장형 태양광, 대지형 태양광',
                    'twitter:title' => '솔라테크 탄소중립지원사업',
                    'twitter:description' => '솔라테크의 태양광 시공 사례, 지붕형 태양광, 주차장형 태양광, 대지형 태양광',
                    'twitter:card' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',                         
                ],
                str_starts_with($path, 'company/about') => [
                    'title' => '솔라테크 회사소개',
                    'keywords' => '태양광,재생에너지,지붕임대,RPS,자가소비,RE100,ESG,건물지원,주택지원,금융지원,탄소중립',
                    'description' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',
                    'og:title' => '솔라테크 회사소개',
                    'og:description' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',
                    'twitter:title' => '솔라테크 회사소개',
                    'twitter:description' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',
                    'twitter:card' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',                         
                ], 
                str_starts_with($path, 'company/history') => [
                    'title' => '솔라테크 연혁',
                    'keywords' => '태양광,재생에너지,지붕임대,RPS,자가소비,RE100,ESG,건물지원,주택지원,금융지원,탄소중립',
                    'description' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',
                    'og:title' => '솔라테크 연혁',
                    'og:description' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',
                    'twitter:title' =>'솔라테크 연혁',
                    'twitter:description' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',
                    'twitter:card' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',                         
                ],                                                
                str_starts_with($path, 'company/patent') => [
                    'title' => '솔라테크 특허·인증',
                    'keywords' => '태양광,재생에너지,지붕임대,RPS,자가소비,RE100,ESG,건물지원,주택지원,금융지원,탄소중립',
                    'description' => '솔라테크의 기술력, ISO 9001·14001·45001, 특허, 디자인, 인증',
                    'og:title' => '솔라테크 회사소식',
                    'og:description' => '솔라테크의 기술력, ISO 9001·14001·45001, 특허, 디자인, 인증',
                    'twitter:title' => '솔라테크 회사소식',
                    'twitter:description' => '솔라테크의 기술력, ISO 9001·14001·45001, 특허, 디자인, 인증',
                    'twitter:card' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',                         
                ],                 
                str_starts_with($path, 'news') => [
                    'title' => '솔라테크 회사소식',
                    'keywords' => '태양광,재생에너지,지붕임대,RPS,자가소비,RE100,ESG,건물지원,주택지원,금융지원,탄소중립',
                    'description' => '솔라테크 소식,각종 수상·언론도보·임직원소식,유튜브,블로그',
                    'og:title' => '솔라테크 회사소식',
                    'og:description' => '솔라테크 소식,각종 수상·언론도보·임직원소식,유튜브,블로그',
                    'twitter:title' => '솔라테크 회사소식',
                    'twitter:description' => '솔라테크 소식,각종 수상·언론도보·임직원소식,유튜브,블로그',
                    'twitter:card' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',                         
                ],                
                str_starts_with($path, 'culture') => [
                    'title' => '솔라테크 조직문화',
                    'keywords' => '태양광,재생에너지,지붕임대,RPS,자가소비,RE100,ESG,건물지원,주택지원,금융지원,탄소중립',
                    'description' => '직원중심의 복지,상여금,우수사원,중식제공,주차지원,동아리,체력단련,명절선물,경조사,카페테리아',
                    'og:title' => '솔라테크 조직문화',
                    'og:description' => '직원중심의 복지,상여금,우수사원,중식제공,주차지원,동아리,체력단련,명절선물,경조사,카페테리아',
                    'twitter:title' => '솔라테크 조직문화',
                    'twitter:description' => '직원중심의 복지,상여금,우수사원,중식제공,주차지원,동아리,체력단련,명절선물,경조사,카페테리아',
                    'twitter:card' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',                         
                ],                
                default => [
                    'title' => '솔라테크 SOLARTEQ',
                    'keywords' => '태양광,재생에너지,지붕임대,RPS,태양광발전,태양광설치,건물지원,주택지원,금융지원,탄소중립',
                    'description' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',
                    'og:title' => '솔라테크 SOLARTEQ',
                    'og:description' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',
                    'twitter:title' => '솔라테크 SOLARTEQ',
                    'twitter:description' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',
                    'twitter:card' => '당신의 에너지 자산 파트너, 신재생에너지 전문기업 솔라테크',
                ],
            };

            $view->with('meta', $meta);
        });
    }
}
