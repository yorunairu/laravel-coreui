<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\tables\Basic as TablesBasic;

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\M_currencyController;
use App\Http\Controllers\M_oumController;
use App\Http\Controllers\ListTenderController;
use App\Http\Controllers\Select2Controller;
use App\Http\Controllers\GetDataController;
use App\Http\Controllers\M_termController;
use App\Http\Controllers\M_delpointController;
use App\Http\Controllers\PaymentStatusController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

// Main Page Route
// Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('roles', RolesController::class)->names('roles');
Route::resource('users', UsersController::class)->names('users');
Auth::routes();

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login/submit', [LoginController::class, 'login'])->name('login.submit');

// Logout Routes
Route::post('/logout/submit', [LoginController::class, 'logout'])->name('logout.submit');

// layout
Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');

// cards
Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

// User Interface
Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

// extended ui
Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

// icons
Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');

// form elements
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

// form layouts
Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

// tables
Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');


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


// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// Route::resource('/sales', SalesController::class, '');
Route::get('/list-tender', [ListTenderController::class, 'index'])->name('list-tender.index');
Route::get('/list-tender-post', [ListTenderController::class, 'postIndex'])->name('list-tender.post');
Route::get('/list-tender/detail-tender/{id}', [ListTenderController::class, 'detailTender'])->name('list-tender.detail-tender');

// SALES
Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
Route::get('/sales/material-list/{id}', [SalesController::class, 'materialList'])->name('sales.material-list');
Route::get('/sales/create', [SalesController::class, 'create'])->name('sales.create');
Route::post('/sales/store', [SalesController::class, 'store'])->name('sales.store');
Route::post('/sales/store-material/{id}', [SalesController::class, 'storeMaterial'])->name('sales.store-material');
Route::post('/sales/material/store', [SalesController::class, 'updateMaterial'])->name('tender.details.store');
Route::get('/sales/{id}/edit/{type}', [SalesController::class, 'edit'])->name('sales.edit');
Route::put('/sales/update/{id}/{type}', [SalesController::class, 'update'])->name('sales.update');
Route::put('/sales/material/{id}/{type}', [SalesController::class, 'updateMaterial'])->name('material.update');
// Route::put('/sales/update/{id}/{type}', [SalesController::class, 'updateMaterial'])->name('detail.update');
Route::delete('/sales/destroy/{id}', [SalesController::class, 'destroy'])->name('sales.destroy');
Route::delete('/sales/material-destroy/{id}', [SalesController::class, 'materialDestroy'])->name('sales.material-destroy');
Route::post('/sales/final/{id}', [SalesController::class, 'final'])->name('sales.final');
Route::post('/sales/update-price-principle-kz/{id}/{type}', [SalesController::class, 'updatePricePrincipleKz'])->name('sales.update-price-principle-kz');
Route::post('/sales/update-review/{id}', [SalesController::class, 'updateReview'])->name('sales.update-review');
Route::get('/sales/review-prices/{id}', [SalesController::class, 'reviewPrices'])->name('sales.review-prices');
Route::get('/sales/detail-tender/{id}', [SalesController::class, 'detailTender'])->name('sales.detail-tender');
Route::get('/sales/detail-tender-post/{id}', [SalesController::class, 'detailTenderPost'])->name('sales.detail-tender-post');
// Route::get('/sales/detail-tender/{id}', [SalesController::class, 'showForm'])->name('sales.pdf-input');
Route::get('/sales/quotation-to-customer-pdf/{id}', [SalesController::class, 'quotationToCustomerPdf'])->name('sales.quotation-to-customer-pdf');
Route::post('/sales/quotation-to-customer-pdf/{id}/{role?}', [SalesController::class, 'quotationToCustomerPdf'])->name('sales.quotation-to-customer-pdf-post');
Route::get('/sales/activity/{id}', [SalesController::class, 'activityList'])->name('sales.activity-list');
Route::post('/sales/activity/store/{id}', [SalesController::class, 'activityStore'])->name('sales.activity-store');
Route::delete('/sales/activity/destroy/{id}', [SalesController::class, 'activityDestroy'])->name('sales.activity-destroy');
// Route::get('/sales/activity/{id}/edit', [SalesController::class, 'activityEdit'])->name('activity.edit');
// Route::put('/sales/activity{id}', [SalesController::class, 'activityUpdate'])->name('activity.destroy');
Route::get('/default-values', [SalesController::class, 'getDefaultValues'])->name('default.values');
Route::post('/sales/store-po/{id}', [SalesController::class, 'poCustomerStore'])->name('sales.po-customer');
Route::get('/sales-detail-tender-post/{id}', [SalesController::class, 'detailTenderPo'])->name('sales.detail-tender-po');
Route::get('/sales/po-customer-list/{id}', [SalesController::class, 'poCustomerList'])->name('sales.po-customer-list');
Route::get('/sales-form-update-price-material', [SalesController::class, 'formUpdatePriceMaterial'])->name('sales.form-update-price-material');

