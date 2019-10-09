@extends('layouts.limitless.l_main')
@section('page_title')
    PROVINSI
@endsection
@section('page_header')
    <i class="icon-earth position-left"></i>
    <span class="text-semibold"> 
        PROVINSI TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.provinsi.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">LOKASI</a></li>
    <li><a href="{!!route('provinsi.index')!!}">PROVINSI</a></li>
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
                        <a href="{!!route('provinsi.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'DMaster\ProvinsiController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                         
                <div class="form-group">
                    {{Form::label('Kd_Prov','KODE PROVINSI',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Kd_Prov','',['class'=>'form-control','placeholder'=>'KODE PROVINSI','maxlength'=>4])}}
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('Nm_Prov','NAMA PROVINSI',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Nm_Prov','',['class'=>'form-control','placeholder'=>'NAMA PROVINSI'])}}
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
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $('#frmdata').validate({
        rules: {
            PMProvID : {
                valueNotEquals : 'none'
            },
            Kd_Prov : {
                required: true,  
                number: true,
                maxlength: 4              
            },
            Nm_Prov : {
                required: true,
                minlength: 5
            }
        },
        messages : {
            PMProvID : {
                valueNotEquals: "Mohon dipilih Provinsi !"
            },
            Kd_Prov : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                number: "Mohon input dengan tipe data bilangan bulat",
                maxlength: "Nilai untuk Kode Urusan maksimal 4 digit"
            },
            Nm_Prov : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 5 karakter atau lebih."
            }
        }        
    });     
});
</script>
@endsection