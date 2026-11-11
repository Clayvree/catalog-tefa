<x-public-layout>
    @php
        $unit = $portfolio->tefaUnit;

        // Auto-detect link jika external_link kosong tapi ada URL di dalam deskripsi
        $externalUrl = $portfolio->external_link;
        if (!$externalUrl && preg_match('/https?:\/\/[^\s]+/', (string) $portfolio->description, $matches)) {
            $externalUrl = rtrim($matches[0], '.');
        }

        // Tampilan URL singkat untuk bar mockup browser
        $domainDisplay = 'tefahub.id/karya/' . Str::slug($portfolio->title);
        if ($externalUrl) {
            $parsed = parse_url($externalUrl);
            $domainDisplay = ($parsed['host'] ?? 'demo') . ($parsed['path'] ?? '');
            if (strlen($domainDisplay) > 35) {
                $domainDisplay = substr($domainDisplay, 0, 32) . '...';
            }
        }

        $cleanDescription = trim((string) $portfolio->description);

        // Susun tim pengerja proyek
        $allMembers = collect();
        $contributors = $portfolio->contributors;
        $primaryWorkerId = $portfolio->worker_profile_id;
        $submitterInContributors = $contributors->firstWhere('worker_profile_id', $primaryWorkerId);

        // Jika pengaju utama belum tercatat di contributors, masukkan sebagai entri pertama
        if (!$submitterInContributors && $portfolio->worker) {
            $allMembers->push([
                'name'         => $portfolio->worker->user->name ?? 'Siswa Pengembang',
                'class_name'   => $portfolio->worker->class_name,
                'bio'          => $portfolio->worker->bio,
                'avatar_url'   => $portfolio->worker->avatar_url,
                'linkedin_url' => null,
                'social_url'   => null,
                'skills'       => $portfolio->worker->skills ?? collect(),
                'role'         => 'Ketua Tim / Lead Developer',
                'badge_style'  => 'bg-indigo-50 text-indigo-700 border-indigo-200/80',
                'is_primary'   => true,
            ]);
        }

        $badgePalette = [
            'bg-indigo-50 text-indigo-700 border-indigo-200/80',
            'bg-blue-50 text-blue-700 border-blue-200/80',
            'bg-violet-50 text-violet-700 border-violet-200/80',
            'bg-emerald-50 text-emerald-700 border-emerald-200/80',
            'bg-amber-50 text-amber-700 border-amber-200/80',
            'bg-rose-50 text-rose-700 border-rose-200/80',
        ];

        foreach ($contributors as $ci => $contrib) {
            $isPrimary = ($contrib->worker_profile_id && $contrib->worker_profile_id === $primaryWorkerId);
            $worker = $contrib->workerProfile ?: ($isPrimary ? $portfolio->worker : null);

            $allMembers->push([
                'name'         => $contrib->display_name,
                'class_name'   => $contrib->class_name ?: $worker?->class_name,
                'bio'          => $worker?->bio,
                'avatar_url'   => $contrib->avatar_url ?: $worker?->avatar_url,
                'linkedin_url' => $contrib->linkedin_url,
                'social_url'   => $contrib->social_url,
                'skills'       => $worker?->skills ?? collect(),
                'role'         => $contrib->role_title ?: ($isPrimary ? 'Ketua Tim / Lead Developer' : 'Anggota Tim'),
                'badge_style'  => $badgePalette[$ci % count($badgePalette)],
                'is_primary'   => $isPrimary,
                'is_guest'     => !$contrib->worker_profile_id,
            ]);
        }

        $avatarGradients = [
            'from-indigo-600 via-indigo-700 to-blue-600 text-white',
            'from-violet-600 via-purple-700 to-indigo-600 text-white',
            'from-cyan-600 via-teal-700 to-blue-600 text-white',
            'from-emerald-600 via-teal-700 to-emerald-600 text-white',
            'from-amber-500 via-orange-600 to-rose-600 text-white',
        ];

        // Validasi fisik file thumbnail
        $hasValidImage = false;
        if ($portfolio->thumbnail_url) {
            if (str_starts_with($portfolio->thumbnail_url, 'http')) {
                $hasValidImage = true;
            } else {
                $rawPath = ltrim(str_replace('/storage/', '', (string) $portfolio->thumbnail_url), '/');
                $hasValidImage = file_exists(public_path($rawPath)) || file_exists(public_path('storage/' . $rawPath));
            }
        }
    @endphp

    @push('meta')
        <meta name="description" content="{{ Str::limit($portfolio->description, 155) }}">
    @endpush

    <div class="bg-slate-950 text-slate-100 min-h-screen selection:bg-indigo-500 selection:text-white">

        {{-- ═══════════════════════════════════════════════════════════
             1. GLOWING TECH HERO BANNER (DARK DIGITAL AESTHETIC)
        ═══════════════════════════════════════════════════════════ --}}
        <div class="relative overflow-hidden border-b border-slate-800/80 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 pt-6 pb-16 sm:pb-24">
            
            {{-- Ambient Cyber Grid Glow Background --}}
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e1b4b15_1px,transparent_1px),linear-gradient(to_bottom,#1e1b4b15_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] pointer-events-none"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-gradient-to-tr from-indigo-600/25 via-blue-500/20 to-purple-600/20 blur-[120px] rounded-full pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-6">

                {{-- Breadcrumb Navigation (Glass Pill) --}}
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    <nav class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-md text-xs text-slate-400">
                        <a href="{{ route('home') }}" class="hover:text-indigo-400 transition">Beranda</a>
                        <span class="text-slate-600">/</span>
                        @if($unit)
                            <a href="{{ route('tefa.storefront', $unit->slug) }}" class="hover:text-indigo-400 transition">{{ $unit->name }}</a>
                            <span class="text-slate-600">/</span>
                            <a href="{{ route('tefa.portfolio', $unit->slug) }}" class="hover:text-indigo-400 transition">Portofolio</a>
                            <span class="text-slate-600">/</span>
                        @endif
                        <span class="text-white font-bold truncate max-w-[160px] sm:max-w-[240px]">{{ $portfolio->title }}</span>
                    </nav>

                    @if($unit)
                        <a href="{{ route('tefa.storefront', $unit->slug) }}" 
                           class="inline-flex items-center gap-2 text-xs font-bold text-slate-300 hover:text-white transition group">
                            <span class="w-6 h-6 rounded-full bg-white/10 group-hover:bg-indigo-600 flex items-center justify-center transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </span>
                            <span>Kembali ke {{ $unit->name }}</span>
                        </a>
                    @endif
                </div>

                {{-- Hero Header Content --}}
                <div class="max-w-4xl space-y-4 pt-2">
                    
                    {{-- Status Pills --}}
                    <div class="flex items-center gap-2.5 flex-wrap">
                        @if($unit)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                                {{ $unit->name }}
                            </span>
                        @endif

                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Divalidasi Guru Pembimbing
                        </span>

                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-800/80 text-slate-300 border border-slate-700/60">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $allMembers->count() }} Siswa Kreator
                        </span>
                    </div>

                    {{-- Main Project Title --}}
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-[1.15]">
                        {{ $portfolio->title }}
                    </h1>

                    <p class="text-sm sm:text-base text-slate-400 leading-relaxed max-w-3xl pt-1">
                        Karya inovasi digital dan implementasi teknologi industri dari siswa vokasi Teaching Factory.
                    </p>

                    {{-- Primary Action CTA Bar --}}
                    <div class="flex items-center gap-3 pt-3 flex-wrap">
                        @if($externalUrl)
                            <a href="{{ $externalUrl }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white font-extrabold text-sm shadow-xl shadow-indigo-600/30 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                                <span>Buka Live Platform / Demo</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @endif

                        @if($portfolio->file_url)
                            <a href="{{ $portfolio->file_url }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 px-5 py-3.5 rounded-2xl bg-white/10 hover:bg-white/15 text-white font-bold text-sm border border-white/15 backdrop-blur-md transition-all duration-200">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>Unduh Berkas Dokumentasi</span>
                            </a>
                        @endif
                    </div>

                </div>

            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════
             2. DIGITAL PROJECT CANVAS / MOCKUP WINDOW (MACOS BROWSER FRAME)
        ═══════════════════════════════════════════════════════════ --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 sm:-mt-14 relative z-20">
            
            <div class="rounded-3xl border border-slate-700/80 bg-slate-900/90 shadow-2xl shadow-indigo-950/60 overflow-hidden backdrop-blur-xl">
                
                {{-- Mockup Window Header (Chrome/Safari Style) --}}
                <div class="bg-slate-900 px-4 py-3 border-b border-slate-800 flex items-center justify-between gap-4">
                    {{-- 3 Mac Dots --}}
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-rose-500/80 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-amber-500/80 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-500/80 inline-block"></span>
                    </div>

                    {{-- Fake Browser URL Bar --}}
                    <div class="flex-1 max-w-md mx-auto hidden sm:flex items-center justify-center gap-2 px-4 py-1 rounded-full bg-slate-950/80 border border-slate-800 text-[11px] font-mono text-slate-400 truncate">
                        <svg class="w-3 h-3 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                        <span class="truncate">{{ $domainDisplay }}</span>
                    </div>

                    {{-- Status Indicator --}}
                    <div class="flex items-center gap-2 text-[11px] font-mono text-slate-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span class="hidden sm:inline">PROD VOKASI</span>
                    </div>
                </div>

                {{-- Window Screen Canvas --}}
                <div class="relative w-full aspect-[16/9] sm:aspect-[21/9] bg-slate-950 flex items-center justify-center overflow-hidden">
                    @if($hasValidImage)
                        <img src="{{ $portfolio->thumbnail_url }}" 
                             alt="{{ $portfolio->title }}" 
                             loading="lazy"
                             decoding="async"
                             class="w-full h-full object-cover opacity-0 blur-md scale-105 transition-all duration-700 ease-out"
                             onload="this.classList.remove('opacity-0', 'blur-md', 'scale-105'); this.classList.add('opacity-100', 'blur-0', 'scale-100');">
                    @else
                        {{-- Futuristic Digital Platform Graphic when dummy image is missing --}}
                        <div class="absolute inset-0 bg-gradient-to-tr from-indigo-950 via-slate-950 to-slate-900 flex items-center justify-center p-6 sm:p-12">
                            <div class="text-center max-w-xl space-y-4">
                                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-gradient-to-tr from-indigo-600 to-blue-500 text-white flex items-center justify-center mx-auto shadow-2xl shadow-indigo-500/40 border border-indigo-400/30">
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] sm:text-xs font-mono font-bold tracking-widest uppercase text-indigo-400">Digital Product Showcase</span>
                                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ $portfolio->title }}</h2>
                                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed max-w-md mx-auto">
                                        Solusi digital terintegrasi hasil karya nyata siswa Teaching Factory.
                                    </p>
                                </div>

                                @if($externalUrl)
                                    <div class="pt-2">
                                        <a href="{{ $externalUrl }}" target="_blank" rel="noopener noreferrer" 
                                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-slate-950 hover:bg-indigo-50 font-bold text-xs shadow-md transition">
                                            <span>Buka Tautan Platform</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

            </div>

        </div>

        {{-- ═══════════════════════════════════════════════════════════
             3. OVERVIEW & SPECIFICATIONS (RESPONSIVE GRID)
        ═══════════════════════════════════════════════════════════ --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 sm:pt-16">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- Left: Deskripsi & Cerita Proyek --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-slate-900/60 rounded-3xl border border-slate-800/80 p-6 sm:p-8 space-y-4">
                        <div class="flex items-center gap-2 text-indigo-400 text-xs font-bold uppercase tracking-wider">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Gambaran & Ringkasan Proyek</span>
                        </div>

                        <div class="text-slate-300 text-sm sm:text-base leading-relaxed space-y-4">
                            {!! nl2br(e($cleanDescription)) !!}
                        </div>

                        @if($externalUrl)
                            <div class="pt-4 border-t border-slate-800 flex items-center justify-between gap-4 flex-wrap">
                                <span class="text-xs text-slate-400">Tautan Akses:</span>
                                <a href="{{ $externalUrl }}" target="_blank" rel="noopener noreferrer" 
                                   class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-400 hover:text-indigo-300 hover:underline">
                                    <span>{{ $externalUrl }}</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Review Notes / Catatan Guru Pembimbing --}}
                    @if($portfolio->review_notes)
                        <div class="p-6 bg-gradient-to-r from-blue-950/40 via-indigo-950/30 to-slate-900 rounded-3xl border border-blue-800/40 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                                <span class="text-[11px] font-black uppercase tracking-wider text-blue-300">Catatan Validasi Guru Pembimbing</span>
                            </div>
                            <p class="text-xs sm:text-sm text-blue-200/90 italic leading-relaxed">
                                "{{ $portfolio->review_notes }}"
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Right: Project Matrix Card --}}
                <div class="space-y-6">
                    <div class="bg-slate-900/60 rounded-3xl border border-slate-800/80 p-6 space-y-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Matriks Spesifikasi</h4>
                        
                        <div class="space-y-3.5 text-xs divide-y divide-slate-800/60">
                            <div class="pt-1 flex items-center justify-between">
                                <span class="text-slate-400">Unit TEFA</span>
                                <span class="font-bold text-white">{{ $unit->name ?? 'Teaching Factory' }}</span>
                            </div>

                            <div class="pt-3 flex items-center justify-between">
                                <span class="text-slate-400">Validasi</span>
                                <span class="font-bold text-emerald-400 flex items-center gap-1">
                                    ✓ Standar Industri
                                </span>
                            </div>

                            <div class="pt-3 flex items-center justify-between">
                                <span class="text-slate-400">Tanggal Rilis</span>
                                <span class="font-bold text-white">{{ $portfolio->created_at?->translatedFormat('d M Y') ?? 'Terverifikasi' }}</span>
                            </div>

                            <div class="pt-3 flex items-center justify-between">
                                <span class="text-slate-400">Keterlibatan</span>
                                <span class="font-bold text-white">{{ $allMembers->count() }} Orang Siswa</span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-800">
                            <a href="{{ route('contact') }}" 
                               class="w-full py-2.5 px-4 rounded-xl bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 font-bold text-xs border border-indigo-500/30 flex items-center justify-center gap-2 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <span>Pesan Proyek Serupa</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- ═══════════════════════════════════════════════════════════
             4. TIM KREATOR SISWA (DIGITAL TALENT / CREATOR CARDS)
        ═══════════════════════════════════════════════════════════ --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 sm:pt-24 space-y-8">
            
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-800 pb-5">
                <div>
                    <span class="text-xs font-mono font-bold uppercase tracking-wider text-indigo-400">Talenta & Pengembang</span>
                    <h2 class="text-2xl sm:text-4xl font-black text-white tracking-tight mt-1">
                        Siswa yang Mengerjakan Proyek Ini
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-400">
                    {{ $allMembers->count() }} siswa vokasi berkontribusi dalam perancangan &amp; pembuatan karya ini
                </p>
            </div>

            {{-- Responsive Grid: Mobile 1 Col, Tablet 2 Col, Desktop 3 Col --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($allMembers as $idx => $member)
                    @php
                        $initials = strtoupper(substr($member['name'], 0, 2));
                        $gradient = $avatarGradients[$idx % count($avatarGradients)];
                    @endphp
                    
                    <div class="group relative bg-slate-900/80 hover:bg-slate-900 border border-slate-800 hover:border-indigo-500/50 rounded-3xl p-6 sm:p-7 transition-all duration-300 hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-1 flex flex-col justify-between overflow-hidden">
                        
                        {{-- Subtle Card Glow Hover Effect --}}
                        <div class="absolute -right-16 -top-16 w-36 h-36 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all pointer-events-none"></div>

                        <div class="space-y-5 relative z-10">
                            
                            {{-- Header Avatar & Badges --}}
                            <div class="flex items-start justify-between gap-4">
                                {{-- Large & Visible Student Portrait --}}
                                <div class="relative">
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl overflow-hidden ring-2 ring-indigo-500/30 group-hover:ring-indigo-400 shadow-xl bg-slate-950 flex-shrink-0 transition-all duration-300">
                                        @if($member['avatar_url'])
                                            <img
                                                src="{{ str_starts_with($member['avatar_url'], 'http') ? $member['avatar_url'] : asset('storage/' . ltrim(str_replace('/storage/', '', $member['avatar_url']), '/')) }}"
                                                alt="{{ $member['name'] }}"
                                                loading="lazy"
                                                decoding="async"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500 opacity-0 blur-xs"
                                                onload="this.classList.remove('opacity-0', 'blur-xs'); this.classList.add('opacity-100', 'blur-0');"
                                            >
                                        @else
                                            <div class="w-full h-full bg-gradient-to-tr {{ $gradient }} flex items-center justify-center font-black text-2xl sm:text-3xl tracking-tight shadow-inner">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                    </div>
                                    {{-- Active Status Dot --}}
                                    <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 ring-4 ring-slate-900" title="Siswa Terverifikasi"></span>
                                </div>

                                {{-- Submitter / Contributor Tag --}}
                                <div>
                                    @if(!empty($member['is_primary']))
                                        <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider text-amber-300 bg-amber-500/20 border border-amber-500/30 px-3 py-1 rounded-full">
                                            ★ Ketua Tim
                                        </span>
                                    @elseif(!empty($member['is_guest']))
                                        <span class="inline-block text-[10px] font-bold uppercase text-slate-400 bg-white/5 border border-white/10 px-2.5 py-0.5 rounded-full">
                                            Kolaborator
                                        </span>
                                    @else
                                        <span class="inline-block text-[10px] font-bold uppercase text-indigo-300 bg-indigo-500/20 border border-indigo-500/30 px-2.5 py-0.5 rounded-full">
                                            Siswa TEFA
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Student Name & Class --}}
                            <div>
                                <h3 class="font-black text-lg sm:text-xl text-white group-hover:text-indigo-400 transition-colors line-clamp-1">
                                    {{ $member['name'] }}
                                </h3>
                                @if($member['class_name'])
                                    <p class="text-xs text-slate-400 font-semibold mt-0.5">
                                        {{ $member['class_name'] }}
                                    </p>
                                @endif
                            </div>

                            {{-- Role Pill Badge --}}
                            <div>
                                <span class="inline-flex items-center gap-1.5 text-xs font-black px-3 py-1.5 rounded-xl bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                                    <svg class="w-3.5 h-3.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    <span class="truncate">{{ $member['role'] }}</span>
                                </span>
                            </div>

                            {{-- Bio if available --}}
                            @if(!empty($member['bio']))
                                <p class="text-xs text-slate-400 leading-relaxed line-clamp-2 italic pt-1">
                                    "{{ $member['bio'] }}"
                                </p>
                            @endif

                            {{-- Skills badges --}}
                            @if($member['skills'] && $member['skills']->count() > 0)
                                <div class="pt-1">
                                    <p class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Stack Keahlian</p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($member['skills']->take(4) as $skill)
                                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-lg border bg-white/5 border-white/10 text-slate-300">
                                                {{ $skill->name }}
                                            </span>
                                        @endforeach
                                        @if($member['skills']->count() > 4)
                                            <span class="text-[10px] font-semibold text-slate-500 bg-white/5 px-2 py-0.5 rounded-lg border border-white/10">
                                                +{{ $member['skills']->count() - 4 }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                        </div>

                        {{-- Footer Action Buttons: Modern Digital Connectivity --}}
                        <div class="pt-5 mt-5 border-t border-slate-800/80 relative z-10">
                            <div class="flex items-center gap-2">
                                {{-- Official LinkedIn Button --}}
                                @if(!empty($member['linkedin_url']))
                                    <a href="{{ $member['linkedin_url'] }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-[#0A66C2] hover:bg-[#004182] text-white font-bold text-xs shadow-md transition-all duration-200 hover:scale-[1.02]">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                        <span>LinkedIn</span>
                                    </a>
                                @endif

                                {{-- Social / Portfolio Web Button --}}
                                @if(!empty($member['social_url']))
                                    <a href="{{ $member['social_url'] }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="{{ !empty($member['linkedin_url']) ? 'flex-1' : 'w-full' }} inline-flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-white/10 hover:bg-indigo-600 text-white font-bold text-xs border border-white/10 transition-all duration-200 hover:scale-[1.02]">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span>Medsos / Web</span>
                                    </a>
                                @endif

                                {{-- Fallback Badge --}}
                                @if(empty($member['linkedin_url']) && empty($member['social_url']))
                                    <div class="w-full text-center py-2 px-3 rounded-xl bg-white/5 text-slate-500 font-mono text-[11px] border border-white/5">
                                        {{ !empty($member['is_guest']) ? 'Kolaborator Eksternal' : 'Siswa TEFA Terdaftar' }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>

    </div>
</x-public-layout>
