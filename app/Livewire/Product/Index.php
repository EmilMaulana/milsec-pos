<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class Index extends Component
{ 
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    use WithFileUploads;
    public $search = '';
    public $file;

    public function delete($slug)
    {
        // Find and delete the product
        $product = Product::where('slug', $slug)->first();

        if ($product) {
            $product->delete();
            session()->flash('message', 'Produk berhasil dihapus!');
            return redirect()->route('product.index');
        } else {
            session()->flash('error', 'Produk tidak ditemukan!');
        }
    }

    public function export()
    {
        // Ambil store_id dari pengguna yang sedang login
        $store_id = Auth::user()->store->id;

        // Ambil produk yang hanya terkait dengan toko pengguna yang sedang login
        $products = Product::where('store_id', $store_id)->get();

        // Menghasilkan nama file dengan format "produk_YYYY-MM-DD_HH-MM-SS.xlsx"
        $fileName = 'produk_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new class($products) implements FromArray, WithHeadings {
            private $products;

            public function __construct($products)
            {
                $this->products = $products;
            }

            public function array(): array
            {
                return $this->products->map(function ($product) {
                    return [
                        $product->name,
                        $product->base_price,
                        $product->sell_price,
                        $product->disc,
                        $product->stock,
                        $product->unit,
                    ];
                })->toArray();
            }

            public function headings(): array
            {
                return [
                    'Nama',
                    'Harga Dasar',
                    'Harga Jual',
                    'Diskon',
                    'Stok',
                    'Satuan'
                ];
            }
        }, $fileName);
    }


    public function import()
    {
        // Validasi file yang diunggah
        $this->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Simpan file sementara
        $path = $this->file->store('uploads'); 

        try {
            // Ambil store_id dari pengguna yang sedang login
            $store_id = Auth::user()->store->id;

            // Import data dari file Excel
            Excel::import(new class($store_id) implements ToModel {
                private $store_id;

                public function __construct($store_id)
                {
                    $this->store_id = $store_id;
                }

                public function model(array $row)
                {
                    // Abaikan baris header (misalnya baris 1)
                    if ($row[0] === 'Nama' && $row[1] === 'Harga Dasar' && $row[2] === 'Harga Jual' && $row[3] === 'Diskon' && $row[4] === 'Stok' && $row[5] === 'Satuan') {
                        return null; // Abaikan baris header
                    }

                    // Generate 'id_barang' jika tidak diisi oleh user
                    $id_barang = 'PRD' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
                    
                    // Pastikan id_barang unik
                    while (Product::where('id_barang', $id_barang)->exists()) {
                        $id_barang = 'PRD' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
                    }

                    // Ambil nama untuk slug
                    $name = $row[0];
                    // Buat slug awal
                    $slug = Str::slug($name);
                    $originalSlug = $slug; // Simpan slug asli untuk pengecekan duplikasi
                    $count = 1;

                    // Cek jika slug sudah ada, dan tambahkan angka jika perlu
                    while (Product::where('slug', $slug)->exists()) {
                        $slug = $originalSlug . '-' . $count;
                        $count++;
                    }

                    // Kembalikan instance model Product baru
                    return new Product([
                        'id_barang'  => $id_barang,
                        'name'       => $name,
                        'base_price' => $row[1],
                        'sell_price' => $row[2],
                        'disc'       => $row[3],
                        'stock'      => $row[4],
                        'unit'       => $row[5],
                        'slug'       => $slug,
                        'store_id'   => $this->store_id, // Tambahkan store_id
                    ]);
                }
            }, $path);

            // Beri tahu pengguna jika impor berhasil
            session()->flash('message', 'Produk berhasil diimpor.');
            return redirect()->route('product.index');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Tangkap error dari validasi Excel
            $errors = $e->failures();
            $errorMessages = [];

            foreach ($errors as $failure) {
                $errorMessages[] = 'Row ' . ($failure->row() + 1) . ': ' . implode(', ', $failure->errors());
            }

            session()->flash('error', 'Gagal mengimpor produk: ' . implode(' | ', $errorMessages));
            return redirect()->route('product.index');  
        } catch (\Exception $e) {
            // Tangkap kesalahan umum
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->route('product.index');
        } finally {
            // Hapus file setelah diimpor
            Storage::delete($path);
        }
    }

    public function exportBarcode()
    {
        // Ambil store_id dari pengguna yang sedang login
        $store_id = Auth::user()->store->id;

        // Ambil produk yang hanya terkait dengan toko pengguna yang sedang login
        $products = Product::where('store_id', $store_id)->get();

        // Buat PDF untuk menyimpan semua barcode produk
        $pdf = Pdf::loadView('pdf.barcode-pdf', compact('products'));

        // Mendapatkan waktu saat ini dalam format 'YYYYMMDD_HHMMSS'
        $timestamp = date('Ymd_His');

        // Menyimpan nama file dengan waktu download
        $fileName = 'BARCODE_' . $timestamp . '.pdf';

        // Unduh PDF dengan semua barcode
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }


    public function render()
    {
        $store_id = Auth::user()->store->id; // Ambil store_id dari pengguna yang sedang login

        if ($this->search) {
            // Jika ada pencarian, cari produk berdasarkan nama dan store_id
            $products = Product::where('store_id', $store_id) // Filter berdasarkan store_id
                ->where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(15);
        } else {
            // Jika tidak ada pencarian, ambil produk milik toko pengguna dengan paginasi
            $products = Product::where('store_id', $store_id) // Filter berdasarkan store_id
                ->latest()
                ->paginate(15);
        }

        return view('livewire.product.index', [
            'products' => $products,
        ]);
    }
}
