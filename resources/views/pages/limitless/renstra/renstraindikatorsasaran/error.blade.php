@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD INDIKASI RENCANA PROGRAM
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        RPJMD INDIKASI RENCANA PROGRAM TAHUN {{config('eplanning.rpjmd_tahun_mulai')}} - {{config('eplanning.rpjmd_tahun_akhir')}}  
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rpjmd.rpjmdindikatorkinerja.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">RPJMD</a></li>
    <li><a href="{!!route('rpjmdindikatorkinerja.index')!!}">INDIKASI RENCANA PROGRAM</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('rpjmdindikatorkinerja.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection