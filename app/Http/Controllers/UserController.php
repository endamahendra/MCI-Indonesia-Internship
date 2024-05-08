<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use DataTables;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(){
        return view('users.index');
    }

    public function getdata(){
        $users = User::where('role', 'admin')->get();
        return DataTables::of($users)->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => 'admin',
        ]);

        return response()->json(['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->role = 'admin';
        $user->save();

        return response()->json(['user' => $user]);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return view('errors.404');
        }

        return response()->json(['user' => $user]);
    }

public function destroy($id): JsonResponse
{
    try {
        // Periksa apakah ID pengguna bukan 1
        if ($id != 1) {
            // Hapus pengguna dengan ID yang diberikan
            User::destroy($id);
            return response()->json([], 204);
        } else {
            // Jika ID pengguna adalah 1, kirim respons dengan pesan error
            return response()->json(['message' => 'Cannot delete user with ID 1'], 403);
        }
    } catch (\Exception $e) {
        // Tangani kesalahan jika terjadi
        return response()->json(['message' => 'Gagal menghapus data pengguna.'], 500);
    }
}
}
