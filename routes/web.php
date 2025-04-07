<?php

use App\Http\Controllers\ApprovalHistoryCRUDController;
use App\Http\Controllers\ApproveController;
use App\Http\Controllers\ArrivedController;
use App\Http\Controllers\BlacklistSupplierController;
use App\Http\Controllers\BOQ\BOQReviewController;
use App\Http\Controllers\BOQ\HistoryBOQ;
use App\Http\Controllers\BOQProjectController;
use App\Http\Controllers\BOQUpdatePriceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryItemController;
use App\Http\Controllers\ChangePoNumController;
use App\Http\Controllers\CompanyDetailCRUDController;
use App\Http\Controllers\ConcernController;
use App\Http\Controllers\DeliveryOrderCRUDController;
use App\Http\Controllers\DeliveryServiceCRUDController;
use App\Http\Controllers\EventTypeCRUD;
use App\Http\Controllers\ExportPriceExcelController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\GudangTransferCRUDController;
use App\Http\Controllers\GudangTransferRequestCRUDController;
use App\Http\Controllers\HandleCronjob;
use App\Http\Controllers\HistoryActionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\InventoryOutExportController;
use App\Http\Controllers\InventoryUsagesCRUDController;
use App\Http\Controllers\InvoiceCRUDController;
use App\Http\Controllers\ItemCRUDController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentListCashController;
use App\Http\Controllers\PaymentListConcernController;
use App\Http\Controllers\PaymentListDireksiController;
use App\Http\Controllers\PaymentListNonCashController;
use App\Http\Controllers\PaymentMetodeCRUDController;
use App\Http\Controllers\PaymentWaitingListController;
use App\Http\Controllers\PengajuanPoController;
use App\Http\Controllers\PhotoViewDeliveryOrderController;
use App\Http\Controllers\PhotoViewInvoiceController;
use App\Http\Controllers\PhotoViewSubmitionController;
use App\Http\Controllers\PriceCRUDController;
use App\Http\Controllers\PrintK3Controller;
use App\Http\Controllers\PrintMemoController;
use App\Http\Controllers\PrintPoController;
use App\Http\Controllers\Prints\PrintBOQController;
use App\Http\Controllers\Prints\PrintReceipt;
use App\Http\Controllers\PrintSpkController;
use App\Http\Controllers\PrintSuratJalanSalesController;
use App\Http\Controllers\PrintWorkOrder;
use App\Http\Controllers\Project\ProjectDraftController;
use App\Http\Controllers\ProjectCRUDController;
use App\Http\Controllers\ProjectDocumentsController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseOrderDetailCRUDController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\PurchaseRequestDestinationCRUDController;
use App\Http\Controllers\PurchaseRequestDetailCRUDController;
use App\Http\Controllers\PurchaseRequestDuplicateController;
use App\Http\Controllers\PurhcaseOrder\PrintLatest;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\SubmitionPoController;
use App\Http\Controllers\SupplierCRUDController;
use App\Http\Controllers\TestCancelController;
use App\Http\Controllers\texpdfcontroller;
use App\Http\Controllers\UploadDoController;
use App\Http\Controllers\UploadInvoiceController;
use App\Http\Controllers\UploadPaymentDocController;
use App\Http\Controllers\UploadPoArrivedController;
use App\Http\Controllers\UpPaymentController;
use App\Http\Controllers\VendorCRUDController;
use App\Http\Controllers\Vendors\PriceQuotationController;
use App\Http\Controllers\Vendors\VendorItemController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\WarehouseCRUDController;
use App\Http\Livewire\AllUser;
use App\Http\Livewire\AllUserForm;
use App\Http\Livewire\APD\APD;
use App\Http\Livewire\APD\CreateAPDRequest;
use App\Http\Livewire\Approval\AprvWlist;
use App\Http\Livewire\Approval\DetailVoucherApproval;
use App\Http\Livewire\Approval\History;
use App\Http\Livewire\Approval\VoucherApproval;
use App\Http\Livewire\ApprovedItems;
use App\Http\Livewire\Attendance;
use App\Http\Livewire\Boq\BOQReview;
use App\Http\Livewire\Boq\BoqReviewList;
use App\Http\Livewire\Boq\BOQSetting;
use App\Http\Livewire\Boq\TableInsert;
use App\Http\Livewire\BOQAccessManager;
use App\Http\Livewire\BOQController;
use App\Http\Livewire\BoqProject;
use App\Http\Livewire\BoqProjectEdit;
use App\Http\Livewire\BoqProjectInsert;
use App\Http\Livewire\BoqProjectList;
use App\Http\Livewire\BoqTask;
use App\Http\Livewire\BulkPurchase\BulkPurchase;
use App\Http\Livewire\BulkPurchase\BulkPurchaseBoq;
use App\Http\Livewire\CapexExpense\CapexExpense;
use App\Http\Livewire\CapexExpense\CapexExpenseBoq;
use App\Http\Livewire\CapexExpense\CapexExpenseBoqDetail;
use App\Http\Livewire\CapexExpense\CapexExpenseBoqEdit;
use App\Http\Livewire\CapexExpense\CapexExpenseBoqInsert;
use App\Http\Livewire\CapexExpense\CapexExpenseBoqList;
use App\Http\Livewire\CapexExpense\CapexExpenseBoqResult;
use App\Http\Livewire\CapexExpense\CapexExpenseItem;
use App\Http\Livewire\CapexExpense\EditCapexExpense;
use App\Http\Livewire\CapexExpense\EditCapexExpenseItem;
use App\Http\Livewire\CapexExpense\InsertCapexExpense;
use App\Http\Livewire\CapexExpense\InsertCapexExpenseItem;
use App\Http\Livewire\ChooseItem;
use App\Http\Livewire\ChooseItemCapexExpense;
use App\Http\Livewire\ChooseItemList;
use App\Http\Livewire\ChooseItemRawMaterial;
use App\Http\Livewire\CreateBulkPO;
use App\Http\Livewire\CreateChecklist;
use App\Http\Livewire\CreateInternalTraining;
use App\Http\Livewire\CreateMCU;
use App\Http\Livewire\CreateMeeting;
use App\Http\Livewire\CreatePRItem;
use App\Http\Livewire\CreatePrNoBoq;
use App\Http\Livewire\CreatePurchaseRequest;
use App\Http\Livewire\CSMSCreate;
use App\Http\Livewire\CSMSList;
use App\Http\Livewire\Customer\Customer;
use App\Http\Livewire\Customer\EditCustomer;
use App\Http\Livewire\Customer\HistoryCustomer;
use App\Http\Livewire\Customer\InsertCustomer;
use App\Http\Livewire\DailyExpense;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\DetailMonitoring;
use App\Http\Livewire\DetailPaymentSubmissionApproval;
use App\Http\Livewire\EditPo;
use App\Http\Livewire\RawMaterial\RawMaterial;

