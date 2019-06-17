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
                    Saat ini Anda berada di Tahun Perencanaan {{HelperKegiatan::getTahunPerencanaan()}} dan Penyerapan Anggaran Tahun {{HelperKegiatan::getTahunPenyerapan()}}
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
                    <li>
                        <a href="{{route('users.profil',['id'=>Auth::user()->id])}}">
                            <i class="icon-user-plus"></i> My profile
                        </a>                        
                    </li>                    
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