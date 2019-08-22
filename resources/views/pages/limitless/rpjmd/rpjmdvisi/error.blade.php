@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD VISI  PERIODE {{HelperKegiatan::getRPJMDTahunMulai()}} - {{HelperKegiatan::getRPJMDTahunAkhir()+1}}
@endsection
@section('page_header')
    <i class="icon-strategy position-left"></i>
    <span class="text-semibold">
        RPJMD VISI  PERIODE {{HelperKegiatan::getRPJMDTahunMulai()}} - {{HelperKegiatan::getRPJMDTahunAkhir()+1}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rpjmd.rpjmdvisi.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RPJMD</a></li>
    <li><a href="{!!route('rpjmdvisi.index')!!}">VISI</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('rpjmdvisi.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection