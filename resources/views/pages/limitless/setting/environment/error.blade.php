@extends('layouts.limitless.l_main')
@section('page_title')
    .ENV
@endsection
@section('page_header')
    <i class="icon-file-text position-left"></i>
    <span class="text-semibold">
        .ENV
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.setting.environment.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="#">KONFIGURASI</a></li>
    <li><a href="{!!route('environment.index')!!}">.ENV</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('environment.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection