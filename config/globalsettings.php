<?php

return [
    //setting general
    'nama_institusi'=>'BAPELITBANG KAB. BINTAN',
    'tahun_perencanaan'=>2020,
    'tahun_penyerapan'=>2019,
    'default_provinsi'=>'uidF1847004D8F547BF',
    'defaul_kota_atau_kab'=>'uidE4829D1F21F44ECA',
    //setting rpjmd
    'rpjmd_tahun_mulai'=>2016,
    'rpjmd_tahun_akhir'=>2021,

    /**
     * Saat mengekspor dan mingimpor file, e-Planning membutuhkan lokasi tempat penyimpanan sementara
     * disini anda bisa mengganti lokasinya.
     */
    'local_path' => sys_get_temp_dir()  
];