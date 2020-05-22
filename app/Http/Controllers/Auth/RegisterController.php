<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required'],
            'filename' => ['mimes:jpeg,bmp,png'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $request = app('request');

        if($request->hasfile('filename')){
            $cover = $request->file('filename');
            $extension = $cover->getClientOriginalExtension();

            Storage::disk('public')->put($cover->getFilename().'.'.$extension,  File::get($cover));
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'mime' => isset($cover) ? $cover->getClientMimeType() : null,
            'original_filename' => isset($cover) ? $cover->getClientOriginalName() : null,
            'filename' => isset($cover) ? $cover->getFilename().'.'.$extension : null,
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * @return string
     */
    public function redirectTo()
    {
        $this->redirectTo = '/home';
        return $this->redirectTo;

    }
}
