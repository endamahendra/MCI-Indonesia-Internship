<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use DataTables;

class PenggunaController extends Controller
{
    public function index()
    {
        return view('pengguna.index');
    }

    public function getdata()
    {
        $penggunas = User::where('role', 'user')->get();
        return DataTables::of($penggunas)
        ->make(true);
    }

public function update(Request $request, $id)
{

// Mengambil user_id dari Auth
$token = $request->bearerToken();
$userData = JWTAuth::setToken($token)->getPayload()->toArray();
$user_id = $userData['sub'];

if ($user_id != $id) {
    return response()->json(['error' => 'Unauthorized'], 401);
}

// Dapatkan pengguna yang sedang diautentikasi dari token bearer
$user = User::find($user_id);
if (!$user) {
    return response()->json(['error' => 'Unauthorized'], 401);
}
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email|unique:users,email,'.$user->id,
        'password' => 'nullable|min:6',
        'jenis_kelamin' => 'required',
        'tanggal_lahir' => 'required',
        'no_hp' => 'required',
        'alamat' => 'required',
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // File foto
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $user->name = $request->input('name');
    $user->email = $request->input('email');
    if ($request->has('password')) {
        $user->password = bcrypt($request->input('password'));
    }
    $user->jenis_kelamin = $request->input('jenis_kelamin');
    $user->tanggal_lahir = $request->input('tanggal_lahir');
    $user->no_hp = $request->input('no_hp');
    $user->alamat = $request->input('alamat');

    // Simpan foto ke folder 'pengguna' di dalam folder 'public'
    if ($request->hasFile('photo')) {
        $photo = $request->file('photo');
        $fileName = time() . '_' . $photo->getClientOriginalName();
        $photo->move(public_path('images/pengguna'), $fileName); // Simpan dengan move
        $user->photo = $fileName;
    }

    $user->role = 'user';
    $user->save();

    return response()->json(['user' => $user]);
}


    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        return response()->json(['user' => $user]);
    }

    public function showuser(Request $request, $id)
    {
        // Mengambil user_id dari Auth
        $token = $request->bearerToken();
        $userData = JWTAuth::setToken($token)->getPayload()->toArray();
        $user_id = $userData['sub'];

        if ($user_id != $id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        return response()->json(['user' => $user]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        // Hapus foto dari folder 'public/pengguna' jika ada
        if ($user->photo) {
            unlink(public_path('images/pengguna/' . $user->photo));
        }

        $user->delete();

        return response()->json(['success' => 'Data deleted'], 204);
    }
}
