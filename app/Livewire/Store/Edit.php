<?php

namespace App\Livewire\Store;

use Livewire\Component;
use App\Models\Store as ModelStore;
use Illuminate\Support\Str;

class Edit extends Component
{
    public $name;
    public $description;
    public $address;
    public $phone;
    public $store_id;

    public function mount($store)
    {
        $this->name = $store->name;
        $this->description = $store->description;
        $this->address = $store->address;
        $this->phone = $store->phone;
        $this->store_id = $store->id;
    }

    public function update()
    {
        // Validasi data yang diterima
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\\+62|0)[0-9]{8,14}$/'
            ],
        ]);

        // Convert phone number to +62 format if it starts with 0
        if (substr($this->phone, 0, 1) === '0') {
            $this->phone = '62' . substr($this->phone, 1);
        }

        // Jika nomor telepon dimulai dengan "+62", hapus tanda "+" agar sesuai format "62"
        if (substr($this->phone, 0, 3) === '+62') {
            $this->phone = substr($this->phone, 1); // Menghapus "+" di depan
        }

        // Cari toko berdasarkan ID
        $store = ModelStore::findOrFail($this->store_id);

        // Simpan data lama untuk perbandingan
        $oldData = $store->getOriginal();

        // Generate slug dari nama toko
        $slug = Str::slug($this->name);

        // Update data toko
        $store->update([
            'name' => $this->name,
            'slug' => $slug,
            'description' => $this->description,
            'address' => $this->address,
            'phone' => $this->phone,
        ]);

        // Deteksi perubahan dan buat daftar perubahan
        $changes = [];
        foreach ($store->getDirty() as $key => $value) {
            $changes[$key] = [
                'old' => $oldData[$key] ?? null,
                'new' => $value,
            ];
        }

        // Log aktivitas perubahan data
        if (!empty($changes)) {
            $changeLog = 'User ' . auth()->user()->name . ' memperbarui toko dengan nama: ' . $store->name . '. Perubahan: ';
            foreach ($changes as $field => $change) {
                $changeLog .= "Field '{$field}' diubah dari '{$change['old']}' menjadi '{$change['new']}'; ";
            }
            logActivity($changeLog);
        }

        // Flash message untuk notifikasi
        session()->flash('message', 'Toko berhasil diperbarui!');

        // Redirect ke halaman daftar toko atau halaman yang diinginkan
        return redirect()->route('store.index');
    }


    public function render()
    {
        return view('livewire.store.edit');
    }
}
