@extends('layouts.limitless.l_main')
@section('page_title')
    TAHUN PERENCANAAN / ANGGARAN
@endsection
@section('page_header')
    <i class="icon-calendar2 position-left"></i>
    <span class="text-semibold">
        TAHUN PERENCANAAN / ANGGARAN
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.kelompokurusan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">ANEKA DATA</a></li>
    <li><a href="{!!route('kelompokurusan.index')!!}">TAHUN PERENCANAAN / ANGGARAN</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('kelompokurusan.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection