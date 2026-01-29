<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    public function checkCookie()
    {
        $selectedLang = request()->cookie('selectedLang');
        $path = trim(request()->path(), '/');
        
        return response()->json([
            'path' => $path,
            'selectedLang_cookie' => $selectedLang,
            'all_cookies' => $_COOKIE,
            'headers' => request()->headers->all()
        ]);
    }
}
