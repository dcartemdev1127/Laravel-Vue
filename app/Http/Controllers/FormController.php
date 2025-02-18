<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\Workspace;

class FormController extends Controller
{
    public function create(Request $request) {
        $form = Form::create($request->all());
        $result = Workspace::find($request->workspace_id)
                    ->update(['form_id'=>$form->id]);
        return response()->json($form, 200);
    }

    public function get(Request $request, $id){
        $result = Form::with(['steps' => function ($query) {
            $query->orderBy('order');
        }])->find($id);
        return response()->json($result, 200);
    }

    public function update(Request $request, $id) {
        $result = Form::find($id)->update($request->all());
        return response()->json($result, 200);
    }
}
