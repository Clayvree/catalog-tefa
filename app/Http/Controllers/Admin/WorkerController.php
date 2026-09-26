<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkerProfile;
use App\Models\Skill;
use App\Models\TefaUnit;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WorkerController extends Controller
{
    public function index(Request $request)
    {
        $managedUnit = $request->user()->managedUnits()->first() ?? TefaUnit::first();
        $unitId = $managedUnit?->id;

        $workers = $unitId 
            ? WorkerProfile::with(['user', 'skills', 'ledTasks', 'memberTasks'])->where('tefa_unit_id', $unitId)->latest()->get() 
            : collect();

        $availableSkills = Skill::orderBy('name')->get();

        return view('admin.workers.index', compact('workers', 'managedUnit', 'availableSkills'));
    }

    public function store(Request $request)
    {
        $managedUnit = $request->user()->managedUnits()->first() ?? TefaUnit::first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'nisn' => 'nullable|string|max:30',
            'class_name' => 'required|string|max:50',
            'bio' => 'nullable|string',
            'skills_text' => 'nullable|string|max:500',
            'overall_proficiency' => 'required|in:beginner,intermediate,advanced',
        ]);

        $user = User::create([
            'id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => UserRole::Worker,
            'email_verified_at' => now(),
        ]);

        $profile = WorkerProfile::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'tefa_unit_id' => $managedUnit->id,
            'nisn' => $validated['nisn'] ?? null,
            'class_name' => $validated['class_name'],
            'bio' => $validated['bio'] ?? null,
        ]);

        $skillsData = [];

        if (!empty($validated['skills_text'])) {
            $skillNames = array_filter(array_map('trim', explode(',', $validated['skills_text'])));
            foreach ($skillNames as $skillName) {
                if (!empty($skillName)) {
                    $slug = Str::slug($skillName);
                    $skill = Skill::firstOrCreate(
                        ['slug' => $slug],
                        [
                            'name' => ucwords(strtolower($skillName)),
                            'color_hex' => '#' . substr(md5($slug), 0, 6),
                        ]
                    );
                    $skillsData[$skill->id] = ['proficiency_level' => $validated['overall_proficiency']];
                }
            }
        }

        if (!empty($skillsData)) {
            $profile->skills()->sync($skillsData);
        }

        return redirect()->back()->with('success', "Data siswa {$user->name} berhasil ditambahkan!");
    }

    public function update(Request $request, WorkerProfile $worker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $worker->user_id,
            'password' => 'nullable|string|min:6',
            'nisn' => 'nullable|string|max:30',
            'class_name' => 'required|string|max:50',
            'bio' => 'nullable|string',
            'skills_text' => 'nullable|string|max:500',
            'overall_proficiency' => 'required|in:beginner,intermediate,advanced',
        ]);

        $user = $worker->user;
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        $worker->update([
            'nisn' => $validated['nisn'] ?? $worker->nisn,
            'class_name' => $validated['class_name'],
            'bio' => $validated['bio'] ?? $worker->bio,
        ]);

        $skillsData = [];

        if (!empty($validated['skills_text'])) {
            $skillNames = array_filter(array_map('trim', explode(',', $validated['skills_text'])));
            foreach ($skillNames as $skillName) {
                if (!empty($skillName)) {
                    $slug = Str::slug($skillName);
                    $skill = Skill::firstOrCreate(
                        ['slug' => $slug],
                        [
                            'name' => ucwords(strtolower($skillName)),
                            'color_hex' => '#' . substr(md5($slug), 0, 6),
                        ]
                    );
                    $skillsData[$skill->id] = ['proficiency_level' => $validated['overall_proficiency']];
                }
            }
        }

        $worker->skills()->sync($skillsData);

        return redirect()->back()->with('success', "Data siswa {$user->name} berhasil diperbarui!");
    }

    public function destroy(WorkerProfile $worker)
    {
        $user = $worker->user;
        $worker->skills()->detach();
        $worker->delete();
        $user?->delete();

        return redirect()->back()->with('success', 'Data siswa berhasil dihapus.');
    }
}