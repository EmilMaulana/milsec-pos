<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Edit extends Component
{
    public $name, $role, $last_name, $email, $phone, $password, $password_confirmation, $userId, $store_id; // Properti untuk input form

    public function mount(User $user)
    {   
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->role = $user->role;
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
            'password' => 'nullable|string|min:6|confirmed', // Password tidak wajib diisi
            'password_confirmation' => 'nullable|string|min:6', // Pastikan konfirmasi juga divalidasi
            'role' => 'required|in:staff,owner', // Validasi untuk role
        ]);

        // Convert phone number to +62 format if it starts with 0
        if (substr($this->phone, 0, 1) === '0') {
            $this->phone = '62' . substr($this->phone, 1);
        }

        // Jika nomor telepon dimulai dengan "+62", hapus tanda "+" agar sesuai format "62"
        if (substr($this->phone, 0, 3) === '+62') {
            $this->phone = substr($this->phone, 1); // Menghapus "+" di depan
        }

        $user = User::findOrFail($this->userId);
        
        // Update data pengguna
        $user->update([
            'name' => $this->name,
            'last_name' => $this->last_name, // Jangan lupa untuk meng-update last_name jika perlu
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role, // Menyimpan role ke database
            // Jika ingin merubah password saat update, tambahkan logika berikut
            'password' => $this->password ? Hash::make($this->password) : $user->password, // Hanya update password jika diisi
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
