<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Brosur Katalog TEFA {{ now()->year }}</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #111827;
            --muted: #64748b;
            --primary: #4f46e5;
            --primary-dark: #312e81;
            --blue: #3b82f6;
            --cyan: #67e8f9;
            --tint: #eef2ff;
            --line: #e2e8f0;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: #edf2ef;
            color: var(--ink);
            font-family: "Segoe UI", Arial, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .toolbar {
            position: sticky;
            z-index: 5;
            top: 0;
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 14px;
            background: rgba(17, 24, 39, .97);
            box-shadow: 0 4px 18px rgba(17,24,39,.18);
        }
        .toolbar a, .toolbar button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 40px;
            padding: 0 16px;
            border: 1px solid rgba(255,255,255,.28);
            border-radius: 8px;
            background: transparent;
            color: #fff;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }
        .toolbar button { border-color: var(--primary); background: var(--primary); }
        .sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 22px auto;
            padding: 17mm;
            background: #fff;
            box-shadow: 0 12px 38px rgba(30,41,59,.12);
        }
        .cover {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            min-height: 263mm;
            padding: 17mm 15mm;
            border: 1px solid #c7d2fe;
            background:
                radial-gradient(ellipse at 88% 82%, rgba(34,211,238,.2), transparent 34%),
                radial-gradient(ellipse at 5% 100%, rgba(99,102,241,.4), transparent 48%),
                linear-gradient(138deg, #111827 0%, #1e1b4b 52%, #312e81 100%);
        }
        .cover::before {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255,255,255,.045) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.045) 1px, transparent 1px);
            background-size: 12mm 12mm;
            content: "";
            opacity: .45;
        }
        .cover::after {
            position: absolute;
            right: -37mm;
            bottom: 27mm;
            width: 112mm;
            height: 112mm;
            border: 1px solid rgba(103,232,249,.32);
            box-shadow: 0 0 0 12mm rgba(99,102,241,.1), 0 0 0 26mm rgba(99,102,241,.06);
            border-radius: 50%;
            content: "";
        }
        .brandline, .cover-copy, .cover-footer { position: relative; z-index: 1; }
        .brandline { display: flex; align-items: center; gap: 13px; color: #fff; font-size: 10px; font-weight: 800; letter-spacing: .1em; line-height: 1.5; text-transform: uppercase; }
        .school-logo { display: block; width: 20mm; height: 20mm; padding: 2mm; border: 1px solid rgba(255,255,255,.55); border-radius: 16px; background: #fff; object-fit: contain; }
        .cover-copy { max-width: 145mm; margin-top: -12mm; }
        .eyebrow { color: var(--cyan); font-size: 9px; font-weight: 800; letter-spacing: .19em; text-transform: uppercase; }
        h1 { margin: 12px 0 16px; color: #fff; font-family: "Segoe UI", Arial, sans-serif; font-size: 43px; font-weight: 800; letter-spacing: -.045em; line-height: 1.04; }
        .cover-copy p { max-width: 112mm; margin: 0; color: #cbd5e1; font-size: 13px; line-height: 1.75; }
        .cover-counts { display: flex; gap: 34px; margin-top: 27px; }
        .cover-counts strong { display: block; color: var(--cyan); font-family: "Segoe UI", Arial, sans-serif; font-size: 30px; font-weight: 800; }
        .cover-counts span { color: #cbd5e1; font-size: 9px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; }
        .cover-footer { display: flex; justify-content: space-between; align-items: end; gap: 12px; border-top: 1px solid rgba(255,255,255,.2); padding-top: 14px; color: #cbd5e1; font-size: 9px; }
        .unit-section { margin-top: 10mm; }
        .unit-heading { display: flex; align-items: center; gap: 14px; margin-bottom: 7mm; padding: 4mm 5mm; border: 1px solid #e0e7ff; border-left: 3px solid var(--primary); border-radius: 10px; background: linear-gradient(110deg, #f5f7ff, #fff 74%); break-after: avoid; page-break-after: avoid; }
        .unit-logo { display: grid; flex: 0 0 56px; width: 56px; height: 56px; place-items: center; overflow: hidden; border: 1px solid #c7d2fe; border-radius: 14px; background: var(--tint); color: var(--primary); font-family: "Segoe UI", Arial, sans-serif; font-size: 20px; font-weight: 800; }
        .unit-logo img { width: 100%; height: 100%; object-fit: contain; }
        .unit-kicker { margin: 0 0 4px; color: var(--primary); font-size: 8px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        h2 { margin: 0; font-family: "Segoe UI", Arial, sans-serif; font-size: 23px; font-weight: 750; letter-spacing: -.025em; line-height: 1.2; }
        .unit-subtitle { margin: 5px 0 0; color: var(--muted); font-size: 10px; }
        .product-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 5mm; align-items: stretch; }
        .product { display: flex; flex-direction: column; overflow: hidden; border: 1px solid #dfe5ee; border-radius: 10px; background: #fff; break-inside: avoid; page-break-inside: avoid; }
        .product-image { display: grid; height: 38mm; flex: 0 0 38mm; place-items: center; overflow: hidden; background: linear-gradient(145deg, #eef2ff, #f8fafc); color: #818cf8; }
        .product-image img { width: 100%; height: 100%; object-fit: cover; }
        .product-body { display: flex; flex: 1; flex-direction: column; padding: 3.5mm 4mm 4mm; }
        .product-meta { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 2.5mm; }
        .tag { padding: 1mm 2mm; border-radius: 20px; background: var(--tint); color: var(--primary-dark); font-size: 7px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
        .category { overflow: hidden; color: var(--muted); font-size: 7.5px; text-overflow: ellipsis; white-space: nowrap; }
        h3 { margin: 0 0 2mm; color: var(--ink); font-size: 12px; line-height: 1.35; }
        .description { display: -webkit-box; overflow: hidden; min-height: 26px; margin: 0 0 3mm; color: var(--muted); font-size: 8.5px; line-height: 1.5; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }
        .price { display: flex; align-items: baseline; justify-content: space-between; gap: 8px; margin-top: auto; padding-top: 2.5mm; border-top: 1px solid var(--line); color: var(--primary-dark); font-family: "Segoe UI", Arial, sans-serif; font-size: 13px; font-weight: 800; }
        .price small { color: var(--muted); font-family: "Segoe UI", Arial, sans-serif; font-size: 7px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        .empty { padding: 35mm 10mm; color: var(--muted); text-align: center; }
        @page { size: A4 portrait; margin: 12mm; }
        @media print {
            body { background: #fff; }
            .toolbar { display: none !important; }
            .sheet { width: auto; min-height: 0; margin: 0; padding: 0; box-shadow: none; }
            .cover { min-height: 273mm; break-after: page; page-break-after: always; }
            .unit-section { margin-top: 0; }
            .unit-section + .unit-section { margin-top: 0; break-before: page; page-break-before: always; }
            .product { box-shadow: none; }
            a { color: inherit; text-decoration: none; }
        }
        @media screen and (max-width: 760px) {
            .sheet { width: calc(100% - 24px); min-height: 0; margin: 12px; padding: 18px; }
            .cover { min-height: 680px; padding: 28px 22px; }
            .school-logo { width: 62px; height: 62px; padding: 6px; }
            .cover-copy { margin-top: 0; }
            h1 { font-size: 34px; }
            .product-grid { grid-template-columns: 1fr; }
            .product-image { height: 58vw; max-height: 280px; flex-basis: auto; }
            .toolbar { position: static; }
        }
    </style>
</head>
<body>
    <nav class="toolbar" aria-label="Aksi brosur">
        <a href="{{ route('superadmin.dashboard') }}">&#8592; Dashboard</a>
        <button type="button" onclick="window.print()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
            Cetak / Simpan PDF
        </button>
    </nav>

    <main class="sheet">
        @if($units->isEmpty())
            <section class="empty">
                <h1>Katalog belum tersedia</h1>
                <p>Belum ada produk terbit dari unit TEFA aktif.</p>
            </section>
        @else
            @php($productCount = $units->sum(fn ($unit) => $unit->catalogItems->count()))
            <section class="cover">
                <div class="brandline">
                    <img class="school-logo" src="{{ asset('assets/images/school-logo.jpg') }}" alt="Logo sekolah">
                    <span>Teaching Factory<br>Produk & Karya Vokasi</span>
                </div>
                <div class="cover-copy">
                    <div class="eyebrow">Katalog Digital TEFA · {{ now()->year }}</div>
                    <h1>Karya vokasi,<br>siap untuk Anda.</h1>
                    <p>Temukan produk, layanan, dan karya pilihan dari unit Teaching Factory. Setiap item dibuat dengan keterampilan dan semangat inovasi siswa vokasi.</p>
                    <div class="cover-counts">
                        <div><strong>{{ $productCount }}</strong><span>Produk & layanan</span></div>
                        <div><strong>{{ $units->count() }}</strong><span>Unit TEFA</span></div>
                    </div>
                </div>
                <div class="cover-footer">
                    <span>Direktori produk Teaching Factory</span>
                    <span>Diperbarui {{ now()->translatedFormat('d F Y') }}</span>
                </div>
            </section>

            @foreach($units as $unit)
                <section class="unit-section">
                    <header class="unit-heading">
                        <div class="unit-logo">
                            @if($unit->logo_url)
                                <img src="{{ filter_var($unit->logo_url, FILTER_VALIDATE_URL) ? $unit->logo_url : asset('storage/' . $unit->logo_url) }}" alt="Logo {{ $unit->name }}">
                            @else
                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($unit->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <p class="unit-kicker">Unit Teaching Factory</p>
                            <h2>{{ $unit->name }}</h2>
                            <p class="unit-subtitle">{{ $unit->catalogItems->count() }} produk dan layanan pilihan</p>
                        </div>
                    </header>

                    <div class="product-grid">
                        @foreach($unit->catalogItems as $item)
                            <article class="product">
                                <div class="product-image">
                                    @if($item->thumbnail_url)
                                        <img src="{{ filter_var($item->thumbnail_url, FILTER_VALIDATE_URL) ? $item->thumbnail_url : asset('storage/' . $item->thumbnail_url) }}" alt="{{ $item->title }}">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                                    @endif
                                </div>
                                <div class="product-body">
                                    <div class="product-meta">
                                        <span class="tag">{{ $item->item_type?->label() ?? 'Produk' }}</span>
                                        <span class="category">{{ $item->category?->name ?? 'Katalog TEFA' }}</span>
                                    </div>
                                    <h3>{{ $item->title }}</h3>
                                    <p class="description">{{ \Illuminate\Support\Str::limit(strip_tags($item->description ?? ''), 105) ?: 'Produk unggulan karya unit Teaching Factory.' }}</p>
                                    <div class="price">
                                        @if($item->price)
                                            Rp{{ number_format((float) $item->price, 0, ',', '.') }}
                                        @else
                                            Hubungi unit
                                        @endif
                                        <small>{{ $item->price ? 'HARGA' : 'INFO HARGA' }}</small>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endforeach
        @endif
    </main>
    <script>
        window.addEventListener('load', () => window.setTimeout(() => window.print(), 300));
    </script>
</body>
</html>