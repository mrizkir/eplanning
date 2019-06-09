@extends('layouts.limitless.l_main')
@section('page_title')
    CLEAR CACHE
@endsection
@section('page_header')
    <i class="icon-database-refresh position-left"></i>
    <span class="text-semibold">
        CLEAR CACHE
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.setting.clearcache.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="#">CACHE</a></li>
    <li class="active">CLEAR CACHE</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.setting.clearcache.datatable')
    </div>
</div>
@endsection