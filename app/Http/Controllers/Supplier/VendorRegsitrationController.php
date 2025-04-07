<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\VendorRegistrant;
use App\Traits\FileUploader;
use Illuminate\Http\Request;

class VendorRegsitrationController extends Controller
{
    use FileUploader;

    public function __invoke()
    {
        return view('supplier.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'npwp_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'ktp' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'address' => 'required',
            'email' => 'required',
            'telp' => 'required',
            'sales_email' => 'required',
            'sales_phone' => 'required',
            'bank_name' => 'required',
            'account_number' => 'required',
            'bank_owner_name' => 'required',
            'bank_branch' => 'required',
            'top' => 'required',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'company_profile' => 'required|mimes:pdf,doc,docx|max:4098',
            'product_catalogue' => 'required|mimes:pdf,doc,docx|max:4098',
            'item_certificate.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:4098',
            'items.*.name' => 'required',
            'items.*.price' => 'required',
        ]);


        $ktp = $this->uploadFile($request->file('ktp'), 'vendor/ktp', 'ktp');
        $npwp_image = $this->uploadFile($request->file('npwp_image'), 'vendor/npwp', 'npwp');
        $company_profile = $this->uploadFile($request->file('company_profile'), 'vendor/company-profile', 'company_profile');
        $product_catalogue = $this->uploadFile($request->file('product_catalogue'), 'vendor/product-catalogue', 'product_catalogue');

        $vendor = VendorRegistrant::create([
            'name' => $request->name,
            'npwp' => $request->npwp,
            'ktp_image' => $ktp,
            'npwp_image' => $npwp_image,
            'email' => $request->email,
            'telp' => $request->telp,
            'sales_email' => $request->sales_email,
            'sales_phone' => $request->sales_phone,
            'address' => $request->address,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'bank_owner_name' => $request->bank_owner_name,
            'bank_branch' => $request->bank_branch,
            'top' => $request->top,
            'company_profile' => $company_profile,
            'product_catalogue' => $product_catalogue,
            'website_link' => $request->website_link,
        ]);

        if ($request->file('documents')) {
            foreach ($request->file('documents') as $document) {
                $file = $this->uploadFile($document, 'vendor/documents', 'documents');
                $vendor->documents()->create([
                    'path' => $file,
                    'file_name' => $document->getClientOriginalName(),
                    'file_type' => $document->getClientOriginalExtension(),
                ]);
            }
        }

        // Insert Items
        foreach ($request->item_name as $key => $item) {
            if (isset($request->item_certificate[$key])) {
                $certificate = $this->uploadFile($request->item_certificate[$key], 'vendor/item-certificate', 'item_certificate');
            } else {
                $certificate = null;
            }

            $vendor->items()->create([
                'item_name' => $request->item_name[$key],
                'price' => $request->item_price[$key],
                'certificate' => $certificate,
                'item_notes'=>$request->item_notes[$key]
            ]);
        }

        return redirect()->route('vendors.register.success')->with('vendor-name', $vendor->name);
    }

    public function success()
    {
        if (!session('vendor-name')) return redirect()->route('vendors.register');
        return view('supplier.success');
    }
}
