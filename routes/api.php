<?php

Route::group (['prefix'=>'v1','middleware'=>['auth:api']],function() {     

    // Rekening
    Route::resource('/rekening/struktur','API\DMaster\RekeningStrukturController',['names'=>'api-rekening-struktur',
                                                                                    'parameters'=>['struktur'=>'uuid'],
                                                                                    'only'=>['index','show']]);

    Route::resource('/rekening/kelompok','API\DMaster\RekeningKelompokController',['names'=>'api-rekening-kelompok',
                                                                                    'parameters'=>['kelompok'=>'uuid'],
                                                                                    'only'=>['index','show']]);

    Route::resource('/rekening/jenis','API\DMaster\RekeningJenisController',['names'=>'api-rekening-jenis',
                                                                            'parameters'=>['jenis'=>'uuid'],
                                                                            'only'=>['index','show']]);

    Route::resource('/rekening/obyek','API\DMaster\RekeningObyekController',['names'=>'api-rekening-obyek',
                                                                            'parameters'=>['obyek'=>'uuid'],
                                                                            'only'=>['index','show']]);

    Route::resource('/rekening/rincianobyek','API\DMaster\RekeningRincianObyekController',['names'=>'api-rekening-rincianobyek',
                                                                                            'parameters'=>['obyek'=>'uuid'],
                                                                                            'only'=>['index','show']]);

    Route::resource('/rekening/subrincianobyek','API\DMaster\RekeningSubRincianObyekController',['names'=>'api-rekening-subrincianobyek',
                                                                                                'parameters'=>['obyek'=>'uuid'],
                                                                                                'only'=>['index','show']]);

    // Masters
    Route::resource('/master/kelompokurusan','API\DMaster\KelompokUrusanController',['names'=>'api-master-kelompokurusan',
                                                                                    'parameters'=>['kelompokurusan'=>'uuid'],
                                                                                    'only'=>['index','show']]);
    Route::resource('/master/urusan','API\DMaster\UrusanController',['names'=>'api-master-urusan',
                                                                    'parameters'=>['urusan'=>'uuid'],
                                                                    'only'=>['index','show']]);

    Route::resource('/master/opd','API\DMaster\OrganisasiController',['names'=>'api-master-opd',
                                                                    'parameters'=>['opd'=>'uuid'],
                                                                    'only'=>['index','show']]);

    Route::resource('/master/unitkerja','API\DMaster\SubOrganisasiController',['names'=>'api-master-unitkerja',
                                                                    'parameters'=>['unitkerja'=>'uuid'],
                                                                    'only'=>['index','show']]);
    
});