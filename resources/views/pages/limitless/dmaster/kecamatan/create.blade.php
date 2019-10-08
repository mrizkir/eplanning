@extends('layouts.limitless.l_main')
@section('page_title')
    KECAMATAN
@endsection
@section('page_header')
    <i class="icon-earth position-left"></i>
    <span class="text-semibold"> 
        KECAMATAN TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.kecamatan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">LOKASI</a></li>
    <li><a href="{!!route('kecamatan.index')!!}">KECAMATAN</a></li>
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
                        <a href="{!!route('kecamatan.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'DMaster\KecamatanController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
                <div class="form-group">
                    {{Form::label('PmKotaID','KOTA',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('PmKotaID', $kota, '',['class'=>'form-control select','id'=>'PmKotaID'])}}                        
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Kd_Kecamatan','KODE KECAMATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Kd_Kecamatan','',['class'=>'form-control','placeholder'=>'KODE KECAMATAN','maxlength'=>4])}}
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('Nm_Kecamatan','NAMA KECAMATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Nm_Kecamatan','',['class'=>'form-control','placeholder'=>'NAMA KECAMATAN'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Descr','',['class'=>'form-control','placeholder'=>'KETERANGAN','rows' => 2, 'cols' => 40])}}
                    </div>
                </div>
                <div class="form-group">            
                    <div class="col-md-10 col-md-offset-2">                        
                        {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] )  }}
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
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    //styling select
    $('.select').select2({
        placeholder: "PILIH KOTA",
        allowClear:true
    });
    $('#frmdata').validate({
        rules: {
            PmKotaID : {
                valueNotEquals : 'none'
            },
            Kd_Kecamatan : {
                required: true,  
                number: true,
                maxlength: 4              
            },
            Nm_Kecamatan : {
                required: true,
                minlength: 5
            }
        },
        messages : {
            PmKotaID : {
                valueNotEquals: "Mohon dipilih Kota !"
            },
            Kd_Kecamatan : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                number: "Mohon input dengan tipe data bilangan bulat",
                maxlength: "Nilai untuk Kode Urusan maksimal 4 digit"
            },
            Nm_Kecamatan : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 5 karakter atau lebih."
            }
        }        
    });     
});
</script>
@endsection