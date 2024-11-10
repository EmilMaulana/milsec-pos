<?php

namespace App\Livewire\Store;

use Livewire\Component;
use App\Models\Store as ModelStore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Index extends Component
{   
    public $email;    // Menambahkan properti untuk email
    public $password; // Menambahkan properti untuk password
    public $name;
    public $description;
    public $address;
    public $phone;
    public $store_id;
    // public $slug; // Menambahkan properti untuk slug

    public function confirmDelete($slug)
    {
        // Melakukan validasi email dan password
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            // Jika validasi sukses, panggil fungsi delete
            $store = ModelStore::where('slug', $slug)->first();

            // Pastikan toko ditemukan
            if ($store) {
                $store->delete(); // Soft delete
                session()->flash('message', 'Toko berhasil dihapus!');
                return redirect()->route('store.index');
            } else {
                session()->flash('error', 'Toko tidak ditemukan.');
                return redirect()->route('store.index');
            }
        } else {
            session()->flash('error', 'Email atau password salah. Silakan coba lagi.');
            // Redirect ke halaman daftar toko atau halaman yang diinginkan
            return redirect()->route('store.index');
        }
    }

    public function render()
    {
        // Ambil hanya toko yang dimiliki oleh pengguna yang sedang login
        $stores = ModelStore::where('user_id', auth()->id())->get();

        return view('livewire.store.index', [
            'stores' => $stores
        ]);
    }

    

}
