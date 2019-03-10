@extends('layouts.limitless.l_main')
@section('page_title')
    MAPPING PROGRAM KE OPD
@endsection
@section('page_header')
    <i class="icon-link position-left"></i>
    <span class="text-semibold">
        MAPPING PROGRAM KE OPD TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.mappingprogramtoopd.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('mappingprogramtoopd.index')!!}">MAPPING PROGRAM KE OPD</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('kelompokurusan.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection