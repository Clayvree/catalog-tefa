<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Brosur Katalog TEFA {{ now()->year }}</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #172b2b;
            --muted: #657575;
            --green: #126b5b;
            --mint: #e6f2ed;
            --coral: #e86e52;
            --line: #dce6e1;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: #e9eeeb;
            color: var(--ink);
            font-family: "Trebuchet MS", "Segoe UI", sans-serif;
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
            background: rgba(23, 43, 43, .96);
        }
        .toolbar a, .toolbar button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 40px;
            padding: 0 16px;
            border: 1px solid rgba(255,255,255,.28);
            border-radius: 6px;
            background: transparent;
            color: #fff;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }
        .toolbar button { border-color: var(--coral); background: var(--coral); }
        .sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 22px auto;
            padding: 17mm;
            background: #fff;
            box-shadow: 0 12px 38px rgba(23,43,43,.12);
        }
        .cover {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            min-height: 263mm;
            padding: 15mm 13mm;
            border: 1px solid var(--line);
            background: linear-gradient(145deg, #f4f8f5 0%, #fff 65%);
        }
        .cover::after {
            position: absolute;
            right: -34mm;
            bottom: 34mm;
            width: 100mm;
            height: 100mm;
            border: 19mm solid var(--mint);
            border-radius: 50%;
            content: "";
        }
        .brandline, .cover-copy, .cover-footer { position: relative; z-index: 1; }
        .brandline { display: flex; align-items: center; gap: 12px; color: var(--green); font-size: 11px; font-weight: 800; text-transform: uppercase; }
        .brandmark { display: grid; width: 38px; height: 38px; place-items: center; border-radius: 50%; background: var(--green); color: white; font-size: 14px; }
        .cover-copy { max-width: 145mm; margin-top: -18mm; }
        .eyebrow { color: var(--coral); font-size: 10px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        h1 { margin: 10px 0 12px; color: var(--ink); font-family: Georgia, "Times New Roman", serif; font-size: 42px; font-weight: 700; line-height: 1.04; }
        .cover-copy p { max-width: 110mm; margin: 0; color: var(--muted); font-size: 14px; line-height: 1.65; }
        .cover-counts { display: flex; gap: 26px; margin-top: 25px; }
        .cover-counts strong { display: block; color: var(--green); font-family: Georgia, "Times New Roman", serif; font-size: 27px; }
        .cover-counts span { color: var(--muted); font-size: 10px; font-weight: 700; text-transform: uppercase; }
        .cover-footer { display: flex; justify-content: space-between; align-items: end; border-top: 1px solid var(--line); padding-top: 12px; color: var(--muted); font-size: 10px; }
        .unit-section { margin-top: 4mm; }
        .unit-heading { display: flex; align-items: center; gap: 13px; margin-bottom: 15px; padding-bottom: 12px; border-bottom: 2px solid var(--green); break-after: avoid; }
        .unit-logo { display: grid; flex: 0 0 48px; width: 48px; height: 48px; place-items: center; overflow: hidden; border-radius: 50%; background: var(--mint); color: var(--green); font-family: Georgia, "Times New Roman", serif; font-size: 18px; font-weight: 700; }
        .unit-logo img { width: 100%; height: 100%; object-fit: cover; }
        .unit-kicker { margin: 0 0 3px; color: var(--coral); font-size: 9px; font-weight: 800; text-transform: uppercase; }
        h2 { margin: 0; font-family: Georgia, "Times New Roman", serif; font-size: 22px; }
        .unit-subtitle { margin: 4px 0 0; color: var(--muted); font-size: 10px; }
        .product-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; align-items: start; }
        .product { overflow: hidden; border: 1px solid var(--line); border-radius: 5px; background: #fff; break-inside: avoid; page-break-inside: avoid; }
        .product-image { display: grid; height: 45mm; place-items: center; overflow: hidden; background: #eef3f0; color: #92a49c; }
        .product-image img { width: 100%; height: 100%; object-fit: cover; }
        .product-body { padding: 10px 11px 11px; }
        .product-meta { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 6px; }
        .tag { color: var(--green); font-size: 8px; font-weight: 800; text-transform: uppercase; }
        .category { overflow: hidden; color: var(--muted); font-size: 8px; text-overflow: ellipsis; white-space: nowrap; }
        h3 { margin: 0 0 5px; font-size: 13px; line-height: 1.25; }
        .description { display: -webkit-box; overflow: hidden; min-height: 28px; margin: 0 0 10px; color: var(--muted); font-size: 9px; line-height: 1.5; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }
        .price { padding-top: 7px; border-top: 1px solid var(--line); color: var(--green); font-family: Georgia, "Times New Roman", serif; font-size: 15px; font-weight: 700; }
        .price small { color: var(--muted); font-family: "Trebuchet MS", "Segoe UI", sans-serif; font-size: 8px; font-weight: 600; }
        .empty { padding: 35mm 10mm; color: var(--muted); text-align: center; }
        @page { size: A4 portrait; margin: 12mm; }
        @media print {
            body { background: #fff; }
            .toolbar { display: none !important; }
            .sheet { width: auto; min-height: 0; margin: 0; padding: 0; box-shadow: none; }
            .cover { min-height: 273mm; break-after: page; page-break-after: always; }
            .unit-section { margin-top: 0; }
            .unit-section + .unit-section { margin-top: 12mm; }
            .product { box-shadow: none; }
            a { color: inherit; text-decoration: none; }
        }
        @media screen and (max-width: 760px) {
            .sheet { width: calc(100% - 24px); min-height: 0; margin: 12px; padding: 18px; }
            .cover { min-height: 680px; padding: 28px 22px; }
            h1 { font-size: 34px; }
            .product-grid { grid-template-columns: 1fr; }
            .product-image { height: 58vw; max-height: 280px; }
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
                    <span class="brandmark">T</span>
                    <span>Teaching Factory<br>Produk & Karya Vokasi</span>
                </div>
                <div class="cover-copy">
                    <div class="eyebrow">Katalog TEFA · {{ now()->year }}</div>
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
                                <img src="{{ asset('storage/' . $unit->logo_url) }}" alt="Logo {{ $unit->name }}">
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
                                        <img src="{{ asset('storage/' . $item->thumbnail_url) }}" alt="{{ $item->title }}">
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
                                        <small> / item</small>
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