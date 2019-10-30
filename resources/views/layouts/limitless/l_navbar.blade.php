<!-- Second navbar -->
<div class="navbar navbar-inverse bg-blue-800 navbar-xs" id="navbar-second">
    <ul class="nav navbar-nav no-border visible-xs-block">
        <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="icon-menu7"></i></a></li>
    </ul>
    <div class="navbar-collapse collapse" id="navbar-second-toggle">
        <ul class="nav navbar-nav">            
            <li class="dropdown mega-menu mega-menu-wide visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-display4 position-left"></i> DASHBOARD <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="row">
                            <div class="col-md-3">
                                <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-map4"></i> RINGKASAN UMUM</span>
                                <ul class="menu-list">
                                    <li{!!Helper::isMenuActive ($page_active,'dashboard',' class="active"')!!}>
                                        <a href="{!!route('dashboard.index')!!}">
                                            <i class="icon-display"></i> PERENCANAAN
                                        </a>   
                                    </li> 
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-map4"></i> PERENCANAAN</span>
                                <ul class="menu-list">
                                    <li{!!Helper::isMenuActive ($page_active,'rekappaguindikatifopd',' class="active"')!!}>
                                        <a href="{!!route('rekappaguindikatifopd.index')!!}">
                                            <i class="icon-display"></i> REKAP. PAGU INDIKATIF OPD										
                                        </a>   
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>                
            </li>            
            @hasrole('superadmin|bapelitbang')
            <li class="dropdown mega-menu mega-menu-wide visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-puzzle4 position-left"></i> MASTERS <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="row">      
                            <div class="col-md-3">
                                <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-earth"></i> LOKASI</span>
                                <ul class="menu-list">
                                    <li{!!Helper::isMenuActive ($page_active,'provinsi',' class="active"')!!}>
                                        <a href="{{route('provinsi.index')}}" title="Provinsi">
                                            <i class="icon-earth"></i> PROVINSI
                                        </a>    
                                    </li>
                                    <li{!!Helper::isMenuActive ($page_active,'kota',' class="active"')!!}>
                                        <a href="{{route('kota.index')}}" title="Kota">
                                            <i class="icon-earth"></i> KOTA
                                        </a>    
                                    </li>
                                    <li{!!Helper::isMenuActive ($page_active,'kecamatan',' class="active"')!!}>
                                        <a href="{{route('kecamatan.index')}}" title="Kecamatan">
                                            <i class="icon-earth"></i> KECAMATAN
                                        </a>    
                                    </li>
                                    <li{!!Helper::isMenuActive ($page_active,'desa',' class="active"')!!}>
                                        <a href="{{route('desa.index')}}" title="Desa / Kelurahan">
                                            <i class="icon-earth"></i> DESA / KELURAHAN
                                        </a>    
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-link2"></i> ANEKA DATA</span>
                                <ul class="menu-list">
                                    <li{!!Helper::isMenuActive ($page_active,'sumberdana',' class="active"')!!}>
                                        <a href="{{route('sumberdana.index')}}" title="SUMBER DANA">
                                            <i class="icon-link"></i> SUMBER DANA
                                        </a>    
                                    </li>
                                    <li{!!Helper::isMenuActive ($page_active,'paguanggaranopd',' class="active"')!!}>
                                        <a href="{{route('paguanggaranopd.index')}}" title="Pagu Anggaran OPD/SKPD">
                                            <i class="icon-link"></i> PAGU ANGGARAN OPD/SKPD
                                        </a>    
                                    </li>
                                    <li{!!Helper::isMenuActive ($page_active,'paguanggarandewan',' class="active"')!!}>
                                        <a href="{{route('paguanggarandewan.index')}}" title="Pagu Anggaran Anggota Dewan">
                                            <i class="icon-link"></i> PAGU ANGGARAN ANGGOTA DEWAN
                                        </a>    
                                    </li>
                                    <li{!!Helper::isMenuActive ($page_active,'ta',' class="active"')!!}>
                                        <a href="{{route('ta.index')}}" title="TAHUN">
                                            <i class="icon-calendar2"></i> TAHUN PERENCANAAN / ANGGARAN
                                        </a>    
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </li>            
            @endhasrole
            @hasrole('superadmin|bapelitbang')
            <li class="dropdown mega-menu mega-menu-wide visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-airplane3 position-left"></i> RPJMD <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="row">         
                            <div class="col-md-3">
                                <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-strategy"></i> BAB II & III && IV</span>
                                <ul class="menu-list">                                
                                    @can('browse_kelompokurusan')
                                    <li{!!Helper::isMenuActive ($page_active,'kelompokurusan',' class="active"')!!}>
                                        <a href="{{route('kelompokurusan.index')}}" title="Data Kelompok Urusan">
                                            <i class="icon-chess-queen"></i> <span class="text-violet"><strong>[1].</strong></span> KELOMPOK URUSAN
                                        </a>
                                    </li>    
                                    @endcan                      
                                    @can('browse_urusan')              
                                    <li{!!Helper::isMenuActive ($page_active,'urusan',' class="active"')!!}>
                                        <a href="{{route('urusan.index')}}" title="Data Urusan">
                                            <i class="icon-chess-king"></i> <span class="text-violet"><strong>[2].</strong></span>  URUSAN
                                        </a>
                                    </li>    
                                    @endcan
                                    <li{!!Helper::isMenuActive ($page_active,'organisasi',' class="active"')!!}>
                                        <a href="{{route('organisasi.index')}}" title="Data Organisasi">
                                            <i class="icon-office"></i> <span class="text-violet"><strong>[2.1].</strong></span> ORGANISASI
                                        </a>
                                    </li>  
                                    <li{!!Helper::isMenuActive ($page_active,'suborganisasi',' class="active"')!!}>
                                        <a href="{{route('suborganisasi.index')}}" title="Data Organisasi">
                                            <i class="icon-office"></i> <span class="text-violet"><strong>[2.1.1].</strong></span> UNIT KERJA
                                        </a>
                                    </li>                                
                                    <li{!!Helper::isMenuActive ($page_active,'program',' class="active"')!!}>
                                        <a href="{{route('program.index')}}" title="Data Program">
                                            <i class="icon-codepen"></i> <span class="text-violet"><strong>[2.2].</strong></span> PROGAM
                                        </a>
                                    </li>      
                                </ul>
                                <span class="menu-heading underlined bg-blue-300" style="padding:10px">
                                    <i class="icon-link2"></i> MAPPING
                                </span>
                                <ul class="menu-list">
                                    <li{!!Helper::isMenuActive ($page_active,'mappingprogramtoopd',' class="active"')!!}>
                                        <a href="{{route('mappingprogramtoopd.index')}}" title="Data Program Kegiatan">
                                            <i class="icon-link"></i> PROGRAM -> OPD / SKPD <span class="text-violet"><strong>[3]</strong></span>
                                        </a>    
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-strategy"></i> BAB V</span>
                                <ul class="menu-list">
                                    <li{!!Helper::isMenuActive ($page_active,'rpjmdvisi',' class="active"')!!}>
                                        <a href="{{route('rpjmdvisi.index')}}">
                                            <i class="icon-strategy"></i> VISI <span class="text-violet"><strong>[1]</strong></span>
                                        </a>
                                    </li> 
                                    <li{!!Helper::isMenuActive ($page_active,'rpjmdmisi',' class="active"')!!}>
                                        <a href="{{route('rpjmdmisi.index')}}">
                                            <i class="icon-strategy"></i> MISI <span class="text-violet"><strong>[2]</strong></span>
                                        </a>
                                    </li>     
                                    <li{!!Helper::isMenuActive ($page_active,'rpjmdtujuan',' class="active"')!!}>
                                        <a href="{{route('rpjmdtujuan.index')}}">
                                            <i class="icon-strategy"></i> TUJUAN <span class="text-violet"><strong>[3]</strong></span>
                                        </a>
                                    </li>  
                                    <li{!!Helper::isMenuActive ($page_active,'rpjmdsasaran',' class="active"')!!}>
                                        <a href="{{route('rpjmdsasaran.index')}}">
                                            <i class="icon-strategy"></i> SASARAN <span class="text-violet"><strong>[4]</strong></span>
                                        </a>
                                    </li>                                       
                                </ul>
                            </div>  
                            <div class="col-md-3">
                                <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-strategy"></i> BAB VI</span>
                                <ul class="menu-list">                                     
                                    <li{!!Helper::isMenuActive ($page_active,'rpjmdstrategi',' class="active"')!!}>
                                        <a href="{{route('rpjmdstrategi.index')}}">
                                            <i class="icon-strategy"></i> STRATEGI <span class="text-violet"><strong>[5]</strong></span>
                                        </a>
                                    </li>           
                                    <li{!!Helper::isMenuActive ($page_active,'rpjmdkebijakan',' class="active"')!!}>
                                        <a href="{{route('rpjmdkebijakan.index')}}">
                                            <i class="icon-strategy"></i> ARAH KEBIJAKAN <span class="text-violet"><strong>[6]</strong></span>
                                        </a>
                                    </li>  
                                    <li{!!Helper::isMenuActive ($page_active,'rpjmdprogrampembangunan',' class="active"')!!}>
                                        <a href="{{route('rpjmdprogrampembangunan.index')}}">
                                            <i class="icon-strategy"></i> PRG. PEMBANGUNAN DAERAH <span class="text-violet"><strong>[7]</strong></span>
                                        </a>
                                    </li>                                
                                </ul>
                            </div>                                                        
                            <div class="col-md-3">
                                <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-strategy"></i> BAB VII</span>
                                <ul class="menu-list">                                
                                    <li{!!Helper::isMenuActive ($page_active,'rpjmdindikatorkinerja',' class="active"')!!}>
                                        <a href="{{route('rpjmdindikatorkinerja.index')}}">
                                            <i class="icon-strategy"></i> INDIKASI RENCANA PROGRAM <span class="text-violet"><strong>[8]</strong></span>
                                        </a>
                                    </li> 
                                </ul>
                            </div>                                                                              
                        </div>
                    </div>
                </div>
            </li>  
            @endhasrole      
            @hasrole('superadmin|bapelitbang|opd')
            <li class="dropdown mega-menu mega-menu-wide visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-airplane3 position-left"></i> RENSTRA <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="row">                                                       
                            <div class="col-md-3">
                                <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-strategy"></i> BAB IV</span>
                                <ul class="menu-list">                                   
                                    <li{!!Helper::isMenuActive ($page_active,'renstratujuan',' class="active"')!!}>
                                        <a href="{{route('renstratujuan.index')}}">
                                            <i class="icon-strategy"></i> TUJUAN <span class="text-violet"><strong>[1]</strong></span>
                                        </a>
                                    </li>  
                                    <li{!!Helper::isMenuActive ($page_active,'renstrasasaran',' class="active"')!!}>
                                        <a href="{{route('renstrasasaran.index')}}">
                                            <i class="icon-strategy"></i> SASARAN <span class="text-violet"><strong>[2]</strong></span>
                                        </a>
                                    </li>                                    
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-strategy"></i> BAB V</span>
                                <ul class="menu-list">
                                    <li{!!Helper::isMenuActive ($page_active,'renstrastrategi',' class="active"')!!}>
                                        <a href="{{route('renstrastrategi.index')}}">
                                            <i class="icon-strategy"></i> STRATEGI <span class="text-violet"><strong>[3]</strong></span>
                                        </a>
                                    </li>           
                                    <li{!!Helper::isMenuActive ($page_active,'renstrakebijakan',' class="active"')!!}>
                                        <a href="{{route('renstrakebijakan.index')}}">
                                            <i class="icon-strategy"></i> ARAH KEBIJAKAN <span class="text-violet"><strong>[4]</strong></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-strategy"></i> BAB VI</span>
                                <ul class="menu-list">
                                    <li{!!Helper::isMenuActive ($page_active,'programkegiatan',' class="active"')!!}>
                                        <a href="{{route('programkegiatan.index')}}" title="Data Program Kegiatan">
                                            <i class="icon-strategy"></i> KEGIATAN <span class="text-violet"><strong>[5]</strong></span>
                                        </a>    
                                    </li>
                                    <li{!!Helper::isMenuActive ($page_active,'renstraprogramkegiatanpendanaan',' class="active"')!!}>
                                        <a href="{{route('renstraprogramkegiatanpendanaan.index')}}">
                                            <i class="icon-strategy"></i> PRG., KEG., DAN PENDANAAN <span class="text-violet"><strong>[6]</strong></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>                        
                    </div>
                </div>
            </li>
            @endhasrole
            @hasrole('superadmin')
            <li class="dropdown mega-menu mega-menu-wide visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-airplane3 position-left"></i> RKPD <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="row">                                                       
                            <div class="col-md-3">
                                <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-strategy"></i> TRANSFER DATA</span>
                                <ul class="menu-list">                                   
                                    <li{!!Helper::isMenuActive ($page_active,'transferrkpdmurnitopembahasan1',' class="active"')!!}>
                                        <a href="{{route('transferrkpdmurnitopembahasan1.index')}}">
                                            <i class="icon-strategy"></i> RKPD MURNI --> PEMBAHASAN </span>
                                        </a>
                                    </li>     
                                    <li{!!Helper::isMenuActive ($page_active,'transferpembahasanrkpdtoperubahan2',' class="active"')!!}>
                                        <a href="{{route('transferpembahasanrkpdtoperubahan2.index')}}">
                                            <i class="icon-strategy"></i> PEMBASAHAN RKPD --> RKPD PERUBAHAN </span>
                                        </a>
                                    </li>                                
                                </ul>
                            </div>                            
                        </div>                        
                    </div>
                </div>
            </li>
            @endhasrole
            <li class="dropdown visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-airplane3 position-left"></i> ASPIRASI <span class="caret"></span>
                </a>
                <ul class="dropdown-menu width-450">
                    @hasrole('superadmin|bapelitbang|opd')
                    <li{!!Helper::isMenuActive ($page_active,'pemilikbansoshibah',' class="active"')!!}>
                        <a href="{{route('pemilikbansoshibah.index')}}" title="Pemilik Bantuan Sosial dan Hibah">
                            <i class="icon-strategy"></i>PEMILIK BANSOS DAN HIBAH</span>
                        </a>                                        
                    </li>                    
                    <li{!!Helper::isMenuActive ($page_active,'bansoshibah',' class="active"')!!}>
                        <a href="{{route('bansoshibah.index')}}" title="Bantuan Sosial dan Hibah">
                            <i class="icon-strategy"></i>BANTUAN SOSIAL DAN HIBAH</span>
                        </a>                                        
                    </li>                    
                    @endhasrole
                    <li class="dropdown-header">ANGGOTA DEWAN</li>
                    @hasrole('superadmin|bapelitbang')
                    <li{!!Helper::isMenuActive ($page_active,'pemilikpokokpikiran',' class="active"')!!}>
                        <a href="{{route('pemilikpokokpikiran.index')}}">
                            <i class="icon-strategy"></i> PEMILIK POKOK</span>
                        </a>
                    </li> 
                    @endhasrole
                    @hasrole('superadmin|bapelitbang|dewan')
                    <li{!!Helper::isMenuActive ($page_active,'pokokpikiran',' class="active"')!!}>
                        <a href="{{route('pokokpikiran.index')}}">
                            <i class="icon-strategy"></i> POKOK PIKIRAN</span>
                        </a>
                    </li>
                    @endhasrole
                    <li class="dropdown-header">MUSRENBANG</li>
                    @hasrole('superadmin|bapelitbang|desa')
                    <li{!!Helper::isMenuActive ($page_active,'aspirasimusrendesa',' class="active"')!!}>
                        <a href="{{route('aspirasimusrendesa.index')}}" title="Aspirasi Musrenbang Desa">
                            <i class="icon-arrow-down16"></i>MUSRENBANG DESA / KELURAHAN <span class="text-violet"><strong>[1]</strong></span>
                        </a>                                        
                    </li>                       
                    <li{!!Helper::isMenuActive ($page_active,'pembahasanmusrendesa',' class="active"')!!}>
                        <a href="{{route('pembahasanmusrendesa.index')}}" title="Pembahasan Musrenbang Desa">
                            <i class="icon-arrow-down16"></i>PEMBAHASAN MUSRENBANG DESA / KELURAHAN <span class="text-violet"><strong>[2]</strong></span>
                        </a>
                    </li>
                    @endhasrole
                    @hasrole('superadmin|bapelitbang|kecamatan')
                    <li{!!Helper::isMenuActive ($page_active,'aspirasimusrenkecamatan',' class="active"')!!}>
                        <a href="{{route('aspirasimusrenkecamatan.index')}}" title="Aspirasi Musrenbang Kecamatan">
                            <i class="icon-arrow-down16"></i>MUSRENBANG KECAMATAN <span class="text-violet"><strong>[3]</strong></span>
                        </a>                                        
                    </li>
                    <li{!!Helper::isMenuActive ($page_active,'pembahasanmusrenkecamatan',' class="active"')!!}>
                        <a href="{{route('pembahasanmusrenkecamatan.index')}}" title="Pembahasan Musrenbang Kecamatan">
                            <i class="icon-arrow-down16"></i>PEMBAHASAN MUSRENBANG KECAMATAN <span class="text-violet"><strong>[4]</strong></span>
                        </a>
                    </li>
                    @endhasrole                   
                </ul>
            </li>            
            @unlessrole('dewan')      
            <li class="dropdown visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-sort-amount-desc position-left"></i> WORKFLOW <span class="caret"></span>
                </a>
                <ul class="dropdown-menu width-450">                     
                    @hasrole('superadmin|bapelitbang|opd|tapd')
                    <li class="dropdown-header">RENCANA KERJA (RENJA) OPD / SKPD</li>
                    <li{!!Helper::isMenuActive ($page_active,'usulanprarenjaopd',' class="active"')!!}>
                        <a href="{{route('usulanprarenjaopd.index')}}" title="Usulan Pra Renja OPD/SKPD">
                            <i class="icon-arrow-down16"></i>USULAN PRA RENJA OPD/SKPD {{HelperKegiatan::getTahunPerencanaan()}} <span class="text-violet"><strong>[1]</strong></span>
                        </a>                                        
                    </li>    
                    <li{!!Helper::isMenuActive ($page_active,'pembahasanprarenjaopd',' class="active"')!!}>
                        <a href="{{route('pembahasanprarenjaopd.index')}}" title="Pembahasan Pra Renja OPD/SKPD">
                            <i class="icon-arrow-down16"></i>PEMBAHASAN PRA RENJA OPD/SKPD {{HelperKegiatan::getTahunPerencanaan()}} <span class="text-violet"><strong>[2]</strong></span>
                        </a>                                        
                    </li>
                    <li{!!Helper::isMenuActive ($page_active,'usulanrakorbidang',' class="active"')!!}>
                        <a href="{{route('usulanrakorbidang.index')}}" title="Aspirasi Musrenbang Kecamatan">
                            <i class="icon-arrow-down16"></i>USULAN RAKOR BIDANG {{HelperKegiatan::getTahunPerencanaan()}} <span class="text-violet"><strong>[3]</strong></span>
                        </a>                                        
                    </li>
                    <li{!!Helper::isMenuActive ($page_active,'pembahasanrakorbidang',' class="active"')!!}>
                        <a href="{{route('pembahasanrakorbidang.index')}}" title="Pembahasan Rakor Bidang">
                            <i class="icon-arrow-down16"></i>PEMBAHASAN RAKOR BIDANG {{HelperKegiatan::getTahunPerencanaan()}} <span class="text-violet"><strong>[4]</strong></span>
                        </a>
                    </li>
                    <li{!!Helper::isMenuActive ($page_active,'usulanforumopd',' class="active"')!!}>
                        <a href="{{route('usulanforumopd.index')}}" title="Usulan Forum OPD/SKPD">
                            <i class="icon-arrow-down16"></i>USULAN FORUM OPD/SKPD {{HelperKegiatan::getTahunPerencanaan()}} <span class="text-violet"><strong>[5]</strong></span>
                        </a>                                        
                    </li>
                    <li{!!Helper::isMenuActive ($page_active,'pembahasanforumopd',' class="active"')!!}>
                        <a href="{{route('pembahasanforumopd.index')}}" title="Pembahasan Forum OPD/SKPD">
                            <i class="icon-arrow-down16"></i>PEMBAHASAN FORUM OPD/SKPD {{HelperKegiatan::getTahunPerencanaan()}} <span class="text-violet"><strong>[6]</strong></span>
                        </a>                                        
                    </li>
                    <li{!!Helper::isMenuActive ($page_active,'usulanmusrenkab',' class="active"')!!}>
                        <a href="{{route('usulanmusrenkab.index')}}" title="Usulan Musrenbang Kabupaten">
                            <i class="icon-arrow-down16"></i>USULAN MUSRENBANG KAB. {{HelperKegiatan::getTahunPerencanaan()}} <span class="text-violet"><strong>[7]</strong></span>
                        </a>                                        
                    </li>
                    <li{!!Helper::isMenuActive ($page_active,'pembahasanmusrenkab',' class="active"')!!}>
                        <a href="{{route('pembahasanmusrenkab.index')}}" title="Pembahasan Musrenbang Kabupaten">
                            <i class="icon-arrow-down16"></i>PEMBAHASAN MUSRENBANG KAB. {{HelperKegiatan::getTahunPerencanaan()}} <span class="text-violet"><strong>[8]</strong></span>
                        </a>
                    </li>
                    @hasrole('superadmin|bapelitbang|tapd')
                    <li{!!Helper::isMenuActive ($page_active,'verifikasirenja',' class="active"')!!}>
                        <a href="{{route('verifikasirenja.index')}}" title="verifikasi tapd">
                            <i class="icon-arrow-down16"></i>VERIFIKASI TAPD {{HelperKegiatan::getTahunPerencanaan()}} <span class="text-violet"><strong>[9]</strong></span>
                        </a>                                        
                    </li>  
                    @endhasrole
                    <li class="dropdown-header">RENCANA KERJA PEMERINTAH (RKP) DAERAH </li>
                    <li{!!Helper::isMenuActive ($page_active,'rkpdmurni',' class="active"')!!}>
                        <a href="{{route('rkpdmurni.index')}}" title="RENCANA KERJA PERANGKAT DAERAH">
                            <i class="icon-arrow-down16"></i>RKPD MURNI {{HelperKegiatan::getTahunPerencanaan()}} <span class="text-violet"><strong>[10]</strong></span>
                        </a>                                        
                    </li>
                    <li{!!Helper::isMenuActive ($page_active,'pembahasanrkpd',' class="active"')!!}>
                        <a href="{{route('pembahasanrkpd.index')}}" title="PEMBAHASAN RKPD">
                            <i class="icon-arrow-down16"></i>PEMBAHASAN RKPD MURNI {{HelperKegiatan::getTahunPerencanaan()}} <span class="text-violet"><strong>[11]</strong></span>
                        </a>                                        
                    </li>
                    <li{!!Helper::isMenuActive ($page_active,'rkpdperubahan',' class="active"')!!}>
                        <a href="{{route('rkpdperubahan.index')}}" title="RENCANA KERJA PERANGKAT DAERAH PERUBAHAN">
                            <i class="icon-arrow-down16"></i>RKPD PERUBAHAN {{HelperKegiatan::getTahunPerencanaan()}} <span class="text-violet"><strong>[12]</strong></span>
                        </a>                                        
                    </li>
                    <li{!!Helper::isMenuActive ($page_active,'pembahasanrkpdp',' class="active"')!!}>
                        <a href="{{route('pembahasanrkpdp.index')}}" title="PEMBAHASAN RKPD PERUBAHAN">
                            <i class="icon-arrow-down16"></i>PEMBAHASAN RKPD PERUBAHAN {{HelperKegiatan::getTahunPerencanaan()}} <span class="text-violet"><strong>[13]</strong></span>
                        </a>                                        
                    </li>
                    @endhasrole
                </ul>
            </li>   
            @endunlessrole
            <li class="dropdown mega-menu mega-menu-wide visible">                
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-file-empty position-left"></i> LAPORAN <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="row">
                            <div class="col-md-3">
                                <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-file-empty"></i> RENCANA KERJA OPD / SKPD</span>
                                <ul class="menu-list">
                                    <li{!!Helper::isMenuActive ($page_active,'reportprarenja',' class="active"')!!}>
                                        <a href="#">
                                            <i class="icon-file-empty"></i> PRA RENJA</span>
                                        </a>
                                    </li> 
                                    <li{!!Helper::isMenuActive ($page_active,'reportrakorbidang',' class="active"')!!}>
                                        <a href="#">
                                            <i class="icon-file-empty"></i> RAKOR BIDANG</span>
                                        </a>
                                    </li>
                                    <li{!!Helper::isMenuActive ($page_active,'reportforumopd',' class="active"')!!}>
                                        <a href="#">
                                            <i class="icon-file-empty"></i> FORUM OPD</span>
                                        </a>
                                    </li>
                                    <li{!!Helper::isMenuActive ($page_active,'reportmusrenkab',' class="active"')!!}>
                                        <a href="#">
                                            <i class="icon-file-empty"></i> MUSRENBANG KABUPATEN</span>
                                        </a>
                                    </li>
                                    <li{!!Helper::isMenuActive ($page_active,'reportverifikasitapd',' class="active"')!!}>
                                        <a href="#">
                                            <i class="icon-file-empty"></i> VERIFIKASI TAPD</span>
                                        </a>
                                    </li>
                                </ul>   
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-file-empty"></i> RENCANA KERJA PEMERINTAH DAERAH</span>
                                <ul class="menu-list">
                                    <li class="dropdown-header">MURNI</li>
                                    <li{!!Helper::isMenuActive ($page_active,'reportrkpdmurniopd',' class="active"')!!}>
                                        <a href="{{route('reportrkpdmurniopd.index')}}">
                                            <i class="icon-file-empty"></i> RKPD OPD</span>
                                        </a>
                                    </li> 
                                    <li{!!Helper::isMenuActive ($page_active,'reportpembahasanrkpdmurniopd',' class="active"')!!}>
                                        <a href="{{route('reportpembahasanrkpdmurniopd.index')}}">
                                            <i class="icon-file-empty"></i> PEMBAHASAN RKPD OPD</span>
                                        </a>
                                    </li> 
                                    <li{!!Helper::isMenuActive ($page_active,'reportprogrammurniopd',' class="active"')!!}>
                                        <a href="{{route('reportprogrammurniopd.index')}}">
                                            <i class="icon-file-empty"></i> PROGRAM RKPD OPD</span>
                                        </a>
                                    </li>  
                                    <li class="dropdown-header">PERUBAHAN</li>
                                    <li{!!Helper::isMenuActive ($page_active,'reportrkpdperubahanopd',' class="active"')!!}>
                                        <a href="{{route('reportrkpdperubahanopd.index')}}">
                                            <i class="icon-file-empty"></i> RKPD PERUBAHAN OPD</span>
                                        </a>
                                    </li> 
                                    <li{!!Helper::isMenuActive ($page_active,'reportpembahasanrkpdperubahanopd',' class="active"')!!}>
                                        <a href="{{route('reportrkpdperubahanopd.index')}}">
                                            <i class="icon-file-empty"></i> PEMBAHASAN RKPD PERUBAHAN OPD</span>
                                        </a>
                                    </li> 
                                    <li{!!Helper::isMenuActive ($page_active,'reportprogramperubahanopd',' class="active"')!!}>
                                        <a href="{{route('reportprogramperubahanopd.index')}}">
                                            <i class="icon-file-empty"></i> PROGRAM RKPD PER. OPD</span>
                                        </a>
                                    </li>
                                </ul>   
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            @hasrole('superadmin|bapelitbang')
            <li class="dropdown mega-menu mega-menu-wide visible">                
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-cogs position-left"></i> SETTING <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-content">
                        <div class="dropdown-content-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-users"></i> PENGGUNA</span>
                                    <ul class="menu-list">
                                        @hasrole('superadmin') 
                                        <li class="dropdown-header">ATRIBUT USER</li>                     
                                        <li{!!Helper::isMenuActive ($page_active,'permissions',' class="active"')!!}>
                                            <a href="{!!route('permissions.index')!!}">
                                                <i class="icon-user"></i> PERMISSIONS
                                            </a>
                                        </li> 
                                        <li{!!Helper::isMenuActive ($page_active,'roles',' class="active"')!!}>
                                            <a href="{!!route('roles.index')!!}">
                                                <i class="icon-user"></i> ROLES
                                            </a>
                                        </li>
                                        @endhasrole	
                                        @hasrole('superadmin')
                                        <li class="dropdown-header">USERS</li>
                                        <li{!!Helper::isMenuActive ($page_active,'users',' class="active"')!!}>
                                            <a href="{!!route('users.index')!!}">
                                                <i class="icon-user"></i> SUPER ADMIN
                                            </a>
                                        </li>
                                        @endhasrole	
                                        @hasrole('superadmin|bapelitbang')
                                        <li{!!Helper::isMenuActive ($page_active,'usersbapelitbang',' class="active"')!!}>
                                            <a href="{!!route('usersbapelitbang.index')!!}">
                                                <i class="icon-user"></i> BAPELITBANG
                                            </a>
                                        </li>
                                        <li{!!Helper::isMenuActive ($page_active,'usersopd',' class="active"')!!}>
                                            <a href="{!!route('usersopd.index')!!}">
                                                <i class="icon-user"></i> OPD
                                            </a>
                                        </li>
                                        <li{!!Helper::isMenuActive ($page_active,'usersdewan',' class="active"')!!}>
                                            <a href="{!!route('usersdewan.index')!!}">
                                                <i class="icon-user"></i> DEWAN
                                            </a>
                                        </li>
                                        <li{!!Helper::isMenuActive ($page_active,'userskecamatan',' class="active"')!!}>
                                            <a href="{!!route('userskecamatan.index')!!}">
                                                <i class="icon-user"></i> KECAMATAN
                                            </a>
                                        </li>
                                        <li{!!Helper::isMenuActive ($page_active,'usersdesa',' class="active"')!!}>
                                            <a href="{!!route('usersdesa.index')!!}">
                                                <i class="icon-user"></i> DESA / KELURAHAN
                                            </a>
                                        </li>
                                        @endhasrole	
                                    </ul>
                                </div>
                                <div class="col-md-3">
                                    <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-database4"></i> PENYIMPANAN</span>
                                    <ul class="menu-list">
                                        @hasrole('superadmin')    
                                        <li class="dropdown-header">DATA EPLANNING</li>                
                                        <li{!!Helper::isMenuActive ($page_active,'copydata',' class="active"')!!}>
                                            <a href="{!!route('copydata.index')!!}">
                                                <i class="icon-copy3"></i> COPY DATA
                                            </a>
                                        </li>  
                                        <li class="dropdown-header">CACHE</li>                
                                        <li{!!Helper::isMenuActive ($page_active,'clearcache',' class="active"')!!}>
                                            <a href="{!!route('clearcache.index')!!}">
                                                <i class="icon-database-refresh"></i> CLEAR CACHE
                                            </a>
                                        </li> 
                                        <li class="dropdown-header">LOG</li>                
                                        <li{!!Helper::isMenuActive ($page_active,'logviewer',' class="active"')!!}>
                                            <a href="{!!route('logviewer.index')!!}">
                                                <i class="icon-history"></i> LOG
                                            </a>
                                        </li> 
                                        @endhasrole	
                                    </ul>
                                </div>
                                <div class="col-md-3">
                                    <span class="menu-heading underlined bg-blue-300" style="padding:10px"><i class="icon-wrench"></i> KONFIGURASI</span>
                                    <ul class="menu-list">
                                        @hasrole('superadmin')                  
                                        <li{!!Helper::isMenuActive ($page_active,'environment',' class="active"')!!}>
                                            <a href="{!!route('environment.index')!!}">
                                                <i class="icon-file-text"></i> .env
                                            </a>
                                        </li>                                          
                                        @endhasrole	
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            @endhasrole
        </ul>
    </div>
</div>
<!-- /second navbar -->