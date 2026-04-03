<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Gambar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangApiController extends Controller
{
    public function index(){
        $data = Barang::with('gambar')->paginate(3);
        $data = [
            'barang' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage()
            ]
        ];

        return response()->json(['message' => "Menampilkan data barang", 'success' => true, 'data' => $data]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'gambar' => 'required',
            'gambar.*' => 'required|image|mimes:png,jpg,jpeg',
            'nama_barang' => 'required',
            'merk' => 'required',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->all(), 'success' => false], 422);
        }

        $barang = Barang::create($request->only(['nama_barang','merk','stok','harga']));
        foreach($request->file('gambar') as $index => $gambar){
            $nmgambar = "image_" . ($index + 1) . time() . '.' . $gambar->getClientOriginalExtension();
            $gambarPath = $gambar->storeAs('images',$nmgambar,'public');
            Gambar::create([
                'id_barang' => $barang->id,
                'nama_gambar' => $gambarPath
            ]);
        }

        $barang->load('gambar');

        return response()->json(['message' => "Barang berhasil ditambahkan", 'success' => true, 'data' => $barang], 201);
    }

    public function show($id){
        $data = Barang::with('gambar')->findOrFail($id);
        return response()->json(['message' => "Menampilkan barang", 'success' => true, 'data' => $data]);
    }
}
