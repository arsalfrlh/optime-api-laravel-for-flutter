<?php

namespace App\Jobs;

use App\Models\Barang;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

// jika error coba jalankan ini
// php artisan queue:retry all

// php artisan queue:flush
// php artisan queue:restart
// php artisan queue:work

class BarangCreateJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels; //tambahkan ini
    protected $barang;

    /**
     * Create a new job instance.
     */
    public function __construct($barang)
    {
        $this->barang = $barang;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $barang = $this->barang;
        $nmgambar = basename($barang['temp_path']);

        // Log::info('JOB DATA', $this->barang); //log di simpan di storage/logs/laravel.log
        
        Barang::create([
            'gambar' => $nmgambar,
            // 'gambar' => $barang['gambar'],
            'nama_barang' => $barang['nama_barang'],
            'merk' => $barang['merk'],
            'stok' => $barang['stok'],
            'harga' => $barang['harga']
        ]);
    }
}
