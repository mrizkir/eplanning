@extends('layouts.limitless.l_main')
@section('page_title')
    RENSTRA PROGRAM, KEGIATAN, DAN PENDANAAN TAHUN {{HelperKegiatan::getRENSTRATahunMulai()}} - {{HelperKegiatan::getRENSTRATahunAkhir()}}
@endsection
@section('page_header')
    <i class="icon-strategy position-left"></i>
    <span class="text-semibold">
        RENSTRA PROGRAM, KEGIATAN, DAN PENDANAAN TAHUN {{HelperKegiatan::getRENSTRATahunMulai()}} - {{HelperKegiatan::getRENSTRATahunAkhir()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.renstra.renstraprogramkegiatanpendanaan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RENSTRA</a></li>
    <li><a href="{!!route('renstraprogramkegiatanpendanaan.index')!!}">RENSTRA PROGRAM, KEGIATAN, DAN PENDANAAN</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('renstraprogramkegiatanpendanaan.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection