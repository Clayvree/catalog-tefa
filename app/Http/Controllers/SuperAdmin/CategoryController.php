<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Enums\ItemType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

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
            'type' => ['nullable', new Enum(ItemType::class)],
        ]);

        Category::create([
            'name' => $validated['name'],
            'type' => $validated['type'] ?? ItemType::Jasa,
        ]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}