<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    //
    public function index()
    {
        return view('staff.index', [
            'title' => 'Manajemen Staff'
        ]);
    }

    public function edit(User $user)
    {
        return view('staff.edit', [
            'title' => $user->fullname,
            'user' => $user
        ]);
    }
}
