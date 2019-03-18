@extends('layouts.limitless.l_main')
@section('page_title')
    USULAN KECAMATAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        USULAN KECAMATAN TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.musrenbang.aspirasimusrenkecamatan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">ASPIRASI / USULAN</a></li>
    <li><a href="{!!route('aspirasimusrenkecamatan.index')!!}">USULAN KECAMATAN</a></li>
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
                        <a href="{!!route('aspirasimusrenkecamatan.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'Musrenbang\AspirasiMusrenKecamatanController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
                <div class="form-group">
                    {{Form::label('replaceit','NAMA KECAMATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('replaceit','',['class'=>'form-control','placeholder'=>'replaceit'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('replaceit','NAMA DESA',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('replaceit','',['class'=>'form-control','placeholder'=>'replaceit'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('replaceit','USULAN DESA / KELURAHAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('replaceit','',['class'=>'form-control','placeholder'=>'replaceit'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('replaceit','KODE KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('replaceit','',['class'=>'form-control','placeholder'=>'replaceit'])}}
                    </div>
                </div>

                <div class="form-group">
                    {{Form::label('replaceit','NAMA KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('replaceit','',['class'=>'form-control','placeholder'=>'replaceit'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('replaceit','OUTPUT / HASIL',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('replaceit','',['class'=>'form-control','placeholder'=>'replaceit'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('replaceit','NILAI USULAN ANGGARAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('replaceit','',['class'=>'form-control','placeholder'=>'replaceit'])}}
                    </div>
                </div>

                <div class="form-group">
                    {{Form::label('replaceit','TARGET (VOLUME)',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('replaceit','',['class'=>'form-control','placeholder'=>'replaceit'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('replaceit','PRIORITAS',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('replaceit','',['class'=>'form-control','placeholder'=>'replaceit'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('replaceit','KEGIATAN FISIK',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('replaceit','',['class'=>'form-control','placeholder'=>'replaceit'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('replaceit','LOKASI',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('replaceit','',['class'=>'form-control','placeholder'=>'replaceit'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('replaceit','SUMBER DANA',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('replaceit','',['class'=>'form-control','placeholder'=>'replaceit'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('replaceit','OPD / SKPD PELAKSANA',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('replaceit','',['class'=>'form-control','placeholder'=>'replaceit'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('replaceit','NAMA PROGRAM',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('replaceit','',['class'=>'form-control','placeholder'=>'replaceit'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('replaceit','SASARAN RPJMD',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('replaceit','',['class'=>'form-control','placeholder'=>'replaceit'])}}
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
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $('#frmdata').validate({
        rules: {
            replaceit : {
                required: true,
                minlength: 2
            }
        },
        messages : {
            replaceit : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            }
        }      
    });   
});
</script>
@endsection