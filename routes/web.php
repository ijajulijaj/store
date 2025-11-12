<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Import\VendorController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PendingOrdersController;
use App\Http\Controllers\CompleteOrdersController;
use App\Http\Controllers\CancelOrdersController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\OrderTransferController;
use App\Http\Controllers\OnlineTransactionController;
use App\Http\Controllers\AllProductsController;
use App\Http\Controllers\AllOffersController;
use App\Http\Controllers\PromosController;
use App\Http\Controllers\OrderReportController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\allCustomerReportController;
use App\Http\Controllers\OrderModifyController;
use App\Http\Controllers\CustomerReviewController;
use App\Http\Controllers\CategorySubCatController;
 

Route::get('/', function () {
    return view('auth.login');
});

// routes/web.php
Route::get('/admin/pending-orders-view/{order_id}', [PendingOrdersController::class, 'view'])
     ->name('admin.orders.pending.view');

Route::get('/admin/complete-orders-view/{order_id}', [CompleteOrdersController::class, 'view'])
     ->name('admin.orders.complete.view');

Route::get('/admin/cancel-orders-view/{order_id}', [CancelOrdersController::class, 'view'])
     ->name('admin.orders.cancel.view');

Route::get('/admin/online-transaction-view/{order_id}', [OnlineTransactionController::class, 'view'])
     ->name('admin.transaction.view');


Route::controller(AuthController::class)->group(function () {
    
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');
  
    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});

  
