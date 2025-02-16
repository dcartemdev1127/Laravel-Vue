<?php

namespace App\Http\Controllers;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request) {
        $result = Department::all();
        return response()->json($result, 200);
    }

    public function create(Request $request) {
        $result = Department::create($request->all());
        return response()->json($result, 200);
    }

    public function delete(Request $request, $id) {
        $department = Department::find($id);
        $department->delete();
        return response()->json(['success'=>true], 200);
    }

    public function get(Request $request, $id) {
        $department = Department::find($id);
        return response()->json($department, 200);
    }
}
