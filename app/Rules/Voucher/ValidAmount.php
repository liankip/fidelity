<?php

namespace App\Rules\Voucher;

use Illuminate\Contracts\Validation\Rule;

class ValidAmount implements Rule
{
    public function passes($attribute, $value): bool
    {
        return is_numeric($value) && $value != 0 && $value != -1;
    }

    public function message(): string
    {
        return 'Total bayar yang dimasukkan tidak boleh 0 atau -1';
    }
}
