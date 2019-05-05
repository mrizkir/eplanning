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
    Route::post('/dmaster/mappingprogramtoopd/search',['uses'=>'DMaster\MappingProgramToOPDController@search','as'=>'mappingprogramtoopd.search']);  
    Route::post('/dmaster/mappingprogramtoopd/filter',['uses'=>'DMaster\MappingProgramToOPDController@filter','as'=>'mappingprogramtoopd.filter']);           
    Route::post('/dmaster/mappingprogramtoopd/filtercreate',['uses'=>'DMaster\MappingProgramToOPDController@filtercreate','as'=>'mappingprogramtoopd.filtercreate']);           
    Route::get('/dmaster/mappingprogramtoopd/paginate/{id}',['uses'=>'DMaster\MappingProgramToOPDController@paginate','as'=>'mappingprogramtoopd.paginate']);              
    Route::get('/dmaster/mappingprogramtoopd/create/paginatecreate/{id}',['uses'=>'DMaster\MappingProgramToOPDController@paginatecreate','as'=>'mappingprogramtoopd.paginatecreate']);
    Route::post('/dmaster/mappingprogramtoopd/changenumberrecordperpage',['uses'=>'DMaster\MappingProgramToOPDController@changenumberrecordperpage','as'=>'mappingprogramtoopd.changenumberrecordperpage']);  
    Route::post('/dmaster/mappingprogramtoopd/changenumberrecordperpagecreate',['uses'=>'DMaster\MappingProgramToOPDController@changenumberrecordperpagecreate','as'=>'mappingprogramtoopd.changenumberrecordperpagecreate']);  
    Route::post('/dmaster/mappingprogramtoopd/orderby',['uses'=>'DMaster\MappingProgramToOPDController@orderby','as'=>'mappingprogramtoopd.orderby']);  
    
    //masters - pagu anggaran OPD/SKPD [aneka data] 
    Route::resource('/dmaster/paguanggaranopd','DMaster\PaguAnggaranOPDController',['parameters'=>['paguanggaranopd'=>'uuid']]); 
    Route::post('/dmaster/paguanggaranopd/search',['uses'=>'DMaster\PaguAnggaranOPDController@search','as'=>'paguanggaranopd.search']);  
    Route::get('/dmaster/paguanggaranopd/paginate/{id}',['uses'=>'DMaster\PaguAnggaranOPDController@paginate','as'=>'paguanggaranopd.paginate']);              
    Route::post('/dmaster/paguanggaranopd/changenumberrecordperpage',['uses'=>'DMaster\PaguAnggaranOPDController@changenumberrecordperpage','as'=>'paguanggaranopd.changenumberrecordperpage']);  
    Route::post('/dmaster/paguanggaranopd/orderby',['uses'=>'DMaster\PaguAnggaranOPDController@orderby','as'=>'paguanggaranopd.orderby']); 
    Route::get('/dmaster/paguanggaranopd/getkodekelompokurusan/{uuid}',['uses'=>'DMaster\PaguAnggaranOPDController@getkodekelompokurusan','as'=>'paguanggaranopd.getkodekelompokurusan']); 
    
    //RPJMD - Visi
    Route::resource('/rpjmd/rpjmdvisi','RPJMD\RPJMDVisiController',['parameters'=>['rpjmdvisi'=>'uuid']]); 
    Route::post('/rpjmd/rpjmdvisi/search',['uses'=>'RPJMD\RPJMDVisiController@search','as'=>'rpjmdvisi.search']); 
    Route::get('/rpjmd/rpjmdvisi/paginate/{id}',['uses'=>'RPJMD\RPJMDVisiController@paginate','as'=>'rpjmdvisi.paginate']);              
    Route::post('/rpjmd/rpjmdvisi/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDVisiController@changenumberrecordperpage','as'=>'rpjmdvisi.changenumberrecordperpage']);  
    Route::post('/rpjmd/rpjmdvisi/orderby',['uses'=>'RPJMD\RPJMDVisiController@orderby','as'=>'rpjmdvisi.orderby']); 

     //RPJMD - Misi
     Route::resource('/rpjmd/rpjmdmisi','RPJMD\RPJMDMisiController',['parameters'=>['rpjmdmisi'=>'uuid']]); 
     Route::post('/rpjmd/rpjmdmisi/search',['uses'=>'RPJMD\RPJMDMisiController@search','as'=>'rpjmdmisi.search']); 
     Route::get('/rpjmd/rpjmdmisi/paginate/{id}',['uses'=>'RPJMD\RPJMDMisiController@paginate','as'=>'rpjmdmisi.paginate']);              
     Route::post('/rpjmd/rpjmdmisi/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDMisiController@changenumberrecordperpage','as'=>'rpjmdmisi.changenumberrecordperpage']);  
     Route::post('/rpjmd/rpjmdmisi/orderby',['uses'=>'RPJMD\RPJMDMisiController@orderby','as'=>'rpjmdmisi.orderby']); 

     //RPJMD - Tujuan
     Route::resource('/rpjmd/rpjmdtujuan','RPJMD\RPJMDTujuanController',['parameters'=>['rpjmdtujuan'=>'uuid']]); 
     Route::post('/rpjmd/rpjmdtujuan/search',['uses'=>'RPJMD\RPJMDTujuanController@search','as'=>'rpjmdtujuan.search']); 
     Route::get('/rpjmd/rpjmdtujuan/paginate/{id}',['uses'=>'RPJMD\RPJMDTujuanController@paginate','as'=>'rpjmdtujuan.paginate']);              
     Route::post('/rpjmd/rpjmdtujuan/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDTujuanController@changenumberrecordperpage','as'=>'rpjmdtujuan.changenumberrecordperpage']);  
     Route::post('/rpjmd/rpjmdtujuan/orderby',['uses'=>'RPJMD\RPJMDTujuanController@orderby','as'=>'rpjmdtujuan.orderby']); 

     //RPJMD - Sasaran
     Route::resource('/rpjmd/rpjmdsasaran','RPJMD\RPJMDSasaranController',['parameters'=>['rpjmdsasaran'=>'uuid']]); 
     Route::post('/rpjmd/rpjmdsasaran/search',['uses'=>'RPJMD\RPJMDSasaranController@search','as'=>'rpjmdsasaran.search']); 
     Route::get('/rpjmd/rpjmdsasaran/paginate/{id}',['uses'=>'RPJMD\RPJMDSasaranController@paginate','as'=>'rpjmdsasaran.paginate']);              
     Route::post('/rpjmd/rpjmdsasaran/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDSasaranController@changenumberrecordperpage','as'=>'rpjmdsasaran.changenumberrecordperpage']);  
     Route::post('/rpjmd/rpjmdsasaran/orderby',['uses'=>'RPJMD\RPJMDSasaranController@orderby','as'=>'rpjmdsasaran.orderby']); 

     //RPJMD - Strategi
     Route::resource('/rpjmd/rpjmdstrategi','RPJMD\RPJMDStrategiController',['parameters'=>['rpjmdstrategi'=>'uuid']]); 
     Route::post('/rpjmd/rpjmdstrategi/search',['uses'=>'RPJMD\RPJMDStrategiController@search','as'=>'rpjmdstrategi.search']); 
     Route::get('/rpjmd/rpjmdstrategi/paginate/{id}',['uses'=>'RPJMD\RPJMDStrategiController@paginate','as'=>'rpjmdstrategi.paginate']);              
     Route::post('/rpjmd/rpjmdstrategi/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDStrategiController@changenumberrecordperpage','as'=>'rpjmdstrategi.changenumberrecordperpage']);  
     Route::post('/rpjmd/rpjmdstrategi/orderby',['uses'=>'RPJMD\RPJMDStrategiController@orderby','as'=>'rpjmdstrategi.orderby']); 

    //RPJMD - Kebijakan
    Route::resource('/rpjmd/rpjmdkebijakan','RPJMD\RPJMDKebijakanController',['parameters'=>['rpjmdkebijakan'=>'uuid']]); 
    Route::post('/rpjmd/rpjmdkebijakan/search',['uses'=>'RPJMD\RPJMDKebijakanController@search','as'=>'rpjmdkebijakan.search']); 
    Route::get('/rpjmd/rpjmdkebijakan/paginate/{id}',['uses'=>'RPJMD\RPJMDKebijakanController@paginate','as'=>'rpjmdkebijakan.paginate']);              
    Route::post('/rpjmd/rpjmdkebijakan/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDKebijakanController@changenumberrecordperpage','as'=>'rpjmdkebijakan.changenumberrecordperpage']);  
    Route::post('/rpjmd/rpjmdkebijakan/orderby',['uses'=>'RPJMD\RPJMDKebijakanController@orderby','as'=>'rpjmdkebijakan.orderby']); 
    
    //RPJMD - Indikator Rencana Program Prioritas atau Indikator Kinerja
    Route::resource('/rpjmd/rpjmdindikatorkinerja','RPJMD\RPJMDIndikatorKinerjaController',['parameters'=>['rpjmdindikatorkinerja'=>'uuid']]); 
    Route::post('/rpjmd/rpjmdindikatorkinerja/search',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@search','as'=>'rpjmdindikatorkinerja.search']); 
    Route::get('/rpjmd/rpjmdindikatorkinerja/paginate/{id}',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@paginate','as'=>'rpjmdindikatorkinerja.paginate']);              
    Route::post('/rpjmd/rpjmdindikatorkinerja/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@changenumberrecordperpage','as'=>'rpjmdindikatorkinerja.changenumberrecordperpage']);  
    Route::post('/rpjmd/rpjmdindikatorkinerja/orderby',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@orderby','as'=>'rpjmdindikatorkinerja.orderby']);

    //Musrenbang - Aspirasi Musrenbang Desa [aspirasi]
    Route::resource('/aspirasi/aspirasimusrendesa','Musrenbang\AspirasiMusrenDesaController',['parameters'=>['aspirasimusrendesa'=>'uuid']]);        
    Route::post('/aspirasi/aspirasimusrendesa/search',['uses'=>'Musrenbang\AspirasiMusrenDesaController@search','as'=>'aspirasimusrendesa.search']);  
    Route::post('/aspirasi/aspirasimusrendesa/filter',['uses'=>'Musrenbang\AspirasiMusrenDesaController@filter','as'=>'aspirasimusrendesa.filter']);                  
    Route::get('/aspirasi/aspirasimusrendesa/paginate/{id}',['uses'=>'Musrenbang\AspirasiMusrenDesaController@paginate','as'=>'aspirasimusrendesa.paginate']);              
    Route::post('/aspirasi/aspirasimusrendesa/changenumberrecordperpage',['uses'=>'Musrenbang\AspirasiMusrenDesaController@changenumberrecordperpage','as'=>'aspirasimusrendesa.changenumberrecordperpage']);  
    Route::post('/aspirasi/aspirasimusrendesa/orderby',['uses'=>'Musrenbang\AspirasiMusrenDesaController@orderby','as'=>'aspirasimusrendesa.orderby']);  
    
    //Musrenbang - Pembahasan Musrenbang Desa [pembahasan]
    Route::resource('/pembahasan/pembahasanmusrendesa','Musrenbang\PembahasanMusrenDesaController',['parameters'=>['pembahasanmusrendesa'=>'uuid'],
                                                                                                    'only'=>['index','show','update']]); 
    Route::post('/pembahasan/pembahasanmusrendesa/search',['uses'=>'Musrenbang\PembahasanMusrenDesaController@search','as'=>'pembahasanmusrendesa.search']);  
    Route::post('/pembahasan/pembahasanmusrendesa/filter',['uses'=>'Musrenbang\PembahasanMusrenDesaController@filter','as'=>'pembahasanmusrendesa.filter']);              
    Route::get('/pembahasan/pembahasanmusrendesa/paginate/{id}',['uses'=>'Musrenbang\PembahasanMusrenDesaController@paginate','as'=>'pembahasanmusrendesa.paginate']);              
    Route::post('/pembahasan/pembahasanmusrendesa/changenumberrecordperpage',['uses'=>'Musrenbang\PembahasanMusrenDesaController@changenumberrecordperpage','as'=>'pembahasanmusrendesa.changenumberrecordperpage']);  
    Route::post('/pembahasan/pembahasanmusrendesa/orderby',['uses'=>'Musrenbang\PembahasanMusrenDesaController@orderby','as'=>'pembahasanmusrendesa.orderby']);  
    
    //Musrenbang - Aspirasi Musrenbang Kecamatan [aspirasi]
    Route::resource('/aspirasi/aspirasimusrenkecamatan','Musrenbang\AspirasiMusrenKecamatanController',['parameters'=>['aspirasimusrenkecamatan'=>'uuid']]);    
    Route::post('/aspirasi/aspirasimusrenkecamatan/storeusulankecamatan',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@storeusulankecamatan','as'=>'aspirasimusrenkecamatan.storeusulankecamatan']);  
    Route::get('/aspirasi/aspirasimusrenkecamatan/pilihusulankegiatan',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@pilihusulankegiatan','as'=>'aspirasimusrenkecamatan.pilihusulankegiatan']);                  
    Route::post('/aspirasi/aspirasimusrenkecamatan/search',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@search','as'=>'aspirasimusrenkecamatan.search']);  
    Route::post('/aspirasi/aspirasimusrenkecamatan/filter',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@filter','as'=>'aspirasimusrenkecamatan.filter']);              
    Route::post('/aspirasi/aspirasimusrenkecamatan/filterurusan',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@filterurusan','as'=>'aspirasimusrenkecamatan.filterurusan']);  
    Route::get('/aspirasi/aspirasimusrenkecamatan/paginate/{id}',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@paginate','as'=>'aspirasimusrenkecamatan.paginate']);                  
    Route::post('/aspirasi/aspirasimusrenkecamatan/changenumberrecordperpage',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@changenumberrecordperpage','as'=>'aspirasimusrenkecamatan.changenumberrecordperpage']);  
    Route::post('/aspirasi/aspirasimusrenkecamatan/orderby',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@orderby','as'=>'aspirasimusrenkecamatan.orderby']);  
    Route::post('/aspirasi/aspirasimusrenkecamatan/orderbypilihusulankegiatan',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@orderbypilihusulankegiatan','as'=>'aspirasimusrenkecamatan.orderbypilihusulankegiatan']);  
    
    //Musrenbang - Pembahasan Musrenbang Kecamatan [pembahasan]
    Route::resource('/pembahasan/pembahasanmusrenkecamatan','Musrenbang\PembahasanMusrenKecamatanController',['parameters'=>['pembahasanmusrenkecamatan'=>'uuid'],
                                                                                                              'only'=>['index','show','update']]); 
    Route::post('/pembahasan/pembahasanmusrenkecamatan/search',['uses'=>'Musrenbang\PembahasanMusrenKecamatanController@search','as'=>'pembahasanmusrenkecamatan.search']);  
    Route::post('/pembahasan/pembahasanmusrenkecamatan/filter',['uses'=>'Musrenbang\PembahasanMusrenKecamatanController@filter','as'=>'pembahasanmusrenkecamatan.filter']);              
    Route::get('/pembahasan/pembahasanmusrenkecamatan/paginate/{id}',['uses'=>'Musrenbang\PembahasanMusrenKecamatanController@paginate','as'=>'pembahasanmusrenkecamatan.paginate']);              
    Route::post('/pembahasan/pembahasanmusrenkecamatan/changenumberrecordperpage',['uses'=>'Musrenbang\PembahasanMusrenKecamatanController@changenumberrecordperpage','as'=>'pembahasanmusrenkecamatan.changenumberrecordperpage']);  
    Route::post('/pembahasan/pembahasanmusrenkecamatan/orderby',['uses'=>'Musrenbang\PembahasanMusrenKecamatanController@orderby','as'=>'pembahasanmusrenkecamatan.orderby']);  
    
    //Rencana Kerja - Usulan Pra Renja OPD/SKPD [aspirasi]
    Route::resource('/aspirasi/usulanprarenjaopd','RKPD\UsulanPraRenjaOPDController',['parameters'=>['usulanprarenjaopd'=>'uuid']]);        
    Route::post('/aspirasi/usulanprarenjaopd/pilihusulankegiatan',['uses'=>'RKPD\UsulanPraRenjaOPDController@pilihusulankegiatan','as'=>'usulanprarenjaopd.pilihusulankegiatan']);                  
    Route::post('/aspirasi/usulanprarenjaopd/pilihindikatorkinerja',['uses'=>'RKPD\UsulanPraRenjaOPDController@pilihindikatorkinerja','as'=>'usulanprarenjaopd.pilihindikatorkinerja']);                  
    Route::get('/aspirasi/usulanprarenjaopd/create1/{uuid}',['uses'=>'RKPD\UsulanPraRenjaOPDController@create1','as'=>'usulanprarenjaopd.create1']);              
    Route::get('/aspirasi/usulanprarenjaopd/create2/{uuid}',['uses'=>'RKPD\UsulanPraRenjaOPDController@create2','as'=>'usulanprarenjaopd.create2']);              
    Route::get('/aspirasi/usulanprarenjaopd/create3/{uuid}',['uses'=>'RKPD\UsulanPraRenjaOPDController@create3','as'=>'usulanprarenjaopd.create3']);              
    Route::get('/aspirasi/usulanprarenjaopd/create4/{uuid}',['uses'=>'RKPD\UsulanPraRenjaOPDController@create4','as'=>'usulanprarenjaopd.create4']);              
    Route::post('/aspirasi/usulanprarenjaopd/store1',['uses'=>'RKPD\UsulanPraRenjaOPDController@store1','as'=>'usulanprarenjaopd.store1']);  
    Route::post('/aspirasi/usulanprarenjaopd/store2',['uses'=>'RKPD\UsulanPraRenjaOPDController@store2','as'=>'usulanprarenjaopd.store2']);  
    Route::post('/aspirasi/usulanprarenjaopd/store3',['uses'=>'RKPD\UsulanPraRenjaOPDController@store3','as'=>'usulanprarenjaopd.store3']); 
    Route::post('/aspirasi/usulanprarenjaopd/store4',['uses'=>'RKPD\UsulanPraRenjaOPDController@store4','as'=>'usulanprarenjaopd.store4']); 
    Route::get('/aspirasi/usulanprarenjaopd/{uuid}/edit1',['uses'=>'RKPD\UsulanPraRenjaOPDController@edit1','as'=>'usulanprarenjaopd.edit1']);              
    Route::get('/aspirasi/usulanprarenjaopd/{uuid}/edit2',['uses'=>'RKPD\UsulanPraRenjaOPDController@edit2','as'=>'usulanprarenjaopd.edit2']);              
    Route::get('/aspirasi/usulanprarenjaopd/{uuid}/edit3',['uses'=>'RKPD\UsulanPraRenjaOPDController@edit3','as'=>'usulanprarenjaopd.edit3']);              
    Route::get('/aspirasi/usulanprarenjaopd/{uuid}/edit4',['uses'=>'RKPD\UsulanPraRenjaOPDController@edit4','as'=>'usulanprarenjaopd.edit4']);              
    Route::put('/aspirasi/usulanprarenjaopd/update1/{uuid}',['uses'=>'RKPD\UsulanPraRenjaOPDController@update1','as'=>'usulanprarenjaopd.update1']);  
    Route::put('/aspirasi/usulanprarenjaopd/update2/{uuid}',['uses'=>'RKPD\UsulanPraRenjaOPDController@update2','as'=>'usulanprarenjaopd.update2']);  
    Route::put('/aspirasi/usulanprarenjaopd/update3/{uuid}',['uses'=>'RKPD\UsulanPraRenjaOPDController@update3','as'=>'usulanprarenjaopd.update3']); 
    Route::put('/aspirasi/usulanprarenjaopd/update4/{uuid}',['uses'=>'RKPD\UsulanPraRenjaOPDController@update4','as'=>'usulanprarenjaopd.update4']); 
    Route::post('/aspirasi/usulanprarenjaopd/search',['uses'=>'RKPD\UsulanPraRenjaOPDController@search','as'=>'usulanprarenjaopd.search']);  
    Route::post('/aspirasi/usulanprarenjaopd/filter',['uses'=>'RKPD\UsulanPraRenjaOPDController@filter','as'=>'usulanprarenjaopd.filter']);                  
    Route::get('/aspirasi/usulanprarenjaopd/paginate/{id}',['uses'=>'RKPD\UsulanPraRenjaOPDController@paginate','as'=>'usulanprarenjaopd.paginate']);              
    Route::post('/aspirasi/usulanprarenjaopd/changenumberrecordperpage',['uses'=>'RKPD\UsulanPraRenjaOPDController@changenumberrecordperpage','as'=>'usulanprarenjaopd.changenumberrecordperpage']);  
    Route::post('/aspirasi/usulanprarenjaopd/orderby',['uses'=>'RKPD\UsulanPraRenjaOPDController@orderby','as'=>'usulanprarenjaopd.orderby']);    
    
    //Rencana Kerja - Pembahasan Pra Renja OPD/SKPD [pembahasan]
    Route::resource('/pembahasan/pembahasanprarenjaopd','RKPD\PembahasanPraRenjaOPDController',['parameters'=>['pembahasanprarenjaopd'=>'uuid'],
                                                                                                              'only'=>['index','show','update']]); 
    Route::post('/pembahasan/pembahasanprarenjaopd/search',['uses'=>'RKPD\PembahasanPraRenjaOPDController@search','as'=>'pembahasanprarenjaopd.search']);  
    Route::post('/pembahasan/pembahasanprarenjaopd/filter',['uses'=>'RKPD\PembahasanPraRenjaOPDController@filter','as'=>'pembahasanprarenjaopd.filter']);              
    Route::get('/pembahasan/pembahasanprarenjaopd/paginate/{id}',['uses'=>'RKPD\PembahasanPraRenjaOPDController@paginate','as'=>'pembahasanprarenjaopd.paginate']);              
    Route::post('/pembahasan/pembahasanprarenjaopd/changenumberrecordperpage',['uses'=>'RKPD\PembahasanPraRenjaOPDController@changenumberrecordperpage','as'=>'pembahasanprarenjaopd.changenumberrecordperpage']);  
    Route::post('/pembahasan/pembahasanprarenjaopd/orderby',['uses'=>'RKPD\PembahasanPraRenjaOPDController@orderby','as'=>'pembahasanprarenjaopd.orderby']);  
    Route::post('/pembahasan/pembahasanprarenjaopd/transfer',['uses'=>'RKPD\PembahasanPraRenjaOPDController@transfer','as'=>'pembahasanprarenjaopd.transfer']);
    
    //Rencana Kerja - Usulan Rakor Bidang [aspirasi]
    Route::resource('/aspirasi/usulanrakorbidang','RKPD\UsulanRAKORBidangController',['parameters'=>['usulanrakorbidang'=>'uuid']]);        
    Route::post('/aspirasi/usulanrakorbidang/pilihusulankegiatan',['uses'=>'RKPD\UsulanRAKORBidangController@pilihusulankegiatan','as'=>'usulanrakorbidang.pilihusulankegiatan']);                  
    Route::post('/aspirasi/usulanrakorbidang/pilihindikatorkinerja',['uses'=>'RKPD\UsulanRAKORBidangController@pilihindikatorkinerja','as'=>'usulanrakorbidang.pilihindikatorkinerja']);                  
    Route::get('/aspirasi/usulanrakorbidang/create1/{uuid}',['uses'=>'RKPD\UsulanRAKORBidangController@create1','as'=>'usulanrakorbidang.create1']);              
    Route::get('/aspirasi/usulanrakorbidang/create2/{uuid}',['uses'=>'RKPD\UsulanRAKORBidangController@create2','as'=>'usulanrakorbidang.create2']);              
    Route::get('/aspirasi/usulanrakorbidang/create3/{uuid}',['uses'=>'RKPD\UsulanRAKORBidangController@create3','as'=>'usulanrakorbidang.create3']);              
    Route::get('/aspirasi/usulanrakorbidang/create4/{uuid}',['uses'=>'RKPD\UsulanRAKORBidangController@create4','as'=>'usulanrakorbidang.create4']);              
    Route::post('/aspirasi/usulanrakorbidang/store1',['uses'=>'RKPD\UsulanRAKORBidangController@store1','as'=>'usulanrakorbidang.store1']);  
    Route::post('/aspirasi/usulanrakorbidang/store2',['uses'=>'RKPD\UsulanRAKORBidangController@store2','as'=>'usulanrakorbidang.store2']);  
    Route::post('/aspirasi/usulanrakorbidang/store3',['uses'=>'RKPD\UsulanRAKORBidangController@store3','as'=>'usulanrakorbidang.store3']); 
    Route::post('/aspirasi/usulanrakorbidang/store4',['uses'=>'RKPD\UsulanRAKORBidangController@store4','as'=>'usulanrakorbidang.store4']); 
    Route::get('/aspirasi/usulanrakorbidang/{uuid}/edit1',['uses'=>'RKPD\UsulanRAKORBidangController@edit1','as'=>'usulanrakorbidang.edit1']);              
    Route::get('/aspirasi/usulanrakorbidang/{uuid}/edit2',['uses'=>'RKPD\UsulanRAKORBidangController@edit2','as'=>'usulanrakorbidang.edit2']);              
    Route::get('/aspirasi/usulanrakorbidang/{uuid}/edit3',['uses'=>'RKPD\UsulanRAKORBidangController@edit3','as'=>'usulanrakorbidang.edit3']);              
    Route::get('/aspirasi/usulanrakorbidang/{uuid}/edit4',['uses'=>'RKPD\UsulanRAKORBidangController@edit4','as'=>'usulanrakorbidang.edit4']);              
    Route::put('/aspirasi/usulanrakorbidang/update1/{uuid}',['uses'=>'RKPD\UsulanRAKORBidangController@update1','as'=>'usulanrakorbidang.update1']);  
    Route::put('/aspirasi/usulanrakorbidang/update2/{uuid}',['uses'=>'RKPD\UsulanRAKORBidangController@update2','as'=>'usulanrakorbidang.update2']);  
    Route::put('/aspirasi/usulanrakorbidang/update3/{uuid}',['uses'=>'RKPD\UsulanRAKORBidangController@update3','as'=>'usulanrakorbidang.update3']); 
    Route::put('/aspirasi/usulanrakorbidang/update4/{uuid}',['uses'=>'RKPD\UsulanRAKORBidangController@update4','as'=>'usulanrakorbidang.update4']); 
    Route::post('/aspirasi/usulanrakorbidang/search',['uses'=>'RKPD\UsulanRAKORBidangController@search','as'=>'usulanrakorbidang.search']);  
    Route::post('/aspirasi/usulanrakorbidang/filter',['uses'=>'RKPD\UsulanRAKORBidangController@filter','as'=>'usulanrakorbidang.filter']);                  
    Route::get('/aspirasi/usulanrakorbidang/paginate/{id}',['uses'=>'RKPD\UsulanRAKORBidangController@paginate','as'=>'usulanrakorbidang.paginate']);              
    Route::post('/aspirasi/usulanrakorbidang/changenumberrecordperpage',['uses'=>'RKPD\UsulanRAKORBidangController@changenumberrecordperpage','as'=>'usulanrakorbidang.changenumberrecordperpage']);  
    Route::post('/aspirasi/usulanrakorbidang/orderby',['uses'=>'RKPD\UsulanRAKORBidangController@orderby','as'=>'usulanrakorbidang.orderby']);
    
    //Rencana Kerja - Pembahasan Rakor Bidang OPD/SKPD [pembahasan]
    Route::resource('/pembahasan/pembahasanrakorbidang','RKPD\PembahasanRAKORBidangController',['parameters'=>['pembahasanrakorbidang'=>'uuid'],
                                                                                                              'only'=>['index','show','update']]); 
    Route::post('/pembahasan/pembahasanrakorbidang/search',['uses'=>'RKPD\PembahasanRAKORBidangController@search','as'=>'pembahasanrakorbidang.search']);  
    Route::post('/pembahasan/pembahasanrakorbidang/filter',['uses'=>'RKPD\PembahasanRAKORBidangController@filter','as'=>'pembahasanrakorbidang.filter']);              
    Route::get('/pembahasan/pembahasanrakorbidang/paginate/{id}',['uses'=>'RKPD\PembahasanRAKORBidangController@paginate','as'=>'pembahasanrakorbidang.paginate']);              
    Route::post('/pembahasan/pembahasanrakorbidang/changenumberrecordperpage',['uses'=>'RKPD\PembahasanRAKORBidangController@changenumberrecordperpage','as'=>'pembahasanrakorbidang.changenumberrecordperpage']);  
    Route::post('/pembahasan/pembahasanrakorbidang/orderby',['uses'=>'RKPD\PembahasanRAKORBidangController@orderby','as'=>'pembahasanrakorbidang.orderby']);  
    Route::post('/pembahasan/pembahasanrakorbidang/transfer',['uses'=>'RKPD\PembahasanRAKORBidangController@transfer','as'=>'pembahasanrakorbidang.transfer']);
    
    //Rencana Kerja - Usulan Forum OPD [aspirasi]
    Route::resource('/aspirasi/usulanforumopd','RKPD\UsulanForumOPDController',['parameters'=>['usulanforumopd'=>'uuid']]);        
    Route::post('/aspirasi/usulanforumopd/pilihusulankegiatan',['uses'=>'RKPD\UsulanForumOPDController@pilihusulankegiatan','as'=>'usulanforumopd.pilihusulankegiatan']);                  
    Route::post('/aspirasi/usulanforumopd/pilihindikatorkinerja',['uses'=>'RKPD\UsulanForumOPDController@pilihindikatorkinerja','as'=>'usulanforumopd.pilihindikatorkinerja']);                  
    Route::get('/aspirasi/usulanforumopd/create1/{uuid}',['uses'=>'RKPD\UsulanForumOPDController@create1','as'=>'usulanforumopd.create1']);              
    Route::get('/aspirasi/usulanforumopd/create2/{uuid}',['uses'=>'RKPD\UsulanForumOPDController@create2','as'=>'usulanforumopd.create2']);              
    Route::get('/aspirasi/usulanforumopd/create3/{uuid}',['uses'=>'RKPD\UsulanForumOPDController@create3','as'=>'usulanforumopd.create3']);              
    Route::get('/aspirasi/usulanforumopd/create4/{uuid}',['uses'=>'RKPD\UsulanForumOPDController@create4','as'=>'usulanforumopd.create4']);              
    Route::post('/aspirasi/usulanforumopd/store1',['uses'=>'RKPD\UsulanForumOPDController@store1','as'=>'usulanforumopd.store1']);  
    Route::post('/aspirasi/usulanforumopd/store2',['uses'=>'RKPD\UsulanForumOPDController@store2','as'=>'usulanforumopd.store2']);  
    Route::post('/aspirasi/usulanforumopd/store3',['uses'=>'RKPD\UsulanForumOPDController@store3','as'=>'usulanforumopd.store3']); 
    Route::post('/aspirasi/usulanforumopd/store4',['uses'=>'RKPD\UsulanForumOPDController@store4','as'=>'usulanforumopd.store4']); 
    Route::get('/aspirasi/usulanforumopd/{uuid}/edit1',['uses'=>'RKPD\UsulanForumOPDController@edit1','as'=>'usulanforumopd.edit1']);              
    Route::get('/aspirasi/usulanforumopd/{uuid}/edit2',['uses'=>'RKPD\UsulanForumOPDController@edit2','as'=>'usulanforumopd.edit2']);              
    Route::get('/aspirasi/usulanforumopd/{uuid}/edit3',['uses'=>'RKPD\UsulanForumOPDController@edit3','as'=>'usulanforumopd.edit3']);              
    Route::get('/aspirasi/usulanforumopd/{uuid}/edit4',['uses'=>'RKPD\UsulanForumOPDController@edit4','as'=>'usulanforumopd.edit4']);              
    Route::put('/aspirasi/usulanforumopd/update1/{uuid}',['uses'=>'RKPD\UsulanForumOPDController@update1','as'=>'usulanforumopd.update1']);  
    Route::put('/aspirasi/usulanforumopd/update2/{uuid}',['uses'=>'RKPD\UsulanForumOPDController@update2','as'=>'usulanforumopd.update2']);  
    Route::put('/aspirasi/usulanforumopd/update3/{uuid}',['uses'=>'RKPD\UsulanForumOPDController@update3','as'=>'usulanforumopd.update3']); 
    Route::put('/aspirasi/usulanforumopd/update4/{uuid}',['uses'=>'RKPD\UsulanForumOPDController@update4','as'=>'usulanforumopd.update4']); 
    Route::post('/aspirasi/usulanforumopd/search',['uses'=>'RKPD\UsulanForumOPDController@search','as'=>'usulanforumopd.search']);  
    Route::post('/aspirasi/usulanforumopd/filter',['uses'=>'RKPD\UsulanForumOPDController@filter','as'=>'usulanforumopd.filter']);                  
    Route::get('/aspirasi/usulanforumopd/paginate/{id}',['uses'=>'RKPD\UsulanForumOPDController@paginate','as'=>'usulanforumopd.paginate']);              
    Route::post('/aspirasi/usulanforumopd/changenumberrecordperpage',['uses'=>'RKPD\UsulanForumOPDController@changenumberrecordperpage','as'=>'usulanforumopd.changenumberrecordperpage']);  
    Route::post('/aspirasi/usulanforumopd/orderby',['uses'=>'RKPD\UsulanForumOPDController@orderby','as'=>'usulanforumopd.orderby']);

    //Rencana Kerja - Pembahasan Forum OPD/SKPD [pembahasan]
    Route::resource('/pembahasan/pembahasanforumopd','RKPD\PembahasanForumOPDController',['parameters'=>['pembahasanforumopd'=>'uuid'],
                                                                                                              'only'=>['index','show','update']]); 
    Route::post('/pembahasan/pembahasanforumopd/search',['uses'=>'RKPD\PembahasanForumOPDController@search','as'=>'pembahasanforumopd.search']);  
    Route::post('/pembahasan/pembahasanforumopd/filter',['uses'=>'RKPD\PembahasanForumOPDController@filter','as'=>'pembahasanforumopd.filter']);              
    Route::get('/pembahasan/pembahasanforumopd/paginate/{id}',['uses'=>'RKPD\PembahasanForumOPDController@paginate','as'=>'pembahasanforumopd.paginate']);              
    Route::post('/pembahasan/pembahasanforumopd/changenumberrecordperpage',['uses'=>'RKPD\PembahasanForumOPDController@changenumberrecordperpage','as'=>'pembahasanforumopd.changenumberrecordperpage']);  
    Route::post('/pembahasan/pembahasanforumopd/orderby',['uses'=>'RKPD\PembahasanForumOPDController@orderby','as'=>'pembahasanforumopd.orderby']);  
    Route::post('/pembahasan/pembahasanforumopd/transfer',['uses'=>'RKPD\PembahasanForumOPDController@transfer','as'=>'pembahasanforumopd.transfer']);
    
    //Rencana Kerja - Usulan Musren Kabupaten [aspirasi]
    Route::resource('/aspirasi/usulanmusrenkab','Musrenbang\UsulanMusrenKabController',['parameters'=>['usulanmusrenkab'=>'uuid']]);        
    Route::post('/aspirasi/usulanmusrenkab/pilihusulankegiatan',['uses'=>'Musrenbang\UsulanMusrenKabController@pilihusulankegiatan','as'=>'usulanmusrenkab.pilihusulankegiatan']);                  
    Route::post('/aspirasi/usulanmusrenkab/pilihindikatorkinerja',['uses'=>'Musrenbang\UsulanMusrenKabController@pilihindikatorkinerja','as'=>'usulanmusrenkab.pilihindikatorkinerja']);                  
    Route::get('/aspirasi/usulanmusrenkab/create1/{uuid}',['uses'=>'Musrenbang\UsulanMusrenKabController@create1','as'=>'usulanmusrenkab.create1']);              
    Route::get('/aspirasi/usulanmusrenkab/create2/{uuid}',['uses'=>'Musrenbang\UsulanMusrenKabController@create2','as'=>'usulanmusrenkab.create2']);              
    Route::get('/aspirasi/usulanmusrenkab/create3/{uuid}',['uses'=>'Musrenbang\UsulanMusrenKabController@create3','as'=>'usulanmusrenkab.create3']);              
    Route::get('/aspirasi/usulanmusrenkab/create4/{uuid}',['uses'=>'Musrenbang\UsulanMusrenKabController@create4','as'=>'usulanmusrenkab.create4']);              
    Route::post('/aspirasi/usulanmusrenkab/store1',['uses'=>'Musrenbang\UsulanMusrenKabController@store1','as'=>'usulanmusrenkab.store1']);  
    Route::post('/aspirasi/usulanmusrenkab/store2',['uses'=>'Musrenbang\UsulanMusrenKabController@store2','as'=>'usulanmusrenkab.store2']);  
    Route::post('/aspirasi/usulanmusrenkab/store3',['uses'=>'Musrenbang\UsulanMusrenKabController@store3','as'=>'usulanmusrenkab.store3']); 
    Route::post('/aspirasi/usulanmusrenkab/store4',['uses'=>'Musrenbang\UsulanMusrenKabController@store4','as'=>'usulanmusrenkab.store4']); 
    Route::get('/aspirasi/usulanmusrenkab/{uuid}/edit1',['uses'=>'Musrenbang\UsulanMusrenKabController@edit1','as'=>'usulanmusrenkab.edit1']);              
    Route::get('/aspirasi/usulanmusrenkab/{uuid}/edit2',['uses'=>'Musrenbang\UsulanMusrenKabController@edit2','as'=>'usulanmusrenkab.edit2']);              
    Route::get('/aspirasi/usulanmusrenkab/{uuid}/edit3',['uses'=>'Musrenbang\UsulanMusrenKabController@edit3','as'=>'usulanmusrenkab.edit3']);              
    Route::get('/aspirasi/usulanmusrenkab/{uuid}/edit4',['uses'=>'Musrenbang\UsulanMusrenKabController@edit4','as'=>'usulanmusrenkab.edit4']);              
    Route::put('/aspirasi/usulanmusrenkab/update1/{uuid}',['uses'=>'Musrenbang\UsulanMusrenKabController@update1','as'=>'usulanmusrenkab.update1']);  
    Route::put('/aspirasi/usulanmusrenkab/update2/{uuid}',['uses'=>'Musrenbang\UsulanMusrenKabController@update2','as'=>'usulanmusrenkab.update2']);  
    Route::put('/aspirasi/usulanmusrenkab/update3/{uuid}',['uses'=>'Musrenbang\UsulanMusrenKabController@update3','as'=>'usulanmusrenkab.update3']); 
    Route::put('/aspirasi/usulanmusrenkab/update4/{uuid}',['uses'=>'Musrenbang\UsulanMusrenKabController@update4','as'=>'usulanmusrenkab.update4']); 
    Route::post('/aspirasi/usulanmusrenkab/search',['uses'=>'Musrenbang\UsulanMusrenKabController@search','as'=>'usulanmusrenkab.search']);  
    Route::post('/aspirasi/usulanmusrenkab/filter',['uses'=>'Musrenbang\UsulanMusrenKabController@filter','as'=>'usulanmusrenkab.filter']);                  
    Route::get('/aspirasi/usulanmusrenkab/paginate/{id}',['uses'=>'Musrenbang\UsulanMusrenKabController@paginate','as'=>'usulanmusrenkab.paginate']);              
    Route::post('/aspirasi/usulanmusrenkab/changenumberrecordperpage',['uses'=>'Musrenbang\UsulanMusrenKabController@changenumberrecordperpage','as'=>'usulanmusrenkab.changenumberrecordperpage']);  
    Route::post('/aspirasi/usulanmusrenkab/orderby',['uses'=>'Musrenbang\UsulanMusrenKabController@orderby','as'=>'usulanmusrenkab.orderby']);
    
    //Rencana Kerja - Pembahasan Musrenbang Kabupaten [pembahasan]
    Route::resource('/pembahasan/pembahasanmusrenkab','Musrenbang\PembahasanMusrenKabController',['parameters'=>['pembahasanmusrenkab'=>'uuid'],
                                                                                                              'only'=>['index','show','update']]); 
    Route::post('/pembahasan/pembahasanmusrenkab/search',['uses'=>'Musrenbang\PembahasanMusrenKabController@search','as'=>'pembahasanmusrenkab.search']);  
    Route::post('/pembahasan/pembahasanmusrenkab/filter',['uses'=>'Musrenbang\PembahasanMusrenKabController@filter','as'=>'pembahasanmusrenkab.filter']);              
    Route::get('/pembahasan/pembahasanmusrenkab/paginate/{id}',['uses'=>'Musrenbang\PembahasanMusrenKabController@paginate','as'=>'pembahasanmusrenkab.paginate']);              
    Route::post('/pembahasan/pembahasanmusrenkab/changenumberrecordperpage',['uses'=>'Musrenbang\PembahasanMusrenKabController@changenumberrecordperpage','as'=>'pembahasanmusrenkab.changenumberrecordperpage']);  
    Route::post('/pembahasan/pembahasanmusrenkab/orderby',['uses'=>'Musrenbang\PembahasanMusrenKabController@orderby','as'=>'pembahasanmusrenkab.orderby']);  
    Route::post('/pembahasan/pembahasanmusrenkab/transfer',['uses'=>'Musrenbang\PembahasanMusrenKabController@transfer','as'=>'pembahasanmusrenkab.transfer']);
    

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
    Route::get('/setting/users/profil',['uses'=>'Setting\UsersController@profil','as'=>'users.profil']);    
    Route::put('/setting/users/updateprofil',['uses'=>'Setting\UsersController@updateprofil','as'=>'users.updateprofil']);        
    Route::post('/setting/users/changenumberrecordperpage',['uses'=>'Setting\UsersController@changenumberrecordperpage','as'=>'users.changenumberrecordperpage']);  
    Route::post('/setting/users/orderby',['uses'=>'Setting\UsersController@orderby','as'=>'users.orderby']); 
    Route::post('/setting/users/search',['uses'=>'Setting\UsersController@search','as'=>'users.search']);    
    Route::post('/setting/users/filter',['uses'=>'Setting\UsersController@filter','as'=>'users.filter']);       

    //setting - users OPD
    Route::resource('/setting/usersopd','Setting\UsersOPDController',['parameters'=>['usersopd'=>'id']]);           
    Route::get('/setting/usersopd/paginate/{id}',['uses'=>'Setting\UsersOPDController@paginate','as'=>'usersopd.paginate']);
    Route::post('/setting/usersopd/changenumberrecordperpage',['uses'=>'Setting\UsersOPDController@changenumberrecordperpage','as'=>'usersopd.changenumberrecordperpage']);  
    Route::post('/setting/usersopd/orderby',['uses'=>'Setting\UsersOPDController@orderby','as'=>'usersopd.orderby']); 
    Route::post('/setting/usersopd/search',['uses'=>'Setting\UsersOPDController@search','as'=>'usersopd.search']);    
    Route::post('/setting/usersopd/filter',['uses'=>'Setting\UsersOPDController@filter','as'=>'usersopd.filter']);    
    Route::post('/setting/usersopd/storeuserpermission', ['uses'=>'Setting\UsersOPDController@storeuserpermission','as'=>'usersopd.storeuserpermission']);
});