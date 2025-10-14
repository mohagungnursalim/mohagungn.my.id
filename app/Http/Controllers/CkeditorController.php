<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CkeditorController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $path = $file->store('uploads', 'public');
            return response()->json(['url' => asset('storage/'.$path)]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

}
