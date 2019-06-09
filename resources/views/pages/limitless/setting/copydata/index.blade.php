@extends('layouts.limitless.l_main')
@section('page_title')
    COPY DATA
@endsection
@section('page_header')
    <i class="icon-copy3 position-left"></i>
    <span class="text-semibold">
        COPY DATA
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.setting.copydata.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="#">DATA EPLANNING</a></li>
    <li class="active">COPY DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.setting.copydata.datatable')
    </div>
</div>
@endsection