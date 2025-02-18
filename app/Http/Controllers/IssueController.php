<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function index(Request $request) {
        $result = Issue::all();
        return response()->json($result, 200);
    }

    public function get(Request $request, $id) {
        $result = Issue::find($id);
        return response()->json($result, 200);
    }

    public function create(Request $request) {
        $result = Issue::create($request->all());
        return response()->json($result, 200);
    }

    public function delete(Request $request, $id) {
        $result = Issue::find($id)->delete();
        return response()->json($result, 200);
    }

    public function update(Request $request, $id) {
        $result = Issue::find($id)->update($request->all());
        return response()->json($result, 200);
    }

    public function getByCategory(Request $request, $id) {
        $result = Issue::where('category_id', $id)->get();
        return response()->json($result, 200);
    }
}
