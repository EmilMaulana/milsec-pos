<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'phone' => 'required',
            'message' => 'required',
        ]);

        // Ambil nomor telepon dan pesan dari input form
        $phone = $request->input('phone');
        $message = $request->input('message');

        // Mengirim permintaan ke API WhatsApp
        $response = Http::post('http://localhost:3007/send', [
            'number' => $phone,  // Pastikan kunci ini sesuai dengan yang diharapkan oleh API
            'message' => $message,
        ]);
        
        // Log respons
        Log::debug("Response dari API WhatsApp: " . $response->body());
        
        // Cek jika status HTTP 200 atau 201, artinya sukses
        if ($response->successful()) {
            return redirect()->back()->with('success', 'Pesan berhasil dikirim!');
        } else {
            // Jika tidak sukses, tampilkan status dan error response
            return redirect()->back()->with('error', 'Gagal mengirim pesan. Status: ' . $response->status() . ', Error: ' . $response->body());
        }
    }
}
