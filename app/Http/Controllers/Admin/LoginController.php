<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function guard()
    {
        return Auth::guard('admin');
    }

    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()){
            return redirect()->route('dashboard');
        } else {
            return view('admin.login.login');
        }
    }

    public function login(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'email'   => 'required|email',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails())
        {
            $error = $validator->errors()->first();
            return Redirect::back()->withInput($request->all())->with('error', $error);
        }

        $admin = \App\Models\Admin::where('email', $request->email)->first();

        $credentials = array('email' => $request->email, 'password' => $request->password);
        
        $remember_me = $request->has('remember') ? true : false;

        if (Auth::guard('admin')->attempt($credentials, $remember_me)) {
            return redirect()->intended('/');
        } else {
            $error = trans('admin.Your Email Or Password Is Not Correct');
            return Redirect::back()->withInput($request->all())->with('error', $error);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        return redirect()->route('admin.login');
    }

}
