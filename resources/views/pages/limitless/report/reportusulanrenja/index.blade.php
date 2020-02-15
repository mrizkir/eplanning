@extends('layouts.limitless.l_main')
@section('page_title')
    {{$page_title}}
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        {{$page_title}} TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}  
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.report.reportusulanrenja.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">WORKFLOW</a></li>
    <li class="active">{{$page_title}}</li>
@endsection
@section('page_content')
<div class="row">
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.report.reportusulanrenja.datatable')
    </div>        
</div>
@endsection