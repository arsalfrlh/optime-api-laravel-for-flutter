<?php

namespace App\Http\Controllers;

use App\Jobs\BarangCreateJob;
use App\Jobs\BarangUpdateJob;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
//install library cache redis "composer require predis/predis"
// ubah konfigurasi di file .env
// lalu jalankan ini
// php artisan config:clear
// php artisan cache:clear

//job laravel agar proses dapat dilakukan oleh job sebagian dan api jadi lebih cepat
// buat job dengan command seperti ini "php artisan make:job BarangCreateJob"
// buat dan tambahkan konfigurasi di jobnya
// lalu jalankan jobnya "php artisan queue:work"

class BarangApiController extends Controller
{
    // public function indexPaginate(){
    //     $data = Barang::paginate(1);
    //     return response()->json(['message' => "Berhasil menampilkan data barang", 'success' => true, 'data' => $data]);
    // }

    public function indexPaginate(){
        //contoh request http://127.0.0.1:8000/api/barang/paginate?page=2
        $barang = Barang::paginate(1); //1 itu jumlah data di 1 pagiante
        $data = [
            'barang' => $barang->items(),
            'pagination' => [
                'current_page' => $barang->currentPage(),
                'total_page' => $barang->lastPage(),
                'first_page' => $barang->currentPage() > 1 ? 1 : null,
                'last_page' => $barang->currentPage() < $barang->lastPage() ? $barang->lastPage() : null,
                'next_page'    => $barang->currentPage() < $barang->lastPage()
                                    ? $barang->currentPage() + 1
                                    : null,
                'prev_page'    => $barang->currentPage() > 1
                                    ? $barang->currentPage() - 1
                                    : null,
            ]
        ];
        return response()->json(['message' => "Berhasil menampilkan data barang", 'success' => true, 'data' => $data]);
    }

    public function createPaginate(Request $request){
        $validator = Validator::make($request->all(),[
            'gambar' => 'required|image|mimes:jpeg,jpg,png',
            'nama_barang' => 'required',
            'merk' => 'required',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->all(), 'success' => false]);
        }

        if($request->hasFile('gambar')){
            $gambar = $request->file('gambar');
            $nmgambar = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('images'), $nmgambar);
        }else{
            $nmgambar = null;
        }

        $data = Barang::create([
            'gambar' => $nmgambar,
            'nama_barang' => $request->nama_barang,
            'merk' => $request->merk,
            'stok' => $request->stok,
            'harga' => $request->harga
        ]);

        return response()->json(['message' => "Barang berhasil di tambahkan", 'success' => true, 'data' => $data]);
    }
    
    public function indexCache(){
        // $data = Cache::remember(
        //     "barang_all", 60, //barang_all itu adalah key cachenya (nama cache) | 60 cache akan kadluarsa dalam 1 menit
        //     fn () => Barang::all() //jika cache tidak ada akan memanggil fungsi
        // );
        //ubah config Cache jadi CACHE_STORE = file
        $data = Cache::remember("barang_all", 60, function(){
            return Barang::all();
        });
        return response()->json(['message' => "Berhasil menampilkan data barang", 'success' => true, 'data' => $data]);
    }

    public function createCache(Request $request){
        $validator = Validator::make($request->all(),[
            'gambar' => 'required|image|mimes:jpeg,jpg,png',
            'nama_barang' => 'required',
            'merk' => 'required',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->all(), 'success' => false]);
        }

        if($request->hasFile('gambar')){
            $gambar = $request->file('gambar');
            $nmgambar = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('images'), $nmgambar);
        }else{
            $nmgambar = null;
        }

        Cache::forget('barang_all'); //melupakan cache barang_all karena ada data baru
        $data = Barang::create([
            'gambar' => $nmgambar,
            'nama_barang' => $request->nama_barang,
            'merk' => $request->merk,
            'stok' => $request->stok,
            'harga' => $request->harga
        ]);

        return response()->json(['message' => "Gambar berhasil di tambahkan", 'success' => true, 'data' => $data]);
    }

    public function createJob(Request $request){
        $validator = Validator::make($request->all(),[
            'gambar' => 'required|image',
            'nama_barang' => 'required',
            'merk' => 'required',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->all(), 'success' => false]);
        }

        //bisa membuat cara seperti ini tapi proses memindahkan gambar tetap di controller dan response api bisa bertambah lama
        // if($request->hasFile('gambar')){
        //     $gambar = $request->file('gambar');
        //     $nmgambar = time().'_'.$gambar->getClientOriginalName();
        //     $gambar->move(public_path('images'), $nmgambar);
        // }else{
        //     $nmgambar = null;
        // }

        //atau bisa cara kedua pindahkan temp path gambarnya dlm bentuk string
        $path = $request->file('gambar')->store('images','public'); //pindahkan images| pindahkan agar ke folder public di storage| lokasi folder jadi storage/app/public/images/nmfile.jpg

        $barang = [
            'temp_path' => $path,
            // 'gambar' => $nmgambar,
            'nama_barang' => $request->nama_barang,
            'merk' => $request->merk,
            'stok' => $request->stok,
            'harga' => $request->harga
        ];
        BarangCreateJob::dispatch($barang); //pangging job nya seperti ini
        Cache::forget("barang_all");

        return response()->json(['message' => "Barang berhasil ditambahkan", 'success' => true]);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(),[
            'id' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png',
            'nama_barang' => 'required',
            'merk' => 'required',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->all(), 'success' => false]);
        }

        $barang = Barang::find($request->id);
        if($request->hasFile('gambar')){
            if(file_exists(public_path('images/'.$barang->gambar))){
                unlink(public_path('images/'.$barang->gambar));
            }

            $gambar = $request->file('gambar');
            $nmgambar = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('images'), $nmgambar);
        }else{
            $nmgambar = $barang->gambar;
        }

        $barang = [
            'id' => $request->id,
            'gambar' => $nmgambar,
            'nama_barang' => $request->nama_barang,
            'merk' => $request->merk,
            'stok' => $request->stok,
            'harga' => $request->harga
        ];

        Cache::forget("barang_all");
        BarangUpdateJob::dispatch($barang);
        return response()->json(['message' => "Barang berhasil di update", 'success' => true]);
    }

    public function destroy($id){
        $barang = Barang::find($id);
        if(file_exists(public_path('images/' . $barang->gambar))){
            unlink(public_path('images/' . $barang->gambar));
        }

        $barang->delete();
        Cache::forget("barang_all");
        return response()->json(['message' => "Barang telah dihapus", 'success' => true]);
    }

    public function search(Request $request){
        $search = $request->get('search');
        if(strlen($search)){
            $barang = Barang::where('nama_barang','like',"%$search%")->orWhere('merk','like',"%$search%")->paginate(1);
            $data = [
                'barang' => $barang->items(),
                'pagination' => [
                    'current_page' => $barang->currentPage(),
                    'total_page' => $barang->lastPage(),
                    'first_page' => $barang->currentPage() > 1 ? 1 : null,
                    'last_page' => $barang->currentPage() < $barang->lastPage() ? $barang->lastPage() : null,
                    'next_page'    => $barang->currentPage() < $barang->lastPage()
                                        ? $barang->currentPage() + 1
                                        : null,
                    'prev_page'    => $barang->currentPage() > 1
                                        ? $barang->currentPage() - 1
                                        : null,
                ]
            ];
        }else{
            $data = [];
            $data["barang"] = [];
        }

        return response()->json(['message' => "Menampilkan hasil pencarian", 'success' => true, 'data' => $data]);
    }
}
