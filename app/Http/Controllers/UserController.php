<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 1 ) {
            $users = User::all();
            return view('users.index', compact('users'));
        } else {
            return redirect('home');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->role == 1 ) {
            $user = User::findOrFail($id);
            return view('users.edit', compact('user'));
        } else {
            return redirect('home');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        if(Auth::user()->role == 1) {
            $this->validate($request, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255',],
                'role' => ['required'],
                'filename' => ['mimes:jpeg,bmp,png'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            if ($request->hasfile('filename')) {
                $cover = $request->file('filename');
                $extension = $cover->getClientOriginalExtension();

                Storage::disk('public')->put($cover->getFilename() . '.' . $extension, File::get($cover));
            }

            $user = User::findOrFail($id);

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->role = $request->input('role');
            $user->mime = isset($cover) ? $cover->getClientMimeType() : null;
            $user->original_filename = isset($cover) ? $cover->getClientOriginalName() : null;
            $user->filename = isset($cover) ? $cover->getFilename().'.'.$extension : null;
            $user->password = Hash::make($request->input('password'));
            $user->save();

            return redirect('users')->with('success', 'User updated successfully');
        } else {
            return redirect('home');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::user()->role == 1 ) {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect('users')->with('success', 'User deleted successfully');
        } else {
            return redirect('home');
        }
    }
}
