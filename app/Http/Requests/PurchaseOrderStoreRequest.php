<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'pr_id'             => ['required', 'exists:purchasre_requests,id'],
            'payment_metode_id' => ['required', 'exists:payment_metodes,id'],
            'project_id'        => ['required', 'exists:projects,id'],
            'warehouse_id'      => ['required', 'exists:warehouses,id'],
            'price_id'          => ['required', 'exists:prices,id'],
            'company_id'        => ['required', 'exists:company_details,id'],
        ];
    }
}
