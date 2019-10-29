@extends('layouts.limitless.l_main')
@section('page_title')
    DASHBOARD TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}  
@endsection
@section('page_header')
    <i class="icon-display position-left"></i> 
    <span class="text-semibold"> 
        DASHBOARD TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}  
    </span>
@endsection
@section('page_breadcrumb')
    <li><a href="#">DASHBOARD</a></li>
@endsection
@section('page_info')
    @include('pages.limitless.dashboard.info')
@endsection
@section('page_content')
<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="panel panel-body bg-green-700 has-bg-image">
            <div class="media no-margin">
                <div class="media-body">
                    <h3 class="no-margin">{{Helper::formatAngka($data['jumlahkegiatan'])}}</h3>
                    <span class="text-uppercase text-size-mini">JUMLAH KEGIATAN KESELURUHAN</span>
                </div>
                <div class="media-right media-middle">
                    <i class="icon-bubbles4 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>        
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="panel panel-body bg-orange-700 has-bg-image">
            <div class="media no-margin">
                <div class="media-body">
                    <h3 class="no-margin">{{Helper::formatUang($data['pagum'])}}</h3>
                    <span class="text-uppercase text-size-mini">PAGU DANA M</span>
                </div>

                <div class="media-right media-middle">
                    <i class="icon-bubbles4 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>        
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="panel panel-body bg-green-700 has-bg-image">
            <div class="media no-margin">
                <div class="media-body">
                    <h3 class="no-margin">{{Helper::formatUang($data['pagup'])}}</h3>
                    <span class="text-uppercase text-size-mini">PAGU DANA P</span>
                </div>

                <div class="media-right media-middle">
                    <i class="icon-bubbles4 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>        
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="panel panel-body bg-blue-400 has-bg-image">
            <div class="media no-margin">
                <div class="media-body">
                    <h3 class="no-margin">{{Helper::formatUang($data['totalrkpdm'])}}</h3>
                    <span class="text-uppercase text-size-mini">RKPD MURNI</span>
                </div>

                <div class="media-right media-middle">
                    <i class="icon-bubbles4 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>        
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="panel panel-body bg-blue-400 has-bg-image">
            <div class="media no-margin">
                <div class="media-body">
                    <h3 class="no-margin">{{Helper::formatUang($data['totalrkpdp'])}}</h3>
                    <span class="text-uppercase text-size-mini">RKPD PERUBAHAN</span>
                </div>

                <div class="media-right media-middle">
                    <i class="icon-bubbles4 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>        
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="panel panel-body bg-blue-400 has-bg-image">
            <div class="media no-margin">
                <div class="media-body">
                    <h3 class="no-margin">{{Helper::formatUang($data['totalrkpdp']-$data['totalrkpdm'])}}</h3>
                    <span class="text-uppercase text-size-mini">SELISIH RKPD P & M</span>
                </div>

                <div class="media-right media-middle">
                    <i class="icon-bubbles4 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>        
    </div>
</div>
@endsection