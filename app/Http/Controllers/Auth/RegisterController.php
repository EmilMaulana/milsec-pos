<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => [
                'required',
                'string',
                'regex:/^(\\62|0)[0-9]{8,14}$/'
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $this->formatPhoneNumber($data['phone']), // Format nomor telepon
            'password' => $data['password'],
            'role' => User::ROLE_OWNER, // Default role bisa ditentukan di sini
        ]);
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
