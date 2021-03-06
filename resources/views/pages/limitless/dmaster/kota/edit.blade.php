@extends('layouts.limitless.l_main')
@section('page_title')
    KOTA
@endsection
@section('page_header')
    <i class="icon-earth position-left"></i>
    <span class="text-semibold"> 
        KOTA TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.kota.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">LOKASI</a></li>
    <li><a href="{!!route('kota.index')!!}">KOTA</a></li>
    <li class="active">UBAH DATA</li>
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                UBAH DATA
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>               
                        <a href="{!!route('kota.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>['DMaster\KotaController@update',$data->PmKotaID],'method'=>'put','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
                <div class="form-group">
                    {{Form::label('PMProvID','PROVINSI',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('PMProvID', $provinsi, $data->Nm_Prov,['class'=>'form-control select','id'=>'PMProvID'])}}                        
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Kd_Kota','KODE KOTA',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Kd_Kota',$data->Kd_Kota,['class'=>'form-control','placeholder'=>'KODE KOTA','maxlength'=>4])}}
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('Nm_Kota','NAMA KOTA',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Nm_Kota',$data->Nm_Kota,['class'=>'form-control','placeholder'=>'NAMA KOTA'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Descr',$data->Descr,['class'=>'form-control','placeholder'=>'KETERANGAN','rows' => 2, 'cols' => 40])}}
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
        placeholder: "PILIH PROVINSI",
        allowClear:true
    });
    $('#frmdata').validate({
        rules: {
            Nm_Prov : {
                valueNotEquals : 'none'
            },
            Kd_Kota : {
                required: true,  
                number: true,
                maxlength: 4              
            },
            Nm_Kota : {
                required: true,
                minlength: 5
            }
        },
        messages : {
            Nm_Prov : {
                valueNotEquals: "Mohon dipilih Provinsi !"
            },
            Kd_Kota : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                number: "Mohon input dengan tipe data bilangan bulat",
                maxlength: "Nilai untuk Kode Urusan maksimal 4 digit"
            },
            Nm_Kota : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 5 karakter atau lebih."
            }
        }        
    });     
});
</script>
@endsection