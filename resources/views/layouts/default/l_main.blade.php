@include('layouts.default.l_header')
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">          
        <a href="{{route('dashboard.index')}}" class="logo">
            <span class="logo-mini"><b>{{config('app.name')}}</b></span>
            <span class="logo-lg"><b>{{config('app.name')}}</b></span>
        </a>              
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-success">0</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">Anda punya 0 pesan</li>
                            <li>
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <p>Belum ada pesan baru</p>                                        
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="#">Lihat semuanya</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">0</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">Anda punya 0 notifikasi</li>
                            <li>
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            Belum ada notif baru
                                        </a>
                                    </li>                                                             
                                </ul>
                            </li>
                            <li class="footer"><a href="#">Lihat semuanya</a></li>
                        </ul>
                    </li>                        
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{!!asset(Auth::user()->foto)!!}" class="user-image" alt="{{Auth::user()->username}}">
                            <span class="hidden-xs">{{Auth::user()->username}}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="{!!asset(Auth::user()->foto)!!}" class="img-circle" alt="{{Auth::user()->username}}">              
                                <p>
                                    {{Auth::user()->username}} - {{Auth::user()->role_name}}
                                    <small>Terdaftar sejak,  {{Auth::user()->created_at->format('M Y')}}</small>
                                </p>
                            </li>
                            <li class="user-body">
                                
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>            
        </nav>
    </header>
    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{!!asset(Auth::user()->foto)!!}" class="img-circle" alt="{{Auth::user()->username}}">
                </div>
                <div class="pull-left info">
                    <p>{{Auth::user()->username}}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <form action="#" method="post" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                        <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </form>
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                <li>
                    <a href="{!!route('dashboard.index')!!}">
                        <i class="fa fa-dashboard"></i> 
                        <span>DASHBOARD</span>
                    </a>
                </li>
                <li class="header">SETTING</li>
                @hasrole('superadmin') 
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-users"></i> <span>USERS</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        @role('superadmin')
                        <li>
                            <a href="{!!route('permissions.index')!!}">
                                <i class="fa fa-circle-o"></i> PERMISSION
                            </a>
                        </li>
                        <li>
                            <a href="{!!route('roles.index')!!}">
                                <i class="fa fa-circle-o"></i> ROLES
                            </a>
                        </li>
                        @endrole
                        <li>
                            <a href="{!!route('users.index')!!}">
                                <i class="fa fa-circle-o"></i> USERS
                            </a>
                        </li>
                    </ul>
                </li>       
                @endhasrole	         
            </ul>       
        </section>
    </aside>                  
    <div class="content-wrapper">
        @yield('page-info')
        <section class="content-header">
            <h1>
                @yield('page_header')                
            </h1>
            <ol class="breadcrumb">
                <li><a href="#">
                    <i class="fa fa-dashboard"></i> HOME</a>
                </li>
                @yield('page_breadcrumb') 
            </ol>
        </section>              
        <section class="content">    
            @include('layouts.default.l_formmessages')           
            @yield('page_content')
        </section>                  
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.0.0            
        </div>
        <strong>Copyright &copy; 2018-2019 <a href="http://bintankab.go.id">TIM IT KAB. BINTAN</a>.</strong> All rights reserved.
    </footer>
    @yield('page_sidebar')    
</div>              
@include('layouts.default.l_footer')