<?php

namespace App\Http\Controllers;
use App\Models\Workspace;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function index(Request $request) {
        $result = Workspace::all();
        return response()->json($result, 200);
    }

    public function get(Request $request, $id) {
        $result = Workspace::with('categories')->find($id);
        return response()->json($result, 200);
    }

    public function create(Request $request) {
        $result = Workspace::create($request->all());
        return response()->json($result, 200);
    }

    public function delete(Request $request, $id) {
        $result = Workspace::find($id)->delete();
        return response($result, 200);
    }

    public function update(Request $request, $id) {
        $result = Workspace::find($id)->update($request->all());
        return response()->json($result, 200);
    }
}
