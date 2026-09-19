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
            $query->where('payment_status', $request->payment_status);
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

    public function show(Order $order)
    {
        $order->load(['items.catalogItem', 'user', 'tefaUnit']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processed,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', "Status pesanan #{$order->id} berhasil diubah ke {$validated['status']}!");
    }

    public function confirmPayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:unpaid,paid,expired,refunded',
        ]);

        $updates = ['payment_status' => $validated['payment_status']];

        if ($validated['payment_status'] === 'paid' && !$order->fulfillment_status) {
            if ($order->isDigital()) {
                $updates['fulfillment_status'] = \App\Enums\FulfillmentStatus::DownloadReady;
            } elseif ($order->isService()) {
                $updates['fulfillment_status'] = \App\Enums\FulfillmentStatus::InProgressService;
            } else {
                $updates['fulfillment_status'] = \App\Enums\FulfillmentStatus::Packing;
            }
        }

        $order->update($updates);

        return redirect()->back()->with('success', "Status pembayaran pesanan #{$order->id} berhasil diperbarui!");
    }

    public function updateFulfillment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'fulfillment_status' => 'required|string',
            'estimated_ready_at' => 'nullable|date',
            'tracking_number'    => 'nullable|string|max:255',
            'fulfillment_notes'  => 'nullable|string',
        ]);

        $order->update([
            'fulfillment_status' => $validated['fulfillment_status'],
            'estimated_ready_at' => $validated['estimated_ready_at'],
            'tracking_number'    => $validated['tracking_number'],
            'fulfillment_notes'  => $validated['fulfillment_notes'],
        ]);

        return redirect()->back()->with('success', "Status pengiriman/progress pesanan #{$order->id} berhasil diperbarui!");
    }
}