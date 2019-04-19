<?php
Auth::routes(['register' => false]);

Route::get('/',function (){    
    if (!Auth::check() && !Request::is('login')) {
        return Redirect::route('login');
    }else{
        return Redirect::route('dashboard.index');
    }
}) ;
Route::get('/logout',['uses'=>'Auth\LoginController@logout','as'=>'logout']);
Route::group (['prefix'=>'admin','middleware'=>['disablepreventback','web', 'auth']],function() {     
    Route::get('/',['uses'=>'DashboardController@index','as'=>'dashboard.index']);          

    //masters - data kelompok urusan [data]
    Route::resource('/dmaster/kelompokurusan','DMaster\KelompokUrusanController',['parameters'=>['kelompokurusan'=>'uuid']]); 
    Route::get('/dmaster/kelompokurusan/paginate/{id}',['uses'=>'DMaster\KelompokUrusanController@paginate','as'=>'kelompokurusan.paginate']);              
    Route::post('/dmaster/kelompokurusan/changenumberrecordperpage',['uses'=>'DMaster\KelompokUrusanController@changenumberrecordperpage','as'=>'kelompokurusan.changenumberrecordperpage']);  
    Route::post('/dmaster/kelompokurusan/orderby',['uses'=>'DMaster\KelompokUrusanController@orderby','as'=>'kelompokurusan.orderby']); 
    Route::get('/dmaster/kelompokurusan/getkodekelompokurusan/{uuid}',['uses'=>'DMaster\KelompokUrusanController@getkodekelompokurusan','as'=>'kelompokurusan.getkodekelompokurusan']); 
    
    //masters - data urusan [data]
    Route::resource('/dmaster/urusan','DMaster\UrusanController',['parameters'=>['urusan'=>'uuid']]); 
    Route::post('/dmaster/urusan/search',['uses'=>'DMaster\UrusanController@search','as'=>'urusan.search']);          
    Route::get('/dmaster/urusan/paginate/{id}',['uses'=>'DMaster\UrusanController@paginate','as'=>'urusan.paginate']);              
    Route::post('/dmaster/urusan/changenumberrecordperpage',['uses'=>'DMaster\UrusanController@changenumberrecordperpage','as'=>'urusan.changenumberrecordperpage']);  
    Route::post('/dmaster/urusan/orderby',['uses'=>'DMaster\UrusanController@orderby','as'=>'urusan.orderby']);  

    //masters - data organisasi (OPD / SKPD) [data]
    Route::resource('/dmaster/organisasi','DMaster\OrganisasiController',['parameters'=>['organisasi'=>'uuid']]); 
    Route::post('/dmaster/organisasi/search',['uses'=>'DMaster\OrganisasiController@search','as'=>'organisasi.search']);  
    Route::post('/dmaster/organisasi/filter',['uses'=>'DMaster\OrganisasiController@filter','as'=>'organisasi.filter']);           
    Route::get('/dmaster/organisasi/paginate/{id}',['uses'=>'DMaster\OrganisasiController@paginate','as'=>'organisasi.paginate']);              
    Route::post('/dmaster/organisasi/changenumberrecordperpage',['uses'=>'DMaster\OrganisasiController@changenumberrecordperpage','as'=>'organisasi.changenumberrecordperpage']);  
    Route::post('/dmaster/organisasi/orderby',['uses'=>'DMaster\OrganisasiController@orderby','as'=>'organisasi.orderby']);  

    //masters - data sub organisasi (Unit Kerja) [data]
    Route::resource('/dmaster/suborganisasi','DMaster\SubOrganisasiController',['parameters'=>['suborganisasi'=>'uuid']]); 
    Route::post('/dmaster/suborganisasi/search',['uses'=>'DMaster\SubOrganisasiController@search','as'=>'suborganisasi.search']);  
    Route::post('/dmaster/suborganisasi/filter',['uses'=>'DMaster\SubOrganisasiController@filter','as'=>'suborganisasi.filter']);           
    Route::get('/dmaster/suborganisasi/paginate/{id}',['uses'=>'DMaster\SubOrganisasiController@paginate','as'=>'suborganisasi.paginate']);              
    Route::post('/dmaster/suborganisasi/changenumberrecordperpage',['uses'=>'DMaster\SubOrganisasiController@changenumberrecordperpage','as'=>'suborganisasi.changenumberrecordperpage']);  
    Route::post('/dmaster/suborganisasi/orderby',['uses'=>'DMaster\SubOrganisasiController@orderby','as'=>'suborganisasi.orderby']);  

    //masters - data program  [data]
    Route::resource('/dmaster/program','DMaster\ProgramController',['parameters'=>['program'=>'uuid']]); 
    Route::post('/dmaster/program/search',['uses'=>'DMaster\ProgramController@search','as'=>'program.search']);  
    Route::post('/dmaster/program/filter',['uses'=>'DMaster\ProgramController@filter','as'=>'program.filter']);           
    Route::get('/dmaster/program/paginate/{id}',['uses'=>'DMaster\ProgramController@paginate','as'=>'program.paginate']);              
    Route::post('/dmaster/program/changenumberrecordperpage',['uses'=>'DMaster\ProgramController@changenumberrecordperpage','as'=>'program.changenumberrecordperpage']);  
    Route::post('/dmaster/program/orderby',['uses'=>'DMaster\ProgramController@orderby','as'=>'program.orderby']);  

    //masters - data program kegiatan [data]
    Route::resource('/dmaster/programkegiatan','DMaster\ProgramKegiatanController',['parameters'=>['kegiatan'=>'uuid']]); 
    Route::post('/dmaster/programkegiatan/search',['uses'=>'DMaster\ProgramKegiatanController@search','as'=>'kegiatan.search']);  
    Route::post('/dmaster/programkegiatan/filter',['uses'=>'DMaster\ProgramKegiatanController@filter','as'=>'kegiatan.filter']);              
    Route::get('/dmaster/programkegiatan/paginate/{id}',['uses'=>'DMaster\ProgramKegiatanController@paginate','as'=>'kegiatan.paginate']);              
    Route::post('/dmaster/programkegiatan/changenumberrecordperpage',['uses'=>'DMaster\ProgramKegiatanController@changenumberrecordperpage','as'=>'kegiatan.changenumberrecordperpage']);  
    Route::post('/dmaster/programkegiatan/orderby',['uses'=>'DMaster\ProgramKegiatanController@orderby','as'=>'kegiatan.orderby']);  

    //masters - mapping program ke OPD [mapping]
    Route::resource('/dmaster/mappingprogramtoopd','DMaster\MappingProgramToOPDController',[
                                                                                            'parameters'=>['mappingprogramtoopd'=>'uuid'],
                                                                                            'only'=>['index','show','create','store','destroy']
                                                                                        ]);     
});