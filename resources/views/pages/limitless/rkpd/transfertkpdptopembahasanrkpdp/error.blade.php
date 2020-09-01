@extends('layouts.limitless.l_main')
@section('page_title')
    TRANSFER PEMBAHASAN RKPD MURNI --> RKPD PERUBAHAN
@endsection
@section('page_header')
    <i class="icon-office position-left"></i>
    <span class="text-semibold">
        TRANSFER RKPD MURNI --> PEMBAHASAN TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.transferrkpdptopembahasanrkpdp.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">RKPD</a></li>
    <li><a href="#">TRANSFER DATA</a></li>
    <li><a href="{!!route('transferrkpdptopembahasanrkpdp.index')!!}">TRANSFER PEMBAHASAN RKPD MURNI --> RKPD PERUBAHAN</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('kelompokurusan.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection