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
    Route::get('/dashboard/rekappaguindikatifopd/printtoexcel',['uses'=>'Report\RekapPaguIndikatifOPDController@printtoexcel','as'=>'rekappaguindikatifopd.printtoexcel']); 
    
    //RAPAT PEMBAHASAN TAPD
    Route::resource('/rapat','RapatController',['parameters'=>['rapat'=>'uuid']]); 
    Route::post('/rapat/search',['uses'=>'RapatController@search','as'=>'rapat.search']); 
    Route::post('/rapat/filter',['uses'=>'RapatController@filter','as'=>'rapat.filter']);                  
    Route::get('/rapat/paginate/{id}',['uses'=>'RapatController@paginate','as'=>'rapat.paginate']);              
    Route::post('/rapat/changenumberrecordperpage',['uses'=>'RapatController@changenumberrecordperpage','as'=>'rapat.changenumberrecordperpage']);  
    Route::post('/rapat/orderby',['uses'=>'RapatController@orderby','as'=>'rapat.orderby']);
    Route::get('/rapat/printtoword/{uuid}',['uses'=>'RapatController@printtoword','as'=>'rapat.printtoword']);

    //masters - provinsi [lokasi]
    Route::resource('/dmaster/provinsi','DMaster\ProvinsiController',['parameters'=>['provinsi'=>'uuid']]); 
    Route::post('/dmaster/provinsi/search',['uses'=>'DMaster\ProvinsiController@search','as'=>'provinsi.search']);          
    Route::get('/dmaster/provinsi/paginate/{id}',['uses'=>'DMaster\ProvinsiController@paginate','as'=>'provinsi.paginate']);              
    Route::post('/dmaster/provinsi/changenumberrecordperpage',['uses'=>'DMaster\ProvinsiController@changenumberrecordperpage','as'=>'provinsi.changenumberrecordperpage']);  
    Route::post('/dmaster/provinsi/orderby',['uses'=>'DMaster\ProvinsiController@orderby','as'=>'provinsi.orderby']); 
    
    //masters - kota [lokasi]
    Route::resource('/dmaster/kota','DMaster\KotaController',['parameters'=>['kota'=>'uuid']]); 
    Route::post('/dmaster/kota/search',['uses'=>'DMaster\KotaController@search','as'=>'kota.search']);          
    Route::get('/dmaster/kota/paginate/{id}',['uses'=>'DMaster\KotaController@paginate','as'=>'kota.paginate']);              
    Route::post('/dmaster/kota/changenumberrecordperpage',['uses'=>'DMaster\KotaController@changenumberrecordperpage','as'=>'kota.changenumberrecordperpage']);  
    Route::post('/dmaster/kota/orderby',['uses'=>'DMaster\KotaController@orderby','as'=>'kota.orderby']); 

    //masters - kecamatan [lokasi]
    Route::resource('/dmaster/kecamatan','DMaster\KecamatanController',['parameters'=>['kecamatan'=>'uuid']]); 
    Route::post('/dmaster/kecamatan/search',['uses'=>'DMaster\KecamatanController@search','as'=>'kecamatan.search']);          
    Route::get('/dmaster/kecamatan/paginate/{id}',['uses'=>'DMaster\KecamatanController@paginate','as'=>'kecamatan.paginate']);              
    Route::post('/dmaster/kecamatan/changenumberrecordperpage',['uses'=>'DMaster\KecamatanController@changenumberrecordperpage','as'=>'kecamatan.changenumberrecordperpage']);  
    Route::post('/dmaster/kecamatan/orderby',['uses'=>'DMaster\KecamatanController@orderby','as'=>'kecamatan.orderby']);  
    
    //masters - desa [lokasi]
    Route::resource('/dmaster/desa','DMaster\DesaController',['parameters'=>['desa'=>'uuid']]); 
    Route::post('/dmaster/desa/search',['uses'=>'DMaster\DesaController@search','as'=>'desa.search']);          
    Route::get('/dmaster/desa/paginate/{id}',['uses'=>'DMaster\DesaController@paginate','as'=>'desa.paginate']);              
    Route::post('/dmaster/desa/changenumberrecordperpage',['uses'=>'DMaster\DesaController@changenumberrecordperpage','as'=>'desa.changenumberrecordperpage']);  
    Route::post('/dmaster/desa/orderby',['uses'=>'DMaster\DesaController@orderby','as'=>'desa.orderby']);  

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
    Route::post('/dmaster/program/reorderkegiatan',['uses'=>'DMaster\ProgramController@reorderkegiatan','as'=>'program.reorderkegiatan']);  
    
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
    
    //masters - sumber dana [aneka data]
    Route::resource('/dmaster/sumberdana','DMaster\SumberDanaController',['parameters'=>['sumberdana'=>'uuid']]); 
    Route::post('/dmaster/sumberdana/search',['uses'=>'DMaster\SumberDanaController@search','as'=>'sumberdana.search']);          
    Route::get('/dmaster/sumberdana/paginate/{id}',['uses'=>'DMaster\SumberDanaController@paginate','as'=>'sumberdana.paginate']);              
    Route::post('/dmaster/sumberdana/changenumberrecordperpage',['uses'=>'DMaster\SumberDanaController@changenumberrecordperpage','as'=>'sumberdana.changenumberrecordperpage']);  
    Route::post('/dmaster/sumberdana/orderby',['uses'=>'DMaster\SumberDanaController@orderby','as'=>'sumberdana.orderby']); 

    //masters - pagu anggaran OPD/SKPD [aneka data] 
    Route::resource('/dmaster/paguanggaranopd','DMaster\PaguAnggaranOPDController',['parameters'=>['paguanggaranopd'=>'uuid']]); 
    Route::post('/dmaster/paguanggaranopd/search',['uses'=>'DMaster\PaguAnggaranOPDController@search','as'=>'paguanggaranopd.search']);  
    Route::get('/dmaster/paguanggaranopd/paginate/{id}',['uses'=>'DMaster\PaguAnggaranOPDController@paginate','as'=>'paguanggaranopd.paginate']);              
    Route::post('/dmaster/paguanggaranopd/changenumberrecordperpage',['uses'=>'DMaster\PaguAnggaranOPDController@changenumberrecordperpage','as'=>'paguanggaranopd.changenumberrecordperpage']);  
    Route::post('/dmaster/paguanggaranopd/orderby',['uses'=>'DMaster\PaguAnggaranOPDController@orderby','as'=>'paguanggaranopd.orderby']); 
    Route::get('/dmaster/paguanggaranopd/getkodekelompokurusan/{uuid}',['uses'=>'DMaster\PaguAnggaranOPDController@getkodekelompokurusan','as'=>'paguanggaranopd.getkodekelompokurusan']); 
    
    //masters - pagu anggaran anggota dewan [aneka data] 
    Route::resource('/dmaster/paguanggarandewan','DMaster\PaguAnggaranDewanController',['parameters'=>['paguanggarandewan'=>'uuid']]); 
    Route::post('/dmaster/paguanggarandewan/search',['uses'=>'DMaster\PaguAnggaranDewanController@search','as'=>'paguanggarandewan.search']);  
    Route::get('/dmaster/paguanggarandewan/paginate/{id}',['uses'=>'DMaster\PaguAnggaranDewanController@paginate','as'=>'paguanggarandewan.paginate']);              
    Route::post('/dmaster/paguanggarandewan/changenumberrecordperpage',['uses'=>'DMaster\PaguAnggaranDewanController@changenumberrecordperpage','as'=>'paguanggarandewan.changenumberrecordperpage']);  
    Route::post('/dmaster/paguanggarandewan/orderby',['uses'=>'DMaster\PaguAnggaranDewanController@orderby','as'=>'paguanggarandewan.orderby']); 
    Route::get('/dmaster/paguanggarandewan/getkodekelompokurusan/{uuid}',['uses'=>'DMaster\PaguAnggaranDewanController@getkodekelompokurusan','as'=>'paguanggarandewan.getkodekelompokurusan']); 
    
    //RPJMD - Visi
    Route::resource('/perencanaan/rpjmd/rpjmdvisi','RPJMD\RPJMDVisiController',['parameters'=>['rpjmdvisi'=>'uuid']]); 
    Route::post('/perencanaan/rpjmd/rpjmdvisi/search',['uses'=>'RPJMD\RPJMDVisiController@search','as'=>'rpjmdvisi.search']); 
    Route::get('/perencanaan/rpjmd/rpjmdvisi/paginate/{id}',['uses'=>'RPJMD\RPJMDVisiController@paginate','as'=>'rpjmdvisi.paginate']);              
    Route::post('/perencanaan/rpjmd/rpjmdvisi/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDVisiController@changenumberrecordperpage','as'=>'rpjmdvisi.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdvisi/orderby',['uses'=>'RPJMD\RPJMDVisiController@orderby','as'=>'rpjmdvisi.orderby']); 

    //RPJMD - Misi
    Route::resource('/perencanaan/rpjmd/rpjmdmisi','RPJMD\RPJMDMisiController',['parameters'=>['rpjmdmisi'=>'uuid']]); 
    Route::get('/perencanaan/rpjmd/rpjmdmisi/getkodemisi/{uuid}',['uses'=>'RPJMD\RPJMDMisiController@getkodemisi','as'=>'rpjmdmisi.getkodemisi']);              
    Route::post('/perencanaan/rpjmd/rpjmdmisi/search',['uses'=>'RPJMD\RPJMDMisiController@search','as'=>'rpjmdmisi.search']); 
    Route::get('/perencanaan/rpjmd/rpjmdmisi/paginate/{id}',['uses'=>'RPJMD\RPJMDMisiController@paginate','as'=>'rpjmdmisi.paginate']);              
    Route::post('/perencanaan/rpjmd/rpjmdmisi/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDMisiController@changenumberrecordperpage','as'=>'rpjmdmisi.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdmisi/orderby',['uses'=>'RPJMD\RPJMDMisiController@orderby','as'=>'rpjmdmisi.orderby']); 

    //RPJMD - Tujuan. getdaftartujuanrpjmd yang jadi uuid-nya dari rpjmdmisi
    Route::resource('/perencanaan/rpjmd/rpjmdtujuan','RPJMD\RPJMDTujuanController',['parameters'=>['rpjmdtujuan'=>'uuid']]); 
    Route::post('/perencanaan/rpjmd/rpjmdtujuan/store1',['uses'=>'RPJMD\RPJMDTujuanController@store1','as'=>'rpjmdtujuan.store1']);      
    Route::get('/perencanaan/rpjmd/rpjmdtujuan/getdaftartujuanrpjmd/{uuid}',['uses'=>'RPJMD\RPJMDTujuanController@getdaftartujuanrpjmd','as'=>'rpjmdtujuan.getdaftartujuanrpjmd']);              
    Route::get('/perencanaan/rpjmd/rpjmdtujuan/getkodetujuan/{uuid}',['uses'=>'RPJMD\RPJMDTujuanController@getkodetujuan','as'=>'rpjmdtujuan.getkodetujuan']);              
    Route::get('/perencanaan/rpjmd/rpjmdtujuan/edit1/{uuid}',['uses'=>'RPJMD\RPJMDTujuanController@edit1','as'=>'rpjmdtujuan.edit1']);  
    Route::put('/perencanaan/rpjmd/rpjmdtujuan/update1/{uuid}',['uses'=>'RPJMD\RPJMDTujuanController@update1','as'=>'rpjmdtujuan.update1']);  
    Route::post('/perencanaan/rpjmd/rpjmdtujuan/search',['uses'=>'RPJMD\RPJMDTujuanController@search','as'=>'rpjmdtujuan.search']); 
    Route::get('/perencanaan/rpjmd/rpjmdtujuan/paginate/{id}',['uses'=>'RPJMD\RPJMDTujuanController@paginate','as'=>'rpjmdtujuan.paginate']);                  
    Route::post('/perencanaan/rpjmd/rpjmdtujuan/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDTujuanController@changenumberrecordperpage','as'=>'rpjmdtujuan.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdtujuan/orderby',['uses'=>'RPJMD\RPJMDTujuanController@orderby','as'=>'rpjmdtujuan.orderby']); 
    
    //RPJMD - Sasaran getdaftarsasaranrpjmd yang jadi uuid-nya dari rpjmdtujuan
    Route::resource('/perencanaan/rpjmd/rpjmdsasaran','RPJMD\RPJMDSasaranController',['parameters'=>['rpjmdsasaran'=>'uuid']]); 
    Route::post('/perencanaan/rpjmd/rpjmdsasaran/store1',['uses'=>'RPJMD\RPJMDSasaranController@store1','as'=>'rpjmdsasaran.store1']);
    Route::get('/perencanaan/rpjmd/rpjmdsasaran/getdaftarsasaranrpjmd/{uuid}',['uses'=>'RPJMD\RPJMDSasaranController@getdaftarsasaranrpjmd','as'=>'rpjmdsasaran.getdaftarsasaranrpjmd']);                
    Route::get('/perencanaan/rpjmd/rpjmdsasaran/getkodesasaran/{uuid}',['uses'=>'RPJMD\RPJMDSasaranController@getkodesasaran','as'=>'rpjmdsasaran.getkodesasaran']);              
    Route::get('/perencanaan/rpjmd/rpjmdsasaran/edit1/{uuid}',['uses'=>'RPJMD\RPJMDSasaranController@edit1','as'=>'rpjmdsasaran.edit1']);  
    Route::put('/perencanaan/rpjmd/rpjmdsasaran/update1/{uuid}',['uses'=>'RPJMD\RPJMDSasaranController@update1','as'=>'rpjmdsasaran.update1']);  
    Route::post('/perencanaan/rpjmd/rpjmdsasaran/search',['uses'=>'RPJMD\RPJMDSasaranController@search','as'=>'rpjmdsasaran.search']); 
    Route::get('/perencanaan/rpjmd/rpjmdsasaran/paginate/{id}',['uses'=>'RPJMD\RPJMDSasaranController@paginate','as'=>'rpjmdsasaran.paginate']);              
    Route::post('/perencanaan/rpjmd/rpjmdsasaran/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDSasaranController@changenumberrecordperpage','as'=>'rpjmdsasaran.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdsasaran/orderby',['uses'=>'RPJMD\RPJMDSasaranController@orderby','as'=>'rpjmdsasaran.orderby']); 

    //RPJMD - Strategi
    Route::resource('/perencanaan/rpjmd/rpjmdstrategi','RPJMD\RPJMDStrategiController',['parameters'=>['rpjmdstrategi'=>'uuid']]); 
    Route::post('/perencanaan/rpjmd/rpjmdstrategi/search',['uses'=>'RPJMD\RPJMDStrategiController@search','as'=>'rpjmdstrategi.search']); 
    Route::get('/perencanaan/rpjmd/rpjmdstrategi/getkodestrategi/{uuid}',['uses'=>'RPJMD\RPJMDStrategiController@getkodestrategi','as'=>'rpjmdstrategi.getkodestrategi']);              
    Route::get('/perencanaan/rpjmd/rpjmdstrategi/paginate/{id}',['uses'=>'RPJMD\RPJMDStrategiController@paginate','as'=>'rpjmdstrategi.paginate']);              
    Route::post('/perencanaan/rpjmd/rpjmdstrategi/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDStrategiController@changenumberrecordperpage','as'=>'rpjmdstrategi.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdstrategi/orderby',['uses'=>'RPJMD\RPJMDStrategiController@orderby','as'=>'rpjmdstrategi.orderby']); 

    //RPJMD - Kebijakan
    Route::resource('/perencanaan/rpjmd/rpjmdkebijakan','RPJMD\RPJMDKebijakanController',['parameters'=>['rpjmdkebijakan'=>'uuid']]); 
    Route::post('/perencanaan/rpjmd/rpjmdkebijakan/store1',['uses'=>'RPJMD\RPJMDKebijakanController@store1','as'=>'rpjmdkebijakan.store1']);  
    Route::get('/perencanaan/rpjmd/rpjmdkebijakan/getkodekebijakan/{uuid}',['uses'=>'RPJMD\RPJMDKebijakanController@getkodekebijakan','as'=>'rpjmdkebijakan.getkodekebijakan']);              
    Route::post('/perencanaan/rpjmd/rpjmdkebijakan/filter',['uses'=>'RPJMD\RPJMDKebijakanController@filter','as'=>'rpjmdkebijakan.filter']);              
    Route::post('/perencanaan/rpjmd/rpjmdkebijakan/search',['uses'=>'RPJMD\RPJMDKebijakanController@search','as'=>'rpjmdkebijakan.search']); 
    Route::get('/perencanaan/rpjmd/rpjmdkebijakan/paginate/{id}',['uses'=>'RPJMD\RPJMDKebijakanController@paginate','as'=>'rpjmdkebijakan.paginate']);              
    Route::post('/perencanaan/rpjmd/rpjmdkebijakan/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDKebijakanController@changenumberrecordperpage','as'=>'rpjmdkebijakan.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdkebijakan/orderby',['uses'=>'RPJMD\RPJMDKebijakanController@orderby','as'=>'rpjmdkebijakan.orderby']); 
    
    //RPJMD - Program Pembangunan Daerah
    Route::resource('/perencanaan/rpjmd/rpjmdprogrampembangunan','RPJMD\RPJMDProgramPembangunanController',['parameters'=>['rpjmdprogrampembangunan'=>'uuid']]); 
    Route::post('/perencanaan/rpjmd/rpjmdprogrampembangunan/search',['uses'=>'RPJMD\RPJMDProgramPembangunanController@search','as'=>'rpjmdprogrampembangunan.search']); 
    Route::post('/perencanaan/rpjmd/rpjmdprogrampembangunan/filter',['uses'=>'RPJMD\RPJMDProgramPembangunanController@filter','as'=>'rpjmdprogrampembangunan.filter']);                  
    Route::get('/perencanaan/rpjmd/rpjmdprogrampembangunan/paginate/{id}',['uses'=>'RPJMD\RPJMDProgramPembangunanController@paginate','as'=>'rpjmdprogrampembangunan.paginate']);              
    Route::post('/perencanaan/rpjmd/rpjmdprogrampembangunan/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDProgramPembangunanController@changenumberrecordperpage','as'=>'rpjmdprogrampembangunan.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdprogrampembangunan/orderby',['uses'=>'RPJMD\RPJMDProgramPembangunanController@orderby','as'=>'rpjmdprogrampembangunan.orderby']);

    //RPJMD - Indikator Rencana Program Prioritas atau Indikator Kinerja
    Route::resource('/perencanaan/rpjmd/rpjmdindikatorkinerja','RPJMD\RPJMDIndikatorKinerjaController',['parameters'=>['rpjmdindikatorkinerja'=>'uuid']]); 
    Route::post('/perencanaan/rpjmd/rpjmdindikatorkinerja/search',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@search','as'=>'rpjmdindikatorkinerja.search']); 
    Route::post('/perencanaan/rpjmd/rpjmdindikatorkinerja/filter',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@filter','as'=>'rpjmdindikatorkinerja.filter']);                  
    Route::get('/perencanaan/rpjmd/rpjmdindikatorkinerja/paginate/{id}',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@paginate','as'=>'rpjmdindikatorkinerja.paginate']);              
    Route::post('/perencanaan/rpjmd/rpjmdindikatorkinerja/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@changenumberrecordperpage','as'=>'rpjmdindikatorkinerja.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdindikatorkinerja/orderby',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@orderby','as'=>'rpjmdindikatorkinerja.orderby']);
    
    
    //RPJMD - Indikator Rencana Program Prioritas atau Indikator Kinerja
    Route::resource('/perencanaan/rpjmd/rpjmdindikatorkinerja','RPJMD\RPJMDIndikatorKinerjaController',['parameters'=>['rpjmdindikatorkinerja'=>'uuid']]); 
    Route::post('/perencanaan/rpjmd/rpjmdindikatorkinerja/search',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@search','as'=>'rpjmdindikatorkinerja.search']); 
    Route::post('/perencanaan/rpjmd/rpjmdindikatorkinerja/filter',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@filter','as'=>'rpjmdindikatorkinerja.filter']);                  
    Route::get('/perencanaan/rpjmd/rpjmdindikatorkinerja/paginate/{id}',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@paginate','as'=>'rpjmdindikatorkinerja.paginate']);              
    Route::post('/perencanaan/rpjmd/rpjmdindikatorkinerja/changenumberrecordperpage',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@changenumberrecordperpage','as'=>'rpjmdindikatorkinerja.changenumberrecordperpage']);  
    Route::post('/perencanaan/rpjmd/rpjmdindikatorkinerja/orderby',['uses'=>'RPJMD\RPJMDIndikatorKinerjaController@orderby','as'=>'rpjmdindikatorkinerja.orderby']);
      
    //RENSTRA - Tujuan
    Route::resource('/perencanaan/renstra/renstratujuan','RENSTRA\RENSTRATujuanController',['parameters'=>['renstratujuan'=>'uuid']]); 
    Route::get('/perencanaan/renstra/renstratujuan/getdaftarsasaranrpjmd/{uuid}',['uses'=>'RENSTRA\RENSTRATujuanController@getdaftarsasaranrpjmd','as'=>'renstratujuan.getdaftarsasaranrpjmd']);                
    Route::get('/perencanaan/renstra/renstratujuan/getkodetujuan/{uuid}',['uses'=>'RENSTRA\RENSTRATujuanController@getkodetujuan','as'=>'renstratujuan.getkodetujuan']);              
    Route::post('/perencanaan/renstra/renstratujuan/filter',['uses'=>'RENSTRA\RENSTRATujuanController@filter','as'=>'renstratujuan.filter']); 
    Route::post('/perencanaan/renstra/renstratujuan/search',['uses'=>'RENSTRA\RENSTRATujuanController@search','as'=>'renstratujuan.search']); 
    Route::get('/perencanaan/renstra/renstratujuan/paginate/{id}',['uses'=>'RENSTRA\RENSTRATujuanController@paginate','as'=>'renstratujuan.paginate']);              
    Route::post('/perencanaan/renstra/renstratujuan/changenumberrecordperpage',['uses'=>'RENSTRA\RENSTRATujuanController@changenumberrecordperpage','as'=>'renstratujuan.changenumberrecordperpage']);  
    Route::post('/perencanaan/renstra/renstratujuan/orderby',['uses'=>'RENSTRA\RENSTRATujuanController@orderby','as'=>'renstratujuan.orderby']); 

    //RENSTRA - Sasaran
    Route::resource('/perencanaan/renstra/renstrasasaran','RENSTRA\RENSTRASasaranController',['parameters'=>['renstrasasaran'=>'uuid']]); 
    Route::post('/perencanaan/renstra/renstrasasaran/store1',['uses'=>'RENSTRA\RENSTRASasaranController@store1','as'=>'renstrasasaran.store1']);
    Route::get('/perencanaan/renstra/renstrasasaran/getkodesasaran/{uuid}',['uses'=>'RENSTRA\RENSTRASasaranController@getkodesasaran','as'=>'renstrasasaran.getkodesasaran']);              
    Route::get('/perencanaan/rpjmd/renstrasasaran/edit1/{uuid}',['uses'=>'RENSTRA\RENSTRASasaranController@edit1','as'=>'renstrasasaran.edit1']);  
    Route::put('/perencanaan/rpjmd/renstrasasaran/update1/{uuid}',['uses'=>'RENSTRA\RENSTRASasaranController@update1','as'=>'renstrasasaran.update1']);  
    Route::post('/perencanaan/renstra/renstrasasaran/search',['uses'=>'RENSTRA\RENSTRASasaranController@search','as'=>'renstrasasaran.search']); 
    Route::post('/perencanaan/renstra/renstrasasaran/filter',['uses'=>'RENSTRA\RENSTRASasaranController@filter','as'=>'renstrasasaran.filter']); 
    Route::get('/perencanaan/renstra/renstrasasaran/paginate/{id}',['uses'=>'RENSTRA\RENSTRASasaranController@paginate','as'=>'renstrasasaran.paginate']);              
    Route::post('/perencanaan/renstra/renstrasasaran/changenumberrecordperpage',['uses'=>'RENSTRA\RENSTRASasaranController@changenumberrecordperpage','as'=>'renstrasasaran.changenumberrecordperpage']);  
    Route::post('/perencanaan/renstra/renstrasasaran/orderby',['uses'=>'RENSTRA\RENSTRASasaranController@orderby','as'=>'renstrasasaran.orderby']); 

    //RENSTRA - Strategi
    Route::resource('/perencanaan/renstra/renstrastrategi','RENSTRA\RENSTRAStrategiController',['parameters'=>['renstrastrategi'=>'uuid']]); 
    Route::get('/perencanaan/renstra/renstrastrategi/getkodestrategi/{uuid}',['uses'=>'RENSTRA\RENSTRAStrategiController@getkodestrategi','as'=>'renstrastrategi.getkodestrategi']);              
    Route::post('/perencanaan/renstra/renstrastrategi/search',['uses'=>'RENSTRA\RENSTRAStrategiController@search','as'=>'renstrastrategi.search']); 
    Route::post('/perencanaan/renstra/renstrastrategi/filter',['uses'=>'RENSTRA\RENSTRAStrategiController@filter','as'=>'renstrastrategi.filter']); 
    Route::get('/perencanaan/renstra/renstrastrategi/paginate/{id}',['uses'=>'RENSTRA\RENSTRAStrategiController@paginate','as'=>'renstrastrategi.paginate']);              
    Route::post('/perencanaan/renstra/renstrastrategi/changenumberrecordperpage',['uses'=>'RENSTRA\RENSTRAStrategiController@changenumberrecordperpage','as'=>'renstrastrategi.changenumberrecordperpage']);  
    Route::post('/perencanaan/renstra/renstrastrategi/orderby',['uses'=>'RENSTRA\RENSTRAStrategiController@orderby','as'=>'renstrastrategi.orderby']); 

    //RENSTRA - Kebijakan
    Route::resource('/perencanaan/renstra/renstrakebijakan','RENSTRA\RENSTRAKebijakanController',['parameters'=>['renstrakebijakan'=>'uuid']]); 
    Route::get('/perencanaan/renstra/renstrakebijakan/getkodekebijakan/{uuid}',['uses'=>'RENSTRA\RENSTRAKebijakanController@getkodekebijakan','as'=>'renstrastrategi.getkodekebijakan']);              
    Route::post('/perencanaan/renstra/renstrakebijakan/search',['uses'=>'RENSTRA\RENSTRAKebijakanController@search','as'=>'renstrakebijakan.search']); 
    Route::post('/perencanaan/renstra/renstrakebijakan/filter',['uses'=>'RENSTRA\RENSTRAKebijakanController@filter','as'=>'renstrakebijakan.filter']); 
    Route::get('/perencanaan/renstra/renstrakebijakan/paginate/{id}',['uses'=>'RENSTRA\RENSTRAKebijakanController@paginate','as'=>'renstrakebijakan.paginate']);              
    Route::post('/perencanaan/renstra/renstrakebijakan/changenumberrecordperpage',['uses'=>'RENSTRA\RENSTRAKebijakanController@changenumberrecordperpage','as'=>'renstrakebijakan.changenumberrecordperpage']);  
    Route::post('/perencanaan/renstra/renstrakebijakan/orderby',['uses'=>'RENSTRA\RENSTRAKebijakanController@orderby','as'=>'renstrakebijakan.orderby']); 
    
    //RENSTRA - Program, Kegiatan, dan Pendanaan
    Route::resource('/perencanaan/renstra/renstraprogramkegiatanpendanaan','RENSTRA\RENSTRAProgramKegiatanPendanaanController',['parameters'=>['renstraprogramkegiatanpendanaan'=>'uuid']]); 
    Route::post('/perencanaan/renstra/renstraprogramkegiatanpendanaan/search',['uses'=>'RENSTRA\RENSTRAProgramKegiatanPendanaanController@search','as'=>'renstraprogramkegiatanpendanaan.search']); 
    Route::post('/perencanaan/renstra/renstraprogramkegiatanpendanaan/filter',['uses'=>'RENSTRA\RENSTRAProgramKegiatanPendanaanController@filter','as'=>'renstraprogramkegiatanpendanaan.filter']);     
    Route::get('/perencanaan/renstra/renstraprogramkegiatanpendanaan/paginate/{id}',['uses'=>'RENSTRA\RENSTRAProgramKegiatanPendanaanController@paginate','as'=>'renstraprogramkegiatanpendanaan.paginate']);              
    Route::post('/perencanaan/renstra/renstraprogramkegiatanpendanaan/changenumberrecordperpage',['uses'=>'RENSTRA\RENSTRAProgramKegiatanPendanaanController@changenumberrecordperpage','as'=>'renstraprogramkegiatanpendanaan.changenumberrecordperpage']);  
    Route::post('/perencanaan/renstra/renstraprogramkegiatanpendanaan/orderby',['uses'=>'RENSTRA\RENSTRAProgramKegiatanPendanaanController@orderby','as'=>'renstraprogramkegiatanpendanaan.orderby']);
        
    //POKIR - Pemilik Pokok Pikiran
    Route::resource('/perencanaan/pokir/pemilikpokokpikiran','Pokir\PemilikPokokPikiranController',['parameters'=>['pemilikpokokpikiran'=>'uuid']]);        
    Route::post('/perencanaan/pokir/pemilikpokokpikiran/search',['uses'=>'Pokir\PemilikPokokPikiranController@search','as'=>'pemilikpokokpikiran.search']); 
    Route::get('/perencanaan/pokir/pemilikpokokpikiran/paginate/{id}',['uses'=>'Pokir\PemilikPokokPikiranController@paginate','as'=>'pemilikpokokpikiran.paginate']);              
    Route::post('/perencanaan/pokir/pemilikpokokpikiran/changenumberrecordperpage',['uses'=>'Pokir\PemilikPokokPikiranController@changenumberrecordperpage','as'=>'pemilikpokokpikiran.changenumberrecordperpage']);  
    Route::post('/perencanaan/pokir/pemilikpokokpikiran/orderby',['uses'=>'Pokir\PemilikPokokPikiranController@orderby','as'=>'pemilikpokokpikiran.orderby']); 

    //POKIR - Pokok Pikiran
    Route::resource('/perencanaan/pokokpikiran','Pokir\PokokPikiranController',['parameters'=>['pokokpikiran'=>'uuid']]);        
    Route::post('/perencanaan/pokokpikiran/search',['uses'=>'Pokir\PokokPikiranController@search','as'=>'pokokpikiran.search']); 
    Route::post('/perencanaan/pokokpikiran/filter',['uses'=>'Pokir\PokokPikiranController@filter','as'=>'pokokpikiran.filter']);                  
    Route::get('/perencanaan/pokokpikiran/paginate/{id}',['uses'=>'Pokir\PokokPikiranController@paginate','as'=>'pokokpikiran.paginate']);              
    Route::post('/perencanaan/pokokpikiran/changenumberrecordperpage',['uses'=>'Pokir\PokokPikiranController@changenumberrecordperpage','as'=>'pokokpikiran.changenumberrecordperpage']);  
    Route::post('/perencanaan/pokokpikiran/orderby',['uses'=>'Pokir\PokokPikiranController@orderby','as'=>'pokokpikiran.orderby']); 
    Route::get('/perencanaan/pokokpikiran/printtoexcel',['uses'=>'Pokir\PokokPikiranController@printtoexcel','as'=>'pokokpikiran.printtoexcel']);
    
    //RKPD - Transfer RKPD MURNI ke Pembahasan [rkpd]
    Route::resource('/rkpd/transferrkpdmurnitopembahasan1','RKPD\TransferRKPDMurniTOPembahasan1',['parameters'=>['transferrkpdmurnitopembahasan1'=>'uuid'],
                                                                                                              'only'=>['index','store','destroy','show']]);
    Route::post('/rkpd/transferrkpdmurnitopembahasan1/search',['uses'=>'RKPD\TransferRKPDMurniTOPembahasan1@search','as'=>'transferrkpdmurnitopembahasan1.search']); 
    Route::get('/rkpd/transferrkpdmurnitopembahasan1/paginate/{id}',['uses'=>'RKPD\TransferRKPDMurniTOPembahasan1@paginate','as'=>'transferrkpdmurnitopembahasan1.paginate']);              
    Route::post('/rkpd/transferrkpdmurnitopembahasan1/changenumberrecordperpage',['uses'=>'RKPD\TransferRKPDMurniTOPembahasan1@changenumberrecordperpage','as'=>'transferrkpdmurnitopembahasan1.changenumberrecordperpage']);  
    Route::post('/rkpd/transferrkpdmurnitopembahasan1/orderby',['uses'=>'RKPD\TransferRKPDMurniTOPembahasan1@orderby','as'=>'transferrkpdmurnitopembahasan1.orderby']);
    
    //RKPD - Transfer Pembahasan RKPD ke RKPD Perubahan [rkpd]
    Route::resource('/rkpd/transferpembahasanrkpdtoperubahan2','RKPD\TransferPembahasanRKPDTOPerubahan2',['parameters'=>['transferpembahasanrkpdtoperubahan2'=>'uuid'],
                                                                                                              'only'=>['index','store','destroy','show']]);
    Route::post('/rkpd/transferpembahasanrkpdtoperubahan2/search',['uses'=>'RKPD\TransferPembahasanRKPDTOPerubahan2@search','as'=>'transferpembahasanrkpdtoperubahan2.search']); 
    Route::get('/rkpd/transferpembahasanrkpdtoperubahan2/paginate/{id}',['uses'=>'RKPD\TransferPembahasanRKPDTOPerubahan2@paginate','as'=>'transferpembahasanrkpdtoperubahan2.paginate']);              
    Route::post('/rkpd/transferpembahasanrkpdtoperubahan2/changenumberrecordperpage',['uses'=>'RKPD\TransferPembahasanRKPDTOPerubahan2@changenumberrecordperpage','as'=>'transferpembahasanrkpdtoperubahan2.changenumberrecordperpage']);  
    Route::post('/rkpd/transferpembahasanrkpdtoperubahan2/orderby',['uses'=>'RKPD\TransferPembahasanRKPDTOPerubahan2@orderby','as'=>'transferpembahasanrkpdtoperubahan2.orderby']);
    
    //Aspirasi - Pemilik Bansos dan Hibah
    Route::resource('/aspirasi/pemilikbansoshibah','Aspirasi\PemilikBansosHibahController',['parameters'=>['pemilikbansoshibah'=>'uuid']]);        
    Route::post('/aspirasi/pemilikbansoshibah/search',['uses'=>'Aspirasi\PemilikBansosHibahController@search','as'=>'pemilikbansoshibah.search']); 
    Route::get('/aspirasi/pemilikbansoshibah/paginate/{id}',['uses'=>'Aspirasi\PemilikBansosHibahController@paginate','as'=>'pemilikbansoshibah.paginate']);              
    Route::post('/aspirasi/pemilikbansoshibah/changenumberrecordperpage',['uses'=>'Aspirasi\PemilikBansosHibahController@changenumberrecordperpage','as'=>'pemilikbansoshibah.changenumberrecordperpage']);  
    Route::post('/aspirasi/pemilikbansoshibah/orderby',['uses'=>'Aspirasi\PemilikBansosHibahController@orderby','as'=>'pemilikbansoshibah.orderby']); 

    //Aspirasi - Hibah dan Bansos
    Route::resource('/aspirasi/bansoshibah','Aspirasi\BansosHibahController',['parameters'=>['bansoshibah'=>'uuid']]);        
    Route::post('/aspirasi/bansoshibah/search',['uses'=>'Aspirasi\BansosHibahController@search','as'=>'bansoshibah.search']); 
    Route::post('/aspirasi/bansoshibah/filter',['uses'=>'Aspirasi\BansosHibahController@filter','as'=>'bansoshibah.filter']);                  
    Route::get('/aspirasi/bansoshibah/paginate/{id}',['uses'=>'Aspirasi\BansosHibahController@paginate','as'=>'bansoshibah.paginate']);              
    Route::post('/aspirasi/bansoshibah/changenumberrecordperpage',['uses'=>'Aspirasi\BansosHibahController@changenumberrecordperpage','as'=>'bansoshibah.changenumberrecordperpage']);  
    Route::post('/aspirasi/bansoshibah/orderby',['uses'=>'Aspirasi\BansosHibahController@orderby','as'=>'bansoshibah.orderby']); 
    Route::get('/aspirasi/bansoshibah/printtoexcel',['uses'=>'Aspirasi\BansosHibahController@printtoexcel','as'=>'bansoshibah.printtoexcel']);

    //Aspirasi - Long List
    Route::resource('/aspirasi/longlist','Aspirasi\LongListController',['parameters'=>['longlist'=>'uuid']]);        
    Route::post('/aspirasi/longlist/search',['uses'=>'Aspirasi\LongListController@search','as'=>'longlist.search']); 
    Route::post('/aspirasi/longlist/filter',['uses'=>'Aspirasi\LongListController@filter','as'=>'longlist.filter']);                  
    Route::get('/aspirasi/longlist/paginate/{id}',['uses'=>'Aspirasi\LongListController@paginate','as'=>'longlist.paginate']);              
    Route::post('/aspirasi/longlist/changenumberrecordperpage',['uses'=>'Aspirasi\LongListController@changenumberrecordperpage','as'=>'longlist.changenumberrecordperpage']);  
    Route::post('/aspirasi/longlist/orderby',['uses'=>'Aspirasi\LongListController@orderby','as'=>'longlist.orderby']); 
    Route::get('/aspirasi/longlist/printtoexcel',['uses'=>'Aspirasi\LongListController@printtoexcel','as'=>'longlist.printtoexcel']);

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
    Route::get('/workflow/musrenbang/pembahasanmusrenkecamatan/printtoexcel',['uses'=>'Musrenbang\PembahasanMusrenKecamatanController@printtoexcel','as'=>'pembahasanmusrenkecamatan.printtoexcel']);              
            
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
                                                                                                        'only'=>['index','edit','update','show']]);
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
                                                                                                              'only'=>['index','edit','update','show']]); 
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
                                                                                                              'only'=>['index','edit','update','show']]); 
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
                                                                                                              'only'=>['index','edit','update','show']]); 
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
                                                                                                              'only'=>['index','edit','update','show']]); 
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
    
    //workflow - Pembahasan RKPD Perubahan [pembahasan]
    Route::resource('/workflow/rkpd/pembahasanrkpd','RKPD\PembahasanRKPDController',['parameters'=>['pembahasanrkpd'=>'uuid']]);        
    Route::post('/workflow/rkpd/pembahasanrkpd/pilihusulankegiatan',['uses'=>'RKPD\PembahasanRKPDController@pilihusulankegiatan','as'=>'pembahasanrkpd.pilihusulankegiatan']);                  
    Route::post('/workflow/rkpd/pembahasanrkpd/pilihindikatorkinerja',['uses'=>'RKPD\PembahasanRKPDController@pilihindikatorkinerja','as'=>'pembahasanrkpd.pilihindikatorkinerja']);                  
    Route::get('/workflow/rkpd/pembahasanrkpd/create1/{uuid}',['uses'=>'RKPD\PembahasanRKPDController@create1','as'=>'pembahasanrkpd.create1']);              
    Route::get('/workflow/rkpd/pembahasanrkpd/create2/{uuid}',['uses'=>'RKPD\PembahasanRKPDController@create2','as'=>'pembahasanrkpd.create2']);              
    Route::get('/workflow/rkpd/pembahasanrkpd/create3/{uuid}',['uses'=>'RKPD\PembahasanRKPDController@create3','as'=>'pembahasanrkpd.create3']);              
    Route::get('/workflow/rkpd/pembahasanrkpd/create4/{uuid}',['uses'=>'RKPD\PembahasanRKPDController@create4','as'=>'pembahasanrkpd.create4']);              
    Route::post('/workflow/rkpd/pembahasanrkpd/store1',['uses'=>'RKPD\PembahasanRKPDController@store1','as'=>'pembahasanrkpd.store1']);  
    Route::post('/workflow/rkpd/pembahasanrkpd/store2',['uses'=>'RKPD\PembahasanRKPDController@store2','as'=>'pembahasanrkpd.store2']);  
    Route::post('/workflow/rkpd/pembahasanrkpd/store3',['uses'=>'RKPD\PembahasanRKPDController@store3','as'=>'pembahasanrkpd.store3']); 
    Route::post('/workflow/rkpd/pembahasanrkpd/store4',['uses'=>'RKPD\PembahasanRKPDController@store4','as'=>'pembahasanrkpd.store4']); 
    Route::get('/workflow/rkpd/pembahasanrkpd/{uuid}/edit1',['uses'=>'RKPD\PembahasanRKPDController@edit1','as'=>'pembahasanrkpd.edit1']);              
    Route::get('/workflow/rkpd/pembahasanrkpd/{uuid}/edit2',['uses'=>'RKPD\PembahasanRKPDController@edit2','as'=>'pembahasanrkpd.edit2']);              
    Route::get('/workflow/rkpd/pembahasanrkpd/{uuid}/edit3',['uses'=>'RKPD\PembahasanRKPDController@edit3','as'=>'pembahasanrkpd.edit3']);              
    Route::get('/workflow/rkpd/pembahasanrkpd/{uuid}/edit4',['uses'=>'RKPD\PembahasanRKPDController@edit4','as'=>'pembahasanrkpd.edit4']);              
    Route::put('/workflow/rkpd/pembahasanrkpd/update1/{uuid}',['uses'=>'RKPD\PembahasanRKPDController@update1','as'=>'pembahasanrkpd.update1']);  
    Route::put('/workflow/rkpd/pembahasanrkpd/update2/{uuid}',['uses'=>'RKPD\PembahasanRKPDController@update2','as'=>'pembahasanrkpd.update2']);  
    Route::put('/workflow/rkpd/pembahasanrkpd/update3/{uuid}',['uses'=>'RKPD\PembahasanRKPDController@update3','as'=>'pembahasanrkpd.update3']); 
    Route::put('/workflow/rkpd/pembahasanrkpd/update4/{uuid}',['uses'=>'RKPD\PembahasanRKPDController@update4','as'=>'pembahasanrkpd.update4']); 
    Route::get('/workflow/rkpd/pembahasanrkpd/showrincian/{uuid}',['uses'=>'RKPD\PembahasanRKPDController@showrincian','as'=>'pembahasanrkpd.showrincian']); 
    Route::post('/workflow/rkpd/pembahasanrkpd/search',['uses'=>'RKPD\PembahasanRKPDController@search','as'=>'pembahasanrkpd.search']);  
    Route::post('/workflow/rkpd/pembahasanrkpd/filter',['uses'=>'RKPD\PembahasanRKPDController@filter','as'=>'pembahasanrkpd.filter']);                  
    Route::get('/workflow/rkpd/pembahasanrkpd/paginate/{id}',['uses'=>'RKPD\PembahasanRKPDController@paginate','as'=>'pembahasanrkpd.paginate']);              
    Route::post('/workflow/rkpd/pembahasanrkpd/changenumberrecordperpage',['uses'=>'RKPD\PembahasanRKPDController@changenumberrecordperpage','as'=>'pembahasanrkpd.changenumberrecordperpage']);  
    Route::post('/workflow/rkpd/pembahasanrkpd/orderby',['uses'=>'RKPD\PembahasanRKPDController@orderby','as'=>'pembahasanrkpd.orderby']);
    Route::get('/workflow/rkpd/pembahasanrkpd/printtoexcelrkpdpembasahanrinci',['uses'=>'RKPD\PembahasanRKPDController@printtoexcelrkpdpembasahanrinci','as'=>'pembahasanrkpd.printtoexcelrkpdpembasahanrinci']);
    Route::get('/workflow/rkpd/pembahasanrkpd/printtoexcel',['uses'=>'RKPD\PembahasanRKPDController@printtoexcel','as'=>'pembahasanrkpd.printtoexcel']);
    Route::get('/workflow/rkpd/pembahasanrkpd/printtopdf',['uses'=>'RKPD\PembahasanRKPDController@printtopdf','as'=>'pembahasanrkpd.printtopdf']);

    // Workflow- RKPD Perubahan    
    Route::resource('/workflow/rkpd/rkpdperubahan','RKPD\RKPDPerubahanController',['parameters'=>['rkpdperubahan'=>'uuid']]);        
    Route::post('/workflow/rkpd/rkpdperubahan/pilihusulankegiatan',['uses'=>'RKPD\RKPDPerubahanController@pilihusulankegiatan','as'=>'rkpdperubahan.pilihusulankegiatan']);                  
    Route::post('/workflow/rkpd/rkpdperubahan/pilihindikatorkinerja',['uses'=>'RKPD\RKPDPerubahanController@pilihindikatorkinerja','as'=>'rkpdperubahan.pilihindikatorkinerja']);                  
    Route::get('/workflow/rkpd/rkpdperubahan/create1/{uuid}',['uses'=>'RKPD\RKPDPerubahanController@create1','as'=>'rkpdperubahan.create1']);              
    Route::get('/workflow/rkpd/rkpdperubahan/create2/{uuid}',['uses'=>'RKPD\RKPDPerubahanController@create2','as'=>'rkpdperubahan.create2']);              
    Route::get('/workflow/rkpd/rkpdperubahan/create3/{uuid}',['uses'=>'RKPD\RKPDPerubahanController@create3','as'=>'rkpdperubahan.create3']);              
    Route::get('/workflow/rkpd/rkpdperubahan/create4/{uuid}',['uses'=>'RKPD\RKPDPerubahanController@create4','as'=>'rkpdperubahan.create4']);              
    Route::post('/workflow/rkpd/rkpdperubahan/store1',['uses'=>'RKPD\RKPDPerubahanController@store1','as'=>'rkpdperubahan.store1']);  
    Route::post('/workflow/rkpd/rkpdperubahan/store2',['uses'=>'RKPD\RKPDPerubahanController@store2','as'=>'rkpdperubahan.store2']);  
    Route::post('/workflow/rkpd/rkpdperubahan/store3',['uses'=>'RKPD\RKPDPerubahanController@store3','as'=>'rkpdperubahan.store3']); 
    Route::post('/workflow/rkpd/rkpdperubahan/store4',['uses'=>'RKPD\RKPDPerubahanController@store4','as'=>'rkpdperubahan.store4']); 
    Route::get('/workflow/rkpd/rkpdperubahan/{uuid}/edit1',['uses'=>'RKPD\RKPDPerubahanController@edit1','as'=>'rkpdperubahan.edit1']);              
    Route::get('/workflow/rkpd/rkpdperubahan/{uuid}/edit2',['uses'=>'RKPD\RKPDPerubahanController@edit2','as'=>'rkpdperubahan.edit2']);              
    Route::get('/workflow/rkpd/rkpdperubahan/{uuid}/edit3',['uses'=>'RKPD\RKPDPerubahanController@edit3','as'=>'rkpdperubahan.edit3']);              
    Route::get('/workflow/rkpd/rkpdperubahan/{uuid}/edit4',['uses'=>'RKPD\RKPDPerubahanController@edit4','as'=>'rkpdperubahan.edit4']);              
    Route::put('/workflow/rkpd/rkpdperubahan/update1/{uuid}',['uses'=>'RKPD\RKPDPerubahanController@update1','as'=>'rkpdperubahan.update1']);  
    Route::put('/workflow/rkpd/rkpdperubahan/update2/{uuid}',['uses'=>'RKPD\RKPDPerubahanController@update2','as'=>'rkpdperubahan.update2']);  
    Route::put('/workflow/rkpd/rkpdperubahan/update3/{uuid}',['uses'=>'RKPD\RKPDPerubahanController@update3','as'=>'rkpdperubahan.update3']); 
    Route::put('/workflow/rkpd/rkpdperubahan/update4/{uuid}',['uses'=>'RKPD\RKPDPerubahanController@update4','as'=>'rkpdperubahan.update4']); 
    Route::get('/workflow/rkpd/rkpdperubahan/showrincian/{uuid}',['uses'=>'RKPD\RKPDPerubahanController@showrincian','as'=>'rkpdperubahan.showrincian']); 
    Route::post('/workflow/rkpd/rkpdperubahan/search',['uses'=>'RKPD\RKPDPerubahanController@search','as'=>'rkpdperubahan.search']);  
    Route::post('/workflow/rkpd/rkpdperubahan/filter',['uses'=>'RKPD\RKPDPerubahanController@filter','as'=>'rkpdperubahan.filter']);                  
    Route::get('/workflow/rkpd/rkpdperubahan/paginate/{id}',['uses'=>'RKPD\RKPDPerubahanController@paginate','as'=>'rkpdperubahan.paginate']);              
    Route::post('/workflow/rkpd/rkpdperubahan/changenumberrecordperpage',['uses'=>'RKPD\RKPDPerubahanController@changenumberrecordperpage','as'=>'rkpdperubahan.changenumberrecordperpage']);  
    Route::post('/workflow/rkpd/rkpdperubahan/orderby',['uses'=>'RKPD\RKPDPerubahanController@orderby','as'=>'rkpdperubahan.orderby']);
    Route::get('/workflow/rkpd/rkpdperubahan/printtoexcel',['uses'=>'RKPD\RKPDPerubahanController@printtoexcel','as'=>'rkpdperubahan.printtoexcel']);
    Route::get('/workflow/rkpd/rkpdperubahan/printtopdf',['uses'=>'RKPD\RKPDPerubahanController@printtopdf','as'=>'rkpdperubahan.printtopdf']);
    
    //workflow - Pembahasan RKPD Perubahan [pembahasan]
    Route::resource('/workflow/rkpd/pembahasanrkpdp','RKPD\PembahasanRKPDPController',['parameters'=>['pembahasanrkpdp'=>'uuid']]);        
    Route::post('/workflow/rkpd/pembahasanrkpdp/pilihusulankegiatan',['uses'=>'RKPD\PembahasanRKPDPController@pilihusulankegiatan','as'=>'pembahasanrkpdp.pilihusulankegiatan']);                  
    Route::post('/workflow/rkpd/pembahasanrkpdp/pilihindikatorkinerja',['uses'=>'RKPD\PembahasanRKPDPController@pilihindikatorkinerja','as'=>'pembahasanrkpdp.pilihindikatorkinerja']);                  
    Route::get('/workflow/rkpd/pembahasanrkpdp/create1/{uuid}',['uses'=>'RKPD\PembahasanRKPDPController@create1','as'=>'pembahasanrkpdp.create1']);              
    Route::get('/workflow/rkpd/pembahasanrkpdp/create2/{uuid}',['uses'=>'RKPD\PembahasanRKPDPController@create2','as'=>'pembahasanrkpdp.create2']);              
    Route::get('/workflow/rkpd/pembahasanrkpdp/create3/{uuid}',['uses'=>'RKPD\PembahasanRKPDPController@create3','as'=>'pembahasanrkpdp.create3']);              
    Route::get('/workflow/rkpd/pembahasanrkpdp/create4/{uuid}',['uses'=>'RKPD\PembahasanRKPDPController@create4','as'=>'pembahasanrkpdp.create4']);              
    Route::post('/workflow/rkpd/pembahasanrkpdp/store1',['uses'=>'RKPD\PembahasanRKPDPController@store1','as'=>'pembahasanrkpdp.store1']);  
    Route::post('/workflow/rkpd/pembahasanrkpdp/store2',['uses'=>'RKPD\PembahasanRKPDPController@store2','as'=>'pembahasanrkpdp.store2']);  
    Route::post('/workflow/rkpd/pembahasanrkpdp/store3',['uses'=>'RKPD\PembahasanRKPDPController@store3','as'=>'pembahasanrkpdp.store3']); 
    Route::post('/workflow/rkpd/pembahasanrkpdp/store4',['uses'=>'RKPD\PembahasanRKPDPController@store4','as'=>'pembahasanrkpdp.store4']); 
    Route::get('/workflow/rkpd/pembahasanrkpdp/{uuid}/edit1',['uses'=>'RKPD\PembahasanRKPDPController@edit1','as'=>'pembahasanrkpdp.edit1']);              
    Route::get('/workflow/rkpd/pembahasanrkpdp/{uuid}/edit2',['uses'=>'RKPD\PembahasanRKPDPController@edit2','as'=>'pembahasanrkpdp.edit2']);              
    Route::get('/workflow/rkpd/pembahasanrkpdp/{uuid}/edit3',['uses'=>'RKPD\PembahasanRKPDPController@edit3','as'=>'pembahasanrkpdp.edit3']);              
    Route::get('/workflow/rkpd/pembahasanrkpdp/{uuid}/edit4',['uses'=>'RKPD\PembahasanRKPDPController@edit4','as'=>'pembahasanrkpdp.edit4']);              
    Route::put('/workflow/rkpd/pembahasanrkpdp/update1/{uuid}',['uses'=>'RKPD\PembahasanRKPDPController@update1','as'=>'pembahasanrkpdp.update1']);  
    Route::put('/workflow/rkpd/pembahasanrkpdp/update2/{uuid}',['uses'=>'RKPD\PembahasanRKPDPController@update2','as'=>'pembahasanrkpdp.update2']);  
    Route::put('/workflow/rkpd/pembahasanrkpdp/update3/{uuid}',['uses'=>'RKPD\PembahasanRKPDPController@update3','as'=>'pembahasanrkpdp.update3']); 
    Route::put('/workflow/rkpd/pembahasanrkpdp/update4/{uuid}',['uses'=>'RKPD\PembahasanRKPDPController@update4','as'=>'pembahasanrkpdp.update4']); 
    Route::get('/workflow/rkpd/pembahasanrkpdp/showrincian/{uuid}',['uses'=>'RKPD\PembahasanRKPDPController@showrincian','as'=>'pembahasanrkpdp.showrincian']); 
    Route::post('/workflow/rkpd/pembahasanrkpdp/search',['uses'=>'RKPD\PembahasanRKPDPController@search','as'=>'pembahasanrkpdp.search']);  
    Route::post('/workflow/rkpd/pembahasanrkpdp/filter',['uses'=>'RKPD\PembahasanRKPDPController@filter','as'=>'pembahasanrkpdp.filter']);                  
    Route::get('/workflow/rkpd/pembahasanrkpdp/paginate/{id}',['uses'=>'RKPD\PembahasanRKPDPController@paginate','as'=>'pembahasanrkpdp.paginate']);              
    Route::post('/workflow/rkpd/pembahasanrkpdp/changenumberrecordperpage',['uses'=>'RKPD\PembahasanRKPDPController@changenumberrecordperpage','as'=>'pembahasanrkpdp.changenumberrecordperpage']);  
    Route::post('/workflow/rkpd/pembahasanrkpdp/orderby',['uses'=>'RKPD\PembahasanRKPDPController@orderby','as'=>'pembahasanrkpdp.orderby']);
    Route::get('/workflow/rkpd/pembahasanrkpdp/printtoexcel',['uses'=>'RKPD\PembahasanRKPDPController@printtoexcel','as'=>'pembahasanrkpdp.printtoexcel']);
    Route::get('/workflow/rkpd/pembahasanrkpdp/printtopdf',['uses'=>'RKPD\PembahasanRKPDPController@printtopdf','as'=>'pembahasanrkpdp.printtopdf']);

    //Report - RENCANA KERJA OPD / SKPD - RKPD MURNI PER OPD   
    Route::resource('/report/rkpd/reportrkpdmurniopd','Report\ReportRKPDMurniOPDController',[
                                                                'parameters'=>['reportrkpdmurniopd'=>'id'],
                                                                'only'=>['index']
                                                            ]);                   

    Route::post('/report/rkpd/reportrkpdmurniopd/search',['uses'=>'Report\ReportRKPDMurniOPDController@search','as'=>'reportrkpdmurniopd.search']);              
    Route::post('/report/rkpd/reportrkpdmurniopd/filter',['uses'=>'Report\ReportRKPDMurniOPDController@filter','as'=>'reportrkpdmurniopd.filter']);                  
    Route::get('/report/rkpd/reportrkpdmurniopd/printtoexcel',['uses'=>'Report\ReportRKPDMurniOPDController@printtoexcel','as'=>'reportrkpdmurniopd.printtoexcel']);  
    
    //Report - RENCANA KERJA OPD / SKPD - RKPD MURNI PER OPD   
    Route::resource('/report/rkpd/reportpembahasanrkpdmurniopd','Report\ReportPembahasanRKPDMurniOPDController',[
                                                                                                                    'parameters'=>['reportpembahasanrkpdmurniopd'=>'id'],
                                                                                                                    'only'=>['index']
                                                                                                                ]);                   

    Route::post('/report/rkpd/reportpembahasanrkpdmurniopd/search',['uses'=>'Report\ReportPembahasanRKPDMurniOPDController@search','as'=>'reportpembahasanrkpdmurniopd.search']);              
    Route::post('/report/rkpd/reportpembahasanrkpdmurniopd/filter',['uses'=>'Report\ReportPembahasanRKPDMurniOPDController@filter','as'=>'reportpembahasanrkpdmurniopd.filter']);                  
    Route::get('/report/rkpd/reportpembahasanrkpdmurniopd/printtoexcel',['uses'=>'Report\ReportPembahasanRKPDMurniOPDController@printtoexcel','as'=>'reportpembahasanrkpdmurniopd.printtoexcel']);  

    //Report - RENCANA KERJA OPD / SKPD - RKPD MURNI PER OPD RINCI
    Route::resource('/report/rkpd/reportrkpdmurniopdrinci','Report\ReportRKPDMurniOPDRinciController',[
                                                                                                        'parameters'=>['reportrkpdmurniopdrinci'=>'id'],
                                                                                                        'only'=>['index']
                                                                                                    ]);                   

    Route::post('/report/rkpd/reportrkpdmurniopdrinci/search',['uses'=>'Report\ReportRKPDMurniOPDRinciController@search','as'=>'reportrkpdmurniopdrinci.search']);              
    Route::post('/report/rkpd/reportrkpdmurniopdrinci/filter',['uses'=>'Report\ReportRKPDMurniOPDRinciController@filter','as'=>'reportrkpdmurniopdrinci.filter']);                  
    Route::get('/report/rkpd/reportrkpdmurniopdrinci/printtoexcel',['uses'=>'Report\ReportRKPDMurniOPDRinciController@printtoexcel','as'=>'reportrkpdmurniopdrinci.printtoexcel']);  

    //Report - RENCANA KERJA OPD / SKPD - PROGRAM RKPD MURNI PER OPD
    Route::resource('/report/rkpd/reportprogrammurniopd','Report\ReportProgramMurniOPDController',[
                                                                                                    'parameters'=>['reportprogrammurniopd'=>'id'],
                                                                                                    'only'=>['index']
                                                                                                ]);                   

    Route::post('/report/rkpd/reportprogrammurniopd/search',['uses'=>'Report\ReportProgramMurniOPDController@search','as'=>'reportprogrammurniopd.search']);              
    Route::post('/report/rkpd/reportprogrammurniopd/filter',['uses'=>'Report\ReportProgramMurniOPDController@filter','as'=>'reportprogrammurniopd.filter']);                  
    Route::get('/report/rkpd/reportprogrammurniopd/printtoexcel',['uses'=>'Report\ReportProgramMurniOPDController@printtoexcel','as'=>'reportprogrammurniopd.printtoexcel']);  

    //Report - RENCANA KERJA OPD / SKPD - PROGRAM RKPD MURNI PER OPD
    Route::resource('/report/rkpd/reportrkpdperubahanopd','Report\ReportRKPDPerubahanOPDController',[
                                                                                                        'parameters'=>['reportrkpdperubahanopd'=>'id'],
                                                                                                        'only'=>['index']
                                                                                                    ]);                   
    
    Route::post('/report/rkpd/reportrkpdperubahanopd/search',['uses'=>'Report\ReportRKPDPerubahanOPDController@search','as'=>'reportrkpdperubahanopd.search']);              
    Route::post('/report/rkpd/reportrkpdperubahanopd/filter',['uses'=>'Report\ReportRKPDPerubahanOPDController@filter','as'=>'reportrkpdperubahanopd.filter']);                  
    Route::get('/report/rkpd/reportrkpdperubahanopd/printtoexcel',['uses'=>'Report\ReportRKPDPerubahanOPDController@printtoexcel','as'=>'reportrkpdperubahanopd.printtoexcel']);  

    //Report - RENCANA KERJA OPD / SKPD - PROGRAM RKPD PERUBAHAN PER OPD
    Route::resource('/report/rkpd/reportprogramperubahanopd','Report\ReportProgramPerubahanOPDController',[
                                                                                                            'parameters'=>['reportprogramperubahanopd'=>'id'],
                                                                                                            'only'=>['index']
                                                                                                        ]);                   
    
    Route::post('/report/rkpd/reportprogramperubahanopd/search',['uses'=>'Report\ReportProgramPerubahanOPDController@search','as'=>'reportprogramperubahanopd.search']);              
    Route::post('/report/rkpd/reportprogramperubahanopd/filter',['uses'=>'Report\ReportProgramPerubahanOPDController@filter','as'=>'reportprogramperubahanopd.filter']);                  
    Route::get('/report/rkpd/reportprogramperubahanopd/printtoexcel',['uses'=>'Report\ReportProgramPerubahanOPDController@printtoexcel','as'=>'reportprogramperubahanopd.printtoexcel']);  

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
    Route::get('/setting/users/paginate/{id}',['uses'=>'Setting\UsersController@paginate','as'=>'users.paginate'])->middleware(['auth','role:superadmin']);    
    Route::get('/setting/users/profil/{id}',['uses'=>'Setting\UsersController@profil','as'=>'users.profil']);    
    Route::put('/setting/users/updateprofil/{id}',['uses'=>'Setting\UsersController@updateprofil','as'=>'users.updateprofil']);        
    Route::post('/setting/users/changenumberrecordperpage',['uses'=>'Setting\UsersController@changenumberrecordperpage','as'=>'users.changenumberrecordperpage'])->middleware(['auth','role:superadmin']);  
    Route::post('/setting/users/orderby',['uses'=>'Setting\UsersController@orderby','as'=>'users.orderby'])->middleware(['auth','role:superadmin']); 
    Route::post('/setting/users/search',['uses'=>'Setting\UsersController@search','as'=>'users.search'])->middleware(['auth','role:superadmin']);    
    Route::post('/setting/users/filter',['uses'=>'Setting\UsersController@filter','as'=>'users.filter'])->middleware(['auth','role:superadmin']); 
    
    //setting - users bapelitbang
    Route::resource('/setting/usersbapelitbang','Setting\UsersBapelitbangController',['parameters'=>['usersbapelitbang'=>'id']]);           
    Route::get('/setting/usersbapelitbang/paginate/{id}',['uses'=>'Setting\UsersBapelitbangController@paginate','as'=>'usersbapelitbang.paginate']);        
    Route::post('/setting/usersbapelitbang/changenumberrecordperpage',['uses'=>'Setting\UsersBapelitbangController@changenumberrecordperpage','as'=>'usersbapelitbang.changenumberrecordperpage']);  
    Route::post('/setting/usersbapelitbang/orderby',['uses'=>'Setting\UsersBapelitbangController@orderby','as'=>'usersbapelitbang.orderby']); 
    Route::post('/setting/usersbapelitbang/search',['uses'=>'Setting\UsersBapelitbangController@search','as'=>'usersbapelitbang.search']);    
    Route::post('/setting/usersbapelitbang/filter',['uses'=>'Setting\UsersBapelitbangController@filter','as'=>'usersbapelitbang.filter']);

    //setting - users OPD
    Route::resource('/setting/usersopd','Setting\UsersOPDController',['parameters'=>['usersopd'=>'id']]);           
    Route::post('/setting/usersopd/store1/{id}',['uses'=>'Setting\UsersOPDController@store1','as'=>'usersopd.store1']);  
    Route::put('/setting/usersopd/changelocked/{id}',['uses'=>'Setting\UsersOPDController@changelocked','as'=>'usersopd.changelocked']);      
    Route::get('/setting/usersopd/paginate/{id}',['uses'=>'Setting\UsersOPDController@paginate','as'=>'usersopd.paginate']);
    Route::post('/setting/usersopd/changenumberrecordperpage',['uses'=>'Setting\UsersOPDController@changenumberrecordperpage','as'=>'usersopd.changenumberrecordperpage']);      
    Route::post('/setting/usersopd/orderby',['uses'=>'Setting\UsersOPDController@orderby','as'=>'usersopd.orderby']); 
    Route::post('/setting/usersopd/search',['uses'=>'Setting\UsersOPDController@search','as'=>'usersopd.search']);    
    Route::post('/setting/usersopd/filter',['uses'=>'Setting\UsersOPDController@filter','as'=>'usersopd.filter']);    
    Route::post('/setting/usersopd/storeuserpermission', ['uses'=>'Setting\UsersOPDController@storeuserpermission','as'=>'usersopd.storeuserpermission']);
    
    //setting - users DEWAN
    Route::resource('/setting/usersdewan','Setting\UsersDewanController',['parameters'=>['usersdewan'=>'id']]);           
    Route::post('/setting/usersdewan/store1/{id}',['uses'=>'Setting\UsersDewanController@store1','as'=>'usersdewan.store1']);  
    Route::get('/setting/usersdewan/paginate/{id}',['uses'=>'Setting\UsersDewanController@paginate','as'=>'usersdewan.paginate']);
    Route::post('/setting/usersdewan/changenumberrecordperpage',['uses'=>'Setting\UsersDewanController@changenumberrecordperpage','as'=>'usersdewan.changenumberrecordperpage']);      
    Route::post('/setting/usersdewan/orderby',['uses'=>'Setting\UsersDewanController@orderby','as'=>'usersdewan.orderby']); 
    Route::post('/setting/usersdewan/search',['uses'=>'Setting\UsersDewanController@search','as'=>'usersdewan.search']);    
    Route::post('/setting/usersdewan/filter',['uses'=>'Setting\UsersDewanController@filter','as'=>'usersdewan.filter']);    
    Route::post('/setting/usersdewan/storeuserpermission', ['uses'=>'Setting\UsersDewanController@storeuserpermission','as'=>'usersdewan.storeuserpermission']);
    
    //setting - users KECAMATAN
    Route::resource('/setting/userskecamatan','Setting\UsersKecamatanController',['parameters'=>['userskecamatan'=>'id']]);           
    Route::post('/setting/userskecamatan/store1/{id}',['uses'=>'Setting\UsersKecamatanController@store1','as'=>'userskecamatan.store1']);  
    Route::get('/setting/userskecamatan/paginate/{id}',['uses'=>'Setting\UsersKecamatanController@paginate','as'=>'userskecamatan.paginate']);
    Route::post('/setting/userskecamatan/changenumberrecordperpage',['uses'=>'Setting\UsersKecamatanController@changenumberrecordperpage','as'=>'userskecamatan.changenumberrecordperpage']);      
    Route::post('/setting/userskecamatan/orderby',['uses'=>'Setting\UsersKecamatanController@orderby','as'=>'userskecamatan.orderby']); 
    Route::post('/setting/userskecamatan/search',['uses'=>'Setting\UsersKecamatanController@search','as'=>'userskecamatan.search']);    
    Route::post('/setting/userskecamatan/filter',['uses'=>'Setting\UsersKecamatanController@filter','as'=>'userskecamatan.filter']);    
    Route::post('/setting/userskecamatan/storeuserpermission', ['uses'=>'Setting\UsersKecamatanController@storeuserpermission','as'=>'userskecamatan.storeuserpermission']);
    
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
    Route::post('/setting/dataeplanning/filter',['uses'=>'Setting\CopyDataController@filter','as'=>'copydata.filter']);    
    Route::get('/setting/dataeplanning/copydata/{id}', ['uses'=>'Setting\CopyDataController@show','as'=>'copydata.show']);
        
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