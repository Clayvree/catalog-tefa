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
                'name' => 'Budi (Worker 1)',
                'password' => Hash::make('password'),
                'phone' => '6281234567021',
                'role' => UserRole::Worker,
                'email_verified_at' => now(),
            ]
        );

        $unit = \App\Models\TefaUnit::first();
        $unitId = $unit ? $unit->id : \Illuminate\Support\Str::uuid();

        $profile1 = WorkerProfile::updateOrCreate(
            ['user_id' => $user1->id],
            [
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
                'name' => 'Siti (Worker 2)',
                'password' => Hash::make('password'),
                'phone' => '6281234567022',
                'role' => UserRole::Worker,
                'email_verified_at' => now(),
            ]
        );

        $profile2 = WorkerProfile::updateOrCreate(
            ['user_id' => $user2->id],
            [
                'tefa_unit_id' => $unitId,
                'nisn' => '0987654321',
                'class_name' => 'XI PPLG 2',
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

        // Student for DKV
        $unitDkv = \App\Models\TefaUnit::where('slug', 'tefa-dkv-creative-agency')->first();
        if ($unitDkv) {
            $userDkv = User::updateOrCreate(
                ['email' => 'worker.dkv@tefa.id'],
                [
                    'name' => 'Rian (Siswa DKV)',
                    'password' => Hash::make('password'),
                    'phone' => '6281234567023',
                    'role' => UserRole::Worker,
                    'email_verified_at' => now(),
                ]
            );
            $profileDkv = WorkerProfile::updateOrCreate(
                ['user_id' => $userDkv->id],
                [
                    'tefa_unit_id' => $unitDkv->id,
                    'nisn' => '1122334455',
                    'class_name' => 'XI DKV 1',
                    'bio' => 'Spesialis desain grafis, ilustrasi vektor, dan branding kemasan.',
                ]
            );
            $skillDesign = Skill::firstOrCreate(['slug' => 'graphic-design'], ['name' => 'Graphic Design', 'color_hex' => '#8b5cf6']);
            $profileDkv->skills()->syncWithoutDetaching([
                $skillDesign->id => ['proficiency_level' => 'advanced'],
            ]);
        }

        // Student for Animasi
        $unitAnimasi = \App\Models\TefaUnit::where('slug', 'tefa-animasi-studio')->first();
        if ($unitAnimasi) {
            $userAnimasi = User::updateOrCreate(
                ['email' => 'worker.animasi@tefa.id'],
                [
                    'name' => 'Doni (Siswa Animasi)',
                    'password' => Hash::make('password'),
                    'phone' => '6281234567024',
                    'role' => UserRole::Worker,
                    'email_verified_at' => now(),
                ]
            );
            $profileAnimasi = WorkerProfile::updateOrCreate(
                ['user_id' => $userAnimasi->id],
                [
                    'tefa_unit_id' => $unitAnimasi->id,
                    'nisn' => '2233445566',
                    'class_name' => 'XI Animasi 1',
                    'bio' => 'Spesialis pemodelan 3D, rigging, dan animasi karakter di Blender.',
                ]
            );
            $skill3D = Skill::firstOrCreate(['slug' => '3d-modeling'], ['name' => '3D Modeling', 'color_hex' => '#f59e0b']);
            $profileAnimasi->skills()->syncWithoutDetaching([
                $skill3D->id => ['proficiency_level' => 'intermediate'],
            ]);
        }

        // Student for PSPT
        $unitPspt = \App\Models\TefaUnit::where('slug', 'tefa-pspt-broadcasting-production')->first();
        if ($unitPspt) {
            $userPspt = User::updateOrCreate(
                ['email' => 'worker.pspt@tefa.id'],
                [
                    'name' => 'Maya (Siswa PSPT)',
                    'password' => Hash::make('password'),
                    'phone' => '6281234567025',
                    'role' => UserRole::Worker,
                    'email_verified_at' => now(),
                ]
            );
            $profilePspt = WorkerProfile::updateOrCreate(
                ['user_id' => $userPspt->id],
                [
                    'tefa_unit_id' => $unitPspt->id,
                    'nisn' => '3344556677',
                    'class_name' => 'XI PSPT 1',
                    'bio' => 'Spesialis video shooting, audio mixing podcast, dan live broadcast.',
                ]
            );
            $skillVideo = Skill::firstOrCreate(['slug' => 'video-editing'], ['name' => 'Video Editing', 'color_hex' => '#ec4899']);
            $profilePspt->skills()->syncWithoutDetaching([
                $skillVideo->id => ['proficiency_level' => 'intermediate'],
            ]);
        }

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
