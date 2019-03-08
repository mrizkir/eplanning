@extends('layouts.default.l_main')
@section('page_title')
    USER ROLES
@endsection
@section('page_header')
    <i class="fa fa-users"></i> 
    USER ROLES   
@endsection
@section('page-info')
    @include('pages.default.setting.permissions.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="{!!route('roles.index')!!}">USER ROLES</a></li>
    <li class="active">TAMBAH DATA</li>
@endsection
@section('page_content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-plus-circle"></i> TAMBAH DATA
                </h3>
                <div class="box-tools">
                    <a href="{!!route('roles.index')!!}" class="btn btn-default" title="keluar">
                        <i class="fa fa-close"></i>
                    </a>
                </div>
            </div>
            {!! Form::open(['action'=>'Setting\RolesController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}
                <div class="box-body">                
                    <div class="form-group">
                        {{Form::label('name','NAMA ROLE',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            {{Form::text('name','',['class'=>'form-control','placeholder'=>'NAMA ROLE'])}}
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
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $('#frmdata').validate({
        rules: {
            name : {
                required: true,
                minlength: 2
            }
        },
        messages : {
            name : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            }
        }
    });   
});
</script>
@endsection