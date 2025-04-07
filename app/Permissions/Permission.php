<?php

namespace App\Permissions;

class Permission
{
    public const CREATE_PO = 'create-po';
    public const CREATE_PR = 'create-pr';
    public const CREATE_PROJECT = 'create-project';
    public const CREATE_BOQ = 'create-boq';
    public const APPROVE_PO = 'approve-po';
    public const APPROVE_BOQ = 'approve-boq';
    public const VIEW_SURAT_JALAN = 'view-surat-jalan';
    public const CANCEL_PO = 'cancel-po';
    public const CANCEL_PR = 'cancel-pr';
    public const PRINT_LATEST_PO = 'print-latest-po';
    public const DUPLICATE_PR = 'duplicate-pr';
    public const EDIT_ITEM_LOAD = 'edit-item-load';
    public const AJUKAN_PR = 'ajukan-pr';
    public const AJUKAN_PO = 'ajukan-po';
    public const EDIT_BARANG = 'edit-barang';
    public const EDIT_PR = 'edit-pr';
    public const EDIT_PO = 'edit-po';
    public const MANAGE_GROUP = 'manage_group';
    public const APPROVE_ITEM = 'approve-item';
    public const APPROVE_SUPPLIER = 'approve-supplier';
    public const EDIT_ITEM = 'edit-item';
    public const REMOVE_ITEM = 'remove-item';
    public const PRINT_VOUCHER = 'print-voucher';
    public const CREATE_VOUCHER = 'create-voucher';
    public const CREATE_ITEM = 'create-item';
    public const CREATE_PRICE = 'create-price';
    public const CREATE_DELIVERY_SERVICE = 'create-delivery-service';
    public const CREATE_SUPPLIER = 'create-supplier';
    public const EDIT_SUPPLIER = 'edit-supplier';
    public const UPDATE_STATUS_BARANG = 'update-status-barang';
    public const UPLOAD_INVOICE = 'upload-invoice';
    public const UPLOAD_DO = 'upload-delivery-order';
    public const BLACKLIST_SUPPLIER = 'blacklist-supplier';
    public const PAY_INVOICE = 'pay-invoice';
    public const CREATE_ADENDUM = 'create-adendum';
    public const CREATE_PR_NO_BOQ = 'create-pr-no-boq';
    public const CREATE_PO_NO_BOQ = 'create-po-no-boq';
    public const APPROVE_BOQ_SPREADSHEET = 'approve-boq-spreadsheet';
    public const APPROVE_PR = 'approve-pr';
}
