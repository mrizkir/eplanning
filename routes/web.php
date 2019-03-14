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

    //masters - data kelompok urusan 
    Route::resource('/dmaster/kelompokurusan','DMaster\KelompokUrusanController',['parameters'=>['kelompokurusan'=>'uuid']]); 
    Route::get('/dmaster/kelompokurusan/paginate/{id}',['uses'=>'DMaster\KelompokUrusanController@paginate','as'=>'kelompokurusan.paginate']);              
    Route::post('/dmaster/kelompokurusan/changenumberrecordperpage',['uses'=>'DMaster\KelompokUrusanController@changenumberrecordperpage','as'=>'kelompokurusan.changenumberrecordperpage']);  
    Route::post('/dmaster/kelompokurusan/orderby',['uses'=>'DMaster\KelompokUrusanController@orderby','as'=>'kelompokurusan.orderby']); 
    Route::get('/dmaster/kelompokurusan/getkodekelompokurusan/{uuid}',['uses'=>'DMaster\KelompokUrusanController@getkodekelompokurusan','as'=>'kelompokurusan.getkodekelompokurusan']); 
    
    //masters - data urusan 
    Route::resource('/dmaster/urusan','DMaster\UrusanController',['parameters'=>['urusan'=>'uuid']]); 
    Route::post('/dmaster/urusan/search',['uses'=>'DMaster\UrusanController@search','as'=>'urusan.search']);          
    Route::get('/dmaster/urusan/paginate/{id}',['uses'=>'DMaster\UrusanController@paginate','as'=>'urusan.paginate']);              
    Route::post('/dmaster/urusan/changenumberrecordperpage',['uses'=>'DMaster\UrusanController@changenumberrecordperpage','as'=>'urusan.changenumberrecordperpage']);  
    Route::post('/dmaster/urusan/orderby',['uses'=>'DMaster\UrusanController@orderby','as'=>'urusan.orderby']);  

    //masters - data organisasi (OPD / SKPD)
    Route::resource('/dmaster/organisasi','DMaster\OrganisasiController',['parameters'=>['organisasi'=>'uuid']]); 
    Route::post('/dmaster/organisasi/search',['uses'=>'DMaster\OrganisasiController@search','as'=>'organisasi.search']);  
    Route::post('/dmaster/organisasi/filter',['uses'=>'DMaster\OrganisasiController@filter','as'=>'organisasi.filter']);           
    Route::get('/dmaster/organisasi/paginate/{id}',['uses'=>'DMaster\OrganisasiController@paginate','as'=>'organisasi.paginate']);              
    Route::post('/dmaster/organisasi/changenumberrecordperpage',['uses'=>'DMaster\OrganisasiController@changenumberrecordperpage','as'=>'organisasi.changenumberrecordperpage']);  
    Route::post('/dmaster/organisasi/orderby',['uses'=>'DMaster\OrganisasiController@orderby','as'=>'organisasi.orderby']);  

    //masters - data sub organisasi (Unit Kerja)
    Route::resource('/dmaster/suborganisasi','DMaster\SubOrganisasiController',['parameters'=>['suborganisasi'=>'uuid']]); 
    Route::post('/dmaster/suborganisasi/search',['uses'=>'DMaster\SubOrganisasiController@search','as'=>'suborganisasi.search']);  
    Route::post('/dmaster/suborganisasi/filter',['uses'=>'DMaster\SubOrganisasiController@filter','as'=>'suborganisasi.filter']);           
    Route::get('/dmaster/suborganisasi/paginate/{id}',['uses'=>'DMaster\SubOrganisasiController@paginate','as'=>'suborganisasi.paginate']);              
    Route::post('/dmaster/suborganisasi/changenumberrecordperpage',['uses'=>'DMaster\SubOrganisasiController@changenumberrecordperpage','as'=>'suborganisasi.changenumberrecordperpage']);  
    Route::post('/dmaster/suborganisasi/orderby',['uses'=>'DMaster\SubOrganisasiController@orderby','as'=>'suborganisasi.orderby']);  

    //masters - data program 
    Route::resource('/dmaster/program','DMaster\ProgramController',['parameters'=>['program'=>'uuid']]); 
    Route::post('/dmaster/program/search',['uses'=>'DMaster\ProgramController@search','as'=>'program.search']);  
    Route::post('/dmaster/program/filter',['uses'=>'DMaster\ProgramController@filter','as'=>'program.filter']);           
    Route::get('/dmaster/program/paginate/{id}',['uses'=>'DMaster\ProgramController@paginate','as'=>'program.paginate']);              
    Route::post('/dmaster/program/changenumberrecordperpage',['uses'=>'DMaster\ProgramController@changenumberrecordperpage','as'=>'program.changenumberrecordperpage']);  
    Route::post('/dmaster/program/orderby',['uses'=>'DMaster\ProgramController@orderby','as'=>'program.orderby']);  

    //masters - data program kegiatan
    Route::resource('/dmaster/programkegiatan','DMaster\ProgramKegiatanController',['parameters'=>['kegiatan'=>'uuid']]); 
    Route::post('/dmaster/programkegiatan/search',['uses'=>'DMaster\ProgramKegiatanController@search','as'=>'kegiatan.search']);  
    Route::post('/dmaster/programkegiatan/filter',['uses'=>'DMaster\ProgramKegiatanController@filter','as'=>'kegiatan.filter']);              
    Route::get('/dmaster/programkegiatan/paginate/{id}',['uses'=>'DMaster\ProgramKegiatanController@paginate','as'=>'kegiatan.paginate']);              
    Route::post('/dmaster/programkegiatan/changenumberrecordperpage',['uses'=>'DMaster\ProgramKegiatanController@changenumberrecordperpage','as'=>'kegiatan.changenumberrecordperpage']);  
    Route::post('/dmaster/programkegiatan/orderby',['uses'=>'DMaster\ProgramKegiatanController@orderby','as'=>'kegiatan.orderby']);  

    //masters - mapping program ke OPD
    Route::resource('/dmaster/mappingprogramtoopd','DMaster\MappingProgramToOPDController',[
                                                                                            'parameters'=>['mappingprogramtoopd'=>'uuid'],
                                                                                            'only'=>['index','show','create','store','destroy']
                                                                                        ]); 
    Route::post('/dmaster/mappingprogramtoopd/search',['uses'=>'DMaster\MappingProgramToOPDController@search','as'=>'mappingprogramtoopd.search']);  
    Route::post('/dmaster/mappingprogramtoopd/filter',['uses'=>'DMaster\MappingProgramToOPDController@filter','as'=>'mappingprogramtoopd.filter']);           
    Route::post('/dmaster/mappingprogramtoopd/filtercreate',['uses'=>'DMaster\MappingProgramToOPDController@filtercreate','as'=>'mappingprogramtoopd.filtercreate']);           
    Route::get('/dmaster/mappingprogramtoopd/paginate/{id}',['uses'=>'DMaster\MappingProgramToOPDController@paginate','as'=>'mappingprogramtoopd.paginate']);              
    Route::get('/dmaster/mappingprogramtoopd/create/paginatecreate/{id}',['uses'=>'DMaster\MappingProgramToOPDController@paginatecreate','as'=>'mappingprogramtoopd.paginatecreate']);
    Route::post('/dmaster/mappingprogramtoopd/changenumberrecordperpage',['uses'=>'DMaster\MappingProgramToOPDController@changenumberrecordperpage','as'=>'mappingprogramtoopd.changenumberrecordperpage']);  
    Route::post('/dmaster/mappingprogramtoopd/changenumberrecordperpagecreate',['uses'=>'DMaster\MappingProgramToOPDController@changenumberrecordperpagecreate','as'=>'mappingprogramtoopd.changenumberrecordperpagecreate']);  
    Route::post('/dmaster/mappingprogramtoopd/orderby',['uses'=>'DMaster\MappingProgramToOPDController@orderby','as'=>'mappingprogramtoopd.orderby']);  
    
    
    //Musrenbang - Aspirasi Musrenbang Desa [aspirasi]
    Route::resource('/aspirasi/aspirasimusrendesa','Musrenbang\AspirasiMusrenDesaController',['parameters'=>['aspirasimusrendesa'=>'uuid']]); 
    Route::post('/aspirasi/aspirasimusrendesa/search',['uses'=>'Musrenbang\AspirasiMusrenDesaController@search','as'=>'aspirasimusrendesa.search']);  
    Route::post('/aspirasi/aspirasimusrendesa/filter',['uses'=>'Musrenbang\AspirasiMusrenDesaController@filter','as'=>'aspirasimusrendesa.filter']);              
    Route::get('/aspirasi/aspirasimusrendesa/paginate/{id}',['uses'=>'Musrenbang\AspirasiMusrenDesaController@paginate','as'=>'aspirasimusrendesa.paginate']);              
    Route::post('/aspirasi/aspirasimusrendesa/changenumberrecordperpage',['uses'=>'Musrenbang\AspirasiMusrenDesaController@changenumberrecordperpage','as'=>'aspirasimusrendesa.changenumberrecordperpage']);  
    Route::post('/aspirasi/aspirasimusrendesa/orderby',['uses'=>'Musrenbang\AspirasiMusrenDesaController@orderby','as'=>'aspirasimusrendesa.orderby']);  


    //Musrenbang - Pembahasan Musrenbang Desa [pembahasan]
    Route::resource('/pembahasan/pembahasanmusrendesa','Musrenbang\PembahasanMusrenDesaController',['parameters'=>['pembahasanmusrendesa'=>'uuid']]); 
    Route::post('/pembahasan/pembahasanmusrendesa/search',['uses'=>'Musrenbang\PembahasanMusrenDesaController@search','as'=>'pembahasanmusrendesa.search']);  
    Route::post('/pembahasan/pembahasanmusrendesa/filter',['uses'=>'Musrenbang\PembahasanMusrenDesaController@filter','as'=>'pembahasanmusrendesa.filter']);              
    Route::get('/pembahasan/pembahasanmusrendesa/paginate/{id}',['uses'=>'Musrenbang\PembahasanMusrenDesaController@paginate','as'=>'pembahasanmusrendesa.paginate']);              
    Route::post('/pembahasan/pembahasanmusrendesa/changenumberrecordperpage',['uses'=>'Musrenbang\PembahasanMusrenDesaController@changenumberrecordperpage','as'=>'pembahasanmusrendesa.changenumberrecordperpage']);  
    Route::post('/pembahasan/pembahasanmusrendesa/orderby',['uses'=>'Musrenbang\PembahasanMusrenDesaController@orderby','as'=>'pembahasanmusrendesa.orderby']);  


    //setting - permissions    
    Route::resource('/setting/permissions','Setting\PermissionsController',[
                                                                            'parameters'=>['permissions'=>'id'],
                                                                            'only'=>['index','show','create','store','destroy']
                                                                        ]);               
    
    Route::get('/setting/permissions/paginate/{id}',['uses'=>'Setting\PermissionsController@paginate','as'=>'permissions.paginate']);    
    Route::post('/setting/permissions/search',['uses'=>'Setting\PermissionsController@search','as'=>'permissions.search']);          
    Route::post('/setting/permissions/changenumberrecordperpage',['uses'=>'Setting\PermissionsController@changenumberrecordperpage','as'=>'permissions.changenumberrecordperpage']);  
    Route::post('/setting/permissions/orderby',['uses'=>'Setting\PermissionsController@orderby','as'=>'permissions.orderby']);  
    
    //setting - roles
    Route::resource('/setting/roles','Setting\RolesController',['parameters'=>['roles'=>'id']]);           
    Route::get('/setting/roles/paginate/{id}',['uses'=>'Setting\RolesController@paginate','as'=>'roles.paginate']);    
    Route::post('/setting/roles/changenumberrecordperpage',['uses'=>'Setting\RolesController@changenumberrecordperpage','as'=>'roles.changenumberrecordperpage']);  
    Route::post('/setting/roles/orderby',['uses'=>'Setting\RolesController@orderby','as'=>'roles.orderby']);  
    Route::post('/setting/roles/storerolepermission', ['uses'=>'Setting\RolesController@storerolepermission','as'=>'roles.storerolepermission']);
    
    //setting - users
    Route::resource('/setting/users','Setting\UsersController',['parameters'=>['users'=>'id']]);           
    Route::get('/setting/users/paginatecreate/{id}',['uses'=>'Setting\UsersController@paginate','as'=>'users.paginate']);    
    Route::post('/setting/users/changenumberrecordperpage',['uses'=>'Setting\UsersController@changenumberrecordperpage','as'=>'users.changenumberrecordperpage']);  
    Route::post('/setting/users/orderby',['uses'=>'Setting\UsersController@orderby','as'=>'users.orderby']); 
    Route::post('/setting/users/search',['uses'=>'Setting\UsersController@search','as'=>'users.search']);    
    Route::post('/setting/users/filter',['uses'=>'Setting\UsersController@filter','as'=>'users.filter']);    
    Route::post('/setting/users/storeuserpermission', ['uses'=>'Setting\UsersController@storeuserpermission','as'=>'users.storeuserpermission']);

    //setting - users OPD
    Route::resource('/setting/usersopd','Setting\UsersOPDController',['parameters'=>['usersopd'=>'id']]);           
    Route::get('/setting/usersopd/paginate/{id}',['uses'=>'Setting\UsersOPDController@paginate','as'=>'usersopd.paginate']);
    Route::post('/setting/usersopd/changenumberrecordperpage',['uses'=>'Setting\UsersOPDController@changenumberrecordperpage','as'=>'usersopd.changenumberrecordperpage']);  
    Route::post('/setting/usersopd/orderby',['uses'=>'Setting\UsersOPDController@orderby','as'=>'usersopd.orderby']); 
    Route::post('/setting/usersopd/search',['uses'=>'Setting\UsersOPDController@search','as'=>'usersopd.search']);    
    Route::post('/setting/usersopd/filter',['uses'=>'Setting\UsersOPDController@filter','as'=>'usersopd.filter']);    
    Route::post('/setting/usersopd/storeuserpermission', ['uses'=>'Setting\UsersOPDController@storeuserpermission','as'=>'usersopd.storeuserpermission']);

});