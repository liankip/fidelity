<?php

namespace App\Http\Controllers\Vendors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    public function __invoke()
    {
        return view("data_vendor.dashboard");
    }
}
