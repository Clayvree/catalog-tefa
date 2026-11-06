<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Statistik TEFA - {{ now()->format('d/m/Y') }}</title>
    <style>
        :root {
            --ink: #0f172a;
            --muted: #64748b;
            --primary: #4f46e5;
            --line: #e2e8f0;
            --bg: #f8fafc;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: #e2e8f0;
            color: var(--ink);
            font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, Arial, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .toolbar {
            position: sticky;
            z-index: 50;
            top: 0;
            display: flex;
            justify-content: center;
            gap: 12px;
            padding: 14px;
            background: #0f172a;
            box-shadow: 0 4px 18px rgba(15,23,42,.25);
        }
        .toolbar a, .toolbar button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 38px;
            padding: 0 16px;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 10px;
            background: transparent;
            color: #fff;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .toolbar a:hover, .toolbar button:hover {
            background: rgba(255,255,255,.12);
            border-color: #fff;
        }
        .toolbar .btn-primary {
            background: #4f46e5;
            border-color: #4f46e5;
        }
        .toolbar .btn-primary:hover {
            background: #4338ca;
        }

        .sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto 40px;
            background: #fff;
            padding: 18mm 16mm;
            box-shadow: 0 10px 35px rgba(0,0,0,.08);
            border-radius: 4px;
        }

        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--ink);
            margin-bottom: 20px;
        }
        .brand {
            font-size: 24px;
            font-weight: 900;
            color: var(--ink);
            letter-spacing: -0.5px;
        }
        .brand span {
            color: var(--primary);
        }
        .report-title {
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #1e293b;
            margin-top: 4px;
        }
        .report-sub {
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
        }
        .meta-box {
            text-align: right;
            font-size: 11px;
            color: var(--muted);
            line-height: 1.5;
        }
        .meta-box strong {
            color: var(--ink);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: var(--bg);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 12px;
        }
        .stat-card .label {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--muted);
            letter-spacing: 0.5px;
        }
        .stat-card .value {
            font-size: 18px;
            font-weight: 900;
            color: var(--ink);
            margin-top: 4px;
        }
        .stat-card .sub {
            font-size: 9px;
            color: #10b981;
            font-weight: 700;
            margin-top: 2px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #1e293b;
            margin: 20px 0 10px;
            padding-bottom: 4px;
            border-bottom: 1px solid var(--line);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .section-title span {
            font-size: 10px;
            color: var(--muted);
            font-weight: 600;
            text-transform: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 20px;
        }
        th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            text-align: left;
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        tr:nth-child(even) td {
            background: #fafbfc;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-blue { background: #e0e7ff; color: #3730a3; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-gray { background: #f1f5f9; color: #475569; }

        .footer-sign {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .sign-box {
            width: 200px;
            text-align: center;
            font-size: 11px;
        }
        .sign-space {
            height: 60px;
        }
        .sign-name {
            font-weight: 800;
            border-top: 1px solid #cbd5e1;
            padding-top: 4px;
        }

        @media print {
            body { background: #fff; }
            .toolbar { display: none !important; }
            .sheet {
                width: 100% !important;
                margin: 0 !important;
                padding: 10mm 12mm !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button type="button" class="btn-primary" onclick="window.print()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
            Cetak / Simpan PDF
        </button>
        <a href="{{ route('superadmin.dashboard') }}">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <div class="sheet">
        <div class="header">
            <div>
                <div class="brand">Tefa<span>Hub</span></div>
                <div class="report-title">Laporan Ringkasan Statistik & Kinerja TEFA</div>
                <div class="report-sub">Sistem Manajemen Teaching Factory Vokasi Terpadu</div>
            </div>
            <div class="meta-box">
                <div>Tanggal Cetak: <strong>{{ now()->translatedFormat('d F Y, H:i') }} WIB</strong></div>
                <div>Dicetak Oleh: <strong>{{ auth()->user()->name }} (Super Admin)</strong></div>
                <div>Status Data: <strong style="color: #10b981;">Tervalidasi Sistem</strong></div>
            </div>
        </div>

        <!-- 4 Key Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="label">Total Omset Est.</div>
                <div class="value">Rp{{ number_format((float)$stats['total_revenue_est'], 0, ',', '.') }}</div>
                <div class="sub">Akumulasi Seluruh Proyek</div>
            </div>
            <div class="stat-card">
                <div class="label">Unit TEFA</div>
                <div class="value">{{ $stats['total_units'] }}</div>
                <div class="sub" style="color: #4f46e5;">Jurusan Vokasi</div>
            </div>
            <div class="stat-card">
                <div class="label">Katalog Produk</div>
                <div class="value">{{ $stats['total_products'] }}</div>
                <div class="sub" style="color: #64748b;">Barang & Jasa</div>
            </div>
            <div class="stat-card">
                <div class="label">Talenta Siswa</div>
                <div class="value">{{ $stats['total_workers'] }}</div>
                <div class="sub" style="color: #64748b;">Worker Aktif</div>
            </div>
        </div>

        <!-- Chart Section for PDF -->
        <div style="margin-bottom: 20px; padding: 12px 14px; background: var(--bg); border: 1px solid var(--line); border-radius: 12px; page-break-inside: avoid;">
            <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; color: #1e293b; margin-bottom: 6px; letter-spacing: 0.5px;">
                Grafik Tren Pertumbuhan Nilai Transaksi & Jumlah Proyek Masuk
            </div>
            <div style="height: 150px; width: 100%;">
                <canvas id="pdfTrendChart"></canvas>
            </div>
        </div>

        <!-- Unit TEFA Breakdown -->
        <div class="section-title">
            Performa Unit Teaching Factory (Jurusan)
            <span>Total: {{ $units->count() }} Unit</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Unit Jurusan</th>
                    <th style="text-align: center;">Katalog</th>
                    <th style="text-align: center;">Siswa</th>
                    <th style="text-align: center;">Proyek</th>
                    <th style="text-align: right;">Total Nilai Proyek</th>
                    <th style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $idx => $u)
                    <tr>
                        <td style="width: 30px;">{{ $idx + 1 }}</td>
                        <td><strong>{{ $u->name }}</strong></td>
                        <td style="text-align: center;">{{ $u->catalog_items_count }}</td>
                        <td style="text-align: center;">{{ $u->worker_profiles_count }}</td>
                        <td style="text-align: center;">{{ $u->total_projects }}</td>
                        <td style="text-align: right; font-weight: 800;">Rp{{ number_format((float)$u->total_revenue, 0, ',', '.') }}</td>
                        <td style="text-align: center;">
                            @if($u->is_active)
                                <span class="badge badge-green">Aktif</span>
                            @else
                                <span class="badge badge-gray">Non-Aktif</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Recent Projects -->
        <div class="section-title">
            Daftar Pesanan & Proyek Terkini
            <span>Menampilkan {{ min(10, $projects->count()) }} proyek terbaru</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Proyek</th>
                    <th>Unit Jurusan</th>
                    <th>Klien</th>
                    <th style="text-align: right;">Nilai Transaksi</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: right;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects->take(10) as $idx => $p)
                    <tr>
                        <td style="width: 30px;">{{ $idx + 1 }}</td>
                        <td><strong>{{ $p->title }}</strong></td>
                        <td>{{ $p->tefaUnit->name ?? '-' }}</td>
                        <td>{{ $p->client_name }}</td>
                        <td style="text-align: right; font-weight: 800;">
                            @if($p->final_price)
                                Rp{{ number_format((float)$p->final_price, 0, ',', '.') }}
                            @else
                                <span style="color: #94a3b8; font-weight: normal;">-</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @php
                                $statusClass = match($p->status?->value ?? '') {
                                    'completed' => 'badge-green',
                                    'active' => 'badge-blue',
                                    'in_progress' => 'badge-yellow',
                                    default => 'badge-gray',
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                {{ $p->status?->label() ?? 'Aktif' }}
                            </span>
                        </td>
                        <td style="text-align: right; color: var(--muted);">{{ $p->created_at?->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--muted); padding: 16px;">Belum ada data proyek.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Signatures -->
        <div class="footer-sign">
            <div class="sign-box">
                <div>Mengetahui,</div>
                <div>Kepala Sekolah / Penanggung Jawab</div>
                <div class="sign-space"></div>
                <div class="sign-name">( ........................................ )</div>
            </div>
            <div class="sign-box">
                <div>Dikeluarkan Oleh,</div>
                <div>Ketua TEFA / Super Admin</div>
                <div class="sign-space"></div>
                <div class="sign-name">{{ auth()->user()->name }}</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);
            const ctx = document.getElementById('pdfTrendChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.monthly.labels,
                        datasets: [
                            {
                                label: 'Estimasi Nilai Proyek (Rp)',
                                data: chartData.monthly.revenue,
                                backgroundColor: 'rgba(79, 70, 229, 0.85)',
                                borderColor: 'rgb(79, 70, 229)',
                                borderWidth: 1,
                                borderRadius: 6,
                                yAxisID: 'yRevenue',
                                order: 2
                            },
                            {
                                label: 'Jumlah Proyek Masuk',
                                data: chartData.monthly.projects,
                                type: 'line',
                                borderColor: 'rgb(16, 185, 129)',
                                backgroundColor: 'rgba(16, 185, 129, 0.15)',
                                borderWidth: 2.5,
                                pointBackgroundColor: 'rgb(16, 185, 129)',
                                pointRadius: 3,
                                tension: 0.3,
                                fill: true,
                                yAxisID: 'yCount',
                                order: 1
                            }
                        ]
                    },
                    options: {
                        animation: false,
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 10,
                                    font: { size: 9, weight: 'bold' }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 9 } }
                            },
                            yRevenue: {
                                type: 'linear',
                                position: 'left',
                                grid: { color: 'rgba(226, 232, 240, 0.6)' },
                                ticks: {
                                    font: { size: 8 },
                                    callback: function(v) {
                                        if (v >= 1000000) return (v / 1000000).toFixed(1) + 'M';
                                        if (v >= 1000) return (v / 1000).toFixed(0) + 'k';
                                        return v;
                                    }
                                }
                            },
                            yCount: {
                                type: 'linear',
                                position: 'right',
                                grid: { display: false },
                                ticks: {
                                    stepSize: 1,
                                    font: { size: 8 }
                                }
                            }
                        }
                    }
                });
            }

            window.setTimeout(() => window.print(), 400);
        });
    </script>
</body>
</html>
