<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GetUsers extends Controller
{
    public function index(Request $request)
    {
        if ($request->q) {
            return User::where('type', '!=', 5)
                ->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->q . '%')
                        ->orWhere('email', 'like', '%' . $request->q . '%');
                })
                ->where('is_disabled', false)
                ->take(3)
                ->get();

        }else {
            return User::where('type', '!=', 5)->where('is_disabled', false)->get();
        }
    }
}
