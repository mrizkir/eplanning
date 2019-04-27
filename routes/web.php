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