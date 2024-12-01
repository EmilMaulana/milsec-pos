<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    public $name, $last_name, $email, $phone, $password, $password_confirmation, $userId, $store_id; // Properti untuk input form
    public $search = '';

    public function mount()
    {
        $this->store_id = auth()->user()->store_id;
    }

    public function render()
    {
        // Ambil staff berdasarkan store_id dan pencarian
        $staff = User::where('store_id', $this->store_id) // Menyaring staff berdasarkan toko
                    ->where(function($query) {
                        $query->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('last_name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
        })->paginate(10);


        return view('livewire.staff.index', [
            'staff' => $staff,
        ]);
    }

    public function store()
    {
        // Validasi input
        $this->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\\62|0)[0-9]{8,14}$/'
            ],
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6', // Pastikan konfirmasi juga divalidasi
        ]);

        // Convert phone number to +62 format if it starts with 0
        if (substr($this->phone, 0, 1) === '0') {
            $this->phone = '62' . substr($this->phone, 1);
        }

        // Jika nomor telepon dimulai dengan "+62", hapus tanda "+" agar sesuai format "62"
        if (substr($this->phone, 0, 3) === '+62') {
            $this->phone = substr($this->phone, 1); // Menghapus "+" di depan
        }

        // Menyimpan staff baru
        $newUser = User::create([
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => $this->password,
            'store_id' => $this->store_id, // Menggunakan store_id yang diambil dari mount
            'role' => 'staff', // Menetapkan role sebagai staff
        ]);

        // Log aktivitas untuk mencatat pembuatan data staff
        logActivity('User menambahkan staff baru: ' . $newUser->name . ' dengan email ' . $newUser->email);

        session()->flash('message', 'Staff berhasil ditambahkan.');
        return redirect()->route('staff.index');
    }



    public function getStoreProperty()
    {
        return auth()->user()->store; // Mengambil toko yang terkait dengan user yang login
    }

    public function delete($id)
    {
        $user = User::find($id);
        
        if ($user) {
            $user->delete();
            session()->flash('message', 'Staff ' . $user->name . ' berhasil dihapus.');
            return redirect()->route('staff.index');
        }
    }

}
