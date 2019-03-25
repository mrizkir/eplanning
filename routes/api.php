<?php

Route::group (['prefix'=>'v1','middleware'=>['auth:api']],function() {     

    // Rekening
    Route::resource('/rekening/struktur','API\DMaster\RekeningStrukturController',['parameters'=>['struktur'=>'uuid'],
                                                                                    'only'=>['index','show']]);

    Route::resource('/rekening/kelompok','API\DMaster\RekeningKelompokController',['parameters'=>['kelompok'=>'uuid'],
                                                                                    'only'=>['index','show']]);

    Route::resource('/rekening/jenis','API\DMaster\RekeningJenisController',['parameters'=>['jenis'=>'uuid'],
                                                                                    'only'=>['index','show']]);

    Route::resource('/rekening/obyek','API\DMaster\RekeningObyekController',['parameters'=>['obyek'=>'uuid'],
                                                                                    'only'=>['index','show']]);

    Route::resource('/rekening/rincianobyek','API\DMaster\RekeningRincianObyekController',['parameters'=>['obyek'=>'uuid'],
                                                                                            'only'=>['index','show']]);

    Route::resource('/rekening/subrincianobyek','API\DMaster\RekeningSubRincianObyekController',['parameters'=>['obyek'=>'uuid'],
                                                                                                'only'=>['index','show']]);
});