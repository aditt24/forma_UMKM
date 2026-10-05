<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StaffAccountController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\VariantController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (Auth::check()) {
        $user = Auth::user();

        return match ($user->tipe_akun) {
            'CUSTOMER' => redirect()->route('customer.dashboard'),
            'KASIR' => redirect()->route('kasir.dashboard'),
            'ADMIN' => redirect()->route('admin.dashboard'),
            default => abort(403),
        };
    }

    $products = DB::table('produk as p')
        ->where('p.status_produk', 'AKTIF')
        ->whereExists(function ($query) {
            $query->selectRaw('1')
                ->from('varian_produk as v')
                ->whereColumn('v.id_produk', 'p.id_produk')
                ->where('v.stok', '>', 0);
        })
        ->select('p.*')
        ->orderByDesc('p.created_at')
        ->limit(4)
        ->get();

    return view('home', compact('products'));

})->name('home');

/*
|--------------------------------------------------------------------------
| Authentication — one login page for all roles
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.process');

    Route::get('/register', [LoginController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [LoginController::class, 'register'])
        ->name('register.process');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Customer
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:CUSTOMER'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/', [CustomerController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/katalog', [CustomerController::class, 'catalog'])
            ->name('catalog');

        Route::get('/promo', [CustomerController::class, 'promotions'])
            ->name('promotions');

        Route::get('/produk/{id}', [CustomerController::class, 'product'])
            ->name('product');

        Route::get('/keranjang', [CartController::class, 'index'])
            ->name('cart');

        Route::post('/keranjang', [CartController::class, 'add'])
            ->name('cart.add');

        Route::patch('/keranjang/{id}', [CartController::class, 'update'])
            ->name('cart.update');

        Route::delete('/keranjang/{id}', [CartController::class, 'remove'])
            ->name('cart.remove');

        Route::delete('/keranjang', [CartController::class, 'clear'])
            ->name('cart.clear');

        Route::get('/checkout', [CheckoutController::class, 'show'])
            ->name('checkout');

        Route::post('/checkout', [CheckoutController::class, 'store'])
            ->name('checkout.store');

        Route::get('/pembayaran/{id}', [CheckoutController::class, 'payment'])
            ->name('payment');

        Route::post('/pembayaran/{id}/berhasil', [CheckoutController::class, 'pay'])
            ->name('payment.pay');

        Route::post('/pembayaran/{id}/gagal', [CheckoutController::class, 'fail'])
            ->name('payment.fail');

        Route::get('/pesanan', [CustomerOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/pesanan/{id}', [CustomerOrderController::class, 'show'])
            ->name('orders.show');

        Route::patch('/pesanan/{id}/batal', [CustomerOrderController::class, 'cancel'])
            ->name('orders.cancel');

        Route::get('/profil', [CustomerProfileController::class, 'edit'])
            ->name('profile');

        Route::put('/profil', [CustomerProfileController::class, 'update'])
            ->name('profile.update');
    });

/*
|--------------------------------------------------------------------------
| Kasir
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:KASIR'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {
        Route::get('/', [CashierController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/riwayat', [CashierController::class, 'history'])
            ->name('history');

        Route::get('/pos', [PosController::class, 'index'])
            ->name('pos');

        Route::post('/pos/item', [PosController::class, 'add'])
            ->name('pos.add');

        Route::patch('/pos/item/{id}', [PosController::class, 'update'])
            ->name('pos.update');

        Route::delete('/pos/item/{id}', [PosController::class, 'remove'])
            ->name('pos.remove');

        Route::post('/pos/member', [PosController::class, 'member'])
            ->name('pos.member');

        Route::delete('/pos/member', [PosController::class, 'clearMember'])
            ->name('pos.member.clear');

        Route::delete('/pos', [PosController::class, 'clear'])
            ->name('pos.clear');

        Route::post('/pos/checkout', [PosController::class, 'checkout'])
            ->name('pos.checkout');

        Route::get('/struk/{id}', [PosController::class, 'receipt'])
            ->name('receipt');
    });

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:ADMIN'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Accounts
        Route::get('/akun', [StaffAccountController::class, 'index'])
            ->name('accounts.index');
        Route::get('/akun/tambah', [StaffAccountController::class, 'create'])
            ->name('accounts.create');
        Route::post('/akun', [StaffAccountController::class, 'store'])
            ->name('accounts.store');
        Route::get('/akun/{id}/edit', [StaffAccountController::class, 'edit'])
            ->name('accounts.edit');
        Route::put('/akun/{id}', [StaffAccountController::class, 'update'])
            ->name('accounts.update');
        Route::patch('/akun/{id}/status', [StaffAccountController::class, 'toggleStatus'])
            ->name('accounts.status');

        // Categories
        Route::get('/kategori', [CategoryController::class, 'index'])
            ->name('categories.index');
        Route::post('/kategori', [CategoryController::class, 'store'])
            ->name('categories.store');
        Route::put('/kategori/{id}', [CategoryController::class, 'update'])
            ->name('categories.update');
        Route::delete('/kategori/{id}', [CategoryController::class, 'destroy'])
            ->name('categories.destroy');

        // Products
        Route::get('/produk', [ProductController::class, 'index'])
            ->name('products.index');
        Route::get('/produk/tambah', [ProductController::class, 'create'])
            ->name('products.create');
        Route::post('/produk', [ProductController::class, 'store'])
            ->name('products.store');
        Route::get('/produk/{id}/edit', [ProductController::class, 'edit'])
            ->name('products.edit');
        Route::put('/produk/{id}', [ProductController::class, 'update'])
            ->name('products.update');
        Route::delete('/produk/{id}', [ProductController::class, 'destroy'])
            ->name('products.destroy');

        // Variants
        Route::get('/produk/{idProduk}/varian', [VariantController::class, 'index'])
            ->name('variants.index');
        Route::post('/produk/{idProduk}/varian', [VariantController::class, 'store'])
            ->name('variants.store');
        Route::put('/varian/{id}', [VariantController::class, 'update'])
            ->name('variants.update');
        Route::delete('/varian/{id}', [VariantController::class, 'destroy'])
            ->name('variants.destroy');

        // Suppliers
        Route::get('/supplier', [SupplierController::class, 'index'])
            ->name('suppliers.index');
        Route::get('/supplier/tambah', [SupplierController::class, 'create'])
            ->name('suppliers.create');
        Route::post('/supplier', [SupplierController::class, 'store'])
            ->name('suppliers.store');
        Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit'])
            ->name('suppliers.edit');
        Route::put('/supplier/{id}', [SupplierController::class, 'update'])
            ->name('suppliers.update');

        // Product purchases / restock
        Route::get('/pembelian', [PurchaseController::class, 'index'])
            ->name('purchases.index');
        Route::get('/pembelian/tambah', [PurchaseController::class, 'create'])
            ->name('purchases.create');
        Route::post('/pembelian', [PurchaseController::class, 'store'])
            ->name('purchases.store');
        Route::get('/pembelian/{id}', [PurchaseController::class, 'show'])
            ->name('purchases.show');
        Route::patch('/pembelian/{id}/terima', [PurchaseController::class, 'receive'])
            ->name('purchases.receive');
        Route::patch('/pembelian/{id}/batal', [PurchaseController::class, 'cancel'])
            ->name('purchases.cancel');

        // Sales monitoring
        Route::get('/penjualan', [SalesController::class, 'index'])
            ->name('sales.index');
        Route::get('/penjualan/{id}', [SalesController::class, 'show'])
            ->name('sales.show');
        Route::patch('/penjualan/{id}/status', [SalesController::class, 'updateStatus'])
            ->name('sales.status');

        // Promotions
        Route::get('/promo', [PromoController::class, 'index'])
            ->name('promos.index');
        Route::get('/promo/tambah', [PromoController::class, 'create'])
            ->name('promos.create');
        Route::post('/promo', [PromoController::class, 'store'])
            ->name('promos.store');
        Route::get('/promo/{id}/edit', [PromoController::class, 'edit'])
            ->name('promos.edit');
        Route::put('/promo/{id}', [PromoController::class, 'update'])
            ->name('promos.update');

        // Operational expenses
        Route::get('/pengeluaran', [ExpenseController::class, 'index'])
            ->name('expenses.index');
        Route::get('/pengeluaran/tambah', [ExpenseController::class, 'create'])
            ->name('expenses.create');
        Route::post('/pengeluaran', [ExpenseController::class, 'store'])
            ->name('expenses.store');
        Route::get('/pengeluaran/{id}/edit', [ExpenseController::class, 'edit'])
            ->name('expenses.edit');
        Route::put('/pengeluaran/{id}', [ExpenseController::class, 'update'])
            ->name('expenses.update');

        // Reports
        Route::get('/laporan', [ReportController::class, 'index'])
            ->name('reports.index');
    });
