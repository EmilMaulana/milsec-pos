<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->id,
            'phone' => [
                'nullable',
                'string',
                'regex:/^(62|0|\+62)[0-9]{8,14}$/'
            ],
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|max:12|required_with:current_password',
            'password_confirmation' => 'nullable|min:8|max:12|required_with:new_password|same:new_password'
        ]);


        $user = User::findOrFail(Auth::user()->id);
        $user->name = $request->input('name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->phone = $this->formatPhoneNumber($request->input('phone'));

        if (!is_null($request->input('current_password'))) {
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = $request->input('new_password');
            } else {
                return redirect()->back()->withInput();
            }
        }

        $user->save();

        return redirect()->route('profile')->withSuccess('Profile updated successfully.');
    }

    private function formatPhoneNumber($phone)
    {
        // Jika nomor telepon dimulai dengan "+62", hapus tanda "+" agar sesuai format "62"
        if (substr($phone, 0, 3) === '+62') {
            return substr($phone, 1); // Menghapus "+" di depan
        }

        // Jika nomor telepon dimulai dengan "0", ubah jadi "62"
        if (substr($phone, 0, 1) === '0') {
            return '62' . substr($phone, 1);
        }

        // Jika sudah diawali "62", langsung return
        if (substr($phone, 0, 2) === '62') {
            return $phone;
        }

        // Jika ada format lain, tambahkan "62" di depan
        return '62' . $phone;
    }
}
