<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class WablasService
{
    protected $token;

    public function __construct()
    {
        // Ganti dengan token Anda
        $this->token = "9BlTphcNxQ7hKQGh8QWNth45FJ3ZjIt9Q5oKV7824WShbBf9HuU73QnDk9IRWSqU";
    }

    public function sendMessage($phone, $message)
    {
        $data = [
            'phone' => $phone,
            'message' => $message,
        ];

        $response = Http::withHeaders([
            "Authorization" => $this->token,
        ])->asForm()->post("https://bdg.wablas.com/api/send-message", $data);

        return $response->json(); // Kembalikan respons dalam format JSON
    }
    

    public function sendMessageWithFile($phone, $message, $filePath)
    {
        // Pastikan file dapat diakses secara publik (misalnya lewat URL)
        $fileUrl = url(Storage::url($filePath)); // Menghasilkan URL publik untuk file PDF

        $data = [
            'phone' => $phone,
            'document' => $fileUrl,  // Mengirimkan URL file PDF
            'caption' => $message,   // Caption yang ingin ditampilkan
        ];

        $response = Http::withHeaders([
            "Authorization" => $this->token,
        ])->asForm()->post("https://bdg.wablas.com/api/send-document", $data);

        // Kembalikan respons dalam format JSON
        return $response->json();
    }



}