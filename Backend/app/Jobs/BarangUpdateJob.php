<?php

namespace App\Jobs;

use App\Models\Barang;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class BarangUpdateJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $barang;

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
        Barang::where('id', $barang['id'])->update($barang);
    }
}
