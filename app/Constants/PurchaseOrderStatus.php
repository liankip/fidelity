<?php

namespace App\Constants;

class PurchaseOrderStatus
{
    public const NEW = 'New';
    public const REVIEW = 'Review';
    public const APPROVED = 'Approved';
    public const CANCEL = 'Cancel';
    public const DRAFT = 'Draft';
    public const DRAFT_WITH_DS = 'Draft With Delivery Services';
    public const NEW_WITH_DS = 'New With Delivery Services';
    public const NEED_TO_PAY = 'Need to Pay';
    public const REJECTED = 'Rejected';
    public const PARTIALLY_PAID = 'Partially Paid';
    public const PAID = 'Paid';
    public const WAIT_FOR_APPROVAL = 'Wait For Approval';
    public const ARRIVED = 'Arrived';
    public const REVERTED = 'Reverted';
    public const COMPLETED = 'Completed';
}
