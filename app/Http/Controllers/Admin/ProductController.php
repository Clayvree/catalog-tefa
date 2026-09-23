<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogItem;
use App\Models\Category;
use App\Models\TefaUnit;
use App\Enums\ItemStatus;
use App\Enums\ItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $managedUnit = $request->user()->managedUnits()->first() ?? TefaUnit::first();
        $unitId = $managedUnit?->id;

        $query = CatalogItem::with(['category', 'categories'])->forUnit($unitId);

        if ($request->filled('type')) {
            $query->where('item_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $keyword = trim((string) $request->q);
            $query->where('title', 'like', "%{$keyword}%");
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::whereNull('tefa_unit_id')->orWhere('tefa_unit_id', $unitId)->orderBy('name')->get();

        $stats = [
            'total' => CatalogItem::forUnit($unitId)->count(),
            'physical' => CatalogItem::forUnit($unitId)->where('item_type', ItemType::Produk->value)->count(),
            'service' => CatalogItem::forUnit($unitId)->whereIn('item_type', [ItemType::Jasa->value, 'kegiatan'])->count(),
            'digital' => CatalogItem::forUnit($unitId)->where('item_type', ItemType::Digital->value)->count(),
            'published' => CatalogItem::forUnit($unitId)->where('status', ItemStatus::Published->value)->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'managedUnit', 'stats'));
    }

    public function store(Request $request)
    {
        $managedUnit = $request->user()->managedUnits()->first() ?? TefaUnit::first();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'item_type' => ['required', new Enum(ItemType::class)],
            'fulfillment_type' => 'nullable|string|in:shipping_only,pickup_only,both,digital_download,service_booking',
            'price' => 'required|numeric|min:0',
            'track_stock' => 'nullable|boolean',
            'stock' => 'nullable|integer|min:0|required_if:track_stock,1',
            'weight_gram' => 'nullable|integer|min:0',
            'digital_file_url' => 'nullable|required_if:fulfillment_type,digital_download|string|max:500',
            'thumbnail_file' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'description' => 'nullable|string',
            'status' => ['required', new Enum(ItemStatus::class)],
        ]);

        $type = $validated['item_type'];
        $fulfillment = $this->normalizeFulfillment($type, $validated['fulfillment_type'] ?? null);
        $trackStock = ($type === ItemType::Jasa->value || $type === ItemType::Digital->value) ? false : $request->boolean('track_stock');
        $stock = $trackStock ? (int) $validated['stock'] : 0;
        $weight = $type === ItemType::Produk->value ? ($validated['weight_gram'] ?? null) : null;
        $path = $request->file('thumbnail_file')->store('products', 'public');
        $thumbnailUrl = '/storage/' . $path;

        $slug = Str::slug($validated['title']) . '-' . Str::random(5);

        CatalogItem::create([
            'id' => (string) Str::uuid(),
            'tefa_unit_id' => $managedUnit->id,
            'category_id' => $validated['category_ids'][0] ?? null,
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $stock,
            'track_stock' => $trackStock,
            'weight_gram' => $weight,
            'fulfillment_type' => $fulfillment,
            'digital_file_url' => $validated['digital_file_url'] ?? null,
            'thumbnail_url' => $thumbnailUrl,
            'item_type' => $validated['item_type'],
            'status' => $validated['status'],
        ])->categories()->sync($validated['category_ids'] ?? []);

        return redirect()->back()->with('success', "Item katalog '{$validated['title']}' berhasil ditambahkan!");
    }

    public function update(Request $request, CatalogItem $product)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'item_type' => ['required', new Enum(ItemType::class)],
            'fulfillment_type' => 'nullable|string|in:shipping_only,pickup_only,both,digital_download,service_booking',
            'price' => 'required|numeric|min:0',
            'track_stock' => 'nullable|boolean',
            'stock' => 'nullable|integer|min:0|required_if:track_stock,1',
            'weight_gram' => 'nullable|integer|min:0',
            'digital_file_url' => 'nullable|required_if:fulfillment_type,digital_download|string|max:500',
            'thumbnail_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'description' => 'nullable|string',
            'status' => ['required', new Enum(ItemStatus::class)],
        ]);

        $type = $validated['item_type'];
        $fulfillment = $this->normalizeFulfillment($type, $validated['fulfillment_type'] ?? null);
        $trackStock = ($type === ItemType::Jasa->value || $type === ItemType::Digital->value) ? false : $request->boolean('track_stock');
        $stock = $trackStock ? (int) $validated['stock'] : 0;
        $weight = $type === ItemType::Produk->value ? ($validated['weight_gram'] ?? null) : null;
        $thumbnailUrl = $product->thumbnail_url;

        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('products', 'public');
            $thumbnailUrl = '/storage/' . $path;
        }

        $product->update([
            'title' => $validated['title'],
            'category_id' => $validated['category_ids'][0] ?? null,
            'description' => $validated['description'] ?? $product->description,
            'price' => $validated['price'],
            'stock' => $stock,
            'track_stock' => $trackStock,
            'weight_gram' => $weight,
            'fulfillment_type' => $fulfillment,
            'digital_file_url' => $validated['digital_file_url'] ?? $product->digital_file_url,
            'thumbnail_url' => $thumbnailUrl,
            'item_type' => $validated['item_type'],
            'status' => $validated['status'],
        ]);
        $product->categories()->sync($validated['category_ids'] ?? []);

        return redirect()->back()->with('success', "Item katalog '{$product->title}' berhasil diperbarui!");
    }

    public function destroy(CatalogItem $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'Item produk berhasil dihapus dari katalog.');
    }

    private function normalizeFulfillment(string $type, ?string $fulfillment): string
    {
        // Digital dan Jasa selalu auto-set, tidak perlu dari form
        if ($type === ItemType::Digital->value) {
            return 'digital_download';
        }

        if ($type === ItemType::Jasa->value) {
            return 'service_booking';
        }

        // Untuk produk fisik, gunakan nilai dari form (default: both)
        $allowed = ['shipping_only', 'pickup_only', 'both'];
        if (!$fulfillment || !in_array($fulfillment, $allowed, true)) {
            return 'both';
        }

        return $fulfillment;
    }
}