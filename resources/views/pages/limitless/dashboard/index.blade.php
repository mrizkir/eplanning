@extends('layouts.limitless.l_main')
@section('page_title')
    RINGKASAN PERENCANAAN
@endsection
@section('page_header')
    <i class="icon-display position-left"></i> 
    <span class="text-semibold"> 
        RINGKASAN PERENCANAAN 
    </span>
@endsection
@section('page_breadcrumb')
    <li class="active">RINGKASAN PERENCANAAN</li>
@endsection
@section('page_content')
<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="panel panel-body bg-blue-400 has-bg-image">
            <div class="media no-margin">
                <div class="media-body">
                    <h3 class="no-margin">{{Helper::formatUang($data['totalrkpdm'])}}</h3>
                    <span class="text-uppercase text-size-mini">RKPD</span>
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
</div>
@endsection