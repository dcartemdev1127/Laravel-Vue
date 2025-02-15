<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

class MainController extends Controller
{
    public function index(Request $request): JsonResponse {
        $result = ['status' => 200];
        return response()->json($result, 200);
    }

    public function redirectTo(Request $request): RedirectResponse
    {
        $query = $request->getQueryString();
        $newUrl = url('/').($query? '?'.$query: '');
        return Redirect::to($newUrl);
    }

}
