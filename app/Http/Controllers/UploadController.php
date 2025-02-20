<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function index(Request $request) {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public');
            return response()->json(['url' => asset('storage/' . $path)]);
        }
        return response()->json(['error' => 'No image uploaded'], 400);
    }
}
