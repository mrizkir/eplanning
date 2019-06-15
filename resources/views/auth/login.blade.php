@extends('layouts.default.l_login')
@section('page_title')
    LOGIN
@endsection
@section('page_content')
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>{{config('app.name')}}</b></a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">Masukan username dan password untuk masuk ke dalam {{config('app.name')}}</p>          
        {!! Form::open(['url'=>route('login'),'method'=>'post','class'=>'form-horizontal','id'=>'frmlogin','name'=>'frmlogin'])!!}
            <div class="form-group has-feedback">
                {{Form::text('username',old('username'),['class'=>'form-control','placeholder'=>'Username'])}}
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                {{Form::password('password',['class'=>'form-control','placeholder'=>'Password'])}}
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>       
            <div class="form-group">
                {{Form::select('TACd', \App\Models\DMaster\TAModel::pluck('TACd','TACd'), config('eplanning.tahun_perencanaan'), ['placeholder' => 'PILIH TAHUN PERENCANAAN','class'=>'form-control'])}}
            </div>       
            <div class="row">     
                <div class="col-md-offset-8 col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
                </div>            
            </div>
            @if ($errors->has('username'))
            <div class="row">     
                <div class="col-xs-12">     
                    <div class="box-body">  
                        <div class="alert alert-danger text-center" style="margin-bottom:0px">                
                            {{ $errors->first('username') }}                        
                        </div>
                    </div>
                </div>            
            </div>                
            @endif            
        {!! Form::close()!!}
    </div>
</div>
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/default/assets/jquery-validation/dist/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/default/assets/jquery-validation/dist/additional-methods.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $('#frmlogin').validate({
        rules: {
            username : {
                required: true
            },
            password : {
                required: true
            },
            TACd : {
                required: true
            },
        },
        messages : {
            username : {
                required: "Mohon untuk di isi username karena diperlukan."
            },
            password : {
                required: "Mohon untuk di isi password karena diperlukan."
            },
            TACd : {
                required: "Mohon pilih tahun perencanaan."
            }
        }        
    });   
});
</script>
@endsection