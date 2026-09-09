<?php

return [

    /*
     * Semua model di direktori ini akan dipindai untuk pembuatan diagram ERD.
     */
    'directories' => [
        base_path('app' . DIRECTORY_SEPARATOR . 'Models'),
    ],

    /*
     * Abaikan model tertentu jika terlalu memenuhi diagram.
     */
    'ignore' => [
        // Contoh: App\Models\PersonalAccessToken::class,
    ],

    /*
     * Jika diisi, HANYA model di sini yang akan ditampilkan.
     */
    'whitelist' => [
        // App\Models\User::class,
    ],

    /*
     * Alias nama model di dalam diagram.
     */
    'aliases' => [
        // User::class => 'CustomUser',
    ],

    'recursive' => true,

    'use_db_schema' => true,

    /*
     * SET TO FALSE: Menyembunyikan tipe data (VARCHAR, INT, dll)
     * Ini menghemat ruang hingga 40% dan membuat tabel jauh lebih bersih.
     */
    'use_column_types' => false,

    /*
     * Sembunyikan kolom umum yang mengotori tampilan tabel.
     */
    'ignore_columns' => [
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'password',
    ],

    /*
     * Warna header dan baris tabel (Kontras tinggi & modern).
     */
    'table' => [
        'header_background_color' => '#1E293B', // Dark Slate Blue
        'header_font_color'       => '#FFFFFF', // Putih
        'row_background_color'    => '#FFFFFF',
        'row_font_color'          => '#0F172A',
    ],

    /*
     * Pengaturan Utama Graphviz Engine
     */
    'graph' => [
        'style'       => 'filled',
        'bgcolor'     => '#F8FAFC',
        'fontsize'    => 14,
        'labelloc'    => 't',
        'concentrate' => false,       // Nonaktifkan agar garis relasi tidak menyatu secara membingungkan
        'splines'     => 'ortho',     // Memaksa garis siku (90 derajat), sangat rapi dibanding 'polyline'
        'overlap'     => 'false',
        'nodesep'     => 1.5,         // Jarak horizontal antar kotak model (diperbesar)
        'rankdir'     => 'LR',        // Layout dari Kiri ke Kanan (Left to Right)
        'pad'         => 0.8,
        'ranksep'     => 2.5,         // Jarak vertikal antar kolom hirarki (diperbesar)
        'esep'        => true,
        'fontname'    => 'Arial'
    ],

    'node' => [
        'margin'   => 0.2,
        'shape'    => 'rectangle',
        'fontname' => 'Arial'
    ],

    'edge' => [
        'color'    => '#475569',
        'penwidth' => 1.5,
        'fontname' => 'Arial'
    ],

    'relations' => [
        'HasOne' => [
            'dir'       => 'both',
            'color'     => '#EF4444', // Merah
            'arrowhead' => 'tee',
            'arrowtail' => 'none',
        ],
        'BelongsTo' => [
            'dir'       => 'both',
            'color'     => '#3B82F6', // Biru
            'arrowhead' => 'tee',
            'arrowtail' => 'crow',
        ],
        'HasMany' => [
            'dir'       => 'both',
            'color'     => '#10B981', // Hijau
            'arrowhead' => 'crow',
            'arrowtail' => 'none',
        ],
    ]

];