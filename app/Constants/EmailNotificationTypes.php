<?php

namespace App\Constants;

class EmailNotificationTypes
{
    public const PR_CREATED = 'PR Created';
    public const ITEM_APPROVED = 'Item Approved';
    public const INVOICE_UPLOADED = 'Receipt Uploaded';
    public const PO_APPROVED = 'PO Approved';
    public const PAYMENT_NOTIFICATION = 'Payment Notification';
    public const BOQ_REVIEW_APPROVED = 'BOQ Review Approved';
    public const ITEM_ARRIVED = 'Item Arrived';
    public const PAYMENT_UPLOADED = 'Payment Uploaded';
    public const PO_COMPLETE_DOCUMENT = 'PO Complete Document';

    public static function getTypes()
    {
        return [
            self::PR_CREATED,
            self::ITEM_APPROVED,
            self::INVOICE_UPLOADED,
            self::PO_APPROVED,
            self::PAYMENT_NOTIFICATION,
            self::BOQ_REVIEW_APPROVED,
            self::ITEM_ARRIVED,
            self::PAYMENT_UPLOADED,
            self::PO_COMPLETE_DOCUMENT,
        ];
    }
}
