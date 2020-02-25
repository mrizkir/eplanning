@extends('layouts.limitless.l_main')
@section('page_title')
    LAPORAN KEGIATAN POKIR DI OPD
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        LAPORAN KEGIATAN POKIR DI OPD TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}  
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.report.reportkegiatanpokiropd.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">LAPORAN</a></li>
    <li><a href="#">ASPIRASI</a></li>
    <li class="active">LAPORAN KEGIATAN POKIR DI OPD </li>
@endsection
@section('page_content')
<div class="row">
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.report.reportkegiatanpokiropd.datatable')
    </div>        
</div>
@endsection