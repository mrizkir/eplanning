@extends('layouts.limitless.l_main')
@section('page_title')
    USULAN PRA RENJA OPD/SKPD
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        USULAN PRA RENJA OPD/SKPD TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.usulanprarenjaopd.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">ASPIRASI / USULAN</a></li>
    <li><a href="{!!route('usulanprarenjaopd.index')!!}">USULAN PRA RENJA OPD/SKPD</a></li>
    <li class="active">TAMBAH DATA RINCIAN KEGIATAN DARI MUSRENBANG KECAMATAN</li>
@endsection
@section('page_sidebar')
    @include('layouts.limitless.l_sidebar_prarenja')
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                TAMBAH DATA RINCIAN KEGIATAN DARI MUSRENBANG KECAMATAN
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>               
                        <a href="{!!route('usulanprarenjaopd.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'RKPD\UsulanPraRenjaOPDController@store2','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                              
                
            {!! Form::close()!!}
        </div>
    </div>
    <div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
        <div class="panel-heading">
            <div class="panel-title">
                <h5>DAFTAR RINCIAN KEGIATAN</h5>
            </div>
            <div class="heading-elements">
                
            </div>
        </div>
    </div>
</div>   
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">

</script>
@endsection