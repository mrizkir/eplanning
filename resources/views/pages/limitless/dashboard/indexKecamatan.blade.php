@extends('layouts.limitless.l_main')
@section('page_title')
    RINGKASAN TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}  
@endsection
@section('page_header')
    <i class="icon-display position-left"></i> 
    <span class="text-semibold"> 
        RINGKASAN TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}  
    </span>
@endsection
@section('page_breadcrumb')
    <li class="active">RINGKASAN PERENCANAAN</li>
@endsection
@section('page_content')

@endsection