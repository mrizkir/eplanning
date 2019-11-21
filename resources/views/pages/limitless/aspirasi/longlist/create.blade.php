@extends('layouts.limitless.l_main')
@section('page_title')
    LONG LIST
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        LONG LIST TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.aspirasi.longlist.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">ASPIRASI</a></li>
    <li><a href="{!!route('longlist.index')!!}">LONG LIST</a></li>
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
                        <a href="{!!route('longlist.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        {!! Form::open(['action'=>'Aspirasi\LongListController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                      
        <div class="panel-body">                      
            {{Form::hidden('OrgID',$filters['OrgID'])}}          
            <div class="form-group">
                {{Form::label('KgtNm','NAMA KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::textarea('KgtNm','',['class'=>'form-control','placeholder'=>'NAMA KEGIATAN','rows'=>2])}}
                </div>
            </div>            
            <div class="form-group">
                {{Form::label('Sasaran_Angka','SASARAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            {{Form::text('Sasaran_Angka','',['class'=>'form-control','placeholder'=>'ANGKA SASARAN'])}}
                        </div>
                        <div class="col-md-6">
                            {{Form::textarea('Sasaran_Uraian','',['class'=>'form-control','placeholder'=>'URAIAN SASARAN','rows'=>3,'id'=>'Sasaran_Uraian'])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::textarea('Descr','',['class'=>'form-control','placeholder'=>'KETERANGAN / CATATAN PENTING','rows'=>2])}}
                </div>
            </div>
        </div>         
        <div class="panel-body">
            <div class="form-horizontal">               
                <div class="form-group">
                    {{Form::label('Lokasi','KETERANGAN LOKASI KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Lokasi','',['class'=>'form-control','placeholder'=>'KETERANGAN LOKASI KEGIATAN','rows'=>2])}}
                    </div>
                </div>
            </div>
        </div>      
        <div class="panel-footer">
            <div class="col-md-10 col-md-offset-2">                        
                {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}                                       
            </div>
        </div>
        {!! Form::close()!!}
    </div>
</div>   
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    AutoNumeric.multiple(['#Sasaran_Angka'], {
                                        allowDecimalPadding: false,
                                        minimumValue:0,
                                        maximumValue:99999999999,
                                        numericPos:true,
                                        decimalPlaces : 0,
                                        digitGroupSeparator : '',
                                        showWarnings:false,
                                        unformatOnSubmit: true,
                                        modifyValueOnWheel:false
                                    });


    $('#frmdata').validate({
        ignore: [],
        rules: {            
            OrgID : {
                required: true,
            },
            KgtNm : {
                required: true
            },            
            Sasaran_Angka : {
                required: true          
            },
            Sasaran_Uraian : {
                required: true
            },
            NilaiUsulan : {
                required: true
            },            
            Lokasi : {
                required: true                
            }
        },
        messages : {
            OrgID : {
                required: "Mohon untuk di pilih pemilik pokok pikiran.",
                valueNotEquals: "Mohon untuk di pilih pemilik pokok pikiran.",                
            },            
            KgtNm : {
                required: "Mohon untuk di isi nama kegiatan.",                
            },            
            Sasaran_Angka : {
                required: "Mohon untuk di isi angka sasaran kegiatan.",                
            },
            Sasaran_Uraian : {
                required: "Mohon untuk di isi uraian sasaran kegiatan.",                
            },            
            Lokasi : {
                required: "Mohon untuk di isi lokasi detail kegiatan.", 
            }
        }      
    });   
});
</script>
@endsection