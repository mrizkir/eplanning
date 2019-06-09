@extends('layouts.limitless.l_main')
@section('page_title')
    .ENV
@endsection
@section('page_header')
    <i class="icon-file-text position-left"></i>
    <span class="text-semibold">
        .ENV
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.setting.environment.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="#">KONFIGURASI</a></li>
    <li class="active">.ENV</li>
@endsection
@section('page_content')
<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">
        <h5 class="panel-title">&nbsp;</h5>
        <div class="heading-elements">
      
        </div>
    </div>
    <div class="panel-body">
        {!! Form::open(['action'=>'Setting\EnvironmentController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
            
        {!! Form::close()!!}
    </div>
</div>
@endsection