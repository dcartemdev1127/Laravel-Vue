<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request){
        $result = Category::all();
        return response()->json($result, 200);
    }

    public function get(Request $request, $id) {
        $result  = Category::with('issues')->find($id);
        return response()->json($result, 200);
    }

    public function create(Request $request){
        $result = Category::create($request->all());
        return response()->json($result, 200);
    }

    public function update(Request $request, $id) {
        $result = Category::find($id)->update($request->all());
        return response()->json($result, 200);
    }

    public function delete(Request $request, $id) {
        $category = Category::find($id);
        $category->delete();
        return response()->json(['success'=>true], 200);
    }

    public function getByWorkspace(Request $request, $id) {
        $result = Category::where('workspace_id', $id)->get();
        return response()->json($result, 200);
    }
}
