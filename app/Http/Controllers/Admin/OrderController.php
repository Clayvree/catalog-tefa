<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TefaUnit;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $managedUnit = $request->user()->managedUnits()->first() ?? TefaUnit::first();
        $unitId = $managedUnit?->id;

        $query = Order::with(['items.catalogItem', 'user'])->where('tefa_unit_id', $unitId);

        if ($request->filled('type')) {
            $query->where('order_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            if ($request->payment_status === 'outstanding') {
                // Belum lunas = belum bayar atau baru DP (pesanan batal tidak dihitung)
                $query->whereIn('payment_status', ['unpaid', 'dp_paid'])->where('status', '!=', 'cancelled');
            } else {
                $query->where('payment_status', $request->payment_status);
            }
        }

        if ($request->filled('q')) {
            $term = trim((string) $request->q);
            // Nomor pesanan "TF-YYMMDD-XXXXX" -> cocokkan 5 karakter terakhir UUID
            $suffix = preg_match('/^TF-\d{6}-([0-9A-F]{5})$/i', $term, $m) ? strtolower($m[1]) : null;

            $query->where(function ($q) use ($term, $suffix) {
                $q->where('customer_name', 'like', "%{$term}%")
                  ->orWhere('customer_contact', 'like', "%{$term}%");

                if ($suffix) {
                    $q->orWhere('id', 'like', "%{$suffix}");
                }
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => Order::where('tefa_unit_id', $unitId)->count(),
            'pending' => Order::where('tefa_unit_id', $unitId)->where('status', 'pending')->count(),
            'delivery' => Order::where('tefa_unit_id', $unitId)->where('fulfillment_method', 'delivery')->count(),
            'pickup' => Order::where('tefa_unit_id', $unitId)->where('fulfillment_method', 'pickup_at_tefa')->count(),
            'digital' => Order::where('tefa_unit_id', $unitId)->where('order_type', 'digital')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'managedUnit', 'stats'));
    }

    public function show(Request $request, Order $order)
    {
        $this->authorizeUnit($request, $order);
        $order->load(['items.catalogItem', 'user', 'tefaUnit']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorizeUnit($request, $order);
        $validated = $request->validate([
            'status' => 'required|in:pending,processed,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', "Status pesanan #{$order->id} berhasil diubah ke {$validated['status']}!");
    }

    public function updateShipping(Request $request, Order $order)
    {
        $this->authorizeUnit($request, $order);
        $validated = $request->validate([
            'shipping_cost' => 'required|numeric|min:0',
        ]);
        $order->update(['shipping_cost' => $validated['shipping_cost']]);
        return redirect()->back()->with('success', "Ongkos kirim pesanan #{$order->id} berhasil diupdate!");
    }

    public function confirmPayment(Request $request, Order $order)
    {
        $this->authorizeUnit($request, $order);
        $validated = $request->validate([
            'payment_status' => 'required|in:unpaid,dp_paid,paid,expired,refunded',
        ]);

        $updates = ['payment_status' => $validated['payment_status']];

        if ($validated['payment_status'] === 'dp_paid') {
            $updates['status'] = 'processed';
            if (!$order->fulfillment_status) {
                $updates['fulfillment_status'] = \App\Enums\FulfillmentStatus::InProgressService;
            }
        } elseif ($validated['payment_status'] === 'paid' && (!$order->fulfillment_status || $order->fulfillment_status === \App\Enums\FulfillmentStatus::InProgressService)) {
            if ($order->isDigital()) {
                $updates['status'] = 'completed';
                $updates['fulfillment_status'] = \App\Enums\FulfillmentStatus::DownloadReady;
            } elseif ($order->isService()) {
                $updates['status'] = 'processed';
                $updates['fulfillment_status'] = \App\Enums\FulfillmentStatus::InProgressService;
            } else {
                $updates['status'] = 'processed';
                $updates['fulfillment_status'] = \App\Enums\FulfillmentStatus::Packing;
            }
        }

        $order->update($updates);

        return redirect()->back()->with('success', "Status pembayaran pesanan #{$order->id} berhasil diperbarui!");
    }

    public function updateFulfillment(Request $request, Order $order)
    {
        $this->authorizeUnit($request, $order);
        $validated = $request->validate([
            'fulfillment_status' => 'required|string',
            'estimated_ready_at' => 'nullable|date',
            'tracking_number'    => 'nullable|string|max:255',
            'fulfillment_notes'  => 'nullable|string',
        ]);

        $updates = [
            'fulfillment_status' => $validated['fulfillment_status'],
            'estimated_ready_at' => $validated['estimated_ready_at'] ?? null,
            'tracking_number'    => $validated['tracking_number'] ?? null,
            'fulfillment_notes'  => $validated['fulfillment_notes'] ?? null,
        ];

        // Jika status fulfillment = selesai/diterima/diambil, set order status ke completed
        if (in_array($validated['fulfillment_status'], ['delivered', 'picked_up', 'completed'])) {
            $updates['status'] = 'completed';
        }

        $order->update($updates);

        return redirect()->back()->with('success', "Status pengiriman/progress pesanan #{$order->id} berhasil diperbarui!");
    }

    private function authorizeUnit(Request $request, Order $order): void
    {
        $user = $request->user();
        if ($user->role?->value === 'superadmin') {
            return;
        }

        $managedUnitIds = $user->managedUnits()->pluck('tefa_units.id')->toArray();
        if (!in_array($order->tefa_unit_id, $managedUnitIds, true)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk pesanan unit jurusan ini.');
        }
    }
}
