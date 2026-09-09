<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AiKnowledgeBase;
use App\Models\TefaUnit;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    public function index(Request $request)
    {
        $query = AiKnowledgeBase::with('tefaUnit')->latest();

        if ($request->filled('unit')) {
            $query->where('tefa_unit_id', $request->unit);
        } elseif ($request->has('global')) {
            $query->whereNull('tefa_unit_id');
        }

        $knowledges = $query->paginate(15)->withQueryString();
        $units = TefaUnit::where('is_active', true)->orderBy('name')->get();

        return view('superadmin.knowledge.index', compact('knowledges', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'tefa_unit_id' => 'nullable|exists:tefa_units,id',
            'is_active'    => 'boolean',
        ]);

        AiKnowledgeBase::create([
            'tefa_unit_id' => $validated['tefa_unit_id'] ?? null,
            'title'        => $validated['title'],
            'description'  => $validated['description'],
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('superadmin.knowledge.index')
            ->with('success', "Konteks AI \"{$validated['title']}\" berhasil ditambahkan.");
    }

    public function update(Request $request, AiKnowledgeBase $knowledge)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'tefa_unit_id' => 'nullable|exists:tefa_units,id',
            'is_active'    => 'boolean',
        ]);

        $knowledge->update([
            'tefa_unit_id' => $validated['tefa_unit_id'] ?? null,
            'title'        => $validated['title'],
            'description'  => $validated['description'],
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('superadmin.knowledge.index')
            ->with('success', "Konteks AI \"{$knowledge->title}\" berhasil diperbarui.");
    }

    public function destroy(AiKnowledgeBase $knowledge)
    {
        $knowledge->delete();

        return redirect()->route('superadmin.knowledge.index')
            ->with('success', 'Konteks AI berhasil dihapus.');
    }
}

