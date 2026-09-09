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

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $managedUnit = $request->user()->managedUnits()->first() ?? TefaUnit::first();
        $unitId = $managedUnit?->id;

        $query = CatalogItem::with('category')->forUnit($unitId);

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
        $categories = Category::orderBy('name')->get();

        $stats = [
            'total' => CatalogItem::forUnit($unitId)->count(),
            'physical' => CatalogItem::forUnit($unitId)->where('item_type', ItemType::Produk->value)->count(),
            'service_digital' => CatalogItem::forUnit($unitId)->whereIn('item_type', [ItemType::Jasa->value, 'kegiatan'])->count(),
            'published' => CatalogItem::forUnit($unitId)->where('status', ItemStatus::Published->value)->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'managedUnit', 'stats'));
    }

    public function store(Request $request)
    {
        $managedUnit = $request->user()->managedUnits()->first() ?? TefaUnit::first();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'item_type' => ['required', new Enum(ItemType::class)],
            'fulfillment_type' => 'required|string|in:shipping_only,pickup_only,both,digital_download,service_booking',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'weight_gram' => 'nullable|integer|min:0',
            'digital_file_url' => 'nullable|string|max:500',
            'thumbnail_url' => 'nullable|string|max:500',
            'thumbnail_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'description' => 'nullable|string',
            'status' => ['required', new Enum(ItemStatus::class)],
        ]);

        $thumbnailUrl = $validated['thumbnail_url'] ?? 'https://images.unsplash.com/photo-1556742049-0a67c5574f73?w=600&q=80';

        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('products', 'public');
            $thumbnailUrl = '/storage/' . $path;
        }

        $slug = Str::slug($validated['title']) . '-' . Str::random(5);

        CatalogItem::create([
            'id' => (string) Str::uuid(),
            'tefa_unit_id' => $managedUnit->id,
            'category_id' => $validated['category_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'] ?? 10,
            'weight_gram' => $validated['weight_gram'] ?? 500,
            'fulfillment_type' => $validated['fulfillment_type'],
            'digital_file_url' => $validated['digital_file_url'] ?? null,
            'thumbnail_url' => $thumbnailUrl,
            'item_type' => $validated['item_type'],
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', "Item katalog '{$validated['title']}' berhasil ditambahkan!");
    }

    public function update(Request $request, CatalogItem $product)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'item_type' => ['required', new Enum(ItemType::class)],
            'fulfillment_type' => 'required|string|in:shipping_only,pickup_only,both,digital_download,service_booking',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'weight_gram' => 'nullable|integer|min:0',
            'digital_file_url' => 'nullable|string|max:500',
            'thumbnail_url' => 'nullable|string|max:500',
            'thumbnail_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'description' => 'nullable|string',
            'status' => ['required', new Enum(ItemStatus::class)],
        ]);

        $thumbnailUrl = $product->thumbnail_url;

        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('products', 'public');
            $thumbnailUrl = '/storage/' . $path;
        } elseif (!empty($validated['thumbnail_url'])) {
            $thumbnailUrl = $validated['thumbnail_url'];
        }

        $product->update([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'] ?? $product->category_id,
            'description' => $validated['description'] ?? $product->description,
            'price' => $validated['price'],
            'stock' => $validated['stock'] ?? $product->stock,
            'weight_gram' => $validated['weight_gram'] ?? $product->weight_gram,
            'fulfillment_type' => $validated['fulfillment_type'],
            'digital_file_url' => $validated['digital_file_url'] ?? $product->digital_file_url,
            'thumbnail_url' => $thumbnailUrl,
            'item_type' => $validated['item_type'],
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', "Item katalog '{$product->title}' berhasil diperbarui!");
    }

    public function destroy(CatalogItem $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'Item produk berhasil dihapus dari katalog.');
    }
}