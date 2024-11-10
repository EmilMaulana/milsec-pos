<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    public $name,$id_barang, $base_price, $sell_price, $disc, $stock,  $product_id;
    public $unit;

    public function mount(Product $product)
    {
        $this->product_id = $product->id;
        $this->id_barang = $product->id_barang;
        $this->name = $product->name;
        $this->stock = $product->stock;
        $this->base_price = $product->base_price;
        $this->sell_price = $product->sell_price;
        $this->disc = $product->disc;
        $this->unit = $product->unit ?? 'pcs';
    }

    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'id_barang' => 'nullable|string|min:5|alpha_num|unique:products,id_barang,' . $this->product_id,
            'base_price' => 'required|numeric|min:0',
            'sell_price'=> 'required|numeric|gte:base_price',
            'disc' => 'nullable|numeric|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
        ];
    }

    public function update()
    {
        // Validasi input
        $this->validate($this->rules());

        // Temukan produk berdasarkan ID
        $product = Product::find($this->product_id);

        if (!$product) {
            session()->flash('error', 'Produk tidak ditemukan!');
            return redirect()->route('products.index');
        }

        // Membuat slug dari nama
        $slug = Str::slug($this->name);

        // Menambahkan angka jika slug sudah ada
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->where('id', '!=', $this->product_id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Ambil store_id dari pengguna yang sedang login
        $store_id = Auth::user()->store->id;

        // Generate 'id_barang' jika tidak diisi oleh user
        $id_barang = $this->id_barang ?: 'PRD' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        // Update data produk
        $product->update([
            'name' => $this->name,
            'id_barang' => $id_barang,
            'slug' => $slug,
            'base_price' => $this->base_price,
            'sell_price' => $this->sell_price,
            'disc' => $this->disc,
            'stock' => $this->stock,
            'unit' => $this->unit,
            'store_id' => $store_id,
        ]);

        // Set flash message for success
        session()->flash('message', 'Produk berhasil diupdate!');

        // Redirect to the products index page to display SweetAlert
        return redirect()->route('product.index');
    }


    public function render()
    {
        return view('livewire.product.edit');
    }
}
