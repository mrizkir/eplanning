@extends('layouts.limitless.l_main')
@section('page_title')
    USERS DESA / KELURAHAN
@endsection
@section('page_header')
    <i class="icon-users position-left"></i>
    <span class="text-semibold"> 
        USERS DESA / KELURAHAN
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.setting.usersdesa.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('usersdesa.index')!!}">USERS DESA / KELURAHAN</a></li>
    <li class="active">TAMBAH DATA</li>
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                TAMBAH DATA
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>               
                        <a href="{!!route('usersdesa.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'Setting\UsersDesaController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
                <div class="form-group">
                    {{Form::label('name','NAMA',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('name','',['class'=>'form-control','placeholder'=>'NAMA USER'])}}
                    </div>
                </div> 
                <div class="form-group">
                    {{Form::label('email','EMAIL',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('email','',['class'=>'form-control','placeholder'=>'EMAIL USER'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('username','USERNAME',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('username','',['class'=>'form-control','placeholder'=>'USERNAME'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('password','PASSWORD',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::password('password',['class'=>'form-control','placeholder'=>'PASSWORD'])}}
                    </div>
                </div>   
                <div class="form-group">
                    <label class="col-md-2 control-label">DESA / KELURAHAN :</label> 
                    <div class="col-md-10">                        
                        <select name="PmDesaID" id="PmDesaID" class="select">
                            <option></option>
                            @foreach ($daftar_desa as $k=>$item)
                                <option value="{{$k}}">{{$item}}</option>
                            @endforeach
                        </select>    
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('theme','THEME',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('theme', $daftar_theme,'default',['class'=>'form-control','id'=>'theme'])}}                                
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('do_sync','SYNCING ROLE ?',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <div class="checkbox checkbox-switch">
                            {{Form::checkbox('do_sync','1',1,['class'=>'switch','data-on-text'=>'OK','data-off-text'=>'NO'])}}                                     
                        </div>
                    </div>
                </div>
                <div class="form-group">            
                    <div class="col-md-10 col-md-offset-2">                        
                        {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}
                    </div>
                </div>
            {!! Form::close()!!}
        </div>
    </div>
</div>   
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/switch.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $(".switch").bootstrapSwitch();
    $('#PmDesaID.select').select2({
        placeholder: "PILIH DESA / KELURAHAN",
        allowClear:true
    });
    $('#frmdata').validate({
        ignore:[],
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
            password : {
                required: true,
                minlength: 5,
            },            
            PmDesaID : {
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
            password : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 5 karakter atau lebih."
            },            
            PmDesaID : {                
                required: "Mohon di pilih Desa / Kelurahan untuk user ini",
            }
        },        
    });   
});
</script>
@endsection