use App\Http\Livewire\Sales\EditSales;
use App\Http\Livewire\Sales\InsertSales;
use App\Http\Livewire\Sales\Sales;
use App\Http\Livewire\Sku\EditSku;
use App\Http\Livewire\EditUser;
use App\Http\Livewire\Forms\MinutesOfMeeting;
use App\Http\Livewire\Forms\MinutesOfMeetingDetail;
use App\Http\Livewire\History\Items;
use App\Http\Livewire\History\Project;
use App\Http\Livewire\History\Supplier;
use App\Http\Livewire\Sku\InsertSku;
use App\Http\Livewire\Inspection\ApdInspection;
use App\Http\Livewire\Inspection\CreateApdInspection;
use App\Http\Livewire\Inspection\CreateEquipmentInspection;
use App\Http\Livewire\Inspection\EditEquipmentInspection;
use App\Http\Livewire\Inspection\EquipmentInspection;
use App\Http\Livewire\InternalTraining;
use App\Http\Livewire\Invontory\DraftItem;
use App\Http\Livewire\Invontory\InventoryIndex;
use App\Http\Livewire\Invontory\InventoryOff;
use App\Http\Livewire\JSAController;
use App\Http\Livewire\JSAList;
use App\Http\Livewire\JSAView;
use App\Http\Livewire\K3\Allhiradc;
use App\Http\Livewire\K3\Createhiradc;
use App\Http\Livewire\K3\Edithiradc;
use App\Http\Livewire\K3\HsePolicy\AllHsePolicy;
use App\Http\Livewire\K3\HsePolicy\CreateHsePolicy;
use App\Http\Livewire\K3\HsePolicy\EditHsePolicy;
use App\Http\Livewire\K3\Ibpr\AllIbpr;
use App\Http\Livewire\K3\Ibpr\CreateIbpr;
use App\Http\Livewire\K3\Ibpr\EditIbpr;
use App\Http\Livewire\K3\Ibpr\List\CreateIbprList;
use App\Http\Livewire\K3\Ibpr\List\EditIbprList;
use App\Http\Livewire\K3\Ibpr\List\IbprAllList;
use App\Http\Livewire\K3\List\AllList;
use App\Http\Livewire\K3\List\CreateList;
use App\Http\Livewire\K3\List\EditList;
use App\Http\Livewire\K3\Otp\AllOtp;
use App\Http\Livewire\K3\Otp\CreateOtp;
use App\Http\Livewire\K3\Otp\EditOtp;
use App\Http\Livewire\K3\Sop\AllSop;
use App\Http\Livewire\K3\Sop\CreateSop;
use App\Http\Livewire\K3\Sop\EditSop;
use App\Http\Livewire\K3\WorkInduction\AllWorkInduction;
use App\Http\Livewire\K3\WorkInduction\CreateWorkInduction;
use App\Http\Livewire\K3\WorkInduction\EditWorkInduction;
use App\Http\Livewire\K3\WorkInstruction\AllWorkInstruction;
use App\Http\Livewire\K3\WorkInstruction\CreateWorkInstruction;
use App\Http\Livewire\K3\WorkInstruction\EditWorkInstruction;
use App\Http\Livewire\LeaveRequest;
use App\Http\Livewire\LegalDocumentManagementCreate;
use App\Http\Livewire\LegalDocumentManagementEdit;
use App\Http\Livewire\LegalDocumentManagementList;
use App\Http\Livewire\ListMeeting;
use App\Http\Livewire\ListService;
use App\Http\Livewire\ListTask;
use App\Http\Livewire\Log\Purchase;
use App\Http\Livewire\MCU;
use App\Http\Livewire\MinutesOfMeetingApproval;
use App\Http\Livewire\MonitoringPurchaseRequest;
use App\Http\Livewire\MSDSList;
use App\Http\Livewire\NewMonitoring;
use App\Http\Livewire\OfficeExpense\EditOfficeExpense;
use App\Http\Livewire\OfficeExpense\EditOfficeExpenseItem;
use App\Http\Livewire\OfficeExpense\EditOfficeExpensePurchase;
use App\Http\Livewire\OfficeExpense\InsertOfficeExpense;
use App\Http\Livewire\OfficeExpense\InsertOfficeExpenseItem;
use App\Http\Livewire\OfficeExpense\InsertOfficeExpensePurchase;
use App\Http\Livewire\OfficeExpense\OfficeExpense;
use App\Http\Livewire\OfficeExpense\OfficeExpenseItem;
use App\Http\Livewire\OfficeExpense\OfficeExpensePurchase;
use App\Http\Livewire\OfficeExpenseApproval;
use App\Http\Livewire\Order\EditMonitoringOrder;
use App\Http\Livewire\Order\EditOrder;
use App\Http\Livewire\Order\InsertOrder;
use App\Http\Livewire\Order\MonitoringOrder;
use App\Http\Livewire\Order\Order;
use App\Http\Livewire\Order\PurchaseRequest as OrderPurchaseRequest;
use App\Http\Livewire\OvertimeEdit;
use App\Http\Livewire\OvertimeForm;
use App\Http\Livewire\OvertimeRequest;
use App\Http\Livewire\Payment\History as PaymentHistory;
use App\Http\Livewire\Payment\PaymentListCash;
use App\Http\Livewire\Payment\PaymentListDirection;
use App\Http\Livewire\Payment\PaymentListNonCash;
use App\Http\Livewire\Payment\PaymentSubmission;
use App\Http\Livewire\Payment\WaitingLists;
use App\Http\Livewire\PaymentSubmissionApproval;
use App\Http\Livewire\Profile;
use App\Http\Livewire\Project\Monitoring;
use App\Http\Livewire\ProjectGroup\AddProject;
use App\Http\Livewire\ProjectGroup\ApprovedItemsGroup;
use App\Http\Livewire\ProjectGroup\ItemList;
use App\Http\Livewire\ProjectGroup\ProjectGroupController;
use App\Http\Livewire\PrWaitingList;
use App\Http\Livewire\Purchaseorder;
use App\Http\Livewire\PurchaseOrder\ChooceItemPr;
use App\Http\Livewire\PurchaseOrder\PoDetail;
use App\Http\Livewire\PurchaseOrderCreate;
use App\Http\Livewire\PurchaseOrderEdit;
use App\Http\Livewire\PurchaseReport;
use App\Http\Livewire\PurchaseRequest\PrIndex;
use App\Http\Livewire\PurchaseRequestItemEdit;
use App\Http\Livewire\PurchaseRequestTest;
use App\Http\Livewire\SafetyTalkList;
use App\Http\Livewire\Setting;
use App\Http\Livewire\Sku\Sku;
use App\Http\Livewire\SPK\Create as SpkCreate;
use App\Http\Livewire\TaskApproval;
use App\Http\Livewire\TaskChart;
use App\Http\Livewire\TaskMonitoring;
use App\Http\Livewire\TaskMonitoringBoq;
use App\Http\Livewire\TaskRevisionApproval;
use App\Http\Livewire\UserManagement;
use App\Http\Livewire\Vendors\VendorNeedApproval;
use App\Http\Livewire\Voucher\CreateAdditional;
use App\Http\Livewire\Voucher\CreateVoucher;
use App\Http\Livewire\Voucher\DetailAdditional;
use App\Http\Livewire\Voucher\DetailVoucher;
use App\Http\Livewire\Voucher\EditAdditional;
use App\Http\Livewire\Voucher\EditVoucher;
use App\Http\Livewire\Voucher\ListVoucher;
use App\Http\Livewire\WorkOrder\InsertWorkOrder;
use App\Http\Livewire\WorkOrder\MonitoringWorkOrder;
use App\Http\Livewire\WorkOrder\WorkOrder;
use App\Http\Livewire\WorkPermitList;
use App\Imports\ProjectsImport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('clear-all', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return 'clear-all';
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::prefix('vendors')->group(function () {
    Route::middleware(['role:it|top-manager|manager|purchasing'])->group(function () {
        Route::get('/', [VendorCRUDController::class, 'index'])->name('vendors.index');
        Route::get('need-approval', VendorNeedApproval::class)->name('vendors.need-approval');
        Route::get('item-list', [VendorItemController::class, 'itemList'])->name('vendors.item-list');
        Route::get('price-quotation', PriceQuotationController::class)->name('vendors.price-quotation');
        Route::get('price-quotation/{id}', [PriceQuotationController::class, 'show'])->name('vendors.price-quotation.show');

        Route::get('/newVendor', [VendorCRUDController::class, 'newVendor'])->name('vendors.newVendor');
        Route::post('/post-newVendor', [VendorCRUDController::class, 'storeVendor'])->name('vendors.storeData');
        Route::get('/quotation/{itemId}', [PriceQuotationController::class, 'quotation'])->name('vendors.quotation');

        Route::get('{id}', [VendorCRUDController::class, 'show'])->name('vendors.show');
    });
});

Auth::routes();

Route::middleware('logoutIfNotActive')->group(function () {
    //livewire
    Route::middleware(['auth'])->group(function () {
        Route::get('/', Dashboard::class)
            ->middleware(['auth'])
            ->name('root');

        Route::get('inventory', InventoryIndex::class)->name('inventory.index');
        Route::get('inventory/out', InventoryOff::class)->name('inventory.out');
        Route::get('inventory/{id}/history', [InventoryController::class, 'history'])->name('inventory.history');
        Route::get('inventory/out/draft', DraftItem::class)->name('inventory.draft');
        Route::get('inventory-out/export/{id}', [InventoryOutExportController::class, 'export'])->name('inventory-out.export');
        Route::get('/itemprindex/{id}', ChooseItem::class)->name('itempr.index');
        Route::get('/raw-material-pr/{id}', ChooseItemRawMaterial::class)->name('raw-material-pr.index');
        Route::get('/capex-expene-pr/{id}', ChooseItemCapexExpense::class)->name('capex-expense-pr.index');
        Route::get('/itempr/{id}/edit', PurchaseRequestItemEdit::class)->name('itempr.edit');
        Route::get('/itemprcreate/{id}', CreatePRItem::class)->name('itempr.create');
        Route::get('/itempradd/{id}', ChooseItemList::class)->name('itempr.addItem');
        Route::post('/itempradd/store', [ChooseItemList::class, 'storeData'])->name('itempr.storeItem');
        // Route::get('/cartindex', \App\Http\Livewire\CartIndex::class)->name('cart.index');
        Route::get('/profiles', Profile::class)->name('log.profiles');
        Route::get('log.payment', [HistoryActionController::class, 'indexPayment'])->name('log.payment');
        Route::get('log.purchase', [HistoryActionController::class, 'indexPurchase'])->name('log.purchase');
        Route::get('log-purchase', Purchase::class)->name('log-purchase');
        Route::get('log.inventory', [HistoryActionController::class, 'indexInventory'])->name('log.inventory');
        Route::get('log.masterdata', [HistoryActionController::class, 'indexMasterData'])->name('log.masterdata');

        //cart
        Route::get('cart', [CartController::class, 'cartList'])->name('cart.list');
        Route::post('store', [CartController::class, 'addToCart'])->name('cart.store');
        Route::post('update-cart', [CartController::class, 'updateCart'])->name('cart.update');
        Route::post('remove', [CartController::class, 'removeCart'])->name('cart.remove');
        Route::post('clear', [CartController::class, 'clearAllCart'])->name('cart.clear');
        Route::post('saveAllCart', [CartController::class, 'saveAllCart'])->name('cart.saveAllCart');

        //status update
        Route::put('/cancel_pr/{id}', [TestCancelController::class, 'cancel_pr'])->name('cancel_pr');
        Route::put('/approve/{id}', [ApproveController::class, 'approve'])->name('approve');
        Route::put('/approve_voucher/{id}', [ApproveController::class, 'approve_voucher'])->name('approve_voucher');
        Route::put('/reject/{id}', [ApproveController::class, 'reject'])->name('reject');
        Route::put('/revert/{id}', [ApproveController::class, 'revert'])->name('revert');
        Route::put('/revert_voucher/{id}', [ApproveController::class, 'revert_voucher'])->name('revert_voucher');
        Route::put('/up_ds/{id}', [ApproveController::class, 'up_ds'])->name('up_ds');
        Route::put('/up_driver_memo/{id}', [ApproveController::class, 'up_driver_memo'])->name('up_driver_memo');
        Route::put('/review/{id}', [ApproveController::class, 'review'])->name('review');

        Route::prefix('ajukan')->group(function () {
            Route::put('/{id}', [ApproveController::class, 'ajukan'])->name('ajukan');
            Route::put('/{id}/purchase_request', [ApproveController::class, 'ajukanPurchaseRequest'])->name('ajukan.purchase-request');
        });

        Route::put('/approve_task/{id}', [ApproveController::class, 'approve_task'])->name('approve_task');
        Route::put('/pengajuanpo/{id}', [PengajuanPoController::class, 'pengajuanpo'])->name('pengajuanpo');
        Route::put('/blacklistsupplier/{id}', [BlacklistSupplierController::class, 'blacklistsupplier'])->name('blacklistsupplier');
        Route::put('/arrivedpo/{id}', [ArrivedController::class, 'arrivedpo'])->name('arrivedpo');
        Route::put('/uppayment/{id}', [UpPaymentController::class, 'uppayment'])->name('uppayment');
        Route::get('/printboq/{id}', [PrintBOQController::class, 'print'])->name('printboq');
        Route::put('/printpo/{id}', [PrintPoController::class, 'print'])->name('printpo');
        Route::put('/printpo_ds/{id}', [PrintPoController::class, 'print_po_ds'])->name('printpo_ds');
        Route::put('/printmemo/{id}', [PrintPoController::class, 'print_memo'])->name('printmemo');
        Route::put('/printspk/{id}', [PrintSpkController::class, 'print'])->name('printspk');
        Route::get('/viewphoto_do/{id}', [PhotoViewDeliveryOrderController::class, 'viewphoto_do'])->name('viewphoto_do');
        Route::get('/viewphoto_inv/{id}', [PhotoViewInvoiceController::class, 'viewphoto_inv'])->name('viewphoto_inv');
        Route::put('/viewphoto_submition/update/{id}', [PhotoViewSubmitionController::class, 'updateFile'])->name('viewphoto_submition.update');
        Route::get('/viewphoto_submition/{id}', [PhotoViewSubmitionController::class, 'viewphoto_submition'])->name('viewphoto_submition');
        Route::get('/create_do/{id}', [PurchaseOrderDetailCRUDController::class, 'create_do'])->name('create_do');
        Route::get('/create_submition/{id}', [PurchaseOrderDetailCRUDController::class, 'create_submition'])->name('create_submition');
        Route::get('/create_inv/{id}', [PurchaseOrderController::class, 'create_inv'])->name('create_inv');
        Route::get('/upload-payment/{id}', [UploadPaymentDocController::class, 'index'])->name('upload-payment');
        Route::post('/uppayment-submissionload-payment', [UploadPaymentDocController::class, 'store'])->name('payment-store');
        Route::put('/concern/{id}', [ConcernController::class, 'concern'])->name('concern');
        Route::put('/paydir/{id}', [ConcernController::class, 'paydir'])->name('paydir');
        Route::put('/cancel/{id}', [ApproveController::class, 'cancel'])->name('cancel');
        Route::put('/duplicate_pr/{id}', [PurchaseRequestDuplicateController::class, 'duplicate_pr'])->name('duplicate_pr');
        Route::put('/change_po_num/{id}', [ChangePoNumController::class, 'change_po_num'])->name('change_po_num');
        Route::post('printlatestpo', [PrintLatest::class, 'index'])->name('printpolatest');
        Route::get('/print-voucher/{date}', [PrintLatest::class, 'voucher'])->name('print-voucher');
        Route::get('/print-receipt/{id}', PrintReceipt::class)->name('print-receipt');
        // Route::get('/po_details/{id}', PurchaseOrderDetailCRUDController::class, 'index')->name('paydir');

        // Route::put('/store-payment', [UploadPaymentController::class, 'store'])->name('store-payment');

        //upload image
        Route::get('upload-invoice', [UploadInvoiceController::class, 'index']);
        Route::post('upload-invoice', [UploadInvoiceController::class, 'store'])->name('inv.store');
        // Route::post('upload-invoice', [ UploadInvoiceController::class, 'show' ])->name('inv.show');
        Route::get('upload-arrivedpo', [UploadPoArrivedController::class, 'index']);
        Route::post('upload-arrivedpo', [UploadPoArrivedController::class, 'store'])->name('arrivedpo.store');
        // Route::get('upload-payment', [UploadPaymentController::class, 'index']);

        Route::get('upload-do', [UploadDoController::class, 'index']);
        Route::post('upload-do', [UploadDoController::class, 'store'])->name('do.store');
        Route::put('update-do-pict/{id}', [UploadDoController::class, 'updateFile'])->name('do.updateFile');
        Route::get('upload-submition', [SubmitionPoController::class, 'index']);
        Route::post('upload-submition', [SubmitionPoController::class, 'store'])->name('submit.store');
        Route::post('upload-submition', [SubmitionPoController::class, 'create'])->name('submit.create');

        //masterdata
        Route::resource('aprv_histories', ApprovalHistoryCRUDController::class);

        Route::group(['middleware' => 'role:manager|it|top-manager|finance|purchasing|adminlapangan'], function () {
            Route::get('approval-histories', History::class)->name('approval-histories');
            Route::get('aprv_waitinglists', AprvWlist::class);
            Route::get('voucher_aprv_waitinglists', VoucherApproval::class);
            Route::get('voucher_aprv_waitinglists/{voucher}/detail', DetailVoucherApproval::class)->name('detailApproval.index');
            // Route::get('voucher_aprv_waitinglists/{voucher}/email', [SubmitionPoController::class, 'viewEma']);

            Route::get('task-approval', TaskApproval::class)->name('task-approval.index');
            Route::get('task-revision-approval', TaskRevisionApproval::class)->name('task-revision-approval.index');

            Route::get('pr-waiting-list', PrWaitingList::class)->name('pr-waiting-list.index');
            Route::prefix('minute-of-meeting-approval')->group(function () {
                Route::get('/', MinutesOfMeetingApproval::class)->name('minutes-of-meeting-approval.index');
            });

            Route::get('office-expense-approval', OfficeExpenseApproval::class)->name('office-expense-approval.index');
        });

        Route::resource('items', ItemCRUDController::class);
        Route::put('items-product-update/{id}', [ItemCRUDController::class, 'updateProductImage'])->name('items.product-update');
        Route::post('/remove-file', [ItemCRUDController::class, 'removeFile']);
        Route::resource('category-item', CategoryItemController::class);
        Route::get('sync-unit', [ItemCRUDController::class, 'sync_unit'])->name('sync-unit');
        Route::patch('unit', [ItemCRUDController::class, 'unit'])->name('unit');
        Route::patch('item-unit', [ItemCRUDController::class, 'item_unit'])->name('item-unit');
        Route::patch('delete/item-unit', [ItemCRUDController::class, 'delete_item_unit'])->name('delete-item-unit');
        Route::patch('item/{id}/approve', [ItemCRUDController::class, 'approve_item'])->name('item.approve');
        Route::patch('item/{id}/reject', [ItemCRUDController::class, 'reject_item'])->name('item.reject');

        Route::resource('paymentmetodes', PaymentMetodeCRUDController::class);
        Route::resource('warehouses', WarehouseCRUDController::class);
        Route::resource('companydetails', CompanyDetailCRUDController::class);

        Route::get('projects/finished', [ProjectCRUDController::class, 'finished'])->name('projects.finished');
        Route::get('projects/group', ProjectGroupController::class)->name('projects.group');
        Route::get('projects/group/showItems/{groupId}', ItemList::class)->name('projects.showItems');
        Route::get('projects/group/showItems/{groupId}/approved-items', ApprovedItemsGroup::class)->name('projects.approvedItems');
        Route::post('projects/group/{groupId}/store', [AddProject::class, 'storeProject'])->name('projects.group.store');

        Route::get('hrd/overtime-form', OvertimeForm::class)->name('overtime.index');
        Route::get('hrd/overtime-request/{filter}', OvertimeRequest::class)->name('overtime.filterData');
        Route::get('hrd/overtime', OvertimeRequest::class)->name('overtime-request.index');
        Route::get('overtime-edit/{id}', OvertimeEdit::class)->name('overtime-edit.index');

        Route::post('overtime-store', [OvertimeForm::class, 'create'])->name('overtime.storeData');
        Route::post('overtime-edit', [OvertimeEdit::class, 'editOvertime'])->name('overtime.editData');

        Route::patch('overtime/update', [OvertimeRequest::class, 'updateData'])->name('overtime-request.update');
        Route::patch('overtime/approval', [OvertimeRequest::class, 'approvalData'])->name('overtime-request.approval');

        Route::get('jsa-index/create', JSAController::class)->name('jsa.index');
        Route::get('jsa-list/{id}', JSAList::class)->name('jsa-list.index');
        Route::get('jsa-view/index', JSAView::class)->name('jsa-view.index');

        Route::get('jsa-print/{id}', [JSAView::class, 'printJSA'])->name('jsa-print');

        Route::post('jsa-create', [JSAController::class, 'handlePost'])->name('jsa-index.create');
        Route::post('jsa-details', [JSAList::class, 'handlePost'])->name('jsa-details.create');

        Route::get('internal-index', InternalTraining::class)->name('internal.index');
        Route::get('create-internal', CreateInternalTraining::class)->name('internal.create');
        Route::get('internal-training-print/{id}', [InternalTraining::class, 'handlePrint'])->name('internal.print');
        Route::post('service-upload', [ListService::class, 'handleSubmit'])->name('service.upload');
        Route::post('internal-upload', [InternalTraining::class, 'handleSubmit'])->name('internal.upload');

        Route::get('list-service', ListService::class)->name('service.index');
        Route::get('create-checklist', CreateChecklist::class)->name('checklist.index');
        Route::get('print-service/{id}', [ListService::class, 'handlePrint'])->name('service.print');

        Route::get('meeting-index', ListMeeting::class)->name('meeting.index');
        Route::get('create-meeting', CreateMeeting::class)->name('meeting.create');
        Route::get('print-meeting/{id}', [ListMeeting::class, 'handlePrint'])->name('meeting.print');

        Route::get('msds-list', MSDSList::class)->name('msds.index');
        Route::post('msds-create', [MSDSList::class, 'handleSubmit'])->name('msds.create');

        Route::get('csms-index', CSMSList::class)->name('csms.index');
        Route::get('csms-create', CSMSCreate::class)->name('csms.create');
        Route::get('safety-talk-index', SafetyTalkList::class)->name('safety-talk.index');

        Route::get('legal-document-management', LegalDocumentManagementList::class)->name('legal-document-management.index');
        Route::get('legal-document-management/create', LegalDocumentManagementCreate::class)->name('legal-document-management.create');
        Route::get('legal-document-management/{id}/delete', [LegalDocumentManagementList::class, 'DeleteLegalDocumentManagement'])->name('legal-document-management.delete');
        Route::get('legal-document-management/{id}/edit', LegalDocumentManagementEdit::class)->name('legal-document-management.edit');
        Route::get('legal-document-management/{id}/print', [LegalDocumentManagementList::class, 'PrintLegalDocumentManagement'])->name('legal-document-management.print');

        Route::get('work-permit-list', WorkPermitList::class)->name('permit.index');
        Route::post('work-permit/create', [WorkPermitList::class, 'handleSubmit'])->name('permit.create');

        Route::prefix('projects')->group(function () {
            Route::post('{id}/upload-file', [ProjectCRUDController::class, 'uploadFile'])->name('projects.uploadFile');
            Route::get('reports', [ProjectCRUDController::class, 'finishedProjects'])->name('projects.reports');
            Route::get('finished', [ProjectCRUDController::class, 'finished'])->name('projects.finished');
            Route::get('draft', [ProjectCRUDController::class, 'draft'])->name('projects.draft');
            Route::get('group', ProjectGroupController::class)->name('projects.group');
            Route::get('group/showItems/{groupId}', ItemList::class)->name('projects.showItems');
            Route::post('group/{groupId}/store', [AddProject::class, 'storeProject'])->name('projects.group.store');
            Route::get('exportReports/{projectId}', [ProjectCRUDController::class, 'reportExport'])->name('projects.reportsExport');

            Route::get('document', [ProjectDocumentsController::class, 'index'])->name('projects.document');
            Route::get('document/{id}', [ProjectDocumentsController::class, 'download'])->name('projects.document.download');
            Route::get('document/{id}/delete', [ProjectDocumentsController::class, 'destroy'])->name('projects.document.delete');

            Route::get('create-draft', ProjectDraftController::class)->name('projects.create-draft');
            Route::post('create-draft', [ProjectDraftController::class, 'store'])->name('projects.create-draft.store');

            Route::get('{project}/task', ListTask::class)->name('project.task');
            Route::get('{project}/task/chart', TaskChart::class)->name('task.chart');
        });
        Route::resource('projects', ProjectCRUDController::class);

        Route::prefix('product')->group(function () {
            Route::prefix('customer')->group(function () {
                Route::get('/', Customer::class)->name('customer.index');
                Route::get('/insert', InsertCustomer::class)->name('customer.insert');
                Route::get('/{customer}/history', HistoryCustomer::class)->name('customer.history');
                Route::get('/{customer}/edit', EditCustomer::class)->name('customer.edit');
            });

            Route::prefix('sku')->group(function () {
                Route::get('/', Sku::class)->name('sku.index');
                Route::get('/insert', InsertSku::class)->name('sku.insert');
                Route::get('/{sku}/edit', EditSku::class)->name('sku.edit');
            });

            Route::prefix('work-order')->group(function () {
                Route::get('/', WorkOrder::class)->name('work-order.index');
                Route::get('/insert/{sales?}', InsertWorkOrder::class)->name('work-order.insert');
                Route::get('/{work}/monitoring', MonitoringWorkOrder::class)->name('work-order.monitoring');
            });

            Route::prefix('sales')->group(function () {
                Route::get('/', Sales::class)->name('sales.index');
                Route::get('/insert', InsertSales::class)->name('sales.insert');
                Route::get('/{sales}/edit', EditSales::class)->name('sales.edit');
            });

            Route::prefix('raw-material')->group(function () {
                Route::get('/', RawMaterial::class)->name('raw-material.index');
            });

            Route::get('/print-surat-jalan-sales/{id}', [PrintSuratJalanSalesController::class, 'index'])->name('print-surat-jalan-sales.index');
            Route::get('/print-work-order/{id}', [PrintWorkOrder::class, 'index'])->name('print-work-order.index');
        });

        Route::prefix('{id}/order')->group(function () {
            Route::get('/', Order::class)->name('order.index');
            Route::get('/insert', InsertOrder::class)->name('order.insert');
            Route::get('/edit/{order}', EditOrder::class)->name('order.edit');
            Route::get('/monitoring', NewMonitoring::class)->name('order.monitoring.index');
            Route::get('/monitoring/{order}', MonitoringOrder::class)->name('order.monitoring');
            Route::get('/monitoring/{order}/purchase-request', OrderPurchaseRequest::class)->name('order.monitoring.purchase-request');
        });

        // Route::resource('payments', PaymentCRUDController::class);
        Route::get('payments', PaymentHistory::class)->name('payment.history');
        Route::get('/daily-expense', DailyExpense::class)->name('daily-expense');

        Route::get('/print-memo/{id}', [PrintMemoController::class, 'show'])->name('print-new-memo');

        Route::prefix('office-expense')->group(function () {
            Route::get('/', OfficeExpense::class)->name('office-expense.index');
            Route::get('/insert', InsertOfficeExpense::class)->name('office-expense.insert');
            Route::get('/edit/{id}', EditOfficeExpense::class)->name('office-expense.edit');

            Route::prefix('/{office}')->group(function () {
                Route::get('/', OfficeExpensePurchase::class)->name('office-expense.purchase');
                Route::get('/insert', InsertOfficeExpensePurchase::class)->name('office-expense.purchase.insert');
                Route::get('/edit/{id}', EditOfficeExpensePurchase::class)->name('office-expense.purchase.edit');
            });

            Route::prefix('/{office}/{purchase}')->group(function () {
                Route::get('/', OfficeExpenseItem::class)->name('office-expense.item');
                Route::get('/insert', InsertOfficeExpenseItem::class)->name('office-expense.item.insert');
                Route::get('/edit/{id}', EditOfficeExpenseItem::class)->name('office-expense.item.edit');
            });
        });

        Route::prefix('capex-expense')->group(function () {
            Route::get('/', CapexExpense::class)->name('capex-expense.index');
            Route::get('/insert', InsertCapexExpense::class)->name('capex-expense.insert');
            Route::get('/edit/{id}', EditCapexExpense::class)->name('capex-expense.edit');

            Route::prefix('/{project_id}')->group(function () {
                Route::get('/', CapexExpenseBoq::class)->name('capex-expense.boq');
                Route::get('/boq', CapexExpenseBoqList::class)->name('capex-expense.boq.list');
                Route::get('/boq/insert', CapexExpenseBoqInsert::class)->name('capex-expense.boq.insert');
                Route::get('/boq/{id}/detail', CapexExpenseBoqDetail::class)->name('capex-expense.boq.detail');
                Route::get('/boq/{id}/edit', CapexExpenseBoqEdit::class)->name('capex-expense.boq.edit');
            });
        });

        Route::resource('event_types', EventTypeCRUD::class);

        Route::get('notifications/delete-all', [NotificationController::class, 'deleteAll'])->name('notifications.delete-all');
        Route::resource('notifications', NotificationController::class);
        Route::resource('purchase_orders', PurchaseOrderController::class);

        Route::group(['middleware' => ['role:it|top-manager|manager|purchasing|admin-gudang|finance|k3|payable|adminlapangan']], function () {
            Route::get('/purchase-orders', Purchaseorder::class)->name('purchase-orders');
            Route::get('/purchase-orders/{id}/edit', EditPo::class)->name('purchase-orders.edit');
            Route::get('purchase_order_edit/{id}', PurchaseOrderEdit::class);

            Route::prefix('general-purchase')->group(function () {
                Route::get('/', BulkPurchase::class)->name('bulk-purchase.index');
                Route::get('/boq/{project}', BulkPurchaseBoq::class)->name('bulk-purchase.boq');
            });
        });

        Route::get('purchase_order/{id}', PurchaseOrderCreate::class)->name('purchase_order_create');
        Route::get('purchase_order/chooceitempr/{id}', ChooceItemPr::class)->name('chooce_item_pr');
        Route::get('bulk-purchase-order/create/{id?}', CreateBulkPO::class)->name('bulk-purchase-order.create');

        Route::get('spk/{id}', SpkCreate::class);
        //old creat pr
        Route::resource('purchase_requests', PurchaseRequestController::class);
        Route::get('purchase-requests', PrIndex::class);
        //new create pr
        Route::get('/prtest', PurchaseRequestTest::class);
        Route::get('/createprnoboq', CreatePrNoBoq::class)->name('newpr.create');
        Route::get('/purchase_request/create', CreatePurchaseRequest::class)->name('purchase_request_create');
        Route::get('/history/items/{id}', Items::class)->name('history.items');
        Route::get('/history/project/{id}', Project::class)->name('history.project');
        Route::get('/history/supplier/{id}', Supplier::class)->name('history.supplier');
        Route::resource('returs', ReturController::class);
        Route::resource('inventory_usages', InventoryUsagesCRUDController::class);
        Route::resource('gudang_transfers', GudangTransferCRUDController::class);
        Route::resource('gudang_transfer_requests', GudangTransferRequestCRUDController::class);
        Route::resource('stocks', ItemCRUDController::class);
        Route::PUT('reject-pr-detail/{id}', [PurchaseRequestDetailCRUDController::class, 'rejectItem'])->name('reject-pr-detail');
        Route::resource('purchase_request_details', PurchaseRequestDetailCRUDController::class);
        Route::resource('purchase_order_details', PurchaseOrderDetailCRUDController::class);
        Route::resource('delivery_services', DeliveryServiceCRUDController::class);
        Route::resource('delivery_orders', DeliveryOrderCRUDController::class);
        Route::resource('pr_destinations', PurchaseRequestDestinationCRUDController::class);
        Route::resource('invoices_index', InvoiceCRUDController::class);
        Route::resource('suppliers', SupplierCRUDController::class);

        Route::get('suppliers/item-list/{id}', [SupplierCRUDController::class, 'item_list'])->name('suppliers.item-list');

        //BOQ
        Route::prefix('boq')->group(function () {
            Route::get('{project}', BOQController::class)->name('boq.index');
            Route::get('{project}/approved-items', ApprovedItems::class)->name('approved.index');
            // Route::get('{project}/create', [BOQController::class, 'create'])->name('boq.create');
            Route::patch('{project}/store', [BOQController::class, 'store'])->name('boq.store');
            Route::get('{item}/edit/{project_id}', [BOQController::class, 'edit'])->name('boq.edit');
            Route::patch('{item}/update/{project_id}', [BOQController::class, 'update'])->name('boq.update');
            Route::get('destroy/{item}/{project_id}', [BOQController::class, 'destroy'])->name('boq.destroy');
            Route::get('{projectId}/access', BOQAccessManager::class)->name('boq.access.index');
            Route::get('{projectId}/access', BOQAccessManager::class)->name('boq.access.index');
            Route::get('{projectId}/access/{id}', [BOQAccessManager::class, 'approval'])->name('boq.access.approval');
            Route::post('{projectId}/access/store', [BOQAccessManager::class, 'store'])->name('boq.access.store');
            Route::put('{projectId}/access/submit-approval', [BOQAccessManager::class, 'storeApproval'])->name('boq.access.submit-approval');

            Route::get('{projectId}/create-boq', TableInsert::class)->name('boq.create-boq');

            Route::get('{projectId}/review', BoqReviewList::class)->name('boq.review.index');
            Route::get('{projectId}/review/{boqId}', BOQReview::class)->name('boq.review.detail');
            Route::get('{projectId}/review-result/{boqId}', BOQReviewController::class)->name('boq.review.result');

            Route::get('{project}/task/{task}', BoqTask::class)->name('boq.task.index');

            Route::get('{projectId}/project/insert/{taskId}', BoqProjectInsert::class)->name('boq.project.insert');
            Route::get('{projectId}/project/task/{taskId}/edit/{boqId}', BoqProjectEdit::class)->name('boq.project.edit');

            Route::get('{projectId}/project/{taskId}', action: BoqProjectList::class)->name('boq.project.index');
            Route::get('{projectId}/project/{boqId}/detail', BoqProject::class)->name('boq.project.detail');
            Route::get('{projectId}/project-result/{boqId}', BOQProjectController::class)->name('boq.project.result');

            Route::get('{projectId}/setting', BOQSetting::class)->name('boq.setting.index');

            Route::get('{projectId}/history', HistoryBOQ::class)->name('boq.history');
            // Route::get('{projectId}/upload', UploadBOQ::class)->name('boq.upload');
        });

        Route::get('project/{project:id}/finished', [ProjectCRUDController::class, 'make_finish'])->name('project-finished');

        Route::get('monitoring/{project_id}', Monitoring::class)->name('monitor-project');

        Route::resource('prices', PriceCRUDController::class);
        Route::prefix('export')->group(function () {
            Route::get('price', [ExportPriceExcelController::class, 'export'])->name('export.price');
        });

        Route::get('/get-units', [PriceCRUDController::class, 'get_units']);
        Route::get('/sync-price', [PriceCRUDController::class, 'sync_price'])->name('sync-price');

        Route::post('Updatepricebydolar', [PriceCRUDController::class, 'Updatepricebydolar']);
        Route::resource('tespdf', texpdfcontroller::class);
        Route::resource('payment_waiting_lists', PaymentWaitingListController::class);
        Route::get('payment-waiting-lists', WaitingLists::class)->name('payment-waiting-lists');
        Route::resource('payment_list_cash', PaymentListCashController::class);
        Route::get('/payment-list-cash', PaymentListCash::class)->name('payment-list-cash');
        Route::resource('/payment_list_noncash', PaymentListNonCashController::class);
        Route::get('/payment-list-noncash', PaymentListNonCash::class)->name('payment-list-noncash');
        Route::resource('po_details', PurchaseOrderDetailCRUDController::class);
        Route::get('po-details/{id}', PoDetail::class)->name('po-detail');
        Route::resource('submitions', SubmitionPoController::class);
        Route::resource('concern_page', PaymentListConcernController::class);
        Route::resource('payment_list', PaymentListDireksiController::class);

        Route::group(['middleware' => 'role:manager|it|top-manager|finance|purchasing|payable'], function () {
            Route::get('payment-list', PaymentListDirection::class)->name('payment-list');
            Route::get('payment-submission', PaymentSubmission::class)->name('payment-submission');
            Route::get('payment-submission/{submission}/voucher/', [VoucherController::class, 'index'])->name('payment-submission.voucher.index');
            Route::get('payment-submission/{submission}/voucher/create', CreateVoucher::class)->name('payment-submission.voucher.create');
            Route::get('payment-submission/{submission}/voucher/{voucher}/detail', DetailVoucher::class)->name('payment-submission.voucher.detail');
            Route::get('payment-submission/{submission}/voucher/{voucher}/edit', EditVoucher::class)->name('payment-submission.voucher.edit');

            Route::get('payment-submission/{submission}/additional/create', CreateAdditional::class)->name('payment-submission.additional.create');
            Route::get('payment-submission/{submission}/additional/{voucher}/detail', DetailAdditional::class)->name('payment-submission.additional.detail');
            Route::get('payment-submission/{submission}/additional/{voucher}/edit', EditAdditional::class)->name('payment-submission.additional.edit');

            Route::get('payment-submission-approval', PaymentSubmissionApproval::class)->name('payment-submission-approval');
            Route::get('payment-submission-approval/{paramId}/detail', DetailPaymentSubmissionApproval::class)->name('payment-submission-approval.detail');

            Route::get('payment-submission/{id}', [ListVoucher::class, 'printVouchers'])->name('vouchers-new.print');
            Route::get('payment-submission/{submission}/print', [PaymentSubmission::class, 'print'])->name('payment-submission.print');

            //            Route::get('purchase-voucher-termin', AllTermin::class)->name('vouchers.termin.index');
            //            Route::get('purchase-voucher-termin/create', CreateTerminVoucher::class)->name('vouchers.termin.create');
            // Route::get('purchase-voucher/create', CreateVoucher::class)->name('vouchers.create');
            // Route::get('purchase-voucher/{voucher}/detail', DetailVoucher::class)->name('vouchers.detail');
            // Route::get('print-voucher-new/{id}', [ListVoucher::class, 'printVouchers'])->name('vouchers-new.print');
        });

        Route::get('purchase-report', PurchaseReport::class);

        //Setting
        Route::get('settings', Setting::class)->name('settings');

        Route::group(['middleware' => 'role:super-admin'], function () {
            Route::get('user-management', UserManagement::class)->name('user-management');
            Route::post('user-management', [UserManagement::class, 'store'])->name('user-management.store');
        });

        //import exports
        Route::controller(CompanyDetailCRUDController::class)->group(function () {
            // Route::get('users', 'index');
            Route::get('companydetails-export', 'export')->name('companydetails.export');
            Route::post('companydetails-import', 'import')->name('companydetails.import');
        });
        Route::controller(DeliveryServiceCRUDController::class)->group(function () {
            // Route::get('users', 'index');
            Route::get('deliveryservices-export', 'export')->name('deliveryservices.export');
            Route::post('deliveryservices-import', 'import')->name('deliveryservices.import');
        });
        Route::controller(EventTypeCRUD::class)->group(function () {
            // Route::get('users', 'index');
            Route::get('eventtypes-export', 'export')->name('eventtypes.export');
            Route::post('eventtypes-import', 'import')->name('eventtypes.import');
        });
        Route::controller(ItemCRUDController::class)->group(function () {
            // Route::get('users', 'index');
            Route::get('items-export', 'export')->name('items.export');
            Route::post('items-import', 'import')->name('items.import');
        });
        Route::controller(ProjectsImport::class)->group(function () {
            // Route::get('users', 'index');
            Route::get('projects-export', 'export')->name('projects.export');
            Route::post('projects-import', 'import')->name('projects.import');
        });
        Route::controller(WarehouseCRUDController::class)->group(function () {
            // Route::get('users', 'index');
            Route::get('warehouses-export', 'export')->name('warehouses.export');
            Route::post('warehouses-import', 'import')->name('warehouses.import');
        });
        Route::controller(SupplierCRUDController::class)->group(function () {
            // Route::get('users', 'index');
            Route::get('suppliers-export', 'export')->name('suppliers.export');
            Route::post('suppliers-import', 'import')->name('suppliers.import');
        });
        Route::controller(PriceCRUDController::class)->group(function () {
            // Route::get('users', 'index');
            Route::get('prices-export', 'export')->name('prices.export');
            Route::post('prices-import', 'import')->name('prices.import');
        });

        Route::get('/hrd/leave', LeaveRequest::class)->name('hrd.leaverequest');
        Route::get('/hrd/user', AllUser::class)->name('hrd.alluser');
        Route::get('/hrd/user/create', AllUserForm::class)->name('hrd.alluserForm');
        Route::get('/hrd/user/{id}', EditUser::class)->name('hrd.editUser');
        Route::patch('/hrd/updateuser/{id}', [EditUser::class, 'editData'])->name('hrd.updateUser');

        Route::get('hrd/attendance', Attendance::class)->name('hrd.attendance');

        Route::prefix('/minute-of-meeting')->group(function () {
            Route::get('/', MinutesOfMeeting::class)->name('minute-of-meeting.index');
            Route::get('/detail/{mom}', MinutesOfMeetingDetail::class)->name('minute-of-meeting.detail');
        });

        Route::prefix('k3')->group(function () {
            Route::get('hiradc', Allhiradc::class)->name('k3.hiradc');
            Route::post('hiradc/{hiradc}/print', [PrintK3Controller::class, 'print'])->name('k3.hiradc.document-print');
            Route::get('hiradc/create', Createhiradc::class)->name('k3.createHiradc');
            Route::get('hiradc/{hiradc}/edit', Edithiradc::class)->name('k3.editHiradc');

            Route::get('hiradc/{hiradc}/list', AllList::class)->name('k3.hiradc.allList');
            Route::get('hiradc/{hiradc}/list/create', CreateList::class)->name('k3.hiradc.createList');
            Route::get('hiradc/{hiradc}/list/{list}/edit', EditList::class)->name('k3.hiradc.editList');

            Route::get('ibpr', AllIbpr::class)->name('k3.ibpr');
            Route::get('ibpr/create', CreateIbpr::class)->name('k3.createIbpr');
            Route::get('ibpr/{ibpr}/edit', EditIbpr::class)->name('k3.editIbpr');

            Route::get('ibpr/{ibpr}/list', IbprAllList::class)->name('k3.ibpr.ibprList');
            Route::get('ibpr/{ibpr}/list/create', CreateIbprList::class)->name('k3.ibpr.createList');
            Route::get('ibpr/{ibpr}/list/{list}/edit', EditIbprList::class)->name('k3.ibpr.editList');
            Route::post('ibpr/{ibpr}/print', [PrintK3Controller::class, 'printIbpr'])->name('k3.ibpr.document-print');

            Route::get('work_instruction', AllWorkInstruction::class)->name('k3.workInstruction');
            Route::get('work_instruction/create', CreateWorkInstruction::class)->name('k3.workInstruction.create');
            Route::get('work_instruction/{work}/edit', EditWorkInstruction::class)->name('k3.workInstruction.edit');

            Route::get('sop', AllSop::class)->name('k3.sop');
            Route::get('sop/create', CreateSop::class)->name('k3.sop.create');
            Route::get('sop/{sop}/edit', EditSop::class)->name('k3.sop.edit');

            Route::get('safety_induction', AllWorkInduction::class)->name('k3.workInduction');
            Route::get('safety_induction/create', CreateWorkInduction::class)->name('k3.workInduction.create');
            Route::get('safety_induction/{work}/edit', EditWorkInduction::class)->name('k3.workInduction.edit');

            Route::get('hse_policy', AllHsePolicy::class)->name('k3.hsePolicy');
            Route::get('hse_policy/create', CreateHsePolicy::class)->name('k3.hsePolicy.create');
            Route::get('hse_policy/{policy}/edit', EditHsePolicy::class)->name('k3.hsePolicy.edit');

            Route::get('otp', AllOtp::class)->name('k3.otp');
            Route::get('otp/create', CreateOtp::class)->name('k3.otp.create');
            Route::get('otp/{otp}/edit', EditOtp::class)->name('k3.otp.edit');

            Route::get('mcu', MCU::class)->name('k3.mcu');
            Route::get('mcu/create', CreateMCU::class)->name('k3.mcu.create');

            Route::get('apd', APD::class)->name('k3.apd');
            Route::get('apd/create', CreateAPDRequest::class)->name('k3.apd.create');

            Route::get('apd-inspection', ApdInspection::class)->name('k3.apd-inspection');
            Route::get('apd-inspection/create', CreateApdInspection::class)->name('k3.apd-inspection.create');

            Route::get('equipment-inspection', EquipmentInspection::class)->name('k3.equipment-inspection');
            Route::get('equipment-inspection/create', CreateEquipmentInspection::class)->name('k3.equipment-inspection.create');

            Route::get('edit-equipment-inspection/{id}', EditEquipmentInspection::class)->name('k3.edit-equipment-inspection');
        });

        // New Monitoring
        Route::prefix('new-monitoring')->group(function () {
            Route::get('monitoring-index', NewMonitoring::class)->name('new-monitoring.index');
            Route::get('detail-monitoring/{prId}', DetailMonitoring::class)->name('new-monitoring.detail');
        });

        Route::get('/task-monitoring/{taskId}', TaskMonitoring::class)->name('task-monitoring.index');
        Route::get('/task-monitoring-boq/{taskId}/print', [TaskMonitoringBoq::class, 'print'])->name('task-monitoring-boq.print');
        Route::post('create-new-pr', [TaskMonitoringBoq::class, 'createPR'])->name('create-new-pr');
        Route::get('monitoring-purchase-request/{taskId}', MonitoringPurchaseRequest::class)->name('monitoring-purchase-request');
    });
    //for cronjob every 30 menutes
    Route::get('handlecronjobapprove', [HandleCronjob::class, 'aprovealert']);
    //every 11 & 17
    Route::get('handlecronjobtime', [HandleCronjob::class, 'everyspecifictime']);
    //hanndle message wa
    Route::get('wamessage', [HandleCronjob::class, 'sendWhatsapp']);

    Route::get('sendemail', [HandleCronjob::class, 'sendEmail']);

    // Route::get("refreshexchange", [HandleCronjob::class, "getexchangerate"]);
    /*------------------------------------------
    --------------------------------------------
    All Normal Users Routes List
    --------------------------------------------
    --------------------------------------------*/
    Route::middleware(['auth', 'user-access:user'])->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');
    });

    /*------------------------------------------
    --------------------------------------------
    All Admin Routes List
    --------------------------------------------
    --------------------------------------------*/
    Route::middleware(['auth', 'user-access:admin'])->group(function () {
        Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');
    });

    /*------------------------------------------
    --------------------------------------------
    All Manager Routes List
    --------------------------------------------
    --------------------------------------------*/
    Route::middleware(['auth', 'user-access:manager'])->group(function () {
        Route::get('/manager/home', [HomeController::class, 'managerHome'])->name('manager.home');
    });

    /*------------------------------------------
    --------------------------------------------
    All Purchasing Routes List
    --------------------------------------------
    --------------------------------------------*/
    Route::middleware(['auth', 'user-access:purchasing'])->group(function () {
        Route::get('/purchasing/home', [HomeController::class, 'PurchasingHome'])->name('purchasing.home');
    });
    /*------------------------------------------
    --------------------------------------------
    All Finance Routes List
    --------------------------------------------
    --------------------------------------------*/
    Route::middleware(['auth', 'user-access:finance'])->group(function () {
        Route::get('/finance/home', [HomeController::class, 'FinanceHome'])->name('finance.home');
    });
    /*------------------------------------------
    --------------------------------------------
    All Lapangan Routes List
    --------------------------------------------
    --------------------------------------------*/
    Route::middleware(['auth', 'user-access:lapangan'])->group(function () {
        Route::get('/lapangan/home', [HomeController::class, 'LapanganHome'])->name('lapangan.home');
    });
    /*------------------------------------------
    --------------------------------------------
    All IT Routes List
    --------------------------------------------
    --------------------------------------------*/
    Route::middleware(['auth', 'user-access:it'])->group(function () {
        Route::get('/it/home', [HomeController::class, 'ITHome'])->name('it.home');
    });

    /*------------------------------------------
    --------------------------------------------
    All Admin Lapangan Routes List
    --------------------------------------------
    --------------------------------------------*/

    Route::middleware(['auth', 'user-access:adminlapangan'])->group(function () {
        Route::get('/adminlapangan/home', [HomeController::class, 'AdminLapanganHome'])->name('adminlapangan.home');
    });

    Route::group(['middleware' => 'role:super-admin'], function () {
        Route::get('/update-boq-price/{projectId}', BOQUpdatePriceController::class);
    });
});
