<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KnowledgeBaseController as AdminKnowledgeBaseController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\WhatsAppImportController;
use App\Http\Controllers\Admin\WorkerController as AdminWorkerController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicOrderController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\KnowledgeBaseController as SuperAdminKnowledgeBaseController;
use App\Http\Controllers\SuperAdmin\TefaUnitController;
use App\Http\Controllers\SuperAdmin\AdminUserController;
use App\Http\Controllers\SuperAdmin\CategoryController;
use App\Http\Controllers\SuperAdmin\ProjectController as SuperAdminProjectController;
use App\Http\Controllers\Worker\DashboardController as WorkerDashboardController;
use App\Http\Controllers\Worker\TaskController;
use App\Http\Controllers\Worker\PortfolioController as WorkerPortfolioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailerController;

// --- EMAIL COMPOSER ---
Route::get("email", [MailerController::class, "email"])->name("email");
Route::post("send-email", [MailerController::class, "composeEmail"])->name("send-email");

// --- PUBLIC PORTAL ---
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/tentang-kami', [PublicController::class, 'about'])->name('about');

Route::get('/contact', function () {
    return view('public.contact');
})->name('contact');

// PROSES KIRIM EMAIL FORM CONTACT (TAMBAHKAN INI)
Route::post('/contact', [MailerController::class, 'composeEmail'])->name('contact.send');

Route::get('/jurusan', [PublicController::class, 'jurusanList'])->name('jurusan.list');
Route::get('/produk', [PublicController::class, 'produkList'])->name('produk.list');

Route::get('/tefa/{slug}', [PublicController::class, 'storefront'])->name('tefa.storefront');
Route::get('/tefa/{slug}/katalog', [PublicController::class, 'catalog'])->name('tefa.catalog');
Route::get('/tefa/{slug}/portofolio', [PublicController::class, 'portfolio'])->name('tefa.portfolio');

// --- AUTHENTICATED CHECKOUT, PAYMENT & ORDER FLOW ---
Route::middleware('auth')->group(function () {
    Route::get('/pesan/{slug}', [PublicOrderController::class, 'checkout'])->name('order.checkout');
    Route::post('/pesan/{slug}', [PublicOrderController::class, 'store'])->name('order.store');
    Route::get('/pesanan/invoice/{order}', [PublicOrderController::class, 'invoice'])->name('order.invoice');
    Route::post('/pesanan/invoice/{order}/pay', [PublicOrderController::class, 'simulatePayment'])->name('order.pay.simulate');
    Route::get('/pesanan-saya', [PublicOrderController::class, 'myOrders'])->name('order.my_orders');

    // --- KONSULTASI & NEGO HARGA JASA TEFA ---
    Route::get('/jasa/{slug}/nego', [PublicOrderController::class, 'nego'])->name('jasa.nego');
    Route::post('/jasa/{slug}/nego', [PublicOrderController::class, 'submitNego'])->name('jasa.nego.submit');
});

// --- AI CHAT WIDGET ---
Route::post('/api/ai/chat', [AiChatController::class, 'message'])->name('api.ai.chat');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        $role = auth()->user()->role?->value;
        return match($role) {
            'superadmin'    => redirect()->route('superadmin.dashboard'),
            'admin_jurusan' => redirect()->route('admin.dashboard'),
            'worker'        => redirect()->route('worker.dashboard'),
            default         => redirect()->route('home'),
        };
    })->name('dashboard');

    // --- SUPERADMIN MODULE ---
    Route::middleware('role:superadmin')->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/projects', [SuperAdminProjectController::class, 'index'])->name('projects.index');
        Route::resource('units', TefaUnitController::class)->except(['show', 'create', 'edit']);
        Route::resource('admins', AdminUserController::class)->except(['show', 'create', 'edit']);
        Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('knowledge', SuperAdminKnowledgeBaseController::class)->only(['index', 'store', 'update', 'destroy']);
    });

    // --- ADMIN JURUSAN MODULE ---
    Route::middleware(['role:admin_jurusan', 'tefa_access'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // WhatsApp Import
        Route::get('/projects/import-wa', [WhatsAppImportController::class, 'create'])->name('projects.import-wa.create');
        Route::post('/projects/import-wa', [WhatsAppImportController::class, 'store'])->name('projects.import-wa.store');
        Route::get('/projects/import-wa/{draft}/review', [WhatsAppImportController::class, 'review'])->name('projects.import-wa.review');
        Route::post('/projects/import-wa/{draft}/confirm', [WhatsAppImportController::class, 'confirm'])->name('projects.import-wa.confirm');
        
        // Projects Monitoring
        Route::resource('projects', AdminProjectController::class);
        Route::patch('/projects/{project}/status', [AdminProjectController::class, 'updateStatus'])->name('projects.status.update');
        Route::patch('/tasks/{task}', [AdminProjectController::class, 'updateTask'])->name('tasks.update');
        Route::patch('/tasks/{task}/approve', [AdminProjectController::class, 'approveTask'])->name('tasks.approve');
        
        // Products & Catalog CRUD (Khusus Admin Jurusan)
        Route::resource('products', AdminProductController::class);
        
        // Orders & Fulfillment Management
        Route::resource('orders', AdminOrderController::class)->only(['index', 'show']);
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status.update');
        Route::patch('/orders/{order}/payment', [AdminOrderController::class, 'confirmPayment'])->name('orders.payment.confirm');
        Route::patch('/orders/{order}/shipping', [AdminOrderController::class, 'updateShipping'])->name('orders.shipping.update');
        Route::patch('/orders/{order}/fulfillment', [AdminOrderController::class, 'updateFulfillment'])->name('orders.fulfillment.update');
        
        // Workers CRUD
        Route::resource('workers', AdminWorkerController::class);

        // AI Knowledge Base (Konteks per Jurusan)
        Route::resource('knowledge', AdminKnowledgeBaseController::class)->only(['index', 'store', 'update', 'destroy']);

        // Portofolio (Review & Approve)
        Route::resource('portfolios', \App\Http\Controllers\Admin\PortfolioController::class)->only(['index', 'show', 'update', 'destroy']);
    });

    // --- WORKER / SISWA MODULE ---
    Route::middleware(['role:worker', 'tefa_access'])->prefix('worker')->name('worker.')->group(function () {
        Route::get('/dashboard', [WorkerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status.update');
        Route::patch('/tasks/{task}/notes', [TaskController::class, 'updateNotes'])->name('tasks.notes.update');
        Route::post('/tasks/{task}/proof', [TaskController::class, 'uploadProof'])->name('tasks.proof.upload');
        Route::resource('portfolios', WorkerPortfolioController::class)->only(['index', 'store', 'destroy']);
    });
});

require __DIR__.'/auth.php';