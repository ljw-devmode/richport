<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;

class EditorController extends Controller
{
	public function upload(Request $request)
	{
		$request->validate([
				'file' => ['required','image','mimes:jpg,jpeg,png,gif,webp','max:10240'] // 5MB
		]);
		
		$path = $request->file('file')->store('upload/editor', 'public'); // storage/app/public/...
		/* $url  = url(Storage::disk('public')->url($path));                 // https://도메인/...' 절대경로
		
		return response()->json(['location' => $url]); */
		
		$location = '/storage/' . $path;
		
		return response()->json(['location' => $location], 200);
	}
}