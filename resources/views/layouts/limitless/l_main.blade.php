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
                    Saat ini Anda berada di T.A untuk Perencanaan {{config('globalsettings.tahun_perencanaan')}} dan Penyerapan T.A {{config('globalsettings.tahun_penyerapan')}}
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
            <li>
                <a href="{!!route('dashboard.index')!!}">
                    <i class="icon-display4 position-left"></i> 
                    <span>DASHBOARD</span>											
                </a>   
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
                                    <li>
                                        <a href="{{route('kelompokurusan.index')}}" title="Kelompok Urusan">
                                            <i class="icon-home9"></i> Kelompok Urusan
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
                    <i class="icon-puzzle4 position-left"></i> PERENCANAAN <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="row">
                            <div class="col-md-3">
                                <span class="menu-heading underlined"><i class="icon-office"></i> ASPIRASI/USULAN</span>
                                <ul class="menu-list">                                   
                                    <li>
                                        <a href="{{route('kelompokurusan.index')}}" title="Kelompok Urusan">
                                            <i class="icon-home9"></i> FORUM SKPD
                                        </a>
                                    </li>                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="dropdown visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-cogs position-left"></i> SETTING <span class="caret"></span>
                </a>
                <ul class="dropdown-menu width-200">
                    @hasrole('superadmin') 
                    <li class="dropdown-header">USER</li>                    
                    <li>
                        <a href="{!!route('permissions.index')!!}">
                            <i class="icon-user"></i> PERMISSIONS
                        </a>
                    </li> 
                    <li>
                        <a href="{!!route('roles.index')!!}">
                            <i class="icon-user"></i> ROLES
                        </a>
                    </li>
                    <li>
                        <a href="{!!route('users.index')!!}">
                            <i class="icon-user"></i> BAPPEDA
                        </a>
                    </li>
                    <li>
                        <a href="{!!route('users.index')!!}">
                            <i class="icon-user"></i> OPD
                        </a>
                    </li>
                    <li>
                        <a href="{!!route('users.index')!!}">
                            <i class="icon-user"></i> DEWAN
                        </a>
                    </li>
                    @endhasrole	
                </ul>
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