Route::middleware('auth')->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/admin/order-statistics', [DashboardController::class, 'getOrderStatistics'])->name('admin.order.statistics');
    Route::post('/admin/monthly-forecast', [DashboardController::class, 'getMonthlyForecastApi'])->name('admin.monthly.forecast');
    Route::post('/dashboard/outlet-order-statistics', [DashboardController::class, 'getOutletWiseOrderStatistics'])->name('admin.outletOrder.statistics');


    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile-updated', [AuthController::class, 'profileUpdate'])->name('profile.update');
    
    // ✅ Show the password update form
    Route::get('/admin/customer/change-password/{user_id}', function($user_id) {
        return view('admin.customers.update', ['user_id' => $user_id]);
    })->name('admin.customers.edit-password');
    
    // ✅ Handle the password update form submission
    Route::post('/admin/customer/change-password/{user_id}', [AuthController::class, 'changeUserPassword'])->name('admin.customers.update-password');


    Route::get('/admin/customer-list', [AuthController::class, 'customerList'])->name('admin.customers.index');

    
    //admin route
    Route::get('admin/promo-offers', [PromosController::class, 'index'])->name('admin.promo.index');
    Route::post('/admin/promo-code/import', [PromosController::class, 'import'])->name('admin.promos.import');
    Route::get('admin/all-offers', [AllOffersController::class, 'index'])->name('admin.offers.index'); 
    Route::post('/admin/offers/import', [AllOffersController::class, 'import'])->name('admin.offers.import');
    Route::get('admin/all-products', [AllProductsController::class, 'index'])->name('admin.products.index');
    Route::post('/admin/products/import', [AllProductsController::class, 'import'])->name('admin.products.import');
    Route::get('admin/outlets', [OutletController::class, 'index'])->name('admin.outlets.index');
    Route::get('admin/pending-orders', [PendingOrdersController::class, 'index'])->name('admin.orders.pending.index');
    Route::get('admin/complete-orders', [CompleteOrdersController::class, 'index'])->name('admin.orders.complete.index');
    Route::get('admin/cancel-orders', [CancelOrdersController::class, 'index'])->name('admin.orders.cancel.index');
    Route::get('admin/online-transaction', [OnlineTransactionController::class, 'index'])->name('admin.transaction.index');
    Route::get('admin/customer-review', [CustomerReviewController::class, 'index'])->name('admin.review.index');
    
    Route::get('/admin/order/modify-order', [OrderModifyController::class, 'index'])->name('admin.orders.modify.index');
    Route::post('/admin/order/override/modify-order', [OrderModifyController::class, 'overrideOrder'])->name('admin.orders.modify.overrideOrder');
    Route::post('/admin/order/cancel/modify-order', [OrderModifyController::class, 'cancelBulkOrder'])->name('admin.orders.modify.cancelOrder');
    
    // Add this to your routes/web.php
    Route::post('/admin/orders/accept', [OrderStatusController::class, 'accept'])->name('admin.orders.accept.submit');
    Route::post('/admin/cancel/orders', [OrderStatusController::class, 'cancel'])->name('admin.orders.cancel.submit');
    // Transfer routes
    Route::get('admin/transfer-order/{order_id}', [OrderTransferController::class, 'showTransferForm'])->name('admin.orders.transfer.orderTransfer');
    Route::post('/admin/transfer-order', [OrderTransferController::class, 'transferOrder'])->name('admin.orders.transfer.orderTransfer.submit');

    Route::get('/admin/modify-order/{order_id}', [PendingOrdersController::class, 'modify'])->name('admin.orders.pending.modify');

    Route::post('/admin/orders/pending/search-product', [PendingOrdersController::class, 'searchProduct'])->name('admin.orders.pending.searchProduct');
    Route::post('/admin/orders/pending/manage-products', [PendingOrdersController::class, 'manageProducts'])->name('admin.orders.pending.manageProducts');
    Route::post('/admin/orders/pending/save-changes', [PendingOrdersController::class, 'saveChanges'])->name('admin.orders.pending.saveChanges');

    
    Route::get('/admin/export-all-products', [AllProductsController::class, 'exportAllProducts'])->name('admin.export_all_products');
    Route::get('/admin/export-online-transaction', [OrderReportController::class, 'onlineTransactionExport'])->name('admin.export_online_transaction');
    Route::get('/admin/export-pending-orders', [OrderReportController::class, 'exportPendingOrders'])->name('admin.export_pending_orders');
    Route::get('/admin/export-complete-orders', [OrderReportController::class, 'exportCompleteOrders'])->name('admin.export_complete_orders');
    Route::get('/admin/export-cancel-orders', [OrderReportController::class, 'exportCancelOrders'])->name('admin.export_cancel_orders');
    Route::get('/admin/export-all-customers', [allCustomerReportController::class, 'exportAllCustomers'])->name('admin.export_all_customers');

    Route::get('/admin/export-order-reports', [OrderReportController::class, 'ordereReportExport'])->name('admin.report.download');
    Route::get('/admin/order-report', [OrderReportController::class, 'index'])->name('admin.orders.report.index');


    Route::get('/admin/announcements/create', [AnnouncementController::class, 'index'])->name('admin.announcements.create');
    Route::post('/admin/announcement-store', [AnnouncementController::class, 'store'])->name('admin.announcement.store');

    Route::get('/admin/announcement-list', [AnnouncementController::class, 'announcements'])->name('admin.announcement.list');
    Route::get('/admin/announcement-edit/{id}', [AnnouncementController::class, 'edit'])->name('admin.announcement.edit');
    Route::put('/admin/announcement-update/{id}', [AnnouncementController::class, 'update'])->name('admin.announcement.update');
    
    Route::get('/admin/banner-create', [ImagesController::class, 'index'])->name('admin.images.banner.index');
    Route::get('/admin/banner', [ImagesController::class, 'view'])->name('admin.images.banner.view');
    Route::post('/admin/banner-store', [ImagesController::class, 'store'])->name('admin.images.banner.store');
    Route::get('/admin/product-images', [ImagesController::class, 'productList'])->name('admin.images.product_images.index');
    Route::post('/admin/product-import', [ImagesController::class, 'import'])->name('admin.images.product_images.import');
    Route::delete('/admin/banner-image/{id}', [ImagesController::class, 'delete'])->name('admin.images.banner.delete');
    
    Route::get('/admin/category-subcategory', [CategorySubCatController::class, 'showCategoryWithSubCategory'])->name('admin.groups.index');




});