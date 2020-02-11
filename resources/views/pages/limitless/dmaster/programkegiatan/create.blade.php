@extends('layouts.limitless.l_main')
@section('page_title')
    KEGIATAN
@endsection
@section('page_header')
    <i class="icon-code position-left"></i>
    <span class="text-semibold"> 
        KEGIATAN TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.programkegiatan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">DATA</a></li>
    <li><a href="{!!route('programkegiatan.index')!!}">KEGIATAN</a></li>
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
                        <a href="{!!route('programkegiatan.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'DMaster\ProgramKegiatanController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
                <div class="form-group">
                    {{Form::label('PrgID','PROGRAM',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('PrgID', $daftar_program, '',['class'=>'select','id'=>'PrgID'])}}
                        {{Form::hidden('Kode_Program','none',['id'=>'Kode_Program'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Kd_Keg','KODE KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Kd_Keg','',['class'=>'form-control','placeholder'=>'KODE KEGIATAN','maxlength'=>4])}}
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('KgtNm','NAMA KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('KgtNm','',['class'=>'form-control','placeholder'=>'NAMA KEGIATAN'])}}
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
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    //styling select
    $('.select').select2({
        placeholder: "PILIH PROGRAM",
        allowClear:true
    });
    AutoNumeric.multiple(['#Kd_Keg'], {
                                        allowDecimalPadding: false,
                                        minimumValue:0,
                                        maximumValue:9999,
                                        numericPos:true,
                                        decimalPlaces : 0,
                                        digitGroupSeparator : '',
                                        showWarnings:false,
                                        unformatOnSubmit: true,
                                        modifyValueOnWheel:false
                                    });  

    @if(!(count($errors) > 0))
        $("#frmdata :input").not('[name=PrgID]').prop("disabled", true);
    @endif
    $(document).on('change','#PrgID',function(ev) {
        ev.preventDefault();  
        PrgID=$(this).val();        
        if (PrgID == null || PrgID=='')
        {
            $("#frmdata :input").not('[name=PrgID]').prop("disabled", true);
            $("#Kode_Program").val('');  
        }
        else
        {
            $("#frmdata *").prop("disabled", false);              
            $.ajax({
                type:'post',
                url: url_current_page+'/filter',
                dataType: 'json',
                data: {
                    "_token": token,
                    "PrgID": PrgID,
                    "create": true,
                },
                success:function(result)
                {   
                    $('#Kd_Keg').val(result.Kd_Keg);
                    const element = AutoNumeric.getAutoNumericElement('#Kd_Keg');
                    element.set(result.Kd_Keg);   
                },
                error:function(xhr, status, error)
                {   
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });            
        }
        
    });
    $('#frmdata').validate({
        ignore:[],
        rules: {
            PrgID : {
                required : true,
            },
            Kd_Keg : {
                required: true            
            },
            KgtNm : {
                required: true,
                minlength: 5      
            }
        },
        messages : {
            PrgID : {
                required: "Mohon dipilih Program !"
            },
            Kd_Keg : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                number: "Mohon input dengan tipe data bilangan bulat",
                maxlength: "Nilai untuk Kode Urusan maksimal 4 digit"
            },
            KgtNm : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 5 karakter atau lebih."
            }
        }        
    }); 
});
</script>
@endsection