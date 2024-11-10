<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use App\Models\User;

class Edit extends Component
{
    public $name, $last_name, $email, $phone, $password, $password_confirmation, $userId, $store_id; // Properti untuk input form

    public function mount(User $user)
    {   
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        // Reset password fields, karena tidak ingin memuat password yang ada
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function update()
    {
        // Validasi input untuk update
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\\62|0)[0-9]{8,14}$/'
            ],
            'password' => 'string|min:6|confirmed',
            'password_confirmation' => 'string|min:6', // Pastikan konfirmasi juga divalidasi
        ]);

        // Convert phone number to +62 format if it starts with 0
        if (substr($this->phone, 0, 1) === '0') {
            $this->phone = '62' . substr($this->phone, 1);
        }

        // Jika nomor telepon dimulai dengan "+62", hapus tanda "+" agar sesuai format "62"
        if (substr($this->phone, 0, 3) === '+62') {
            return substr($this->phone, 1); // Menghapus "+" di depan
        }

        $user = User::findOrFail($this->userId);
        $user->update([
            'name' => $this->name,
            'last_name' => $this->last_name, // Jangan lupa untuk meng-update last_name jika perlu
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => $this->password,
            // Jika ingin merubah password saat update, tambahkan logika berikut
            // 'password' => Hash::make($this->password), // Tambahkan jika password diubah
        ]);

        session()->flash('message', 'Staff berhasil diperbarui.');
        return redirect()->route('staff.index');
    }

    public function getStoreProperty()
    {
        return auth()->user()->store; // Mengambil toko yang terkait dengan user yang login
    }

    public function render()
    {
        return view('livewire.staff.edit');
    }
}
