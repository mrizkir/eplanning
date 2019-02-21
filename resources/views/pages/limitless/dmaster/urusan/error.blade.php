@extends('layouts.default.l_main')
@section('page_title')
    URUSAN
@endsection
@section('page_header')
    <i class="fa fa-lock"></i> 
    URUSAN
@endsection
@section('page-info')
    @include('pages.limitless.dmaster.urusan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('urusan.index')!!}">URUSAN</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger">
    <button type="button" class="close" onclick="location.href='{{route('urusan.index')}}'">×</button>
    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
    {{$errormessage}}
</div>
@endsection