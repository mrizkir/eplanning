<?php

Route::group (['prefix'=>'v1','middleware'=>['auth:api']],function() {     
    Route::resource('/rekening/struktur','API\DMaster\StrukturController',['parameters'=>['struktur'=>'uuid']]); 
});