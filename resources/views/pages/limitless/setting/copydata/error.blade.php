@extends('layouts.limitless.l_main')
@section('page_title')
    COPY DATA
@endsection
@section('page_header')
    <i class="icon-copy3 position-left"></i>
    <span class="text-semibold">
        COPY DATA
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.setting.copydata.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="#">DATA EPLANNING</a></li>
    <li><a href="{!!route('copydata.index')!!}">COPY DATA</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('copydata.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection