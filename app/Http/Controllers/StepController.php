<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Step;

class StepController extends Controller
{
    public function index(Request $reqeust) {
        $result = Step::all();
        return response()->json($result, 200);
    }

    public function get(Request $request, $id) {
        $result = Step::find($id);
        return response()->json($result, 200);
    }

    public function delete(Request $request, $id) {
        $result = Step::find($id)->delete();
        return response()->json($result, 200);
    }

    public function update(Request $request, $id) {
        $result = Step::find($id)->update($request->all());
        return response()->json($result, 200);
    }

    public function create(Request $request) {
        $result = Step::create($request->all());
        return response()->json($result, 200);
    }
}
