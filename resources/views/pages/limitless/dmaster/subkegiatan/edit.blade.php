@extends('layouts.limitless.l_main')
@section('page_title')
    SUB KEGIATAN
@endsection
@section('page_header')
    <i class="icon-code position-left"></i>
    <span class="text-semibold"> 
        SUB KEGIATAN TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.subkegiatan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">DATA</a></li>
    <li><a href="{!!route('subkegiatan.index')!!}">SUB KEGIATAN</a></li>
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
                        <a href="{!!route('subkegiatan.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>['DMaster\SubKegiatanController@update',$data->SubKgtID],'method'=>'put','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}        
                <div class="form-group">
                    {{Form::label('KgtID','KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('KgtID', $daftar_kegiatan, $data['KgtID'],['class'=>'select','id'=>'KgtID'])}}
                        {{Form::hidden('Kode_Kegiatan','none',['id'=>'Kode_Kegiatan'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Kd_SubKeg','KODE SUB KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Kd_SubKeg',$data['Kd_SubKeg'],['class'=>'form-control','placeholder'=>'KODE SUB KEGIATAN','maxlength'=>4])}}
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('SubKgtNm','NAMA SUB KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('SubKgtNm',$data['SubKgtNm'],['class'=>'form-control','placeholder'=>'NAMA SUB KEGIATAN'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Descr',$data['Descr'],['class'=>'form-control','placeholder'=>'KETERANGAN','rows' => 2, 'cols' => 40])}}
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
<script src="{!!asset('themes/limitless/assets/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    //styling select
    $('.select').select2({
        placeholder: "PILIH KEGIATAN",
        allowClear:true
    });
    AutoNumeric.multiple(['#Kd_SubKeg'], {
                                        allowDecimalPadding: false,
                                        minimumValue:0,
                                        lZero:'keep',
                                        maximumValue:9999,
                                        numericPos:true,
                                        decimalPlaces : 0,
                                        digitGroupSeparator : '',
                                        showWarnings:false,
                                        unformatOnSubmit: true,
                                        modifyValueOnWheel:false
                                    });  
                                    $(document).on('change','#KgtID',function(ev) {
        ev.preventDefault();  
        KgtID=$(this).val();
        if (KgtID == null || KgtID=='')
        {
            $("#frmdata :input").not('[name=KgtID]').prop("disabled", true);
            $("#Kode_Kegiatan").val('');  
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
                    "KgtID": KgtID,
                    "create": true,
                },
                success:function(result)
                {   
                    $('#Kd_SubKeg').val(result.Kd_SubKeg);
                    const element = AutoNumeric.getAutoNumericElement('#Kd_SubKeg');
                    element.set(result.Kd_SubKeg);   
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
            KgtID : {
                required : true,
            },
            Kd_SubKeg : {
                required: true          
            },
            SubKgtNm : {
                required: true,
                minlength: 5      
            }
        },
        messages : {
            KgtID : {
                required: "Mohon dipilih Program !"
            },
            Kd_SubKeg : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                number: "Mohon input dengan tipe data bilangan bulat",
                maxlength: "Nilai untuk Kode Urusan maksimal 4 digit"
            },
            SubKgtNm : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 5 karakter atau lebih."
            }
        }     
    });           
});
</script>
@endsection