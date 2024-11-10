<?php

namespace App\Livewire\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class Modal extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Menggunakan tema bootstrap untuk paginasi
    public $totalModal; // Untuk menyimpan total modal berdasarkan base price dan stock
    public $search = ''; // Untuk menyimpan input pencarian

    public function render()
    {
        // Ambil store_id dari pengguna yang sedang login
        $store_id = Auth::user()->store->id;

        // Ambil data produk dari database dengan paginasi, filter toko, dan pencarian
        $products = Product::where('store_id', $store_id)
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(15);

        // Hitung total modal berdasarkan produk yang sesuai dengan filter toko dan pencarian
        $this->totalModal = Product::where('store_id', $store_id)
            ->where('name', 'like', '%' . $this->search . '%')
            ->selectRaw('SUM(base_price * stock) as total')
            ->first()
            ->total;

        return view('livewire.report.modal', [
            'products' => $products, // Pass data produk ke view
            'totalModal' => $this->totalModal,
        ]);
    }


    public function updating($propertyName)
    {
        // Reset pagination saat properti diperbarui
        $this->resetPage();
    }
}
