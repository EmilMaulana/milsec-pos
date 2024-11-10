<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $name, $id_barang, $base_price, $sell_price, $disc, $stock, $unit;

    protected $rules = [
        'name' => 'required|min:3',
        'id_barang' => 'nullable|string|min:5|alpha_num|unique:products,id_barang',
        'base_price' => 'required|numeric|min:0',
        'sell_price'=> 'required|numeric|gte:base_price', // gte: memeriksa apakah sell_price lebih besar dari atau sama dengan base_price
        'disc' => 'nullable|numeric|min:0|max:100',
        'stock' => 'required|integer|min:0',
        'unit' => 'nullable|string|max:50', // Validasi untuk unit
    ];

    public function store()
    {
        $this->validate();

        // Membuat slug dari nama
        $slug = Str::slug($this->name);

        // Menambahkan angka jika slug sudah ada
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Ambil store_id dari pengguna yang sedang login
        $store_id = Auth::user()->store->id; // Pastikan relasi 'store' ada di model User
        // Generate 'id_barang' jika tidak diisi oleh user
        $id_barang = $this->id_barang ?: 'PRD' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        Product::create([
            'id_barang' => $id_barang,
            'name' => $this->name,
            'slug' => $slug,
            'base_price' => $this->base_price,
            'sell_price' => $this->sell_price,
            'disc' => $this->disc,
            'stock' => $this->stock,
            'unit' => $this->unit,
            'store_id' => $store_id, // Menyimpan store_id dari pengguna yang login
        ]);

        // Reset input fields
        $this->reset(['name', 'id_barang', 'base_price', 'sell_price', 'stock' , 'disc', 'unit']);

        // Set flash message for success
        session()->flash('message', 'Produk berhasil ditambahkan!');
        
        // Redirect to the same page to display SweetAlert
        return redirect()->route('product.index');
    }


    public function render()
    {
        return view('livewire.product.create');
    }
}
