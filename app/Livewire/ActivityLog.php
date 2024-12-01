<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ActivityLog as ModelActivityLog;
USE Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class ActivityLog extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $user = Auth::user();

        // Pastikan user memiliki toko yang terkait
        if (!$user->store) {
            return redirect()->route('home')->with('error', 'Toko tidak ditemukan.');
        }

        $store_id = $user->store->id; // Ambil store_id dari pengguna yang sedang login

        // Ambil log aktivitas berdasarkan store_id
        $activityLogs = ModelActivityLog::where('store_id', $store_id)
            ->latest()
            ->paginate(15);

        return view('livewire.activity-log', [
            'activityLogs' => $activityLogs,
        ]);
    }

}
