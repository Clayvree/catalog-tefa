<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CatalogItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Project;
use App\Models\TefaUnit;
use App\Enums\ItemType;
use App\Enums\ProjectStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use App\Enums\UserRole;
use App\Models\User;

class PublicOrderController extends Controller
{
    public function checkout(string $slug)
    {
        $item = CatalogItem::with(['tefaUnit', 'category'])->where('slug', $slug)->firstOrFail();

        // If it's a Jasa, redirect to Nego page instead of direct checkout!
        if ($item->item_type === ItemType::Jasa) {
            return redirect()->route('jasa.nego', $slug);
        }

        if ($item->item_type !== ItemType::Jasa && $item->track_stock && $item->stock < 1) {
            return redirect()->route('produk.list')->with('error', 'Produk tersebut sedang habis.');
        }

        return view('public.checkout', compact('item'));
    }

    public function store(Request $request, string $slug)
    {
        $item = CatalogItem::with('tefaUnit')->where('slug', $slug)->firstOrFail();

        if ($item->item_type === ItemType::Jasa) {
            return redirect()->route('jasa.nego', $slug);
        }

        // Auto-fill from logged-in user
        $user = auth()->user();
        $request->merge([
            'customer_name' => $user->name,
            'customer_contact' => $user->whatsapp_number ?? $user->phone ?? '-',
        ]);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_contact' => 'required|string|max:50',
            'fulfillment_method' => ['required', 'string', \Illuminate\Validation\Rule::in(['delivery', 'pickup_at_tefa', 'digital_download', 'onsite_service'])],
            'shipping_address' => 'nullable|required_if:fulfillment_method,delivery|string',
            'shipping_city' => 'nullable|required_if:fulfillment_method,delivery|string|max:100',
            'payment_method' => 'required|string',
            'quantity' => 'required|integer|min:1|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $order = DB::transaction(function () use ($validated, $slug) {
            $item = CatalogItem::where('slug', $slug)->lockForUpdate()->firstOrFail();

            if ($item->track_stock && $item->stock < (int) $validated['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => "Stok {$item->title} tersisa {$item->stock}. Silakan kurangi jumlah pembelian.",
                ]);
            }

            $quantity = (int) $validated['quantity'];
            $unitPrice = (float) $item->price;
            $shippingCost = 0; // Akan diupdate Admin
            $totalPrice = ($unitPrice * $quantity);
            $orderType = ($item->item_type === ItemType::Digital || $item->fulfillment_type === 'digital_download')
                ? 'digital'
                : 'physical';

            $isFree = ($totalPrice <= 0);

            $order = Order::create([
                'id' => (string) Str::uuid(),
                'tefa_unit_id' => $item->tefa_unit_id,
                'user_id' => auth()->id() ?? null,
                'customer_name' => $validated['customer_name'],
                'customer_contact' => $validated['customer_contact'],
                'order_type' => $orderType,
                'fulfillment_method' => $validated['fulfillment_method'],
                'shipping_address' => $validated['shipping_address'] ?? null,
                'shipping_city' => $validated['shipping_city'] ?? null,
                'shipping_courier' => ($validated['fulfillment_method'] === 'delivery') ? 'Kurir TEFA Express' : null,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $isFree ? 'paid' : 'unpaid',
                'status' => $isFree ? 'completed' : 'pending',
                'fulfillment_status' => ($isFree && $orderType === 'digital') ? \App\Enums\FulfillmentStatus::DownloadReady : null,
                'digital_access_token' => $orderType === 'digital' ? Str::random(32) : null,
                'notes' => $validated['notes'] ?? null,
                'total_price' => $totalPrice,
                'shipping_cost' => $shippingCost,
                'order_date' => now(),
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'catalog_item_id' => $item->id,
                'item_title' => $item->title,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'subtotal' => $unitPrice * $quantity,
                'price_at_purchase' => $unitPrice,
            ]);

            if ($item->track_stock) {
                $item->decrement('stock', $quantity);
            }

