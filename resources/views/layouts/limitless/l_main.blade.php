@include('layouts.limitless.l_header')
<body>
<div class="navbar navbar-inverse bg-blue-300 navbar-lg">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{route('dashboard.index')}}">{{config('app.name')}}</a>
        <ul class="nav navbar-nav pull-right visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    </div>
    <div class="navbar-collapse collapse" id="navbar-mobile">   
        <p class="navbar-text">
            <a href="#">
                <span class="label bg-success-400">
                    Saat ini Anda berada di Tahun Perencanaan {{config('globalsettings.tahun_perencanaan')}} dan Penyerapan Anggaran Tahun {{config('globalsettings.tahun_penyerapan')}}
                </span>
            </a>
        </p>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown dropdown-user visible">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{!!asset(Auth::user()->foto)!!}" alt="{{Auth::user()->username}}">
                    <span>{{Auth::user()->username}}</span>
                    <i class="caret"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="#"><i class="icon-user-plus"></i> My profile</a></li>                    
                    <li class="divider"></li>                    
                    <li>                       
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="icon-switch2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->
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
                                <span class="menu-heading underlined"><i class="icon-map4"></i> RINGKASAN UMUM</span>
                                <ul class="menu-list">
                                    <li{!!Helper::isMenuActive ($page_active,'dashboard',' class="active"')!!}>
                                        <a href="{!!route('dashboard.index')!!}">
                                            <i class="icon-display"></i> PERENCANAAN DAN REALISASI 											
                                        </a>   
                                    </li> 
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined"><i class="icon-map4"></i> PERENCANAAN</span>
                                <ul class="menu-list">
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>                
            </li>            
            <li class="dropdown mega-menu mega-menu-wide visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-puzzle4 position-left"></i> MASTERS <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="row">                            
                            <div class="col-md-3">
                                <span class="menu-heading underlined"><i class="icon-office"></i> DATA</span>
                                <ul class="menu-list">
                                    @can('browse_kelompokurusan')
                                    <li{!!Helper::isMenuActive ($page_active,'kelompokurusan',' class="active"')!!}>
                                        <a href="{{route('kelompokurusan.index')}}" title="Data Kelompok Urusan">
                                            <i class="icon-chess-queen"></i> KELOMPOK URUSAN <span class="text-violet"><strong>[1]</strong></span>
                                        </a>
                                    </li>    
                                    @endcan                      
                                    @can('browse_urusan')              
                                    <li{!!Helper::isMenuActive ($page_active,'urusan',' class="active"')!!}>
                                        <a href="{{route('urusan.index')}}" title="Data Urusan">
                                            <i class="icon-chess-king"></i> URUSAN <span class="text-violet"><strong>[2]</strong></span>
                                        </a>
                                    </li>    
                                    @endcan
                                    <li{!!Helper::isMenuActive ($page_active,'organisasi',' class="active"')!!}>
                                        <a href="{{route('organisasi.index')}}" title="Data Organisasi">
                                            <i class="icon-office"></i> ORGANISASI <span class="text-violet"><strong>[2.1]</strong></span>
                                        </a>
                                    </li>  
                                    <li{!!Helper::isMenuActive ($page_active,'suborganisasi',' class="active"')!!}>
                                        <a href="{{route('suborganisasi.index')}}" title="Data Organisasi">
                                            <i class="icon-office"></i> UNIT KERJA <span class="text-violet"><strong>[2.1.1]</strong></span>
                                        </a>
                                    </li>                                
                                    <li{!!Helper::isMenuActive ($page_active,'program',' class="active"')!!}>
                                        <a href="{{route('program.index')}}" title="Data Program">
                                            <i class="icon-codepen"></i> PROGAM <span class="text-violet"><strong>[2.2]</strong></span>
                                        </a>
                                    </li>
                                    <li{!!Helper::isMenuActive ($page_active,'programkegiatan',' class="active"')!!}>
                                        <a href="{{route('programkegiatan.index')}}" title="Data Program Kegiatan">
                                            <i class="icon-code"></i> KEGIATAN <span class="text-violet"><strong>[2.2.1]</strong></span>
                                        </a>    
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined"><i class="icon-link2"></i> MAPPING</span>
                                <ul class="menu-list">
                                    <li{!!Helper::isMenuActive ($page_active,'mappingprogramtoopd',' class="active"')!!}>
                                        <a href="{{route('mappingprogramtoopd.index')}}" title="Data Program Kegiatan">
                                            <i class="icon-link"></i> PROGRAM -> OPD / SKPD <span class="text-violet"><strong>[3]</strong></span>
                                        </a>    
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined"><i class="icon-link2"></i> ANEKA DATA</span>
                                <ul class="menu-list">
                                    <li{!!Helper::isMenuActive ($page_active,'paguanggaranopd',' class="active"')!!}>
                                        <a href="{{route('paguanggaranopd.index')}}" title="Pagu Anggaran OPD/SKPD">
                                            <i class="icon-link"></i> PAGU ANGGARAN OPD/SKPD
                                        </a>    
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </li>            
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-strategy position-left"></i> RPJMD <span class="caret"></span>
                </a>
                <ul class="dropdown-menu width-350">   
                    <li{!!Helper::isMenuActive ($page_active,'rpjmdmisi',' class="active"')!!}>
                        <a href="{{route('rpjmdmisi.index')}}">
                            <i class="icon-align-center-horizontal"></i> MISI
                        </a>
                    </li>     
                    <li{!!Helper::isMenuActive ($page_active,'rpjmdtujuan',' class="active"')!!}>
                        <a href="{{route('rpjmdtujuan.index')}}">
                            <i class="icon-align-center-horizontal"></i> TUJUAN
                        </a>
                    </li>  
                    <li{!!Helper::isMenuActive ($page_active,'rpjmdsasaran',' class="active"')!!}>
                        <a href="{{route('rpjmdsasaran.index')}}">
                            <i class="icon-align-center-horizontal"></i> SASARAN
                        </a>
                    </li>   
                    <li{!!Helper::isMenuActive ($page_active,'rpjmdstrategi',' class="active"')!!}>
                        <a href="{{route('rpjmdstrategi.index')}}">
                            <i class="icon-align-center-horizontal"></i> STRATEGI
                        </a>
                    </li>           
                    <li{!!Helper::isMenuActive ($page_active,'rpjmdkebijakan',' class="active"')!!}>
                        <a href="{{route('rpjmdkebijakan.index')}}">
                            <i class="icon-align-center-horizontal"></i> PRIORITAS KEBIJAKAN
                        </a>
                    </li>  
                    <li{!!Helper::isMenuActive ($page_active,'rpjmdindikatorkinerja',' class="active"')!!}>
                        <a href="{{route('rpjmdindikatorkinerja.index')}}">
                            <i class="icon-align-center-horizontal"></i> INDIKASI RENCANA PROGRAM
                        </a>
                    </li>                  
                </ul>
            </li>
            <li class="dropdown mega-menu mega-menu-wide visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-airplane3 position-left"></i> WORKFLOW <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="row">
                            <div class="col-md-3">
                                <span class="menu-heading underlined"><i class="icon-people"></i> ASPIRASI / USULAN</span>
                                <ul class="menu-list">                                   
                                    <li{!!Helper::isMenuActive ($page_active,'aspirasimusrendesa',' class="active"')!!}>
                                        <a href="{{route('aspirasimusrendesa.index')}}" title="Aspirasi Musrenbang Desa">
                                            <i class="icon-cube"></i>A. MUSRENBANG DESA / KELURAHAN <span class="text-violet"><strong>[1]</strong></span>
                                        </a>                                        
                                    </li>     
                                    <li{!!Helper::isMenuActive ($page_active,'usulanprarenjaopd',' class="active"')!!}>
                                        <a href="{{route('usulanprarenjaopd.index')}}" title="Usulan Pra Renja OPD/SKPD">
                                            <i class="icon-cube"></i>U. PRA RENJA OPD/SKPD <span class="text-violet"><strong>[5]</strong></span>
                                        </a>                                        
                                    </li>                               
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined"><i class="icon-comment-discussion"></i> PEMBAHASAN</span>
                                <ul class="menu-list">                                   
                                    <li{!!Helper::isMenuActive ($page_active,'pembahasanmusrendesa',' class="active"')!!}>
                                        <a href="{{route('pembahasanmusrendesa.index')}}" title="Aspirasi Musrenbang Desa">
                                            <i class="icon-cube"></i>P. MUSRENBANG DESA / KELURAHAN <span class="text-violet"><strong>[2]</strong></span>
                                        </a>
                                    </li>  
                                    <li{!!Helper::isMenuActive ($page_active,'pembahasanprarenjaopd',' class="active"')!!}>
                                        <a href="{{route('pembahasanprarenjaopd.index')}}" title="Pembahasan Pra Renja OPD/SKPD">
                                            <i class="icon-cube"></i>P. PRA RENJA OPD/SKPD <span class="text-violet"><strong>[6]</strong></span>
                                        </a>                                        
                                    </li>                              
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined"><i class="icon-people"></i> ASPIRASI / USULAN</span>
                                <ul class="menu-list">                                                                                                          
                                    <li{!!Helper::isMenuActive ($page_active,'aspirasimusrenkecamatan',' class="active"')!!}>
                                        <a href="{{route('aspirasimusrenkecamatan.index')}}" title="Aspirasi Musrenbang Kecamatan">
                                            <i class="icon-cube"></i>A. MUSRENBANG KECAMATAN <span class="text-violet"><strong>[3]</strong></span>
                                        </a>                                        
                                    </li>                              
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined"><i class="icon-comment-discussion"></i> PEMBAHASAN</span>
                                <ul class="menu-list">
                                    <li{!!Helper::isMenuActive ($page_active,'pembahasanmusrenkecamatan',' class="active"')!!}>
                                        <a href="{{route('pembahasanmusrenkecamatan.index')}}" title="Aspirasi Musrenbang Kecamatan">
                                            <i class="icon-cube"></i>P. MUSRENBANG KECAMATAN <span class="text-violet"><strong>[4]</strong></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="dropdown mega-menu mega-menu-wide visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-airplane3 position-left"></i> MONEV <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="row">
                            <div class="col-md-3">
                                <span class="menu-heading underlined"><i class="icon-cart"></i> BELANJA MURNI</span>
                                
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined"><i class="icon-cart"></i> BELANJA PERUBAHAN</span>                                
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="dropdown mega-menu mega-menu-wide visible">                
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-cogs position-left"></i> SETTING <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-content">
                        <div class="dropdown-content-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <span class="menu-heading underlined"><i class="icon-users"></i> PENGGUNA</span>
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
                                        <li class="dropdown-header">USERS</li>
                                        <li{!!Helper::isMenuActive ($page_active,'users',' class="active"')!!}>
                                            <a href="{!!route('users.index')!!}">
                                                <i class="icon-user"></i> BAPPEDA
                                            </a>
                                        </li>
                                        <li{!!Helper::isMenuActive ($page_active,'usersopd',' class="active"')!!}>
                                            <a href="{!!route('usersopd.index')!!}">
                                                <i class="icon-user"></i> OPD
                                            </a>
                                        </li>
                                        <li{!!Helper::isMenuActive ($page_active,'dewan',' class="active"')!!}>
                                            <a href="{!!route('users.index')!!}">
                                                <i class="icon-user"></i> DEWAN
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
        </ul>
    </div>
</div>
<!-- /second navbar -->
<!-- Page header -->
<div class="page-header page-header-default border-bottom border-bottom-primary" style="border-top: 1px solid #ddd; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                @yield('page_header')
                @yield('page_info')
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li><a href="{!!route('dashboard.index')!!}">HOME</a></li>            
            @yield('page_breadcrumb') 
        </ul>
        @yield('page_breadcrumbelement') 
        @yield('page_headerelement')   
    </div>
</div>
<!-- /page header -->
<div class="page-container">    
    <div class="page-content">
        @yield('page_sidebar')
        <div class="content-wrapper">
            @include('layouts.limitless.l_formmessages') 
            @yield('page_content')
        </div>        
    </div>    
</div>
<!-- Footer -->
<div class="footer text-muted">
    {{config('app.name')}} Powered by <a href="http://bintankab.go.id">TIM IT KAB. BINTAN</a>
</div>           
@include('layouts.limitless.l_footer')