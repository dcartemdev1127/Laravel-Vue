<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    public function index(Request $request) {
        $result = Report::all();
        return response()->json($result, 200);
    }

    public function create(Request $request) {
        $result = Report::create($request->all());
        return response()->json($result, 200);
    }

    public function getWithWorkspace(Request $request, $id) {
        $access = request()->input('access', null);
        $reports = Report::whereHas('issue.category', function ($query) use ($id) {
            $query->where('workspace_id', $id);
        });
        if ($access) {
            $reports->where('access', $access);
        }
        $reports = $reports->get();
        return response()->json($reports, 200);
    }

    public function get(Request $request, $id) {
        $result = Report::find($id);
        return response()->json($result, 200);
    }

    public function delete(Request $request, $id) {
        $result = Report::find($id)->delete();
        return response()->json($result, 200);
    }
}
