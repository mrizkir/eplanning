@extends('layouts.limitless.l_main')
@section('page_title')
    PEMBAHASAN FORUM OPD / SKPD
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        PEMBAHASAN FORUM OPD / SKPD TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.pembahasanforumopd.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('pembahasanforumopd.index')!!}">PEMBAHASAN FORUM OPD / SKPD</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA PEMBAHASAN FORUM OPD / SKPD
                </h5>
                <div class="heading-elements">   
                    
                </div>
            </div>
            <div class="panel-body">
                
            </div>
        </div>
    </div>
</div>
@endsection