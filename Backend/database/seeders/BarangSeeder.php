<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0; $i < 50; $i++){
            Barang::create([
                "gambar" => 'test.jpg',
                "nama_barang" => "Test",
                "merk" => "test",
                "stok" => 10,
                "harga" => 1000
            ]);
        }
    }
}
