<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        $user = User::where('email', request()->email)->first();
    if ($user and $user->type == 'admin') {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            return redirect()->route('admin.home');
        } else {
            return back()->with('error','نأسف لكن كلمة المرور أو البريد الالكتروني غير صحيح');
        }
    }
    return back()->with('error','نأسف لكن كلمة المرور أو البريد الالكتروني غير صحيح');
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/admin/login');
    }
}
