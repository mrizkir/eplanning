<?php

return [
    //setting general
    'nama_institusi'=>env('EPLANNING_NAMA_INSTITUSI'),
    'tahun_perencanaan'=>env('EPLANNING_TAHUN_PERENCANAAN'),
    'tahun_penyerapan'=>env('EPLANNING_TAHUN_PENYERAPAN'),
    'default_provinsi'=>env('EPLANNING_DEFAULT_PROVINSI'),
    'defaul_kota_atau_kab'=>env('EPLANNING_DEFAULT_KOTA_KAB'),

    //setting rpjmd
    'rpjmd_tahun_mulai'=>env('EPLANNING_RPJMD_TAHUN_MULAI'),
    'rpjmd_tahun_akhir'=>env('EPLANNING_RPJMD_TAHUN_AKHIR'),
    'renstra_tahun_mulai'=>env('EPLANNING_RENSTRA_TAHUN_MULAI'),
    'renstra_tahun_akhir'=>env('EPLANNING_RENSTRA_TAHUN_AKHIR'),

    /**
     * Saat mengekspor dan mingimpor file, e-Planning membutuhkan lokasi tempat penyimpanan sementara
     * disini anda bisa mengganti lokasinya.
     */
    'local_path' => env('EPLANNING_LOCAL_PATH', sys_get_temp_dir()),

    'log_pattern' => env('EPLANNING_LOG_PATTERN', '*.log'),
    'storage_path' => env('EPLANNING_STORAGE_PATH', storage_path('logs')),
];