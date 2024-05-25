<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelPackage;
use Validator;
use Illuminate\Support\Facades\Storage;
use DataTables;

class TravelPackageController extends Controller
{
    public function index()
    {
        $travelPackages = TravelPackage::all();
        return view('travel_packages.index', compact('travelPackages'));
    }

    public function getdata()
    {
        $travelpackages = TravelPackage::all();
                return DataTables::of($travelpackages)->make(true);
    }

public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required',
            'tanggal' => 'required|date',
            'target' => 'required|numeric',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk foto
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Simpan foto ke folder public/images/travel
        $photoPath = $request->file('photo')->move(public_path('images/travel'), uniqid() . '.' . $request->file('photo')->getClientOriginalExtension());

        $travelPackage = TravelPackage::create([
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'target' => $request->target,
            'photo' => 'images/travel/'.$photoPath->getFilename(),
        ]);

        return response()->json(['travel_package' => $travelPackage]);
    }

        public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required',
            'tanggal' => 'required|date',
            'target' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk foto (opsional)
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $travelPackage = TravelPackage::find($id);

        if (!$travelPackage) {
            return response()->json(['error' => 'Travel package not found'], 404);
        }

        // Jika ada foto baru, pindahkan foto ke folder public/images/travel
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->move(public_path('images/travel'), uniqid() . '.' . $photo->getClientOriginalExtension());
            // Hapus foto lama dari storage
            if ($travelPackage->photo) {
                unlink(public_path($travelPackage->photo));
            }
            $travelPackage->photo = 'images/travel/'.$photoPath->getFilename();
        }

        $travelPackage->deskripsi = $request->deskripsi;
        $travelPackage->tanggal = $request->tanggal;
        $travelPackage->target = $request->target;
        $travelPackage->save();

        return response()->json(['travel_package' => $travelPackage]);
    }

    public function show($id)
    {
        $travelPackage = TravelPackage::find($id);

        if (!$travelPackage) {
            return response()->json(['error' => 'Travel package not found'], 404);
        }

        return response()->json(['travel_package' => $travelPackage]);
    }

    public function destroy($id)
    {
        $travelPackage = TravelPackage::find($id);

        if (!$travelPackage) {
            return response()->json(['error' => 'Travel package not found'], 404);
        }else{
                unlink(public_path($travelPackage->photo));
                $travelPackage->delete();
        }
        // Hapus travel package dari database

        return response()->json([], 204);
    }
}