            return $order;
        });

        return redirect()->route('order.invoice', $order->id)->with('success', 'Pesanan Anda berhasil dibuat!');
    }

    public function invoice(Order $order)
    {
        // Cegah akses tidak sah: hanya pemilik pesanan atau admin yang dapat melihat invoice
        if ($order->user_id && auth()->check() && $order->user_id !== auth()->id() && !in_array(auth()->user()?->role?->value, ['superadmin', 'admin_jurusan'])) {
            abort(403, 'Akses invoice tidak diizinkan.');
        }

        $order->load(['items.catalogItem', 'tefaUnit']);
        return view('public.invoice', compact('order'));
    }

    public function simulatePayment(Order $order)
    {
        $user = auth()->user();
        $isAdmin = $user && ($user->role?->value === 'superadmin' || ($user->role?->value === 'admin_jurusan' && in_array($order->tefa_unit_id, $user->managedUnits()->pluck('tefa_units.id')->toArray(), true)));

        if (!$isAdmin) {
            abort(403, 'Aksi tidak diizinkan: Konfirmasi pelunasan pembayaran hanya dapat dilakukan oleh Admin TEFA.');
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => ($order->status === 'pending') ? 'processed' : $order->status,
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi oleh Admin! Pesanan segera diproses.');
    }

    public function downloadDigital(string $token)
    {
        $order = Order::where('digital_access_token', $token)->firstOrFail();

        if ($order->payment_status !== 'paid') {
            abort(403, 'Akses unduhan ditolak: Pesanan belum berstatus lunas.');
        }

        $item = $order->items->first()?->catalogItem;
        if (!$item || empty($item->digital_file_url)) {
            abort(404, 'File digital belum tersedia atau tautan belum dikonfigurasi.');
        }

        if (filter_var($item->digital_file_url, FILTER_VALIDATE_URL)) {
            return redirect()->away($item->digital_file_url);
        }

        $cleanPath = ltrim(str_replace('/storage/', '', $item->digital_file_url), '/');
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($cleanPath)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->download($cleanPath);
        }

        return redirect()->away($item->digital_file_url);
    }

    public function nego(string $slug)
    {
        $item = CatalogItem::with(['tefaUnit.admins', 'category'])->where('slug', $slug)->firstOrFail();
        if ($item->item_type !== ItemType::Jasa && $item->item_type !== 'kegiatan') {
            abort(404, 'Negosiasi hanya tersedia untuk layanan kategori Jasa.');
        }

        $admin = $item->tefaUnit->admins->first();
        return view('public.jasa_nego', compact('item', 'admin'));
    }

    public function submitNego(Request $request, string $slug)
    {
        $item = CatalogItem::with(['tefaUnit.admins'])->where('slug', $slug)->firstOrFail();
        if ($item->item_type !== ItemType::Jasa && $item->item_type !== 'kegiatan') {
            abort(404, 'Negosiasi hanya tersedia untuk layanan kategori Jasa.');
        }

        $admin = $item->tefaUnit->admins->first();
        $user = auth()->user();

        // Defaultkan dari user jika tidak diisi
        if (!$request->filled('client_name')) {
            $request->merge(['client_name' => $user->name]);
        }
        if (!$request->filled('client_contact')) {
            $request->merge(['client_contact' => $user->whatsapp_number ?? $user->phone ?? '-']);
        }

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_contact' => 'required|string|max:50',
            'project_brief' => 'required|string|max:2000',
            'budget_expectation' => 'nullable|numeric|min:10000|max:100000000',
            'target_deadline' => 'nullable|date|after_or_equal:today',
        ]);

        // Catat sebagai Proyek (Draft) dan Pesanan (Pending) sekaligus
        DB::transaction(function () use ($validated, $item, $admin) {
            $project = Project::create([
                'id' => (string) Str::uuid(),
                'tefa_unit_id' => $item->tefa_unit_id,
                'created_by' => $admin?->id ?? auth()->id() ?? (string) Str::uuid(),
                'title' => "Pengajuan Jasa: {$item->title} - {$validated['client_name']}",
                'client_name' => $validated['client_name'],
                'client_contact' => $validated['client_contact'],
                'description' => "Permintaan Konsultasi & Nego untuk layanan {$item->title}.\n\nBrief Kebutuhan Klien:\n{$validated['project_brief']}\n\nEkspektasi Budget: Rp" . number_format((float)($validated['budget_expectation'] ?? $item->price), 0, ',', '.'),
                'estimated_price' => $validated['budget_expectation'] ?? $item->price,
                'final_price' => $validated['budget_expectation'] ?? $item->price,
                'deadline' => $validated['target_deadline'] ?? now()->addDays(14),
                'status' => ProjectStatus::Draft,
            ]);

            $order = Order::create([
                'id' => (string) Str::uuid(),
                'tefa_unit_id' => $item->tefa_unit_id,
                'user_id' => auth()->id() ?? null,
                'project_id' => $project->id, // Tautkan pesanan ke proyek
                'customer_name' => $validated['client_name'],
                'customer_contact' => $validated['client_contact'],
                'order_type' => 'service',
                'fulfillment_method' => 'onsite_service',
                'payment_method' => 'wa',
                'payment_status' => 'unpaid',
                'notes' => $validated['project_brief'],
                'total_price' => $project->estimated_price,
                'shipping_cost' => 0,
                'status' => 'pending',
                'order_date' => now(),
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'catalog_item_id' => $item->id,
                'item_title' => $item->title,
                'unit_price' => $project->estimated_price,
                'quantity' => 1,
                'subtotal' => $project->estimated_price,
                'price_at_purchase' => $project->estimated_price,
            ]);
        });

        // Buat Template Pesan WhatsApp ke Admin Jurusan
        $superAdmin = User::where('role', UserRole::SuperAdmin)->first();
        // Admin terhubung via pivot tefa_unit_user, bukan kolom langsung di users
        $admin = $item->tefaUnit->admins->first();
        $adminPhone = $admin->phone ?? $superAdmin->phone; // Default WhatsApp TEFA
        $deadlineText = !empty($validated['target_deadline']) ? "\nTarget selesai: " . \Carbon\Carbon::parse($validated['target_deadline'])->format('d/m/Y') : "";
        $budgetFormatted = "Rp" . number_format((float)($validated['budget_expectation'] ?? $item->price), 0, ',', '.');
        $message = urlencode("Halo, saya {$validated['client_name']}. Saya tadi isi form di TEFAHUB untuk {$item->title}.\n\n*PENGAJUAN KEBUTUHAN – TEFAHUB*\nLayanan: {$item->title}\nNama/Instansi: {$validated['client_name']}\nKontak: {$validated['client_contact']}\nRincian kebutuhan: {$validated['project_brief']}\nEkspektasi budget: {$budgetFormatted}{$deadlineText}\n\nMohon informasi ketersediaan jadwal & penawaran resmi. Terima kasih!");

        $waUrl = "https://wa.me/{$adminPhone}?text={$message}";

        return redirect()->away($waUrl);
    }

        public function myOrders(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $query = Order::with(['tefaUnit', 'items.catalogItem'])
            ->where('user_id', auth()->id())
            ->latest('order_date');

        if ($request->has('type') && in_array($request->type, ['physical', 'digital', 'service'])) {
            $query->where('order_type', $request->type);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('public.my_orders', compact('orders'));
    }
}