// REPORT
Route::get('/tender-margin', [ReportController::class, 'tenderMargin'])->name('tender-margin-post.index');
Route::get('/tender-margin-par', [ReportController::class, 'tenderMarginPra'])->name('tender-margin-pra.index');
Route::get('/bank-guarantee', [ReportController::class, 'bankGuarantee'])->name('bank-guarantee.index');
Route::get('/bank-guarantee', [ReportController::class, 'BankGuarantee'])->name('bank-guarantee.index');
// END

Route::get('/payment/', [PaymentStatusController::class, 'index'])->name('payment.index');


Route::get('/pdf', function() {
    return view('procurement.po-to-principle');
});

// CUSTOMER
Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
// Route::get('/customer/edit', [CustomerController::class, 'edit'])->name('customer.edit');
Route::put('/customer/{id}/update', [CustomerController::class, 'update'])->name('customer.update');
Route::get('/customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
Route::delete('/customer/destroy/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
// END CUSTOMER


// PRINCIPLE
Route::get('/principle', [PrincipalController::class, 'index'])->name('principle.index');
Route::get('/principle/create', [PrincipalController::class, 'create'])->name('principle.create');
Route::post('/principle/store', [PrincipalController::class, 'store'])->name('principle.store');
Route::put('/principle/{id}/update', [PrincipalController::class, 'update'])->name('principle.update');
Route::get('/principle/{id}/edit', [PrincipalController::class, 'edit'])->name('principle.edit');
Route::delete('/principle/destroy/{id}', [PrincipalController::class, 'destroy'])->name('principle.destroy');
// END PRINCIPLE


// MASTER DATA UOM
Route::get('/uom', [M_oumController::class, 'index'])->name('uom.index');
Route::get('/uom/create', [M_oumController::class, 'create'])->name('uom.create');
Route::post('/uom/store', [M_oumController::class, 'store'])->name('uom.store');
Route::put('/uom/{id}', [M_oumController::class, 'update'])->name('uom.update');
Route::get('/uom/{id}/edit', [M_oumController::class, 'edit'])->name('uom.edit');
Route::delete('/uom/destroy/{id}', [M_oumController::class, 'destroy'])->name('uom.destroy');
// END MASTER DATA UOM


// MASTER DATA CURRENCY
Route::get('/currency', [M_currencyController::class, 'index'])->name('currency.index');
Route::get('/currency/create', [M_currencyController::class, 'create'])->name('currency.create');
Route::post('/currency/store', [M_currencyController::class, 'store'])->name('currency.store');
Route::put('/currency/{id}', [M_currencyController::class, 'update'])->name('currency.update');
Route::get('/currency/{id}/edit', [M_currencyController::class, 'edit'])->name('currency.edit');
Route::delete('/currency/destroy/{id}', [M_currencyController::class, 'destroy'])->name('currency.destroy');
// END MASTER DATA CURRENCY


// MASTER DATA DELPOINT //
Route::get('/delivery-point', [M_delpointController::class, 'index'])->name('delivery-point.index');
Route::get('/delivery-point/create', [M_delpointController::class, 'create'])->name('delivery-point.create');
Route::post('/delivery-point/store', [M_delpointController::class, 'store'])->name('delivery-point.store');
Route::put('/delivery-point/{id}', [M_delpointController::class, 'update'])->name('delivery-point.update');
Route::get('/delivery-point/{id}/edit', [M_delpointController::class, 'edit'])->name('delivery-point.edit');
Route::delete('/delivery-point/destroy/{id}', [M_delpointController::class, 'destroy'])->name('delivery-point.destroy');
// END MASTER DELPOINT


// MASTER DATA COMPWITH //
Route::get('/term', [M_termController::class, 'index'])->name('term.index');
Route::get('/term/create', [M_termController::class, 'create'])->name('term.create');
Route::post('/term/store', [M_termController::class, 'store'])->name('term.store');
Route::put('/term/{id}', [M_termController::class, 'update'])->name('term.update');
Route::get('/term/{id}/edit', [M_termController::class, 'edit'])->name('term.edit');
Route::delete('/term/destroy/{id}', [M_termController::class, 'destroy'])->name('term.destroy');
// END MASTER DELPOINT


// FINANCE
Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
// END FINANCE


// PROCUREMENT
Route::get('/procurement', [ProcurementController::class, 'index'])->name('procurement.index');
Route::get('/procurement/create-rfq', [ProcurementController::class, 'createRfq'])->name('procurement.create-rfq');
Route::get('/procurement/create-po', [ProcurementController::class, 'createPo'])->name('procurement.create-po');
Route::get('/procurement/generate-rfq/{id}', [ProcurementController::class, 'generateRfq'])->name('procurement.generate-rfq');
Route::get('/procurement/detail/{id}', [ProcurementController::class, 'detail'])->name('procurement.detail');
Route::post('/procurement/store-rfq/{id}/{rfq_id?}', [ProcurementController::class, 'storeRfq'])->name('procurement.store-rfq');
Route::get('/procurement/rfq-list/{id}', [ProcurementController::class, 'rfqList'])->name('procurement.rfq-list');
// Route::get('/procurement/procurement-list/{id}', [ProcurementController::class, 'principleList'])->name('procurement.principle-list');
Route::get('/procurement/rfq-to-principle-pdf/{id}', [ProcurementController::class, 'rfqToPrinciplePdf'])->name('procurement.rfq-to-principle-pdf');
Route::delete('/procurement/destroy-principle/{id}', [ProcurementController::class, 'destroyPrinciple'])->name('procurement.destroy-principle');
Route::post('/procurement/winner-principle/{id}', [ProcurementController::class, 'winnerPrinciple'])->name('procurement.winner-principle');
Route::post('/procurement/update-date-delivery/{id}', [ProcurementController::class, 'updateDateDelivery'])->name('procurement.update-date-delivery');
Route::post('/procurement/update-payment-method/{id}', [ProcurementController::class, 'updatePaymentMethod'])->name('procurement.update-payment-method');
Route::post('/procurement/update-price-from-principle/{id}', [ProcurementController::class, 'updatePriceFromPrinciple'])->name('procurement.update-price-from-principle');
Route::post('/procurement/update-doc-quo-from-principle/{id}', [ProcurementController::class, 'updateDocQuoFromPrinciple'])->name('procurement.update-doc-quo-from-principle');
Route::get('/procurement/detail-tender/{id}', [ProcurementController::class, 'detailTender'])->name('procurement.detail-tender');
Route::get('/procurement/detail-tender-post/{id}', [ProcurementController::class, 'detailTenderPost'])->name('procurement.detail-tender-post');

Route::get('/procurement/material-list/{id}', [ProcurementController::class, 'materialList'])->name('procurement.material-list');
Route::post('/procurement/store-material/{id}', [ProcurementController::class, 'storeMaterial'])->name('procurement.store-material');
Route::post('/procurement/material/store', [ProcurementController::class, 'updateMaterial'])->name('procurement.tender.details.store');
Route::get('/procurement/{id}/edit/{type}', [ProcurementController::class, 'edit'])->name('procurement.edit');
Route::put('/procurement/material/{id}/{type}', [ProcurementController::class, 'updateMaterial'])->name('procurement.material.update');
Route::delete('/procurement/material-destroy/{id}', [ProcurementController::class, 'materialDestroy'])->name('procurement.material-destroy');
Route::get('/procurement/review-prices/{id}', [ProcurementController::class, 'reviewPrices'])->name('procurement.review-prices');
Route::post('/procurement/send-rfq/{id}', [ProcurementController::class, 'sendRfq'])->name('procurement.send-rfq');
Route::post('/procurement/send-quo/{id}/{role}', [ProcurementController::class, 'sendQuo'])->name('procurement.send-quo');
Route::get('/procurement/po-customer-list/{id}', [ProcurementController::class, 'poCustomerList'])->name('procurement.po-customer-list');
Route::post('/procurement/store-po/{id}', [ProcurementController::class, 'poCustomerStore'])->name('procurement.po-customer');
Route::get('/procurement/detail-tender-post/{id}', [ProcurementController::class, 'detailTenderPo'])->name('procurement.detail-tender-po');
Route::post('/procurement/po-to-principle-pdf/{id}', [ProcurementController::class, 'poToPrinciplePdf'])->name('procurement.po-to-principle-pdf');
Route::get('/procurement/view-pdf-po/{id}', [ProcurementController::class, 'viewPdf'])->name('procurement.view-pdf');
Route::get('/procurement/view-principle-po/{id}', [ProcurementController::class, 'viewPdf'])->name('procurement.princple-pdf');


Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
Route::get('/finance/detail/{id}', [FinanceController::class, 'detail'])->name('finance.detail');
Route::get('/finance/detail-tender/{id}', [FinanceController::class, 'detailTender'])->name('finance.detail-tender');

// Route::post('/sales/store-po/{id}', [SalesController::class, 'poCustomerStore'])->name('sales.po-customer');

// END PROCIREMENT


Route::group(['prefix' => 'select2'], function () {
    Route::get('/cusprin/{type}', [Select2Controller::class, 'getCusPrin'])->name('select2.getCusPrin');
    Route::get('/get-currency', [Select2Controller::class, 'getCurrency'])->name('select2.getCurrency');
    Route::get('/get-uom', [Select2Controller::class, 'getUom'])->name('select2.getUom');
    Route::get('/get-material', [Select2Controller::class, 'getMaterialCode'])->name('select2.getMaterialCode');
    Route::get('/get-delivery-point', [Select2Controller::class, 'getDeliveryPoint'])->name('select2.getDeliveryPoint');
    Route::get('/get-term-delivery-point', [Select2Controller::class, 'getTermDeliveryPoint'])->name('select2.getTermDeliveryPoint');
    Route::get('/get-term-comp', [Select2Controller::class, 'getTermComp'])->name('select2.getTermComp');
    Route::get('/get-term-for-master-only/{category?}', [Select2Controller::class, 'getTermForMasterOnly'])->name('select2.get-term-for-master-only');
});

Route::group(['prefix' => 'fetch'], function () {
    Route::get('/get-tender-log/{id}', [GetDataController::class, 'getTenderLog'])->name('fetch.get-tender-log');
});

/**
 * Admin routes
 */

// Forget Password Routes
// Route::get('/password/reset', 'Auth\ForgetPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('/password/reset/submit', 'Auth\ForgetPasswordController@reset')->name('password.update');
