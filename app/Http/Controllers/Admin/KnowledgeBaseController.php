<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiKnowledgeBase;
use App\Models\TefaUnit;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    private function managedUnit(Request $request): TefaUnit
    {
        return $request->user()->managedUnits()->first() ?? TefaUnit::first();
    }

    public function index(Request $request)
    {
        $unit = $this->managedUnit($request);

        $knowledges = AiKnowledgeBase::where('tefa_unit_id', $unit->id)
            ->latest()
            ->paginate(15);

        return view('admin.knowledge.index', compact('knowledges', 'unit'));
    }

    public function store(Request $request)
    {
        $unit = $this->managedUnit($request);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'is_active'   => 'boolean',
        ]);

        AiKnowledgeBase::create([
            'tefa_unit_id' => $unit->id,
            'title'        => $validated['title'],
            'description'  => $validated['description'],
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.knowledge.index')
            ->with('success', "Konteks AI \"{$validated['title']}\" berhasil ditambahkan.");
    }

    public function update(Request $request, AiKnowledgeBase $knowledge)
    {
        $unit = $this->managedUnit($request);

        // Pastikan hanya mengedit knowledge milik unit sendiri
        abort_if($knowledge->tefa_unit_id !== $unit->id, 403);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'is_active'   => 'boolean',
        ]);

        $knowledge->update([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.knowledge.index')
            ->with('success', "Konteks AI \"{$knowledge->title}\" berhasil diperbarui.");
    }

    public function destroy(Request $request, AiKnowledgeBase $knowledge)
    {
        $unit = $this->managedUnit($request);
        abort_if($knowledge->tefa_unit_id !== $unit->id, 403);

        $knowledge->delete();

        return redirect()->route('admin.knowledge.index')
            ->with('success', 'Konteks AI berhasil dihapus.');
    }
}

