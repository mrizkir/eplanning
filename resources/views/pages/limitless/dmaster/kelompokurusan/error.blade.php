@extends('layouts.limitless.l_main')
@section('page_title')
    KELOMPOK URUSAN {{config('globalsettings.tahun_perencanaan')}}
@endsection
@section('page_header')
    <i class="fa fa-lock"></i> 
    KELOMPOK URUSAN
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.kelompokurusan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">DATA</a></li>
    <li><a href="{!!route('kelompokurusan.index')!!}">KELOMPOK URUSAN</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger">
    <button type="button" class="close" onclick="location.href='{{route('kelompokurusan.index')}}'">×</button>
    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
    {{$errormessage}}
</div>
@endsection