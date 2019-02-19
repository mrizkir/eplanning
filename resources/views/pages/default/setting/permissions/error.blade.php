@extends('layouts.default.l_main')
@section('page_title')
    USER PERMISSIONS
@endsection
@section('page_header')
    <i class="fa fa-lock"></i> 
    USER PERMISSIONS
@endsection
@section('page-info')
    @include('pages.default.setting.permissions.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="{!!route('permissions.index')!!}">USER PERMISSIONS</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger">
    <button type="button" class="close" onclick="location.href='{{route('permissions.index')}}'">×</button>
    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
    {{$errormessage}}
</div>
@endsection