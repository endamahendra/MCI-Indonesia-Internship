<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Session;
use Illuminate\Support\Facades\Hash;

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

public function updateUser(Request $request, $id)
{
    $user = User::find($id);
    return view ('front.updateuser', compact('user'));
}


public function updateUserstore(Request $request, $id)
{
    $user = User::find($id);
    $validator = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'tanggal_lahir' => 'required|date',
        'no_hp' => 'required|string|max:15',
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'alamat' => 'required|string|max:255',
        'current_password' => 'nullable|string|min:8', // Validasi password lama
        'new_password' => 'nullable|string|min:8|different:current_password', // Validasi password baru
        'new_password_confirmation' => 'nullable|string|same:new_password', // Validasi konfirmasi password baru
    ]);

    try {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->no_hp = $request->no_hp;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->alamat = $request->alamat;

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                throw ValidationException::withMessages(['current_password' => 'Password lama tidak sesuai.']);
            }
            $user->password = bcrypt($request->new_password);
        }
        $user->save();

        // Set pesan sukses
        Session::flash('success', 'Profil berhasil diperbarui');
        return redirect()->back();
    } catch (ValidationException $e) {
        // Set pesan error validasi dan kembalikan input yang sudah dimasukkan
        Session::flash('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        return redirect()->back()->withErrors($validator)->withInput();
    } catch (\Exception $e) {
        // Set pesan error umum
        Session::flash('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        return redirect()->back();
    }
}

public function updateUserfoto(Request $request, $id)
{
    $user = User::find($id);

    // Validasi input foto baru
    $request->validate([
        'new_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // maksimum 2MB
    ]);

    // Mengunggah foto baru dan menghapus foto lama jika ada
    if ($request->hasFile('new_photo')) {
        $image = $request->file('new_photo');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/pengguna'), $imageName);

        // Hapus foto lama jika ada
        if ($user->photo) {
            $oldPhotoPath = public_path($user->photo);
            if (file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath); // Hapus foto lama dari direktori
            }
        }

        // Simpan nama foto baru dengan prefix direktori ke dalam database
        $user->photo = 'images/pengguna/' . $imageName;
        $user->save();

        Session::flash('success', 'Foto profil berhasil diperbarui');
        return redirect()->back();
    }

    Session::flash('error', 'Gagal mengunggah foto profil');
    return redirect()->back();
}


}
