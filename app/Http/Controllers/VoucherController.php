<?php

namespace App\Http\Controllers;

use App\Models\PaymentSubmissionModel;

class VoucherController extends Controller
{
    public function index(PaymentSubmissionModel $submission)
    {
        return view('vouchers.index', [
            'submission' => $submission,
        ]);
    }
}
