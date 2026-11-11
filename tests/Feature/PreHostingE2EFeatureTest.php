<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ItemStatus;
use App\Enums\ItemType;
use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Enums\UserRole;
use App\Models\CatalogItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\Task;
use App\Models\TefaUnit;
use App\Models\User;
use App\Models\WorkerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PreHostingE2EFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $superadmin;
    private User $adminJurusan;
    private User $workerUser;
    private User $customer;
    private TefaUnit $unit;
    private Category $category;
    private CatalogItem $product;
    private CatalogItem $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->unit = TefaUnit::create([
            'name' => 'Unit RPL TEFA',
            'slug' => 'rpl-tefa',
            'description' => 'Unit produksi perangkat lunak',
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Web Development',
            'type' => ItemType::Jasa,
        ]);

        $this->superadmin = User::factory()->create([
            'name' => 'Super Administrator',
            'role' => UserRole::SuperAdmin,
            'phone' => '081111111111',
        ]);

        $this->adminJurusan = User::factory()->create([
            'name' => 'Admin RPL',
            'role' => UserRole::AdminJurusan,
            'phone' => '081222222222',
        ]);
        $this->adminJurusan->managedUnits()->attach($this->unit->id);

        $this->workerUser = User::factory()->create([
            'name' => 'Siswa RPL 1',
            'role' => UserRole::Worker,
            'phone' => '081333333333',
        ]);
        WorkerProfile::create([
            'user_id' => $this->workerUser->id,
            'tefa_unit_id' => $this->unit->id,
            'nisn' => '1234567890',
            'class_name' => 'XII RPL 1',
        ]);

        $this->customer = User::factory()->create([
            'name' => 'Budi Pelanggan',
            'role' => UserRole::Public,
            'phone' => '081444444444',
        ]);

        $this->product = CatalogItem::create([
            'tefa_unit_id' => $this->unit->id,
            'category_id' => $this->category->id,
            'title' => 'Flashdisk Bootable Windows',
            'slug' => 'flashdisk-bootable',
            'description' => 'Flashdisk 32GB dengan OS siap install.',
            'price' => 120000,
            'stock' => 15,
            'track_stock' => true,
            'item_type' => ItemType::Produk,
            'fulfillment_type' => 'both',
            'status' => ItemStatus::Published,
        ]);

        $this->service = CatalogItem::create([
            'tefa_unit_id' => $this->unit->id,
            'category_id' => $this->category->id,
            'title' => 'Pembuatan Website Landing Page',
            'slug' => 'website-landing-page',
            'description' => 'Jasa pembuatan landing page responsif.',
            'price' => 750000,
            'stock' => 0,
            'track_stock' => false,
            'item_type' => ItemType::Jasa,
            'status' => ItemStatus::Published,
        ]);
    }

    /**
     * TEST 1: Public Portal Routes Render Tanpa Error 500
     */
    public function test_public_pages_render_successfully(): void
    {
        $this->get('/')->assertStatus(200)->assertSee('TefaHub');
        $this->get('/tentang-kami')->assertStatus(200);
        $this->get('/contact')->assertStatus(200);
        $this->get('/jurusan')->assertStatus(200)->assertSee($this->unit->name);
        $this->get('/produk')->assertStatus(200)->assertSee($this->product->title);
        $this->get('/tefa/' . $this->unit->slug)->assertStatus(200);
        $this->get('/tefa/' . $this->unit->slug . '/katalog')->assertStatus(200)->assertSee($this->product->title);
        $this->get('/tefa/' . $this->unit->slug . '/portofolio')->assertStatus(200);
        $this->get('/sitemap.xml')->assertStatus(200)->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
    }

    /**
     * TEST 2: Alur Pemesanan Produk Fisik (Checkout -> Order -> Invoice -> Bayar)
     */
    public function test_customer_can_order_physical_product_and_simulate_payment(): void
    {
        // 1. Customer buka halaman checkout
        $this->actingAs($this->customer)
            ->get('/pesan/' . $this->product->slug)
            ->assertStatus(200)
            ->assertSee($this->product->title);

        // 2. Customer submit order
        $orderData = [
            'quantity' => 2,
            'fulfillment_method' => 'delivery',
            'shipping_address' => 'Jl. Merdeka No. 10',
            'shipping_city' => 'Tanjungpinang',
            'payment_method' => 'qris',
            'notes' => 'Tolong bungkus rapi dengan bubble wrap.',
        ];

        $response = $this->actingAs($this->customer)
            ->post('/pesan/' . $this->product->slug, $orderData);

        // Harus redirect ke halaman invoice
        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'customer_name' => $this->customer->name,
            'fulfillment_method' => 'delivery',
            'payment_status' => 'unpaid',
        ]);

        $order = Order::where('customer_name', $this->customer->name)->first();
        $this->assertNotNull($order);

        // Stok produk harus berkurang (15 - 2 = 13)
        $this->assertEquals(13, $this->product->fresh()->stock);

        // 3. Customer buka invoice
        $this->actingAs($this->customer)
            ->get('/pesanan/invoice/' . $order->id)
            ->assertStatus(200)
            ->assertSee($this->customer->name);

        // 4. Admin konfirmasi pembayaran
        $this->actingAs($this->adminJurusan)
            ->patch('/admin/orders/' . $order->id . '/payment', ['payment_status' => 'paid'])
            ->assertRedirect();

        $this->assertEquals('paid', $order->fresh()->payment_status);

        // 5. Cek di daftar pesanan customer
        $this->actingAs($this->customer)
            ->get('/pesanan-saya')
            ->assertStatus(200)
            ->assertSee($this->product->title);
    }

    /**
     * TEST 3: Alur Konsultasi & Nego Jasa (Nego -> Proyek Pipeline -> Redirect WhatsApp)
     */
    public function test_customer_can_consult_and_nego_service(): void
    {
        $this->actingAs($this->customer)
            ->get('/jasa/' . $this->service->slug . '/nego')
            ->assertStatus(200)
            ->assertSee($this->service->title);

        $negoData = [
            'client_name' => 'PT Maju Bersama',
            'client_contact' => '081999888777',
            'project_brief' => 'Kebutuhan landing page company profile 5 halaman.',
            'budget_expectation' => 700000,
            'target_deadline' => now()->addDays(20)->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->customer)
            ->post('/jasa/' . $this->service->slug . '/nego', $negoData);

        // Harus redirect ke tautan WhatsApp API
        $response->assertRedirect();
        $targetUrl = $response->headers->get('Location');
        $this->assertStringContainsString('wa.me', $targetUrl);

        // Pipeline Proyek harus otomatis tercatat di database dengan nama instansi/klien yang diinputkan
        $this->assertDatabaseHas('projects', [
            'client_name' => 'PT Maju Bersama',
            'tefa_unit_id' => $this->unit->id,
        ]);
    }

    /**
     * TEST 4: Modul SuperAdmin (Dashboard, Unit, Kategori, Admins, Laporan)
     */
    public function test_superadmin_full_management_access(): void
    {
        // 1. Dashboard SuperAdmin
        $this->actingAs($this->superadmin)
            ->get('/superadmin/dashboard')
            ->assertStatus(200)
            ->assertSee('Ringkasan Data TEFA');

        // 2. Kelola Kategori
        $this->actingAs($this->superadmin)
            ->from('/superadmin/categories')
            ->post('/superadmin/categories', [
                'name' => 'Mobile Development',
                'type' => 'jasa',
            ])->assertRedirect('/superadmin/categories');

        $this->assertDatabaseHas('categories', ['name' => 'Mobile Development']);

        // 3. Stats Report & Catalog Brochure
        $this->actingAs($this->superadmin)
            ->get('/superadmin/stats-report')
            ->assertStatus(200);

        $this->actingAs($this->superadmin)
            ->get('/superadmin/catalog-brochure')
            ->assertStatus(200);
    }

    /**
     * TEST 5: Modul Admin Jurusan (Dashboard, Produk CRUD, Orders, Status Update)
     */
    public function test_admin_jurusan_order_and_product_fulfillment(): void
    {
        Storage::fake('public');

        // 1. Dashboard Admin Jurusan
        $this->actingAs($this->adminJurusan)
            ->get('/admin/dashboard')
            ->assertStatus(200)
            ->assertSee($this->unit->name);

        // 2. Tambah Produk Baru
        $file = UploadedFile::fake()->image('produk_baru.jpg');
        $this->actingAs($this->adminJurusan)
            ->from('/admin/products')
            ->post('/admin/products', [
                'title' => 'Mouse Gaming TEFA',
                'category_ids' => [$this->category->id],
                'price' => 150000,
                'stock' => 20,
                'track_stock' => '1',
                'item_type' => 'produk',
                'fulfillment_type' => 'shipping_only',
                'thumbnail_file' => $file,
                'ai_context' => 'Mouse gaming presisi tinggi karya siswa TEFA.',
                'status' => 'published',
            ])->assertRedirect('/admin/products');

        $this->assertDatabaseHas('catalog_items', ['title' => 'Mouse Gaming TEFA']);

        // 3. Update Status & Resi Pesanan
        $order = Order::create([
            'tefa_unit_id' => $this->unit->id,
            'customer_name' => 'Pak Ahmad',
            'status' => 'pending',
            'order_type' => 'physical',
            'fulfillment_method' => 'delivery',
        ]);

        // Admin konfirmasi pembayaran
        $this->actingAs($this->adminJurusan)
            ->patch('/admin/orders/' . $order->id . '/payment', ['payment_status' => 'paid'])
            ->assertRedirect();
        $this->assertEquals('paid', $order->fresh()->payment_status);

        // Admin update status proses
        $this->actingAs($this->adminJurusan)
            ->patch('/admin/orders/' . $order->id . '/status', ['status' => 'processed'])
            ->assertRedirect();
        $this->assertEquals('processed', $order->fresh()->status);

        // Admin update pemenuhan resi pengiriman
        $this->actingAs($this->adminJurusan)
            ->patch('/admin/orders/' . $order->id . '/fulfillment', [
                'fulfillment_status' => 'shipped',
                'tracking_number' => 'TEFA-EXP-123456',
                'fulfillment_notes' => 'Paket sudah diserahkan ke kurir.',
            ])->assertRedirect();

        $this->assertEquals('TEFA-EXP-123456', $order->fresh()->tracking_number);
    }

    /**
     * TEST 6: Modul Siswa / Worker (Task Monitoring, Upload Bukti, Submit Portofolio)
     */
    public function test_worker_can_complete_task_and_submit_portfolio(): void
    {
        Storage::fake('public');

        $workerProfile = $this->workerUser->workerProfile;

        $project = Project::create([
            'tefa_unit_id' => $this->unit->id,
            'title' => 'Revamp Web SMKN 4',
            'client_name' => 'Pihak Sekolah',
            'status' => ProjectStatus::Active,
            'created_by' => $this->adminJurusan->id,
        ]);

        $task = Task::create([
            'project_id' => $project->id,
            'tefa_unit_id' => $this->unit->id,
            'title' => 'Buat Wireframe Mockup',
            'leader_id' => $workerProfile->id,
            'status' => TaskStatus::InProgress,
        ]);

        // 1. Worker buka dashboard dan task
        $this->actingAs($this->workerUser)
            ->get('/worker/dashboard')
            ->assertStatus(200);

        $this->actingAs($this->workerUser)
            ->get('/worker/tasks/' . $task->id)
            ->assertStatus(200)
            ->assertSee('Buat Wireframe Mockup');

        // 2. Worker upload bukti tugas
        $proofFile = UploadedFile::fake()->create('wireframe.pdf', 1000);
        $this->actingAs($this->workerUser)
            ->from('/worker/tasks/' . $task->id)
            ->post('/worker/tasks/' . $task->id . '/proof', [
                'proof_file' => $proofFile,
                'proof_notes' => 'Mockup 5 halaman selesai.',
            ])->assertRedirect('/worker/tasks/' . $task->id);

        $this->assertEquals(TaskStatus::Review, $task->fresh()->status);
        $this->assertNotNull($task->fresh()->proof_file_url);

        // 3. Worker submit karya portofolio
        $thumbnail = UploadedFile::fake()->image('karya.png');
        $this->actingAs($this->workerUser)
            ->from('/worker/portfolios')
            ->post('/worker/portfolios', [
                'title' => 'Aplikasi Kasir SMKN 4',
                'description' => 'Aplikasi POS desktop berbasis Electron & Laravel API.',
                'thumbnail_file' => $thumbnail,
            ])->assertRedirect('/worker/portfolios');

        $this->assertDatabaseHas('portfolios', [
            'title' => 'Aplikasi Kasir SMKN 4',
            'worker_profile_id' => $workerProfile->id,
            'status' => 'pending',
        ]);
    }

    /**
     * TEST 7: Akses Keamanan & Otorisasi Antar Role (RBAC)
     */
    public function test_role_based_access_controls_prevent_unauthorized_access(): void
    {
        // 1. Tamu (Guest) mencoba akses halaman terproteksi
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/superadmin/dashboard')->assertRedirect('/login');
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/worker/dashboard')->assertRedirect('/login');

        // 2. Customer biasa mencoba akses area admin
        $this->actingAs($this->customer)
            ->get('/admin/dashboard')
            ->assertStatus(403);

        $this->actingAs($this->customer)
            ->get('/superadmin/dashboard')
            ->assertStatus(403);

        $this->actingAs($this->customer)
            ->get('/worker/dashboard')
            ->assertStatus(403);

        // 3. Siswa (Worker) mencoba akses area SuperAdmin
        $this->actingAs($this->workerUser)
            ->get('/superadmin/dashboard')
            ->assertStatus(403);

        // 4. Customer tidak boleh akses atau bayar invoice orang lain
        $otherCustomer = User::factory()->create(['role' => UserRole::Public, 'phone' => '081555555555']);
        $otherOrder = Order::create([
            'tefa_unit_id' => $this->unit->id,
            'user_id' => $otherCustomer->id,
            'customer_name' => 'Orang Lain',
            'payment_status' => 'unpaid',
        ]);

        $this->actingAs($this->customer)
            ->get('/pesanan/invoice/' . $otherOrder->id)
            ->assertStatus(403);

        $this->actingAs($this->customer)
            ->post('/pesanan/invoice/' . $otherOrder->id . '/pay')
            ->assertStatus(403);
    }
}
