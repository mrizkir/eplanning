@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD PROGRAM PEMBANGUNAN DAERAH
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        RPJMD PROGRAM PEMBANGUNAN DAERAH PERIODE {{HelperKegiatan::getRPJMDTahunMulai()}} - {{HelperKegiatan::getRPJMDTahunAkhir()+1}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rpjmd.rpjmdprogrampembangunan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">RPJMD</a></li>
    <li><a href="{!!route('rpjmdprogrampembangunan.index')!!}">PROGRAM PEMBANGUNAN DAERAH</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('rpjmdprogrampembangunan.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection