<?php
Route::group (['prefix'=>'v0'],function() {
    // filepond upload
    Route::post('/file/upload', 'API\v0\FilepondController@upload')->name('filepond.upload');
    Route::delete('/file/delete', 'API\v0\FilepondController@delete')->name('filepond.delete');

    // Rekening
    Route::resource('/rekening/struktur','API\v0\DMaster\RekeningStrukturController',['names'=>'api-v0-rekening-struktur',
                                                                                    'parameters'=>['struktur'=>'uuid'],
                                                                                    'only'=>['index','show']]);

    Route::resource('/rekening/kelompok','API\v0\DMaster\RekeningKelompokController',['names'=>'api-v0-rekening-kelompok',
                                                                                    'parameters'=>['kelompok'=>'uuid'],
                                                                                    'only'=>['index','show']]);

    Route::resource('/rekening/jenis','API\v0\DMaster\RekeningJenisController',['names'=>'api-v0-rekening-jenis',
                                                                            'parameters'=>['jenis'=>'uuid'],
                                                                            'only'=>['index','show']]);

    Route::resource('/rekening/obyek','API\v0\DMaster\RekeningObyekController',['names'=>'api-v0-rekening-obyek',
                                                                            'parameters'=>['obyek'=>'uuid'],
                                                                            'only'=>['index','show']]);

    Route::resource('/rekening/rincianobyek','API\v0\DMaster\RekeningRincianObyekController',['names'=>'api-v0-rekening-rincianobyek',
                                                                                            'parameters'=>['obyek'=>'uuid'],
                                                                                            'only'=>['index','show']]);

    Route::resource('/rekening/subrincianobyek','API\v0\DMaster\RekeningSubRincianObyekController',['names'=>'api-v0-rekening-subrincianobyek',
                                                                                                'parameters'=>['obyek'=>'uuid'],
                                                                                                'only'=>['index','show']]);

    // Masters
    Route::resource('/master/kelompokurusan','API\v0\DMaster\KelompokUrusanController',['names'=>'api-v0-master-kelompokurusan',
                                                                                    'parameters'=>['kelompokurusan'=>'uuid'],
                                                                                    'only'=>['index','show']]);
    Route::resource('/master/urusan','API\v0\DMaster\UrusanController',['names'=>'api-v0-master-urusan',
                                                                    'parameters'=>['urusan'=>'uuid'],
                                                                    'only'=>['index','show']]);

    Route::resource('/master/opd','API\v0\DMaster\OrganisasiController',['names'=>'api-v0-master-opd',
                                                                    'parameters'=>['opd'=>'uuid'],
                                                                    'only'=>['index','show']]);

    Route::resource('/master/unitkerja','API\v0\DMaster\SubOrganisasiController',['names'=>'api-v0-master-unitkerja',
                                                                    'parameters'=>['unitkerja'=>'uuid'],
                                                                    'only'=>['index','show']]);
    
    Route::resource('/master/fungsi','API\v0\DMaster\FungsiController',['names'=>'api-v0-master-fungsi',
                                                                    'parameters'=>['fungsi'=>'uuid'],
                                                                    'only'=>['index','show']]);

    Route::resource('/master/program','API\v0\DMaster\ProgramController',['names'=>'api-v0-master-program',
                                                                    'parameters'=>['program'=>'uuid'],
                                                                    'only'=>['index','show']]);
                                                                    
    Route::get('/master/program/byurusan/{uuid}',['uses'=>'API\v0\DMaster\ProgramController@byurusan','as'=>'api-v0-master-program.byurusan']);


    Route::resource('/master/kegiatan','API\v0\DMaster\KegiatanController',['names'=>'api-v0-master-kegiatan',
                                                                    'parameters'=>['kegiatan'=>'uuid'],
                                                                    'only'=>['index','show']]);

    // Indikator Kinerja [RPJMD]
    Route::resource('/rpjmd/indikatorkinerja','API\v0\RPJMD\IndikatorKinerjaController',['names'=>'api-v0-master-indikatorkinerja',
                                                                    'parameters'=>['indikatorkinerja'=>'uuid'],
                                                                    'only'=>['index','show']]);

    // Plafon [RKPD]
    Route::resource('/rkpd/plafon1','API\v0\RKPD\Plafon1Controller',['names'=>'api-v0-master-plafon1',
                                                                    'parameters'=>['plafon1'=>'uuid'],
                                                                    'only'=>['index','show']]);
    Route::resource('/rkpd/plafon2','API\v0\RKPD\Plafon2Controller',['names'=>'api-v0-master-plafon2',
                                                                    'parameters'=>['plafon2'=>'uuid'],
                                                                    'only'=>['index','show']]);
    Route::resource('/rkpd/plafon3','API\v0\RKPD\Plafon3Controller',['names'=>'api-v0-master-plafon3',
                                                                    'parameters'=>['plafon3'=>'uuid'],
                                                                    'only'=>['index','show']]);
    Route::resource('/rkpd/plafon4','API\v0\RKPD\Plafon4Controller',['names'=>'api-v0-master-plafon4',
                                                                    'parameters'=>['plafon4'=>'uuid'],
                                                                    'only'=>['index','show']]);
    /**
     * RKPD Murni
     */
    Route::resource('/rkpd/plafon5','API\v0\RKPD\Plafon5Controller',['names'=>'api-v0-rkpd-plafon5',
                                                                    'parameters'=>['plafon5'=>'uuid'],
                                                                    'only'=>['index','show']]);
    /**
     * RKPD Perubahan
     */
    Route::resource('/rkpd/plafon6','API\v0\RKPD\Plafon6Controller',['names'=>'api-v0-rkpd-plafon6',
                                                                    'parameters'=>['plafon6'=>'uuid'],
                                                                    'only'=>['index','show']]);
});
Route::group (['prefix'=>'v1','middleware'=>['auth:api']],function() {     

    // Rekening
    Route::resource('/rekening/struktur','API\v1\DMaster\RekeningStrukturController',['names'=>'api-v1-rekening-struktur',
                                                                                    'parameters'=>['struktur'=>'uuid'],
                                                                                    'only'=>['index','show']]);

    Route::resource('/rekening/kelompok','API\v1\DMaster\RekeningKelompokController',['names'=>'api-v1-rekening-kelompok',
                                                                                    'parameters'=>['kelompok'=>'uuid'],
                                                                                    'only'=>['index','show']]);

    Route::resource('/rekening/jenis','API\v1\DMaster\RekeningJenisController',['names'=>'api-v1-rekening-jenis',
                                                                            'parameters'=>['jenis'=>'uuid'],
                                                                            'only'=>['index','show']]);

    Route::resource('/rekening/obyek','API\v1\DMaster\RekeningObyekController',['names'=>'api-v1-rekening-obyek',
                                                                            'parameters'=>['obyek'=>'uuid'],
                                                                            'only'=>['index','show']]);

    Route::resource('/rekening/rincianobyek','API\v1\DMaster\RekeningRincianObyekController',['names'=>'api-v1-rekening-rincianobyek',
                                                                                            'parameters'=>['obyek'=>'uuid'],
                                                                                            'only'=>['index','show']]);

    Route::resource('/rekening/subrincianobyek','API\v1\DMaster\RekeningSubRincianObyekController',['names'=>'api-v1-rekening-subrincianobyek',
                                                                                                'parameters'=>['obyek'=>'uuid'],
                                                                                                'only'=>['index','show']]);

    // Masters
    Route::resource('/master/kelompokurusan','API\v1\DMaster\KelompokUrusanController',['names'=>'api-v1-master-kelompokurusan',
                                                                                    'parameters'=>['kelompokurusan'=>'uuid'],
                                                                                    'only'=>['index','show']]);
    Route::resource('/master/urusan','API\v1\DMaster\UrusanController',['names'=>'api-v1-master-urusan',
                                                                    'parameters'=>['urusan'=>'uuid'],
                                                                    'only'=>['index','show']]);

    Route::resource('/master/opd','API\v1\DMaster\OrganisasiController',['names'=>'api-v1-master-opd',
                                                                    'parameters'=>['opd'=>'uuid'],
                                                                    'only'=>['index','show']]);

    Route::resource('/master/unitkerja','API\v1\DMaster\SubOrganisasiController',['names'=>'api-v1-master-unitkerja',
                                                                    'parameters'=>['unitkerja'=>'uuid'],
                                                                    'only'=>['index','show']]);
    
    Route::resource('/master/fungsi','API\v1\DMaster\FungsiController',['names'=>'api-v1-master-fungsi',
                                                                    'parameters'=>['fungsi'=>'uuid'],
                                                                    'only'=>['index','show']]);

    Route::resource('/master/program','API\v1\DMaster\ProgramController',['names'=>'api-v1-master-program',
                                                                    'parameters'=>['program'=>'uuid'],
                                                                    'only'=>['index','show']]);

    Route::resource('/master/kegiatan','API\v1\DMaster\KegiatanController',['names'=>'api-v1-master-kegiatan',
                                                                    'parameters'=>['kegiatan'=>'uuid'],
                                                                    'only'=>['index','show']]);

    // Indikator Kinerja [RPJMD]
    Route::resource('/rpjmd/indikatorkinerja','API\v1\RPJMD\IndikatorKinerjaController',['names'=>'api-v1-master-indikatorkinerja',
                                                                    'parameters'=>['indikatorkinerja'=>'uuid'],
                                                                    'only'=>['index','show']]);

    // Plafon [RKPD]
    Route::resource('/rkpd/plafon1','API\v1\RKPD\Plafon1Controller',['names'=>'api-v1-master-plafon1',
                                                                    'parameters'=>['plafon1'=>'uuid'],
                                                                    'only'=>['index','show']]);
    Route::resource('/rkpd/plafon2','API\v1\RKPD\Plafon2Controller',['names'=>'api-v1-master-plafon2',
                                                                    'parameters'=>['plafon2'=>'uuid'],
                                                                    'only'=>['index','show']]);
    Route::resource('/rkpd/plafon3','API\v1\RKPD\Plafon3Controller',['names'=>'api-v1-master-plafon3',
                                                                    'parameters'=>['plafon3'=>'uuid'],
                                                                    'only'=>['index','show']]);
    Route::resource('/rkpd/plafon4','API\v1\RKPD\Plafon4Controller',['names'=>'api-v1-master-plafon4',
                                                                    'parameters'=>['plafon4'=>'uuid'],
                                                                    'only'=>['index','show']]);

});