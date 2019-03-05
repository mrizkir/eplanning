@extends('layouts.default.l_main')
@section('page_title')
    USERS
@endsection
@section('page_header')
    <i class="fa fa-lock"></i> 
    USERS
@endsection
@section('page-info')
    @include('pages.default.setting.users.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="{!!route('users.index')!!}">USERS</a></li>
    <li class="active">UBAH DATA</li>
@endsection
@section('page_asset_css')
<link rel="stylesheet" href="{!!asset('themes/default/assets/select2/dist/css/select2.min.css')!!}">
<link rel="stylesheet" href="{!!asset('themes/default/assets/admin-lte/plugins/iCheck/minimal/blue.css')!!}">
@endsection
@section('page_content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-pencil"></i> UBAH DATA
                </h3>
                <div class="box-tools">
                    <a href="{!!route('users.index')!!}" class="btn btn-default" title="keluar">
                        <i class="fa fa-close"></i>
                    </a>
                </div>
            </div>
            {!! Form::open(['action'=>['Setting\UsersController@update',$data->id],'method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}        
                <div class="box-body">
                    {{Form::hidden('_method','PUT')}}
                    {{Form::hidden('old_username',$data['username'])}}
                    <div class="form-group">
                        {{Form::label('name','NAMA',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            {{Form::text('name',$data['name'],['class'=>'form-control','placeholder'=>'NAMA USER'])}}
                        </div>
                    </div> 
                    <div class="form-group">
                        {{Form::label('email','EMAIL',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            {{Form::text('email',$data['email'],['class'=>'form-control','placeholder'=>'EMAIL USER'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('username','USERNAME',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            {{Form::text('username',$data['username'],['class'=>'form-control','placeholder'=>'USERNAME'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('password','PASSWORD',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            {{Form::password('password',['class'=>'form-control','placeholder'=>'PASSWORD'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('role_name','ROLE',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            {{Form::select('role_name[]', $daftar_roles, $user_roles,['class'=>'form-control select2','multiple' => 'multiple','data-placeholder'=>'Pilih Role','id'=>'role_name'])}}                                
                        </div>
                    </div>           
                    <div class="form-group">
                        {{Form::label('do_sync','SYNCING ROLE ?',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            {{Form::checkbox("do_sync", 1,'',['class'=>'minimal'])}}  
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="form-group">            
                        <div class="col-md-12 col-md-offset-2">                        
                            {{ Form::button('<i class="fa fa-save"></i> Simpan', ['type' => 'submit', 'class' => 'btn btn-primary'] )  }}
                        </div>
                    </div>     
                </div>
            {!! Form::close()!!}
        </div>
    </div>
</div>   
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/default/assets/jquery-validation/dist/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/default/assets/jquery-validation/dist/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/default/assets/select2/dist/js/select2.full.min.js')!!}"></script>
<script src="{!!asset('themes/default/assets/admin-lte/plugins/iCheck/icheck.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $('input[type="checkbox"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue'
    });
    $('.select2').select2({
        minimumResultsForSearch: Infinity
    });
    $('#frmdata').validate({
        rules: {
            name : {
                required: true,
                minlength: 2
            },
            email : {
                required: true,
                email: true,
            },
            username : {
                required: true,
                minlength: 5,
            },            
            role_name : {
                required: true,
            }
        },
        messages : {
            name : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            },
            email : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                email: "Format email tidak benar."
            },
            username : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 5 karakter atau lebih."
            },           
            role_name : {
                required: "Mohon di pilih Role untuk user ini sebagai apa ?",
            },
        }, 
    });   
});
</script>
@endsection