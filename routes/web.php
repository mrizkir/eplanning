<?php

//disable halaman register [pendaftaran]
Auth::routes(['register' => false]);

Route::get('/',['uses'=>'FrontendController@welcome','as'=>'frontend.index']);
Route::get('/logout',['uses'=>'Auth\LoginController@logout','as'=>'logout']);
Route::group (['prefix'=>'admin','middleware'=>['disablepreventback','web', 'auth']],function() {     
    Route::get('/',['uses'=>'DashboardController@index','as'=>'dashboard.index']);          
    Route::resource('/dashboard/rekappaguindikatifopd','Report\RekapPaguIndikatifOPDController',[
                                                                                                    'parameters'=>['rekappaguindikatifopd'=>'uuid'],
                                                                                                    'only'=>['index','store','update']
                                                                                                ]); 
    Route::post('/dashboard/rekappaguindikatifopd/orderby',['uses'=>'Report\RekapPaguIndikatifOPDController@orderby','as'=>'rekappaguindikatifopd.orderby']); 

    //masters - tahun perencanaan dan penyerapan anggaran
    Route::resource('/dmaster/ta','DMaster\TAController',['parameters'=>['ta'=>'uuid']]); 
    Route::get('/dmaster/ta/paginate/{id}',['uses'=>'DMaster\TAController@paginate','as'=>'ta.paginate']);              
    Route::post('/dmaster/ta/changenumberrecordperpage',['uses'=>'DMaster\TAController@changenumberrecordperpage','as'=>'ta.changenumberrecordperpage']);  
    Route::post('/dmaster/ta/orderby',['uses'=>'DMaster\TAController@orderby','as'=>'ta.orderby']); 
    
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
    Route::resource('/perencanaan/rpjmd/rpjmdvisi','RPJMD\RPJMDVisiController',['parameters'=>['rpjmdvisi'=>'uuid']]); 
    Route::post('/perencanaan/rpjmd/rpjmdvisi/search',['uses'=>'RPJMD\RPJMDVisiController@search','as'=>'rpjmdvisi.search']); 
    Route::get('/perencanaan/rpjmd/rpjmdvisi/paginate/{id}',['uses'=>'RPJMD\RPJMDVisiController@paginate','as'=>'rpjmdvisi.paginate']);              
    Route::post('/perencanaan/rpjmd/rpjmdvisi/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDVisiController@changenumberrecordperpage','as'=>'rpjmdvisi.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdvisi/orderby',['uses'=>'RPJMD\RPJMDVisiController@orderby','as'=>'rpjmdvisi.orderby']); 

    //RPJMD - Misi
    Route::resource('/perencanaan/rpjmd/rpjmdmisi','RPJMD\RPJMDMisiController',['parameters'=>['rpjmdmisi'=>'uuid']]); 
    Route::post('/perencanaan/rpjmd/rpjmdmisi/search',['uses'=>'RPJMD\RPJMDMisiController@search','as'=>'rpjmdmisi.search']); 
    Route::get('/perencanaan/rpjmd/rpjmdmisi/paginate/{id}',['uses'=>'RPJMD\RPJMDMisiController@paginate','as'=>'rpjmdmisi.paginate']);              
    Route::post('/perencanaan/rpjmd/rpjmdmisi/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDMisiController@changenumberrecordperpage','as'=>'rpjmdmisi.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdmisi/orderby',['uses'=>'RPJMD\RPJMDMisiController@orderby','as'=>'rpjmdmisi.orderby']); 

    //RPJMD - Tujuan
    Route::resource('/perencanaan/rpjmd/rpjmdtujuan','RPJMD\RPJMDTujuanController',['parameters'=>['rpjmdtujuan'=>'uuid']]); 
    Route::post('/perencanaan/rpjmd/rpjmdtujuan/search',['uses'=>'RPJMD\RPJMDTujuanController@search','as'=>'rpjmdtujuan.search']); 
    Route::get('/perencanaan/rpjmd/rpjmdtujuan/paginate/{id}',['uses'=>'RPJMD\RPJMDTujuanController@paginate','as'=>'rpjmdtujuan.paginate']);              
    Route::post('/perencanaan/rpjmd/rpjmdtujuan/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDTujuanController@changenumberrecordperpage','as'=>'rpjmdtujuan.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdtujuan/orderby',['uses'=>'RPJMD\RPJMDTujuanController@orderby','as'=>'rpjmdtujuan.orderby']); 
    
    //RPJMD - Sasaran
    Route::resource('/perencanaan/rpjmd/rpjmdsasaran','RPJMD\RPJMDSasaranController',['parameters'=>['rpjmdsasaran'=>'uuid']]); 
    Route::post('/perencanaan/rpjmd/rpjmdsasaran/search',['uses'=>'RPJMD\RPJMDSasaranController@search','as'=>'rpjmdsasaran.search']); 
    Route::get('/perencanaan/rpjmd/rpjmdsasaran/paginate/{id}',['uses'=>'RPJMD\RPJMDSasaranController@paginate','as'=>'rpjmdsasaran.paginate']);              
    Route::post('/perencanaan/rpjmd/rpjmdsasaran/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDSasaranController@changenumberrecordperpage','as'=>'rpjmdsasaran.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdsasaran/orderby',['uses'=>'RPJMD\RPJMDSasaranController@orderby','as'=>'rpjmdsasaran.orderby']); 

    //RPJMD - Strategi
    Route::resource('/perencanaan/rpjmd/rpjmdstrategi','RPJMD\RPJMDStrategiController',['parameters'=>['rpjmdstrategi'=>'uuid']]); 
    Route::post('/perencanaan/rpjmd/rpjmdstrategi/search',['uses'=>'RPJMD\RPJMDStrategiController@search','as'=>'rpjmdstrategi.search']); 
    Route::get('/perencanaan/rpjmd/rpjmdstrategi/paginate/{id}',['uses'=>'RPJMD\RPJMDStrategiController@paginate','as'=>'rpjmdstrategi.paginate']);              
    Route::post('/perencanaan/rpjmd/rpjmdstrategi/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDStrategiController@changenumberrecordperpage','as'=>'rpjmdstrategi.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdstrategi/orderby',['uses'=>'RPJMD\RPJMDStrategiController@orderby','as'=>'rpjmdstrategi.orderby']); 

    //RPJMD - Kebijakan
    Route::resource('/perencanaan/rpjmd/rpjmdkebijakan','RPJMD\RPJMDKebijakanController',['parameters'=>['rpjmdkebijakan'=>'uuid']]); 
    Route::post('/perencanaan/rpjmd/rpjmdkebijakan/search',['uses'=>'RPJMD\RPJMDKebijakanController@search','as'=>'rpjmdkebijakan.search']); 
    Route::get('/perencanaan/rpjmd/rpjmdkebijakan/paginate/{id}',['uses'=>'RPJMD\RPJMDKebijakanController@paginate','as'=>'rpjmdkebijakan.paginate']);              
    Route::post('/perencanaan/rpjmd/rpjmdkebijakan/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDKebijakanController@changenumberrecordperpage','as'=>'rpjmdkebijakan.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdkebijakan/orderby',['uses'=>'RPJMD\RPJMDKebijakanController@orderby','as'=>'rpjmdkebijakan.orderby']); 
    
    //RPJMD - Indikator Rencana Program Prioritas atau Indikator Kinerja
    Route::resource('/perencanaan/rpjmd/rpjmdindikatorkinerja','RPJMD\RPJMDIndikatorKinerjaController',['parameters'=>['rpjmdindikatorkinerja'=>'uuid']]); 
    Route::post('/perencanaan/rpjmd/rpjmdindikatorkinerja/search',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@search','as'=>'rpjmdindikatorkinerja.search']); 
    Route::post('/perencanaan/rpjmd/rpjmdindikatorkinerja/filter',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@filter','as'=>'rpjmdindikatorkinerja.filter']);                  
    Route::get('/perencanaan/rpjmd/rpjmdindikatorkinerja/paginate/{id}',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@paginate','as'=>'rpjmdindikatorkinerja.paginate']);              
    Route::post('/perencanaan/rpjmd/rpjmdindikatorkinerja/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@changenumberrecordperpage','as'=>'rpjmdindikatorkinerja.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdindikatorkinerja/orderby',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@orderby','as'=>'rpjmdindikatorkinerja.orderby']);
   
    //RENSTRA - Visi
    Route::resource('/perencanaan/renstra/renstravisi','RENSTRA\RENSTRAVisiController',['parameters'=>['renstravisi'=>'uuid']]); 
    Route::post('/perencanaan/renstra/renstravisi/filter',['uses'=>'RENSTRA\RENSTRAVisiController@filter','as'=>'renstravisi.filter']);   
    Route::post('/perencanaan/renstra/renstravisi/search',['uses'=>'RENSTRA\RENSTRAVisiController@search','as'=>'renstravisi.search']); 
    Route::get('/perencanaan/renstra/renstravisi/paginate/{id}',['uses'=>'RENSTRA\RENSTRAVisiController@paginate','as'=>'renstravisi.paginate']);              
    Route::post('/perencanaan/renstra/renstravisi/changenumberrecordperpage',['uses'=>'RENSTRA\RENSTRAVisiController@changenumberrecordperpage','as'=>'renstravisi.changenumberrecordperpage']);  
    Route::post('/perencanaan/renstra/renstravisi/orderby',['uses'=>'RENSTRA\RENSTRAVisiController@orderby','as'=>'renstravisi.orderby']); 
   
    //RENSTRA - Misi
    Route::resource('/perencanaan/renstra/renstramisi','RENSTRA\RENSTRAMisiController',['parameters'=>['renstramisi'=>'uuid']]); 
    Route::post('/perencanaan/renstra/renstramisi/filter',['uses'=>'RENSTRA\RENSTRAMisiController@filter','as'=>'renstramisi.filter']);   
    Route::post('/perencanaan/renstra/renstramisi/search',['uses'=>'RENSTRA\RENSTRAMisiController@search','as'=>'renstramisi.search']); 
    Route::get('/perencanaan/renstra/renstramisi/paginate/{id}',['uses'=>'RENSTRA\RENSTRAMisiController@paginate','as'=>'renstramisi.paginate']);              
    Route::post('/perencanaan/renstra/renstramisi/changenumberrecordperpage',['uses'=>'RENSTRA\RENSTRAMisiController@changenumberrecordperpage','as'=>'renstramisi.changenumberrecordperpage']);  
    Route::post('/perencanaan/renstra/renstramisi/orderby',['uses'=>'RENSTRA\RENSTRAMisiController@orderby','as'=>'renstramisi.orderby']); 
   
    //RENSTRA - Tujuan
    Route::resource('/perencanaan/renstra/renstratujuan','RENSTRA\RENSTRATujuanController',['parameters'=>['renstratujuan'=>'uuid']]); 
    Route::post('/perencanaan/renstra/renstratujuan/filter',['uses'=>'RENSTRA\RENSTRATujuanController@filter','as'=>'renstratujuan.filter']); 
    Route::post('/perencanaan/renstra/renstratujuan/search',['uses'=>'RENSTRA\RENSTRATujuanController@search','as'=>'renstratujuan.search']); 
    Route::get('/perencanaan/renstra/renstratujuan/paginate/{id}',['uses'=>'RENSTRA\RENSTRATujuanController@paginate','as'=>'renstratujuan.paginate']);              
    Route::post('/perencanaan/renstra/renstratujuan/changenumberrecordperpage',['uses'=>'RENSTRA\RENSTRATujuanController@changenumberrecordperpage','as'=>'renstratujuan.changenumberrecordperpage']);  
    Route::post('/perencanaan/renstra/renstratujuan/orderby',['uses'=>'RENSTRA\RENSTRATujuanController@orderby','as'=>'renstratujuan.orderby']); 

    //RENSTRA - Sasaran
    Route::resource('/perencanaan/renstra/renstrasasaran','RENSTRA\RENSTRASasaranController',['parameters'=>['renstrasasaran'=>'uuid']]); 
    Route::post('/perencanaan/renstra/renstrasasaran/search',['uses'=>'RENSTRA\RENSTRASasaranController@search','as'=>'renstrasasaran.search']); 
    Route::post('/perencanaan/renstra/renstrasasaran/search',['uses'=>'RENSTRA\RENSTRASasaranController@search','as'=>'renstrasasaran.filter']); 
    Route::get('/perencanaan/renstra/renstrasasaran/paginate/{id}',['uses'=>'RENSTRA\RENSTRASasaranController@paginate','as'=>'renstrasasaran.paginate']);              
    Route::post('/perencanaan/renstra/renstrasasaran/changenumberrecordperpage',['uses'=>'RENSTRA\RENSTRASasaranController@changenumberrecordperpage','as'=>'renstrasasaran.changenumberrecordperpage']);  
    Route::post('/perencanaan/renstra/renstrasasaran/orderby',['uses'=>'RENSTRA\RENSTRASasaranController@orderby','as'=>'renstrasasaran.orderby']); 

    //RENSTRA - Strategi
    Route::resource('/perencanaan/renstra/renstrastrategi','RENSTRA\RENSTRAStrategiController',['parameters'=>['renstrastrategi'=>'uuid']]); 
    Route::post('/perencanaan/renstra/renstrastrategi/search',['uses'=>'RENSTRA\RENSTRAStrategiController@search','as'=>'renstrastrategi.search']); 
    Route::post('/perencanaan/renstra/renstrastrategi/filter',['uses'=>'RENSTRA\RENSTRAStrategiController@filter','as'=>'renstrastrategi.filter']); 
    Route::get('/perencanaan/renstra/renstrastrategi/paginate/{id}',['uses'=>'RENSTRA\RENSTRAStrategiController@paginate','as'=>'renstrastrategi.paginate']);              
    Route::post('/perencanaan/renstra/renstrastrategi/changenumberrecordperpage',['uses'=>'RENSTRA\RENSTRAStrategiController@changenumberrecordperpage','as'=>'renstrastrategi.changenumberrecordperpage']);  
    Route::post('/perencanaan/renstra/renstrastrategi/orderby',['uses'=>'RENSTRA\RENSTRAStrategiController@orderby','as'=>'renstrastrategi.orderby']); 

    //RENSTRA - Kebijakan
    Route::resource('/perencanaan/renstra/renstrakebijakan','RENSTRA\RENSTRAKebijakanController',['parameters'=>['renstrakebijakan'=>'uuid']]); 
    Route::post('/perencanaan/renstra/renstrakebijakan/search',['uses'=>'RENSTRA\RENSTRAKebijakanController@search','as'=>'renstrakebijakan.search']); 
    Route::post('/perencanaan/renstra/renstrakebijakan/filter',['uses'=>'RENSTRA\RENSTRAKebijakanController@filter','as'=>'renstrakebijakan.filter']); 
    Route::get('/perencanaan/renstra/renstrakebijakan/paginate/{id}',['uses'=>'RENSTRA\RENSTRAKebijakanController@paginate','as'=>'renstrakebijakan.paginate']);              
    Route::post('/perencanaan/renstra/renstrakebijakan/changenumberrecordperpage',['uses'=>'RENSTRA\RENSTRAKebijakanController@changenumberrecordperpage','as'=>'renstrakebijakan.changenumberrecordperpage']);  
    Route::post('/perencanaan/renstra/renstrakebijakan/orderby',['uses'=>'RENSTRA\RENSTRAKebijakanController@orderby','as'=>'renstrakebijakan.orderby']); 
    
    //RENSTRA - Indikator Sasaran
    Route::resource('/perencanaan/renstra/renstraindikatorsasaran','RENSTRA\RENSTRAIndikatorKinerjaController',['parameters'=>['renstraindikatorsasaran'=>'uuid']]); 
    Route::post('/perencanaan/renstra/renstraindikatorsasaran/search',['uses'=>'RENSTRA\RENSTRAIndikatorKinerjaController@search','as'=>'renstraindikatorsasaran.search']); 
    Route::post('/perencanaan/renstra/renstraindikatorsasaran/filter',['uses'=>'RENSTRA\RENSTRAIndikatorKinerjaController@filter','as'=>'renstraindikatorsasaran.filter']); 
    Route::get('/perencanaan/renstra/renstraindikatorsasaran/paginate/{id}',['uses'=>'RENSTRA\RENSTRAIndikatorKinerjaController@paginate','as'=>'renstraindikatorsasaran.paginate']);              
    Route::post('/perencanaan/renstra/renstraindikatorsasaran/changenumberrecordperpage',['uses'=>'RENSTRA\RENSTRAIndikatorKinerjaController@changenumberrecordperpage','as'=>'renstraindikatorsasaran.changenumberrecordperpage']);  
    Route::post('/perencanaan/renstra/renstraindikatorsasaran/orderby',['uses'=>'RENSTRA\RENSTRAIndikatorKinerjaController@orderby','as'=>'renstraindikatorsasaran.orderby']);
        
    //POKIR - Pemilik Pokok Pikiran
    Route::resource('/perencanaan/pokir/pemilikpokokpikiran','Pokir\PemilikPokokPikiranController',['parameters'=>['pemilikpokokpikiran'=>'uuid']]);        
    Route::post('/perencanaan/pokir/pemilikpokokpikiran/search',['uses'=>'Pokir\PemilikPokokPikiranController@search','as'=>'pemilikpokokpikiran.search']); 
    Route::get('/perencanaan/pokir/pemilikpokokpikiran/paginate/{id}',['uses'=>'Pokir\PemilikPokokPikiranController@paginate','as'=>'pemilikpokokpikiran.paginate']);              
    Route::post('/perencanaan/pokir/pemilikpokokpikiran/changenumberrecordperpage',['uses'=>'Pokir\PemilikPokokPikiranController@changenumberrecordperpage','as'=>'pemilikpokokpikiran.changenumberrecordperpage']);  
    Route::post('/perencanaan/pokir/pemilikpokokpikiran/orderby',['uses'=>'Pokir\PemilikPokokPikiranController@orderby','as'=>'pemilikpokokpikiran.orderby']); 

    //POKIR - Pokok Pikiran
    Route::resource('/perencanaan/pokokpikiran','Pokir\PokokPikiranController',['parameters'=>['pokir'=>'uuid']]);        
    Route::post('/perencanaan/pokokpikiran/search',['uses'=>'Pokir\PokokPikiranController@search','as'=>'pokokpikiran.search']); 
    Route::post('/perencanaan/pokokpikiran/filter',['uses'=>'Pokir\PokokPikiranController@filter','as'=>'pokokpikiran.filter']);                  
    Route::get('/perencanaan/pokokpikiran/paginate/{id}',['uses'=>'Pokir\PokokPikiranController@paginate','as'=>'pokokpikiran.paginate']);              
    Route::post('/perencanaan/pokokpikiran/changenumberrecordperpage',['uses'=>'Pokir\PokokPikiranController@changenumberrecordperpage','as'=>'pokokpikiran.changenumberrecordperpage']);  
    Route::post('/perencanaan/pokokpikiran/orderby',['uses'=>'Pokir\PokokPikiranController@orderby','as'=>'pokokpikiran.orderby']); 

    //workflow - Aspirasi Musrenbang Desa
    Route::resource('/workflow/musrenbang/aspirasimusrendesa','Musrenbang\AspirasiMusrenDesaController',['parameters'=>['aspirasimusrendesa'=>'uuid']]);        
    Route::post('/workflow/musrenbang/aspirasimusrendesa/search',['uses'=>'Musrenbang\AspirasiMusrenDesaController@search','as'=>'aspirasimusrendesa.search']);  
    Route::post('/workflow/musrenbang/aspirasimusrendesa/filter',['uses'=>'Musrenbang\AspirasiMusrenDesaController@filter','as'=>'aspirasimusrendesa.filter']);                  
    Route::get('/workflow/musrenbang/aspirasimusrendesa/paginate/{id}',['uses'=>'Musrenbang\AspirasiMusrenDesaController@paginate','as'=>'aspirasimusrendesa.paginate']);              
    Route::post('/workflow/musrenbang/aspirasimusrendesa/changenumberrecordperpage',['uses'=>'Musrenbang\AspirasiMusrenDesaController@changenumberrecordperpage','as'=>'aspirasimusrendesa.changenumberrecordperpage']);  
    Route::post('/workflow/musrenbang/aspirasimusrendesa/orderby',['uses'=>'Musrenbang\AspirasiMusrenDesaController@orderby','as'=>'aspirasimusrendesa.orderby']);

     //workflow - Pembahasan Musrenbang Desa [pembahasan]
    Route::resource('/workflow/musrenbang/pembahasanmusrendesa','Musrenbang\PembahasanMusrenDesaController',['parameters'=>['pembahasanmusrendesa'=>'uuid'],
                                                                                                    'only'=>['index','update']]); 
    Route::post('/workflow/musrenbang/pembahasanmusrendesa/search',['uses'=>'Musrenbang\PembahasanMusrenDesaController@search','as'=>'pembahasanmusrendesa.search']);  
    Route::post('/workflow/musrenbang/pembahasanmusrendesa/filter',['uses'=>'Musrenbang\PembahasanMusrenDesaController@filter','as'=>'pembahasanmusrendesa.filter']);              
    Route::get('/workflow/musrenbang/pembahasanmusrendesa/paginate/{id}',['uses'=>'Musrenbang\PembahasanMusrenDesaController@paginate','as'=>'pembahasanmusrendesa.paginate']);              
    Route::post('/workflow/musrenbang/pembahasanmusrendesa/changenumberrecordperpage',['uses'=>'Musrenbang\PembahasanMusrenDesaController@changenumberrecordperpage','as'=>'pembahasanmusrendesa.changenumberrecordperpage']);  
    Route::post('/workflow/musrenbang/pembahasanmusrendesa/orderby',['uses'=>'Musrenbang\PembahasanMusrenDesaController@orderby','as'=>'pembahasanmusrendesa.orderby']); 
    
    //workflow - Aspirasi Musrenbang Kecamatan [aspirasi]
    Route::resource('/workflow/musrenbang/aspirasimusrenkecamatan','Musrenbang\AspirasiMusrenKecamatanController',['parameters'=>['aspirasimusrenkecamatan'=>'uuid']]);    
    Route::post('/workflow/musrenbang/aspirasimusrenkecamatan/storeusulankecamatan',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@storeusulankecamatan','as'=>'aspirasimusrenkecamatan.storeusulankecamatan']);  
    Route::get('/workflow/musrenbang/aspirasimusrenkecamatan/pilihusulankegiatan',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@pilihusulankegiatan','as'=>'aspirasimusrenkecamatan.pilihusulankegiatan']);                  
    Route::post('/workflow/musrenbang/aspirasimusrenkecamatan/search',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@search','as'=>'aspirasimusrenkecamatan.search']);  
    Route::post('/workflow/musrenbang/aspirasimusrenkecamatan/filter',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@filter','as'=>'aspirasimusrenkecamatan.filter']);              
    Route::post('/workflow/musrenbang/aspirasimusrenkecamatan/filterurusan',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@filterurusan','as'=>'aspirasimusrenkecamatan.filterurusan']);  
    Route::get('/workflow/musrenbang/aspirasimusrenkecamatan/paginate/{id}',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@paginate','as'=>'aspirasimusrenkecamatan.paginate']);                  
    Route::post('/workflow/musrenbang/aspirasimusrenkecamatan/changenumberrecordperpage',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@changenumberrecordperpage','as'=>'aspirasimusrenkecamatan.changenumberrecordperpage']);  
    Route::post('/workflow/musrenbang/aspirasimusrenkecamatan/orderby',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@orderby','as'=>'aspirasimusrenkecamatan.orderby']);  
    Route::post('/workflow/musrenbang/aspirasimusrenkecamatan/orderbypilihusulankegiatan',['uses'=>'Musrenbang\AspirasiMusrenKecamatanController@orderbypilihusulankegiatan','as'=>'aspirasimusrenkecamatan.orderbypilihusulankegiatan']);  
    
    //workflow - Pembahasan Musrenbang Kecamatan [pembahasan]
    Route::resource('/workflow/musrenbang/pembahasanmusrenkecamatan','Musrenbang\PembahasanMusrenKecamatanController',['parameters'=>['pembahasanmusrenkecamatan'=>'uuid'],
                                                                                                              'only'=>['index','update']]); 
    Route::post('/workflow/musrenbang/pembahasanmusrenkecamatan/search',['uses'=>'Musrenbang\PembahasanMusrenKecamatanController@search','as'=>'pembahasanmusrenkecamatan.search']);  
    Route::post('/workflow/musrenbang/pembahasanmusrenkecamatan/filter',['uses'=>'Musrenbang\PembahasanMusrenKecamatanController@filter','as'=>'pembahasanmusrenkecamatan.filter']);              
    Route::get('/workflow/musrenbang/pembahasanmusrenkecamatan/paginate/{id}',['uses'=>'Musrenbang\PembahasanMusrenKecamatanController@paginate','as'=>'pembahasanmusrenkecamatan.paginate']);              
    Route::post('/workflow/musrenbang/pembahasanmusrenkecamatan/changenumberrecordperpage',['uses'=>'Musrenbang\PembahasanMusrenKecamatanController@changenumberrecordperpage','as'=>'pembahasanmusrenkecamatan.changenumberrecordperpage']);  
    Route::post('/workflow/musrenbang/pembahasanmusrenkecamatan/orderby',['uses'=>'Musrenbang\PembahasanMusrenKecamatanController@orderby','as'=>'pembahasanmusrenkecamatan.orderby']);  
            
    //workflow - Usulan Pra Renja OPD/SKPD [aspirasi]
    Route::resource('/workflow/rkpd/usulanprarenjaopd','RKPD\UsulanRenjaController',['parameters'=>['usulanprarenjaopd'=>'uuid']]);        
    Route::post('/workflow/rkpd/usulanprarenjaopd/pilihusulankegiatan',['uses'=>'RKPD\UsulanRenjaController@pilihusulankegiatan','as'=>'usulanprarenjaopd.pilihusulankegiatan']);                  
    Route::post('/workflow/rkpd/usulanprarenjaopd/pilihindikatorkinerja',['uses'=>'RKPD\UsulanRenjaController@pilihindikatorkinerja','as'=>'usulanprarenjaopd.pilihindikatorkinerja']);                  
    Route::get('/workflow/rkpd/usulanprarenjaopd/create1/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create1','as'=>'usulanprarenjaopd.create1']);              
    Route::get('/workflow/rkpd/usulanprarenjaopd/create2/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create2','as'=>'usulanprarenjaopd.create2']);              
    Route::get('/workflow/rkpd/usulanprarenjaopd/create3/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create3','as'=>'usulanprarenjaopd.create3']);              
    Route::get('/workflow/rkpd/usulanprarenjaopd/create4/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create4','as'=>'usulanprarenjaopd.create4']);              
    Route::post('/workflow/rkpd/usulanprarenjaopd/store1',['uses'=>'RKPD\UsulanRenjaController@store1','as'=>'usulanprarenjaopd.store1']);  
    Route::post('/workflow/rkpd/usulanprarenjaopd/store2',['uses'=>'RKPD\UsulanRenjaController@store2','as'=>'usulanprarenjaopd.store2']);  
    Route::post('/workflow/rkpd/usulanprarenjaopd/store3',['uses'=>'RKPD\UsulanRenjaController@store3','as'=>'usulanprarenjaopd.store3']); 
    Route::post('/workflow/rkpd/usulanprarenjaopd/store4',['uses'=>'RKPD\UsulanRenjaController@store4','as'=>'usulanprarenjaopd.store4']); 
    Route::get('/workflow/rkpd/usulanprarenjaopd/{uuid}/edit1',['uses'=>'RKPD\UsulanRenjaController@edit1','as'=>'usulanprarenjaopd.edit1']);              
    Route::get('/workflow/rkpd/usulanprarenjaopd/{uuid}/edit2',['uses'=>'RKPD\UsulanRenjaController@edit2','as'=>'usulanprarenjaopd.edit2']);              
    Route::get('/workflow/rkpd/usulanprarenjaopd/{uuid}/edit3',['uses'=>'RKPD\UsulanRenjaController@edit3','as'=>'usulanprarenjaopd.edit3']);              
    Route::get('/workflow/rkpd/usulanprarenjaopd/{uuid}/edit4',['uses'=>'RKPD\UsulanRenjaController@edit4','as'=>'usulanprarenjaopd.edit4']);              
    Route::put('/workflow/rkpd/usulanprarenjaopd/update1/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update1','as'=>'usulanprarenjaopd.update1']);  
    Route::put('/workflow/rkpd/usulanprarenjaopd/update2/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update2','as'=>'usulanprarenjaopd.update2']);  
    Route::put('/workflow/rkpd/usulanprarenjaopd/update3/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update3','as'=>'usulanprarenjaopd.update3']); 
    Route::put('/workflow/rkpd/usulanprarenjaopd/update4/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update4','as'=>'usulanprarenjaopd.update4']); 
    Route::get('/workflow/rkpd/usulanprarenjaopd/showrincian/{uuid}',['uses'=>'RKPD\UsulanRenjaController@showrincian','as'=>'usulanprarenjaopd.showrincian']); 
    Route::post('/workflow/rkpd/usulanprarenjaopd/search',['uses'=>'RKPD\UsulanRenjaController@search','as'=>'usulanprarenjaopd.search']);  
    Route::post('/workflow/rkpd/usulanprarenjaopd/filter',['uses'=>'RKPD\UsulanRenjaController@filter','as'=>'usulanprarenjaopd.filter']);                  
    Route::get('/workflow/rkpd/usulanprarenjaopd/paginate/{id}',['uses'=>'RKPD\UsulanRenjaController@paginate','as'=>'usulanprarenjaopd.paginate']);              
    Route::post('/workflow/rkpd/usulanprarenjaopd/changenumberrecordperpage',['uses'=>'RKPD\UsulanRenjaController@changenumberrecordperpage','as'=>'usulanprarenjaopd.changenumberrecordperpage']);  
    Route::post('/workflow/rkpd/usulanprarenjaopd/orderby',['uses'=>'RKPD\UsulanRenjaController@orderby','as'=>'usulanprarenjaopd.orderby']);    
    
    //workflow - Pembahasan Pra Renja OPD/SKPD [pembahasan]
    Route::resource('/workflow/rkpd/pembahasanprarenjaopd','RKPD\PembahasanRenjaController',['parameters'=>['pembahasanprarenjaopd'=>'uuid'],
                                                                                                        'only'=>['index','edit','update']]);
    Route::put('/workflow/rkpd/pembahasanprarenjaopd/update2/{uuid}',['uses'=>'RKPD\PembahasanRenjaController@update2','as'=>'pembahasanprarenjaopd.update2']);
    Route::get('/workflow/rkpd/pembahasanprarenjaopd/showrincian/{uuid}',['uses'=>'RKPD\PembahasanRenjaController@showrincian','as'=>'pembahasanprarenjaopd.showrincian']); 
    Route::post('/workflow/rkpd/pembahasanprarenjaopd/search',['uses'=>'RKPD\PembahasanRenjaController@search','as'=>'pembahasanprarenjaopd.search']);  
    Route::post('/workflow/rkpd/pembahasanprarenjaopd/filter',['uses'=>'RKPD\PembahasanRenjaController@filter','as'=>'pembahasanprarenjaopd.filter']);              
    Route::get('/workflow/rkpd/pembahasanprarenjaopd/paginate/{id}',['uses'=>'RKPD\PembahasanRenjaController@paginate','as'=>'pembahasanprarenjaopd.paginate']);              
    Route::post('/workflow/rkpd/pembahasanprarenjaopd/changenumberrecordperpage',['uses'=>'RKPD\PembahasanRenjaController@changenumberrecordperpage','as'=>'pembahasanprarenjaopd.changenumberrecordperpage']);  
    Route::post('/workflow/rkpd/pembahasanprarenjaopd/orderby',['uses'=>'RKPD\PembahasanRenjaController@orderby','as'=>'pembahasanprarenjaopd.orderby']);  
    Route::post('/workflow/rkpd/pembahasanprarenjaopd/transfer',['uses'=>'RKPD\PembahasanRenjaController@transfer','as'=>'pembahasanprarenjaopd.transfer']);
    
    //Workflow- Usulan Rakor Bidang [aspirasi]
    Route::resource('/workflow/rkpd/usulanrakorbidang','RKPD\UsulanRenjaController',['parameters'=>['usulanrakorbidang'=>'uuid']]);        
    Route::post('/workflow/rkpd/usulanrakorbidang/pilihusulankegiatan',['uses'=>'RKPD\UsulanRenjaController@pilihusulankegiatan','as'=>'usulanrakorbidang.pilihusulankegiatan']);                  
    Route::post('/workflow/rkpd/usulanrakorbidang/pilihindikatorkinerja',['uses'=>'RKPD\UsulanRenjaController@pilihindikatorkinerja','as'=>'usulanrakorbidang.pilihindikatorkinerja']);                  
    Route::get('/workflow/rkpd/usulanrakorbidang/create1/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create1','as'=>'usulanrakorbidang.create1']);              
    Route::get('/workflow/rkpd/usulanrakorbidang/create2/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create2','as'=>'usulanrakorbidang.create2']);              
    Route::get('/workflow/rkpd/usulanrakorbidang/create3/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create3','as'=>'usulanrakorbidang.create3']);              
    Route::get('/workflow/rkpd/usulanrakorbidang/create4/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create4','as'=>'usulanrakorbidang.create4']);              
    Route::post('/workflow/rkpd/usulanrakorbidang/store1',['uses'=>'RKPD\UsulanRenjaController@store1','as'=>'usulanrakorbidang.store1']);  
    Route::post('/workflow/rkpd/usulanrakorbidang/store2',['uses'=>'RKPD\UsulanRenjaController@store2','as'=>'usulanrakorbidang.store2']);  
    Route::post('/workflow/rkpd/usulanrakorbidang/store3',['uses'=>'RKPD\UsulanRenjaController@store3','as'=>'usulanrakorbidang.store3']); 
    Route::post('/workflow/rkpd/usulanrakorbidang/store4',['uses'=>'RKPD\UsulanRenjaController@store4','as'=>'usulanrakorbidang.store4']); 
    Route::get('/workflow/rkpd/usulanrakorbidang/{uuid}/edit1',['uses'=>'RKPD\UsulanRenjaController@edit1','as'=>'usulanrakorbidang.edit1']);              
    Route::get('/workflow/rkpd/usulanrakorbidang/{uuid}/edit2',['uses'=>'RKPD\UsulanRenjaController@edit2','as'=>'usulanrakorbidang.edit2']);              
    Route::get('/workflow/rkpd/usulanrakorbidang/{uuid}/edit3',['uses'=>'RKPD\UsulanRenjaController@edit3','as'=>'usulanrakorbidang.edit3']);              
    Route::get('/workflow/rkpd/usulanrakorbidang/{uuid}/edit4',['uses'=>'RKPD\UsulanRenjaController@edit4','as'=>'usulanrakorbidang.edit4']);              
    Route::put('/workflow/rkpd/usulanrakorbidang/update1/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update1','as'=>'usulanrakorbidang.update1']);  
    Route::put('/workflow/rkpd/usulanrakorbidang/update2/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update2','as'=>'usulanrakorbidang.update2']);  
    Route::put('/workflow/rkpd/usulanrakorbidang/update3/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update3','as'=>'usulanrakorbidang.update3']); 
    Route::put('/workflow/rkpd/usulanrakorbidang/update4/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update4','as'=>'usulanrakorbidang.update4']); 
    Route::get('/workflow/rkpd/usulanrakorbidang/showrincian/{uuid}',['uses'=>'RKPD\UsulanRenjaController@showrincian','as'=>'usulanrakorbidang.showrincian']); 
    Route::post('/workflow/rkpd/usulanrakorbidang/search',['uses'=>'RKPD\UsulanRenjaController@search','as'=>'usulanrakorbidang.search']);  
    Route::post('/workflow/rkpd/usulanrakorbidang/filter',['uses'=>'RKPD\UsulanRenjaController@filter','as'=>'usulanrakorbidang.filter']);                  
    Route::get('/workflow/rkpd/usulanrakorbidang/paginate/{id}',['uses'=>'RKPD\UsulanRenjaController@paginate','as'=>'usulanrakorbidang.paginate']);              
    Route::post('/workflow/rkpd/usulanrakorbidang/changenumberrecordperpage',['uses'=>'RKPD\UsulanRenjaController@changenumberrecordperpage','as'=>'usulanrakorbidang.changenumberrecordperpage']);  
    Route::post('/workflow/rkpd/usulanrakorbidang/orderby',['uses'=>'RKPD\UsulanRenjaController@orderby','as'=>'usulanrakorbidang.orderby']);
    
    //Workflow- Pembahasan Rakor Bidang OPD/SKPD [pembahasan]
    Route::resource('/workflow/rkpd/pembahasanrakorbidang','RKPD\PembahasanRenjaController',['parameters'=>['pembahasanrakorbidang'=>'uuid'],
                                                                                                              'only'=>['index','edit','update']]); 
    Route::put('/workflow/rkpd/pembahasanrakorbidang/update2/{uuid}',['uses'=>'RKPD\PembahasanRenjaController@update2','as'=>'pembahasanrakorbidang.update2']);
    Route::get('/workflow/rkpd/pembahasanrakorbidang/showrincian/{uuid}',['uses'=>'RKPD\PembahasanRenjaController@showrincian','as'=>'pembahasanrakorbidang.showrincian']); 
    Route::post('/workflow/rkpd/pembahasanrakorbidang/search',['uses'=>'RKPD\PembahasanRenjaController@search','as'=>'pembahasanrakorbidang.search']);  
    Route::post('/workflow/rkpd/pembahasanrakorbidang/filter',['uses'=>'RKPD\PembahasanRenjaController@filter','as'=>'pembahasanrakorbidang.filter']);              
    Route::get('/workflow/rkpd/pembahasanrakorbidang/paginate/{id}',['uses'=>'RKPD\PembahasanRenjaController@paginate','as'=>'pembahasanrakorbidang.paginate']);              
    Route::post('/workflow/rkpd/pembahasanrakorbidang/changenumberrecordperpage',['uses'=>'RKPD\PembahasanRenjaController@changenumberrecordperpage','as'=>'pembahasanrakorbidang.changenumberrecordperpage']);  
    Route::post('/workflow/rkpd/pembahasanrakorbidang/orderby',['uses'=>'RKPD\PembahasanRenjaController@orderby','as'=>'pembahasanrakorbidang.orderby']);  
    Route::post('/workflow/rkpd/pembahasanrakorbidang/transfer',['uses'=>'RKPD\PembahasanRenjaController@transfer','as'=>'pembahasanrakorbidang.transfer']);
    
    //Workflow- Usulan Forum OPD [aspirasi]
    Route::resource('/workflow/rkpd/usulanforumopd','RKPD\UsulanRenjaController',['parameters'=>['usulanforumopd'=>'uuid']]);        
    Route::post('/workflow/rkpd/usulanforumopd/pilihusulankegiatan',['uses'=>'RKPD\UsulanRenjaController@pilihusulankegiatan','as'=>'usulanforumopd.pilihusulankegiatan']);                  
    Route::post('/workflow/rkpd/usulanforumopd/pilihindikatorkinerja',['uses'=>'RKPD\UsulanRenjaController@pilihindikatorkinerja','as'=>'usulanforumopd.pilihindikatorkinerja']);                  
    Route::get('/workflow/rkpd/usulanforumopd/create1/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create1','as'=>'usulanforumopd.create1']);              
    Route::get('/workflow/rkpd/usulanforumopd/create2/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create2','as'=>'usulanforumopd.create2']);              
    Route::get('/workflow/rkpd/usulanforumopd/create3/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create3','as'=>'usulanforumopd.create3']);              
    Route::get('/workflow/rkpd/usulanforumopd/create4/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create4','as'=>'usulanforumopd.create4']);              
    Route::post('/workflow/rkpd/usulanforumopd/store1',['uses'=>'RKPD\UsulanRenjaController@store1','as'=>'usulanforumopd.store1']);  
    Route::post('/workflow/rkpd/usulanforumopd/store2',['uses'=>'RKPD\UsulanRenjaController@store2','as'=>'usulanforumopd.store2']);  
    Route::post('/workflow/rkpd/usulanforumopd/store3',['uses'=>'RKPD\UsulanRenjaController@store3','as'=>'usulanforumopd.store3']); 
    Route::post('/workflow/rkpd/usulanforumopd/store4',['uses'=>'RKPD\UsulanRenjaController@store4','as'=>'usulanforumopd.store4']); 
    Route::get('/workflow/rkpd/usulanforumopd/{uuid}/edit1',['uses'=>'RKPD\UsulanRenjaController@edit1','as'=>'usulanforumopd.edit1']);              
    Route::get('/workflow/rkpd/usulanforumopd/{uuid}/edit2',['uses'=>'RKPD\UsulanRenjaController@edit2','as'=>'usulanforumopd.edit2']);              
    Route::get('/workflow/rkpd/usulanforumopd/{uuid}/edit3',['uses'=>'RKPD\UsulanRenjaController@edit3','as'=>'usulanforumopd.edit3']);              
    Route::get('/workflow/rkpd/usulanforumopd/{uuid}/edit4',['uses'=>'RKPD\UsulanRenjaController@edit4','as'=>'usulanforumopd.edit4']);              
    Route::put('/workflow/rkpd/usulanforumopd/update1/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update1','as'=>'usulanforumopd.update1']);  
    Route::put('/workflow/rkpd/usulanforumopd/update2/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update2','as'=>'usulanforumopd.update2']);  
    Route::put('/workflow/rkpd/usulanforumopd/update3/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update3','as'=>'usulanforumopd.update3']); 
    Route::put('/workflow/rkpd/usulanforumopd/update4/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update4','as'=>'usulanforumopd.update4']); 
    Route::get('/workflow/rkpd/usulanforumopd/showrincian/{uuid}',['uses'=>'RKPD\UsulanRenjaController@showrincian','as'=>'usulanforumopd.showrincian']); 
    Route::post('/workflow/rkpd/usulanforumopd/search',['uses'=>'RKPD\UsulanRenjaController@search','as'=>'usulanforumopd.search']);  
    Route::post('/workflow/rkpd/usulanforumopd/filter',['uses'=>'RKPD\UsulanRenjaController@filter','as'=>'usulanforumopd.filter']);                  
    Route::get('/workflow/rkpd/usulanforumopd/paginate/{id}',['uses'=>'RKPD\UsulanRenjaController@paginate','as'=>'usulanforumopd.paginate']);              
    Route::post('/workflow/rkpd/usulanforumopd/changenumberrecordperpage',['uses'=>'RKPD\UsulanRenjaController@changenumberrecordperpage','as'=>'usulanforumopd.changenumberrecordperpage']);  
    Route::post('/workflow/rkpd/usulanforumopd/orderby',['uses'=>'RKPD\UsulanRenjaController@orderby','as'=>'usulanforumopd.orderby']);
    
    //Workflow- Pembahasan Forum OPD/SKPD [pembahasan]
    Route::resource('/workflow/rkpd/pembahasanforumopd','RKPD\PembahasanRenjaController',['parameters'=>['pembahasanforumopd'=>'uuid'],
                                                                                                              'only'=>['index','edit','update']]); 
    Route::put('/workflow/rkpd/pembahasanforumopd/update2/{uuid}',['uses'=>'RKPD\PembahasanRenjaController@update2','as'=>'pembahasanforumopd.update2']);
    Route::get('/workflow/rkpd/pembahasanforumopd/showrincian/{uuid}',['uses'=>'RKPD\PembahasanRenjaController@showrincian','as'=>'pembahasanforumopd.showrincian']); 
    Route::post('/workflow/rkpd/pembahasanforumopd/search',['uses'=>'RKPD\PembahasanRenjaController@search','as'=>'pembahasanforumopd.search']);  
    Route::post('/workflow/rkpd/pembahasanforumopd/filter',['uses'=>'RKPD\PembahasanRenjaController@filter','as'=>'pembahasanforumopd.filter']);              
    Route::get('/workflow/rkpd/pembahasanforumopd/paginate/{id}',['uses'=>'RKPD\PembahasanRenjaController@paginate','as'=>'pembahasanforumopd.paginate']);              
    Route::post('/workflow/rkpd/pembahasanforumopd/changenumberrecordperpage',['uses'=>'RKPD\PembahasanRenjaController@changenumberrecordperpage','as'=>'pembahasanforumopd.changenumberrecordperpage']);  
    Route::post('/workflow/rkpd/pembahasanforumopd/orderby',['uses'=>'RKPD\PembahasanRenjaController@orderby','as'=>'pembahasanforumopd.orderby']);  
    Route::post('/workflow/rkpd/pembahasanforumopd/transfer',['uses'=>'RKPD\PembahasanRenjaController@transfer','as'=>'pembahasanforumopd.transfer']);
    
    //Workflow- Usulan Musren Kabupaten [aspirasi]
    Route::resource('/workflow/musrenbang/usulanmusrenkab','RKPD\UsulanRenjaController',['parameters'=>['usulanmusrenkab'=>'uuid']]);        
    Route::post('/workflow/musrenbang/usulanmusrenkab/pilihusulankegiatan',['uses'=>'RKPD\UsulanRenjaController@pilihusulankegiatan','as'=>'usulanmusrenkab.pilihusulankegiatan']);                  
    Route::post('/workflow/musrenbang/usulanmusrenkab/pilihindikatorkinerja',['uses'=>'RKPD\UsulanRenjaController@pilihindikatorkinerja','as'=>'usulanmusrenkab.pilihindikatorkinerja']);                  
    Route::get('/workflow/musrenbang/usulanmusrenkab/create1/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create1','as'=>'usulanmusrenkab.create1']);              
    Route::get('/workflow/musrenbang/usulanmusrenkab/create2/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create2','as'=>'usulanmusrenkab.create2']);              
    Route::get('/workflow/musrenbang/usulanmusrenkab/create3/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create3','as'=>'usulanmusrenkab.create3']);              
    Route::get('/workflow/musrenbang/usulanmusrenkab/create4/{uuid}',['uses'=>'RKPD\UsulanRenjaController@create4','as'=>'usulanmusrenkab.create4']);              
    Route::post('/workflow/musrenbang/usulanmusrenkab/store1',['uses'=>'RKPD\UsulanRenjaController@store1','as'=>'usulanmusrenkab.store1']);  
    Route::post('/workflow/musrenbang/usulanmusrenkab/store2',['uses'=>'RKPD\UsulanRenjaController@store2','as'=>'usulanmusrenkab.store2']);  
    Route::post('/workflow/musrenbang/usulanmusrenkab/store3',['uses'=>'RKPD\UsulanRenjaController@store3','as'=>'usulanmusrenkab.store3']); 
    Route::post('/workflow/musrenbang/usulanmusrenkab/store4',['uses'=>'RKPD\UsulanRenjaController@store4','as'=>'usulanmusrenkab.store4']); 
    Route::get('/workflow/musrenbang/usulanmusrenkab/{uuid}/edit1',['uses'=>'RKPD\UsulanRenjaController@edit1','as'=>'usulanmusrenkab.edit1']);              
    Route::get('/workflow/musrenbang/usulanmusrenkab/{uuid}/edit2',['uses'=>'RKPD\UsulanRenjaController@edit2','as'=>'usulanmusrenkab.edit2']);              
    Route::get('/workflow/musrenbang/usulanmusrenkab/{uuid}/edit3',['uses'=>'RKPD\UsulanRenjaController@edit3','as'=>'usulanmusrenkab.edit3']);              
    Route::get('/workflow/musrenbang/usulanmusrenkab/{uuid}/edit4',['uses'=>'RKPD\UsulanRenjaController@edit4','as'=>'usulanmusrenkab.edit4']);              
    Route::put('/workflow/musrenbang/usulanmusrenkab/update1/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update1','as'=>'usulanmusrenkab.update1']);  
    Route::put('/workflow/musrenbang/usulanmusrenkab/update2/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update2','as'=>'usulanmusrenkab.update2']);  
    Route::put('/workflow/musrenbang/usulanmusrenkab/update3/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update3','as'=>'usulanmusrenkab.update3']); 
    Route::put('/workflow/musrenbang/usulanmusrenkab/update4/{uuid}',['uses'=>'RKPD\UsulanRenjaController@update4','as'=>'usulanmusrenkab.update4']); 
    Route::get('/workflow/rkpd/usulanmusrenkab/showrincian/{uuid}',['uses'=>'RKPD\UsulanRenjaController@showrincian','as'=>'usulanmusrenkab.showrincian']); 
    Route::post('/workflow/musrenbang/usulanmusrenkab/search',['uses'=>'RKPD\UsulanRenjaController@search','as'=>'usulanmusrenkab.search']);  
    Route::post('/workflow/musrenbang/usulanmusrenkab/filter',['uses'=>'RKPD\UsulanRenjaController@filter','as'=>'usulanmusrenkab.filter']);                  
    Route::get('/workflow/musrenbang/usulanmusrenkab/paginate/{id}',['uses'=>'RKPD\UsulanRenjaController@paginate','as'=>'usulanmusrenkab.paginate']);              
    Route::post('/workflow/musrenbang/usulanmusrenkab/changenumberrecordperpage',['uses'=>'RKPD\UsulanRenjaController@changenumberrecordperpage','as'=>'usulanmusrenkab.changenumberrecordperpage']);  
    Route::post('/workflow/musrenbang/usulanmusrenkab/orderby',['uses'=>'RKPD\UsulanRenjaController@orderby','as'=>'usulanmusrenkab.orderby']);
    
    //Workflow- Pembahasan Musrenbang Kabupaten [pembahasan]
    Route::resource('/workflow/musrenbang/pembahasanmusrenkab','RKPD\PembahasanRenjaController',['parameters'=>['pembahasanmusrenkab'=>'uuid'],
                                                                                                              'only'=>['index','edit','update']]); 
    Route::put('/workflow/musrenbang/pembahasanmusrenkab/update2/{uuid}',['uses'=>'RKPD\PembahasanRenjaController@update2','as'=>'pembahasanmusrenkab.update2']);  
    Route::get('/workflow/rkpd/pembahasanmusrenkab/showrincian/{uuid}',['uses'=>'RKPD\PembahasanRenjaController@showrincian','as'=>'pembahasanmusrenkab.showrincian']); 
    Route::post('/workflow/musrenbang/pembahasanmusrenkab/search',['uses'=>'RKPD\PembahasanRenjaController@search','as'=>'pembahasanmusrenkab.search']);  
    Route::post('/workflow/musrenbang/pembahasanmusrenkab/filter',['uses'=>'RKPD\PembahasanRenjaController@filter','as'=>'pembahasanmusrenkab.filter']);              
    Route::get('/workflow/musrenbang/pembahasanmusrenkab/paginate/{id}',['uses'=>'RKPD\PembahasanRenjaController@paginate','as'=>'pembahasanmusrenkab.paginate']);              
    Route::post('/workflow/musrenbang/pembahasanmusrenkab/changenumberrecordperpage',['uses'=>'RKPD\PembahasanRenjaController@changenumberrecordperpage','as'=>'pembahasanmusrenkab.changenumberrecordperpage']);  
    Route::post('/workflow/musrenbang/pembahasanmusrenkab/orderby',['uses'=>'RKPD\PembahasanRenjaController@orderby','as'=>'pembahasanmusrenkab.orderby']);  
    Route::post('/workflow/musrenbang/pembahasanmusrenkab/transfer',['uses'=>'RKPD\PembahasanRenjaController@transfer','as'=>'pembahasanmusrenkab.transfer']);
    
    //Workflow- verifikasi renja [pembahasan tapd]
    Route::resource('/workflow/rkpd/verifikasirenja','RKPD\PembahasanRenjaController',['parameters'=>['verifikasirenja'=>'uuid'],
                                                                                                              'only'=>['index','edit','update']]); 
    Route::put('/workflow/rkpd/verifikasirenja/update2/{uuid}',['uses'=>'RKPD\PembahasanRenjaController@update2','as'=>'verifikasirenja.update2']);  
    Route::get('/workflow/rkpd/verifikasirenja/showrincian/{uuid}',['uses'=>'RKPD\PembahasanRenjaController@showrincian','as'=>'verifikasirenja.showrincian']); 
    Route::post('/workflow/rkpd/verifikasirenja/search',['uses'=>'RKPD\PembahasanRenjaController@search','as'=>'verifikasirenja.search']);  
    Route::post('/workflow/rkpd/verifikasirenja/filter',['uses'=>'RKPD\PembahasanRenjaController@filter','as'=>'verifikasirenja.filter']);              
    Route::get('/workflow/rkpd/verifikasirenja/paginate/{id}',['uses'=>'RKPD\PembahasanRenjaController@paginate','as'=>'verifikasirenja.paginate']);              
    Route::post('/workflow/rkpd/verifikasirenja/changenumberrecordperpage',['uses'=>'RKPD\PembahasanRenjaController@changenumberrecordperpage','as'=>'verifikasirenja.changenumberrecordperpage']);  
    Route::post('/workflow/rkpd/verifikasirenja/orderby',['uses'=>'RKPD\PembahasanRenjaController@orderby','as'=>'verifikasirenja.orderby']);  
    Route::post('/workflow/rkpd/verifikasirenja/transfer',['uses'=>'RKPD\PembahasanRenjaController@transfer','as'=>'verifikasirenja.transfer']);
    
    //Workflow- RKPD Murni
    Route::resource('/workflow/rkpd/rkpdmurni','RKPD\RKPDMurniController',['parameters'=>['rkpdmurni'=>'uuid'],
                                                                    'only'=>['index','show','edit','update']]);     
    Route::post('/workflow/rkpd/rkpdmurni/search',['uses'=>'RKPD\RKPDMurniController@search','as'=>'rkpdmurni.search']);  
    Route::post('/workflow/rkpd/rkpdmurni/filter',['uses'=>'RKPD\RKPDMurniController@filter','as'=>'rkpdmurni.filter']);              
    Route::get('/workflow/rkpd/rkpdmurni/paginate/{id}',['uses'=>'RKPD\RKPDMurniController@paginate','as'=>'rkpdmurni.paginate']);              
    Route::post('/workflow/rkpd/rkpdmurni/changenumberrecordperpage',['uses'=>'RKPD\RKPDMurniController@changenumberrecordperpage','as'=>'rkpdmurni.changenumberrecordperpage']);  
    Route::post('/workflow/rkpd/rkpdmurni/orderby',['uses'=>'RKPD\RKPDMurniController@orderby','as'=>'rkpdmurni.orderby']);  
    Route::put('/workflow/rkpd/rkpdmurni/transfer/{uuid}',['uses'=>'RKPD\RKPDMurniController@transfer','as'=>'rkpdmurni.transfer']);
    Route::get('/workflow/rkpd/rkpdmurni/printtoexcel',['uses'=>'RKPD\RKPDMurniController@printtoexcel','as'=>'rkpdmurni.printtoexcel']);
    Route::get('/workflow/rkpd/rkpdmurni/printtopdf',['uses'=>'RKPD\RKPDMurniController@printtopdf','as'=>'rkpdmurni.printtopdf']);

    //Workflow- RKPD Perubahan
    Route::resource('/workflow/rkpd/rkpdperubahan','RKPD\RKPDPerubahanController',['parameters'=>['rkpdperubahan'=>'uuid'],
                                                                                    'only'=>['index','show','edit','update']]);     
    Route::post('/workflow/rkpd/rkpdperubahan/search',['uses'=>'RKPD\RKPDPerubahanController@search','as'=>'rkpdperubahan.search']);  
    Route::post('/workflow/rkpd/rkpdperubahan/filter',['uses'=>'RKPD\RKPDPerubahanController@filter','as'=>'rkpdperubahan.filter']);              
    Route::get('/workflow/rkpd/rkpdperubahan/paginate/{id}',['uses'=>'RKPD\RKPDPerubahanController@paginate','as'=>'rkpdperubahan.paginate']);              
    Route::post('/workflow/rkpd/rkpdperubahan/changenumberrecordperpage',['uses'=>'RKPD\RKPDPerubahanController@changenumberrecordperpage','as'=>'rkpdperubahan.changenumberrecordperpage']);  
    Route::post('/workflow/rkpd/rkpdperubahan/orderby',['uses'=>'RKPD\RKPDPerubahanController@orderby','as'=>'rkpdperubahan.orderby']);  
    Route::put('/workflow/rkpd/rkpdperubahan/transfer/{uuid}',['uses'=>'RKPD\RKPDPerubahanController@transfer','as'=>'rkpdperubahan.transfer']);
    Route::get('/workflow/rkpd/rkpdperubahan/printtoexcel',['uses'=>'RKPD\RKPDPerubahanController@printtoexcel','as'=>'rkpdperubahan.printtoexcel']);
    Route::get('/workflow/rkpd/rkpdperubahan/printtopdf',['uses'=>'RKPD\RKPDPerubahanController@printtopdf','as'=>'rkpdperubahan.printtopdf']);

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
    Route::resource('/setting/users','Setting\UsersController',['parameters'=>['users'=>'id']])->middleware(['auth','role:superadmin']);           
    Route::get('/setting/users/paginatecreate/{id}',['uses'=>'Setting\UsersController@paginate','as'=>'users.paginate'])->middleware(['auth','role:superadmin']);    
    Route::get('/setting/users/profil/{id}',['uses'=>'Setting\UsersController@profil','as'=>'users.profil']);    
    Route::put('/setting/users/updateprofil/{id}',['uses'=>'Setting\UsersController@updateprofil','as'=>'users.updateprofil']);        
    Route::post('/setting/users/changenumberrecordperpage',['uses'=>'Setting\UsersController@changenumberrecordperpage','as'=>'users.changenumberrecordperpage'])->middleware(['auth','role:superadmin']);  
    Route::post('/setting/users/orderby',['uses'=>'Setting\UsersController@orderby','as'=>'users.orderby'])->middleware(['auth','role:superadmin']); 
    Route::post('/setting/users/search',['uses'=>'Setting\UsersController@search','as'=>'users.search'])->middleware(['auth','role:superadmin']);    
    Route::post('/setting/users/filter',['uses'=>'Setting\UsersController@filter','as'=>'users.filter'])->middleware(['auth','role:superadmin']); 
    
    //setting - users bapelitbang
    Route::resource('/setting/usersbapelitbang','Setting\UsersBapelitbangController',['parameters'=>['usersbapelitbang'=>'id']]);           
    Route::get('/setting/usersbapelitbang/paginatecreate/{id}',['uses'=>'Setting\UsersBapelitbangController@paginate','as'=>'usersbapelitbang.paginate']);        
    Route::post('/setting/usersbapelitbang/changenumberrecordperpage',['uses'=>'Setting\UsersBapelitbangController@changenumberrecordperpage','as'=>'usersbapelitbang.changenumberrecordperpage']);  
    Route::post('/setting/usersbapelitbang/orderby',['uses'=>'Setting\UsersBapelitbangController@orderby','as'=>'usersbapelitbang.orderby']); 
    Route::post('/setting/usersbapelitbang/search',['uses'=>'Setting\UsersBapelitbangController@search','as'=>'usersbapelitbang.search']);    
    Route::post('/setting/usersbapelitbang/filter',['uses'=>'Setting\UsersBapelitbangController@filter','as'=>'usersbapelitbang.filter']);

    //setting - users OPD
    Route::resource('/setting/usersopd','Setting\UsersOPDController',['parameters'=>['usersopd'=>'id']]);           
    Route::get('/setting/usersopd/paginate/{id}',['uses'=>'Setting\UsersOPDController@paginate','as'=>'usersopd.paginate']);
    Route::post('/setting/usersopd/changenumberrecordperpage',['uses'=>'Setting\UsersOPDController@changenumberrecordperpage','as'=>'usersopd.changenumberrecordperpage']);  
    Route::post('/setting/usersopd/orderby',['uses'=>'Setting\UsersOPDController@orderby','as'=>'usersopd.orderby']); 
    Route::post('/setting/usersopd/search',['uses'=>'Setting\UsersOPDController@search','as'=>'usersopd.search']);    
    Route::post('/setting/usersopd/filter',['uses'=>'Setting\UsersOPDController@filter','as'=>'usersopd.filter']);    
    Route::post('/setting/usersopd/storeuserpermission', ['uses'=>'Setting\UsersOPDController@storeuserpermission','as'=>'usersopd.storeuserpermission']);

    //setting - users Desa
    Route::resource('/setting/usersdesa','Setting\UsersDesaController',['parameters'=>['usersdesa'=>'id']]);           
    Route::get('/setting/usersdesa/paginate/{id}',['uses'=>'Setting\UsersDesaController@paginate','as'=>'usersdesa.paginate']);
    Route::post('/setting/usersdesa/changenumberrecordperpage',['uses'=>'Setting\UsersDesaController@changenumberrecordperpage','as'=>'usersdesa.changenumberrecordperpage']);  
    Route::post('/setting/usersdesa/orderby',['uses'=>'Setting\UsersDesaController@orderby','as'=>'usersdesa.orderby']); 
    Route::post('/setting/usersdesa/search',['uses'=>'Setting\UsersDesaController@search','as'=>'usersdesa.search']);    
    Route::post('/setting/usersdesa/filter',['uses'=>'Setting\UsersDesaController@filter','as'=>'usersdesa.filter']);    
    Route::post('/setting/usersdesa/storeuserpermission', ['uses'=>'Setting\UsersDesaController@storeuserpermission','as'=>'usersdesa.storeuserpermission']);
    
    //setting - data eplanning [copydata]
    Route::get('/setting/dataeplanning/copydata', ['uses'=>'Setting\CopyDataController@index','as'=>'copydata.index']);

    //setting - cache [clear]
    Route::get('/setting/cache/clear', ['uses'=>'Setting\ClearCacheController@index','as'=>'clearcache.index']);
    
    //setting - konfigurasi [environment]    
    Route::resource('/setting/konfigurasi/environment','Setting\EnvironmentController',['parameters'=>['environment'=>'id'],
                                                                                        'only'=>['index','store']
                                                                                    ]);           

    //setting - histori renja    
    Route::get('/setting/historirenja/onlypagu/{uuid}',['uses'=>'Setting\HistoriRenjaController@onlypagu','as'=>'historirenja.onlypagu']);          

    Route::get('/logs', ['uses'=>'Setting\LogViewerController@index','as'=>'logviewer.index']);
});