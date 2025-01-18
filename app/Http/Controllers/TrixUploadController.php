<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrixUploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048',
        ]);

        $path = $request->file('file')->store('images', 'public');

        return response()->json(['url' => asset("storage/$path")]);
    }
}
