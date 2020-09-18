<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

class UserController extends Controller
{
    
    public function index(){
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create(){
        return view('admin.users.create');
    }

    public function store(Request $request){

    	$messages = [
            'phone.regex' => 'phone must contain only numbers',
            'email.regex' => 'enter your email like example@gmail.xyz',
        ];

        $validator = validator()->make($request->all(), [
            'username' => 'required|string|max:50|unique:users,username|alpha_dash',
            'phone' => 'required|regex:/[0-9]/u|min:11|max:15|unique:users,phone',
            'email' => 'required|unique:users,email|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'password' => 'required|min:8',
        ], $messages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return Redirect::back()->withInput($request->all())->with('error', $error);
        }

        if(filter_var(filter_var(strtolower($request->email), FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL)){
            $user = new User;
            $user->username = $request->username;
            $user->phone = convert($request->phone);
            $user->email = strtolower($request->email);
            $user->password = Hash::make($request->password);
            $user->isVerifyed = 1;
            $user->save();

        } else {
            $error = 'This Email is Not Correct';
            return Redirect::back()->withInput($request->all())->with('error', $error);
        }

        $message = 'Inserted Successfully';

        return Redirect::back()->with('message', $message);
    }

    public function edit($id){
        $user = User::find($id);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id){
    	$messages = [
            'phone.regex' => 'phone must contain only numbers',
            'email.regex' => 'enter your email like example@gmail.xyz',
        ];

        $validator = validator()->make($request->all(), [
            'username' => 'required|string|max:50|unique:users,username,'.$id.',id|alpha_dash',
            'phone' => 'required|regex:/[0-9]/u|min:11|max:15|unique:users,phone,'.$id.',id',
            'email' => 'required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users,email,'.$id.',id',
            'password' => 'nullable|min:8',
        ], $messages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return Redirect::back()->with('error', $error);
        }

        $email = strtolower($request->email);
        
        if(filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL)){
            $user = User::find($id);
            $user->username = $request->username;
            $user->phone = convert($request->phone);
            $user->email = strtolower($request->email);
            $user->isVerifyed = 1;
            
            if($request->password) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            
                
        } else {
            $error = 'This Email is Not Correct';
            return Redirect::back()->with('error', $error);
        }

        $message = 'Updated Successfully';

        return Redirect::back()->with('message', $message);
    }

    public function destroy($id){
        $user = User::find($id);
        $user->delete();
    }
}
