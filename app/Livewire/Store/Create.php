<?php

namespace App\Livewire\Store;

use Livewire\Component;
use App\Models\Store as ModelStore;
use Illuminate\Support\Str;
use App\Models\User;


class Create extends Component
{   
    public $name;
    public $description;
    public $address;
    public $phone;

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\\62|0)[0-9]{8,14}$/'
            ],
        ]);

        // Convert phone number to +62 format if it starts with 0
        if (substr($this->phone, 0, 1) === '0') {
            $this->phone = '62' . substr($this->phone, 1);
        }

        // Jika nomor telepon dimulai dengan "+62", hapus tanda "+" agar sesuai format "62"
        if (substr($this->phone, 0, 3) === '+62') {
            return substr($this->phone, 1); // Menghapus "+" di depan
        }

        // Generate slug dari nama toko
        $slug = Str::slug($this->name);

        // Buat toko baru dan ambil ID toko yang baru dibuat
        $store = ModelStore::create([
            'name' => $this->name,
            'slug' => $slug,
            'user_id' => auth()->id(),
            'description' => $this->description,
            'address' => $this->address,
            'phone' => $this->phone,
        ]);

        // Update field store_id di tabel users
        $user = auth()->user(); // Ambil user yang sedang login
        $user->store_id = $store->id; // Set store_id ke ID toko yang baru dibuat
        $user->save(); // Simpan perubahan

        session()->flash('message', 'Toko berhasil dibuat!');
        return redirect()->route('store.index');
    }


    public function render()
    {
        return view('livewire.store.create');
    }
}
