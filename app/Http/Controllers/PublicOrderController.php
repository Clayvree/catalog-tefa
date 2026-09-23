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

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_contact' => 'required|string|max:50',
            'fulfillment_method' => 'required|string',
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
                'payment_status' => 'unpaid',
                'digital_access_token' => $orderType === 'digital' ? Str::random(32) : null,
                'notes' => $validated['notes'] ?? null,
                'total_price' => $totalPrice,
                'shipping_cost' => $shippingCost,
                'status' => 'pending',
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
        $order->load(['items.catalogItem', 'tefaUnit']);
        return view('public.invoice', compact('order'));
    }

    

    public function nego(string $slug)
    {
        $item = CatalogItem::with(['tefaUnit.admins', 'category'])->where('slug', $slug)->firstOrFail();
        $admin = $item->tefaUnit->admins->first();
        return view('public.jasa_nego', compact('item', 'admin'));
    }

    public function submitNego(Request $request, string $slug)
    {
        $item = CatalogItem::with(['tefaUnit.admins'])->where('slug', $slug)->firstOrFail();
        $admin = $item->tefaUnit->admins->first();

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_contact' => 'required|string|max:50',
            'project_brief' => 'required|string|max:2000',
            'budget_expectation' => 'nullable|numeric|min:0',
            'target_deadline' => 'nullable|date',
        ]);

        // Catat sebagai Calon Proyek Masuk ke database Admin Jurusan
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

        // Buat Template Pesan WhatsApp ke Admin Jurusan
        $admin = User::where('role', UserRole::AdminJurusan)->where('tefa_unit_id', $item->tefa_unit_id)->first();
        $adminPhone = $admin->phone ?? '628164104669'; // Default WhatsApp TEFA
        $message = urlencode("Halo Admin TEFA {$item->tefaUnit->name},\n\nSaya ingin berkonsultasi & nego harga untuk layanan *{$item->title}*.\n\n*Nama Klien:* {$validated['client_name']}\n*Kontak:* {$validated['client_contact']}\n*Kebutuhan:* {$validated['project_brief']}\n*Estimasi Budget:* Rp" . number_format((float)($validated['budget_expectation'] ?? $item->price), 0, ',', '.') . "\n\nMohon informasi ketersediaan jadwal & penawaran resmi. Terima kasih!");

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


