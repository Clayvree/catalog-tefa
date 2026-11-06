<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ItemStatus;
use App\Enums\ItemType;
use App\Enums\ProjectStatus;
use App\Models\CatalogItem;
use App\Models\Category;
use App\Models\Project;
use App\Models\Task;
use App\Models\TefaUnit;
use App\Models\User;
use App\Models\WaImportDraft;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecuritySeoAndCleanupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * BUKTI 1: Security Headers aktif di semua HTTP Response
     */
    public function test_security_headers_are_present_on_responses(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $this->assertNotEmpty($response->headers->get('Content-Security-Policy'));
    }

    /**
     * BUKTI 2: SEO Meta Tags & OpenGraph terpasang lengkap
     */
    public function test_seo_meta_tags_and_opengraph_exist_in_public_html(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('<meta name="keywords"', false);
        $response->assertSee('<meta name="robots" content="index, follow">', false);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('<meta property="og:title"', false);
        $response->assertSee('<meta property="og:description"', false);
        $response->assertSee('<meta name="twitter:card"', false);
    }

    /**
     * BUKTI 3: Robots.txt & Dynamic Sitemap.xml berfungsi
     */
    public function test_robots_txt_and_dynamic_sitemap_xml(): void
    {
        // Cek file robots.txt
        $robotsContent = file_get_contents(public_path('robots.txt'));
        $this->assertStringContainsString('Disallow: /admin/', $robotsContent);
        $this->assertStringContainsString('Disallow: /superadmin/', $robotsContent);
        $this->assertStringContainsString('Sitemap: /sitemap.xml', $robotsContent);

        // Cek endpoint dynamic sitemap.xml
        $sitemapResponse = $this->get('/sitemap.xml');
        $sitemapResponse->assertStatus(200);
        $sitemapResponse->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $sitemapResponse->assertSee('<urlset', false);
        $sitemapResponse->assertSee('<loc>', false);
    }

    /**
     * BUKTI 4: Throttling / Rate Limiting pada API Chat & Form Email Contact
     */
    public function test_throttling_protects_contact_email_against_spam(): void
    {
        RateLimiter::clear('127.0.0.1');

        // Limit contact form adalah 5 per menit
        for ($i = 0; $i < 5; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])->post('/contact', [
                'name' => 'Spammer',
                'email' => 'spam@test.com',
                'subject' => 'Spam ' . $i,
                'message' => 'Test message payload',
            ]);
        }

        // Request ke-6 harus ditolak dengan HTTP 429 Too Many Requests
        $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])->post('/contact', [
            'name' => 'Spammer',
            'email' => 'spam@test.com',
            'subject' => 'Spam overflow',
            'message' => 'Should be blocked',
        ]);

        $response->assertStatus(429);
    }

    /**
     * BUKTI 5: CRUD Gambar Lama Terhapus Otomatis - CatalogItem (Produk/Jasa)
     */
    public function test_old_image_deleted_when_catalog_item_is_updated_and_deleted(): void
    {
        Storage::fake('public');

        $unit = TefaUnit::create([
            'name' => 'Unit RPL',
            'slug' => 'unit-rpl',
            'is_active' => true,
        ]);

        // 1. Simpan file gambar pertama
        $file1 = UploadedFile::fake()->image('produk1.jpg');
        $path1 = $file1->store('products', 'public');
        Storage::disk('public')->assertExists($path1);

        $item = CatalogItem::create([
            'tefa_unit_id' => $unit->id,
            'title' => 'Produk A',
            'slug' => 'produk-a',
            'price' => 50000,
            'item_type' => ItemType::Produk,
            'status' => ItemStatus::Published,
            'thumbnail_url' => '/storage/' . $path1,
        ]);

        // 2. Update dengan file gambar kedua
        $file2 = UploadedFile::fake()->image('produk2.jpg');
        $path2 = $file2->store('products', 'public');
        Storage::disk('public')->assertExists($path2);

        $item->update([
            'thumbnail_url' => '/storage/' . $path2,
        ]);

        // Gambar lama ($path1) HARUS sudah terhapus dari storage
        Storage::disk('public')->assertMissing($path1);
        Storage::disk('public')->assertExists($path2);

        // 3. Hapus item -> Gambar kedua ($path2) juga HARUS terhapus dari storage
        $item->delete();
        Storage::disk('public')->assertMissing($path2);
    }

    /**
     * BUKTI 6: CRUD Gambar Lama Terhapus Otomatis - Category
     */
    public function test_old_image_deleted_when_category_is_updated_and_deleted(): void
    {
        Storage::fake('public');

        $file1 = UploadedFile::fake()->image('cat1.png');
        $path1 = $file1->store('categories', 'public');
        Storage::disk('public')->assertExists($path1);

        $category = Category::create([
            'name' => 'Desain Grafis',
            'type' => ItemType::Jasa,
            'image' => $path1,
        ]);

        // Update gambar baru
        $file2 = UploadedFile::fake()->image('cat2.png');
        $path2 = $file2->store('categories', 'public');

        $category->update(['image' => $path2]);

        // File 1 harus lenyap
        Storage::disk('public')->assertMissing($path1);
        Storage::disk('public')->assertExists($path2);

        // Hapus category -> File 2 harus lenyap
        $category->delete();
        Storage::disk('public')->assertMissing($path2);
    }

    /**
     * BUKTI 7: CRUD Gambar Lama Terhapus Otomatis - TefaUnit (Banner & Logo)
     */
    public function test_old_banner_and_logo_deleted_when_tefa_unit_is_updated_and_deleted(): void
    {
        Storage::fake('public');

        $banner1 = UploadedFile::fake()->image('banner1.jpg')->store('units', 'public');
        $logo1 = UploadedFile::fake()->image('logo1.png')->store('units', 'public');

        $unit = TefaUnit::create([
            'name' => 'RPL Studio',
            'slug' => 'rpl-studio',
            'banner_url' => '/storage/' . $banner1,
            'logo_url' => '/storage/' . $logo1,
            'is_active' => true,
        ]);

        // Update dengan banner baru
        $banner2 = UploadedFile::fake()->image('banner2.jpg')->store('units', 'public');
        $unit->update(['banner_url' => '/storage/' . $banner2]);

        // Banner lama harus hilang, banner baru tetap ada, logo1 tetap ada
        Storage::disk('public')->assertMissing($banner1);
        Storage::disk('public')->assertExists($banner2);
        Storage::disk('public')->assertExists($logo1);

        // Hapus unit
        $unit->delete();
        Storage::disk('public')->assertMissing($banner2);
        Storage::disk('public')->assertMissing($logo1);
    }

    /**
     * BUKTI 8: CRUD Berkas Lama Terhapus Otomatis - Task (Proof File)
     */
    public function test_old_proof_file_deleted_when_task_is_updated_and_deleted(): void
    {
        Storage::fake('public');

        $unit = TefaUnit::create([
            'name' => 'Unit DKV',
            'slug' => 'unit-dkv',
            'is_active' => true,
        ]);
        $admin = User::factory()->create(['role' => 'admin_jurusan', 'phone' => '081234567891']);
        $project = Project::create([
            'tefa_unit_id' => $unit->id,
            'title' => 'Web App Client',
            'client_name' => 'PT Sukses',
            'status' => ProjectStatus::Active,
            'created_by' => $admin->id,
        ]);

        $proof1 = UploadedFile::fake()->create('proof1.pdf', 500)->store('task_proofs', 'public');
        Storage::disk('public')->assertExists($proof1);

        $task = Task::create([
            'project_id' => $project->id,
            'tefa_unit_id' => $unit->id,
            'title' => 'Slicing UI',
            'proof_file_url' => '/storage/' . $proof1,
        ]);

        // Siswa reupload bukti baru
        $proof2 = UploadedFile::fake()->create('proof2.pdf', 800)->store('task_proofs', 'public');
        $task->update(['proof_file_url' => '/storage/' . $proof2]);

        // Bukti 1 harus terhapus dari storage
        Storage::disk('public')->assertMissing($proof1);
        Storage::disk('public')->assertExists($proof2);

        // Hapus task -> Bukti 2 harus terhapus
        $task->delete();
        Storage::disk('public')->assertMissing($proof2);
    }

    /**
     * BUKTI 9: Berkas Draft WA Import Terhapus Otomatis - WaImportDraft
     */
    public function test_wa_chat_draft_file_deleted_when_draft_is_deleted(): void
    {
        Storage::fake('local');

        $user = User::factory()->create(['phone' => '081234567892']);
        $unit = TefaUnit::create([
            'name' => 'Unit TKJ',
            'slug' => 'unit-tkj',
            'is_active' => true,
        ]);

        $path = 'whatsapp_imports/test_chat.txt';
        Storage::disk('local')->put($path, 'Sample chat messages from WA');
        Storage::disk('local')->assertExists($path);

        $draft = WaImportDraft::create([
            'tefa_unit_id' => $unit->id,
            'uploaded_by' => $user->id,
            'chat_file_path' => $path,
            'status' => 'processing',
        ]);

        $draft->delete();

        // File chat .txt harus otomatis terhapus
        Storage::disk('local')->assertMissing($path);
    }
}
