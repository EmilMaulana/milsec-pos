<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance as ModelAttendance;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class Index extends Component
{
    use WithFileUploads;

    // Properti publik untuk status absensi, foto, dan lokasi
    public $status;
    public $photo;
    public $location, $latitude, $longitude;

    // Listener untuk lokasi menggunakan atribut On di Livewire 3
    #[On('location-updated')]
    public function updateLocation($location)
    {
        $this->location = $location;
    }

    public function recordAttendance()
    {
        $this->validate([
            'status' => 'required',
            'photo' => 'required|image|max:2048',
            'location' => 'required|string', // Format: "latitude,longitude"
        ]);

        // Simpan foto
        $photoPath = $this->photo->store('photos', 'public');

        // Memisahkan latitude dan longitude
        list($latitude, $longitude) = explode(',', $this->location);

        // Simpan data absensi
        ModelAttendance::create([
            'user_id' => Auth::id(),
            'store_id' => Auth::user()->store->id,
            'status' => $this->status,
            'photo' => $photoPath,
            'latitude' => trim($latitude), // Menghilangkan spasi tambahan
            'longitude' => trim($longitude), // Menghilangkan spasi tambahan
            'location' => $this->location, // Menyimpan string lokasi jika perlu
        ]);

        session()->flash('message', 'Absensi berhasil dicatat.');

        return redirect()->route('attendance.index');
    }


    public function render()
    {
        // Ambil ID toko pengguna yang sedang login
        $store_id = Auth::user()->store->id;

        // Ambil data absensi untuk toko terkait dengan pencarian dan paginasi
        $query = ModelAttendance::where('store_id', $store_id);

        // Tambahkan pencarian jika ada
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%')
                    ->orWhere('location', 'like', '%' . $this->search . '%');
            });
        }

        $attendances = $query->latest()->paginate(10);

        // Kembalikan view dengan data absensi
        return view('livewire.attendance.index', [
            'attendances' => $attendances
        ]);
    }
}