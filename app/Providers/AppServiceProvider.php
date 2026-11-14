<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\WebDAV\WebDAVAdapter;
use Sabre\DAV\Client;



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
        // WebDAV 드라이버 확장 설정
        Storage::extend('webdav', function ($app, $config) {
            $client = new Client([
                'baseUri' => $config['baseUri'],
                'userName' => $config['userName'],
                'password' => $config['password'],
            ]);

            $adapter = new WebDAVAdapter($client);
            
            // 라라벨 Filesystem 구성
            return new \Illuminate\Filesystem\FilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });

    
        View::composer('*', function ($view) {
            // 사용자 화면 메타 : 언어(/ko, /en, /vn) + 페이지별 제목
            $segments = explode('/', request()->path());
            $lang     = in_array($segments[0], ['ko', 'en', 'vn'], true) ? $segments[0] : 'ko';
            $page     = implode('/', array_slice($segments, 1, 2));

            $titles = [
                'ko' => [
                    'company/about'   => '리치포트 회사소개',
                    'company/contact' => '리치포트 오시는길',
                    'company/crew'    => '리치포트 크루',
                    'company/history' => '리치포트 연혁',
                ],
                'en' => [
                    'company/about'   => 'RICHPORT About Us',
                    'company/contact' => 'RICHPORT Location',
                    'company/crew'    => 'RICHPORT Crew',
                    'company/history' => 'RICHPORT History',
                ],
                'vn' => [
                    'company/about'   => 'RICHPORT Giới thiệu công ty',
                    'company/contact' => 'RICHPORT Đường đến',
                    'company/crew'    => 'RICHPORT Đội ngũ',
                    'company/history' => 'RICHPORT Lịch sử phát triển',
                ],
            ];
            $siteName = ['ko' => '리치포트', 'en' => 'RICHPORT', 'vn' => 'RICHPORT'][$lang];

            if (isset($titles[$lang][$page])) {
                $title = $titles[$lang][$page];
                $meta  = [
                    'title'               => $title,
                    'canonical'           => 'https://richport.kr/'.$lang.'/'.$page,
                    'keywords'            => $title,
                    'description'         => $title,
                    'og:title'            => $title,
                    'og:description'      => $title,
                    'twitter:title'       => $title,
                    'twitter:description' => $title,
                    'twitter:card'        => $title,
                ];
            } else {
                $meta = [
                    'title'               => $lang === 'ko' ? '리치포트 RICHPORT' : 'RICHPORT',
                    'canonical'           => $lang === 'ko' ? 'https://richport.kr/' : 'https://richport.kr/'.$lang,
                    'keywords'            => '',
                    'description'         => '',
                    'og:title'            => $siteName,
                    'og:description'      => '',
                    'twitter:title'       => $siteName,
                    'twitter:description' => '',
                    'twitter:card'        => $siteName,
                ];
            }

            $view->with('meta', $meta);
        });
    }
}
