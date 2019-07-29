@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD VISI TAHUN {{HelperKegiatan::getRPJMDTahunMulai()}} - {{HelperKegiatan::getRPJMDTahunAkhir()}}
@endsection
@section('page_header')
    <i class="icon-strategy position-left"></i>
    <span class="text-semibold"> 
        RPJMD VISI TAHUN {{HelperKegiatan::getRPJMDTahunMulai()}} - {{HelperKegiatan::getRPJMDTahunAkhir()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rpjmd.rpjmdvisi.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RPJMD</a></li>
    <li><a href="{!!route('rpjmdvisi.index')!!}">VISI</a></li>    
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
                        <a href="{!!route('rpjmdvisi.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'RPJMD\RPJMDVisiController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                             
                <div class="form-group">
                    {{Form::label('Nm_RpjmdVisi','VISI',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Nm_RpjmdVisi','',['class'=>'form-control','placeholder'=>'VISI KEPALA DAERAH ATAU TEKNOKRATIK','rows' => 2, 'cols' => 40])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Descr','PERATURAN DAERAH',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Descr','',['class'=>'form-control','placeholder'=>'PERATURAN DAERAH','rows' => 2, 'cols' => 40])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('TA_Awal','TAHUN KONDISI AWAL',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('TA_Awal','',['class'=>'form-control','placeholder'=>'TAHUN KONDISI AWAL'])}}
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
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () { 
    AutoNumeric.multiple(['#TA_Awal'], format_angka_options);
    $('#frmdata').validate({
        rules: {            
            Nm_RpjmdVisi : {
                required: true,
                minlength: 2
            },
            Descr : {
                required: true
            },
            TA_Awal : {
                required: true
            },
        },
        messages : {           
            Nm_RpjmdVisi : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            },
            Descr : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            },
            TA_Awal : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            },
        }      
    });     
});
</script>
@endsection