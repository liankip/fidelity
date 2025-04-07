<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use APP\Models\User;

class ProfilesController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http
     */
    public function index()
    {
        $user = User::Where('id', auth()->user()->id)->first();
        return view('Profile', ['user' => $user]);
    }

    public function update()
    {
        dd("masuk sini");
    }
}
