<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;




class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function registerSave(Request $request)
    {

        Validator::extend('age_check', function($attribute, $value, $parameters, $validator) {
            $minAge = now()->subYears(17); // Mengurangi 17 tahun dari tahun sekarang
            return strtotime($value) <= strtotime($minAge);
        });

        Validator::replacer('age_check', function ($message, $attribute, $rule, $parameters) {
            return ucfirst(str_replace('_', ' ', $attribute)) . ' must be over 17 years old.';
        });

        $checkPassword = $request->password != $request->password_confirmation;
        $request->validate([
            'name' => 'required',
            'date_of_birth' => 'required|date|age_check',
            'gender' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'password_confirmation' => $checkPassword ? 'required|confirmed' : '',
            
        ],[

            'name' => ucfirst('name must be filled in'),
            'date_of_birth.required' => ucfirst('date of birth must be filled in'),
            'gender.required' => ucfirst('gender must be filled in'),
            'email.required' => ucfirst('email must be filled in'),
            'email.email' => ucfirst('email must be in @ format'),
            'email.unique' => ucfirst('email already exists'),
            'password.required' => ucfirst('password must be filled in'),
            'password_confirmation.confirmed' => ucfirst('password confirmation is not match'),
        ]);
        
 
        User::create([
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'email' => $request->email,
            'username' =>$request->username,
            'password' => Hash::make($request->password),
            'role' => 'GUEST'
        ]);

        Auth::logout();
 
        return redirect()->route('login');
    }

    public function loginAction(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required'
        ],[
            'login.required' => ucfirst('email must be filled in'),
            'password.required' => ucfirst('password must be filled in'),
        ]);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';


        $checkLogin = [
            $loginField => $request->login,
            'password' => $request->password,
        ];

        if(Auth::attempt($checkLogin)){
         //   dd(Auth::user()->role);
            if(Auth::user()->role == 'ADMIN'){
                Alert::success('Success', 'Successfully Login Admin');
                return redirect()->route('admin.home');
            } elseif(Auth::user()->role == 'MEMBER'){
                Alert::success('Success', 'Successfully Login Member');
                return redirect()->route('member.home');

            } elseif(Auth::user()->role == 'INSTRUCTOR'){
                Alert::success('Success', 'Successfully Login Instuctor');
                return redirect()->route('instructor.home');

            } elseif(Auth::user()->role == 'OWNER'){
                Alert::success('Success', 'Successfully Login Owner');
                return redirect()->route('report.index-Monthly');

            } elseif(Auth::user()->role === 'GUEST'){
                Alert::success('Success', 'Successfully Login Guest');
                return redirect()->route('guest.home');
            }
        } else{
            notify()->error('Username and password do not match!');
            return redirect('login');
        }
        
    }


    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        notify()->success('Successfully Logout!');
        return redirect('login');
    }
 
}
