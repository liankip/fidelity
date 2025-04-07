<?php

namespace App\Permissions;

class PermissionList
{
    public static function getLists()
    {
        return [
            Permission::PRINT_VOUCHER,
            Permission::CREATE_VOUCHER,
            Permission::CREATE_PO,
            Permission::CREATE_PR,
            Permission::CREATE_PROJECT,
            Permission::CREATE_BOQ,
            Permission::APPROVE_PO,
            Permission::APPROVE_BOQ,
            Permission::VIEW_SURAT_JALAN,
            Permission::CANCEL_PO,
            Permission::CANCEL_PR,
            Permission::PRINT_LATEST_PO,
            Permission::DUPLICATE_PR,
            Permission::EDIT_ITEM_LOAD,
            Permission::AJUKAN_PR,
            Permission::EDIT_BARANG,
            Permission::EDIT_PR,
            Permission::EDIT_PO,
            Permission::MANAGE_GROUP,
            Permission::APPROVE_ITEM,
            Permission::APPROVE_SUPPLIER,
            Permission::EDIT_ITEM,
            Permission::REMOVE_ITEM,
            Permission::CREATE_ITEM,
            Permission::CREATE_PRICE,
            Permission::CREATE_DELIVERY_SERVICE,
            Permission::CREATE_SUPPLIER,
            Permission::EDIT_SUPPLIER,
            Permission::UPDATE_STATUS_BARANG,
            Permission::UPLOAD_INVOICE,
            Permission::UPLOAD_DO,
            Permission::BLACKLIST_SUPPLIER,
            Permission::PAY_INVOICE,
            Permission::AJUKAN_PO,
            Permission::CREATE_ADENDUM,
            Permission::CREATE_PR_NO_BOQ,
            Permission::CREATE_PO_NO_BOQ,
            Permission::APPROVE_BOQ_SPREADSHEET,
            Permission::APPROVE_PR,
        ];
    }
}
