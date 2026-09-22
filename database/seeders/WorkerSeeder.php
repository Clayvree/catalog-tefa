<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Skill;
use App\Models\User;
use App\Models\WorkerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WorkerSeeder extends Seeder
{
    public function run(): void
    {
        // Worker 1 – example with networking skills
        $user1 = User::updateOrCreate(
            ['email' => 'worker1@tefa.id'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Budi (Worker 1)',
                'password' => Hash::make('password'),
                'role' => UserRole::Worker,
                'email_verified_at' => now(),
            ]
        );

        $unit = \App\Models\TefaUnit::first();
        $unitId = $unit ? $unit->id : \Illuminate\Support\Str::uuid();

        $profile1 = WorkerProfile::updateOrCreate(
            ['user_id' => $user1->id],
            [
                'id' => (string) Str::uuid(),
                'tefa_unit_id' => $unitId, // Assign to first unit
                'nisn' => '1234567890',
                'class_name' => 'XII RPL 1',
                'bio' => 'Enthusiastic about networking and hardware.',
            ]
        );

        // Assign some skills to worker 1
        $skillNet = Skill::firstOrCreate(['slug' => 'networking'], ['name' => 'Networking', 'color_hex' => '#3b82f6']);
        $skillLinux = Skill::firstOrCreate(['slug' => 'linux'], ['name' => 'Linux', 'color_hex' => '#10b981']);
        $profile1->skills()->syncWithoutDetaching([
            $skillNet->id => ['proficiency_level' => 'intermediate'],
            $skillLinux->id => ['proficiency_level' => 'intermediate'],
        ]);

        // Worker 2 – example with web development skills
        $user2 = User::updateOrCreate(
            ['email' => 'worker2@tefa.id'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Siti (Worker 2)',
                'password' => Hash::make('password'),
                'role' => UserRole::Worker,
                'email_verified_at' => now(),
            ]
        );

        $profile2 = WorkerProfile::updateOrCreate(
            ['user_id' => $user2->id],
            [
                'id' => (string) Str::uuid(),
                'tefa_unit_id' => $unitId,
                'nisn' => '0987654321',
                'class_name' => 'XI TKJ 2',
                'bio' => 'Passionate about web development and UI/UX.',
            ]
        );

        // Assign web‑dev skills to worker 2
        $skillLaravel = Skill::firstOrCreate(['slug' => 'laravel'], ['name' => 'Laravel', 'color_hex' => '#ef4444']);
        $skillVue = Skill::firstOrCreate(['slug' => 'vue-js'], ['name' => 'Vue JS', 'color_hex' => '#10b981']);
        $profile2->skills()->syncWithoutDetaching([
            $skillLaravel->id => ['proficiency_level' => 'intermediate'],
            $skillVue->id => ['proficiency_level' => 'intermediate'],
        ]);

        // Create a Dummy Project to demonstrate the Collaboration Room & Team Notes
        $admin = User::where('role', UserRole::AdminJurusan)->first();
        if ($admin && $unit) {
            $project = \App\Models\Project::firstOrCreate(
                ['title' => 'Pembuatan Web E-Commerce Sekolah'],
                [
                    'id' => (string) Str::uuid(),
                    'tefa_unit_id' => $unit->id,
                    'client_name' => 'Bapak Kepala Sekolah',
                    'description' => 'Proyek pembuatan website e-commerce internal untuk menjual karya siswa.',
                    'status' => \App\Enums\ProjectStatus::Active,
                    'created_by' => $admin->id,
                ]
            );

            $task = \App\Models\Task::firstOrCreate(
                ['title' => 'Desain & Implementasi Frontend Vue JS'],
                [
                    'id' => (string) Str::uuid(),
                    'project_id' => $project->id,
                    'tefa_unit_id' => $unit->id,
                    'description' => 'Buat halaman utama menggunakan Vue.js dan Tailwind CSS sesuai mockup Figma.',
                    'goals' => 'Menyelesaikan 3 halaman utama (Beranda, Produk, Checkout) dengan UI responsif.',
                    'team_notes' => 'Catatan Ketua (Siti): Repository sudah saya buat di GitHub, silakan branch dari main ya. Budi tolong bantu testing.',
                    'progress_percentage' => 45,
                    'leader_id' => $profile2->id, // Siti (Web Dev) is leader
                    'skill_id' => $skillVue->id,
                    'status' => \App\Enums\TaskStatus::InProgress,
                    'priority' => \App\Enums\TaskPriority::High,
                    'ai_recommendation_notes' => 'Siti direkomendasikan sebagai ketua karena memiliki skill Vue JS yang dibutuhkan untuk Frontend.',
                ]
            );
            
            // Assign Budi as a supporting member to Siti's task
            $task->members()->syncWithoutDetaching([$profile1->id]);
        }
    }
}
