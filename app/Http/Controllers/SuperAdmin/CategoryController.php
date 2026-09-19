<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Enums\ItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('catalogItems')->orderBy('name')->get();
        return view('superadmin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'type' => ['nullable', Rule::in(['produk', 'jasa', 'kegiatan'])],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        Category::create([
            'name' => $validated['name'],
            'type' => $validated['type'] ?? ItemType::Jasa,
            'image' => $request->hasFile('image')
                ? $request->file('image')->store('categories', 'public')
                : null,
        ]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
            'type' => ['nullable', Rule::in(['produk', 'jasa', 'kegiatan'])],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $category->name = $validated['name'];
        $category->type = $validated['type'] ?? ItemType::Jasa;

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $category->image = $request->file('image')->store('categories', 'public');
        }

        $category->save();

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}