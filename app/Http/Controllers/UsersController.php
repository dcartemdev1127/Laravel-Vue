<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request) {
        $result = User::with('department')->get();
        return response()->json($result, 200);
    }

    public function delete(Request $request, $id) {
        $user = User::find($id);
        $user->delete();
        return response()->json(['success'=>true], 200);
    }

    public function get(Request $request, $id) {
        $user = User::find($id);
        return response()->json($user, 200);
    }

    public function update(Request $request, $id) {
        $result = User::find($id)->update($request->all());
        $user = User::find($id);
        return response()->json($user, 200);
    }
}
