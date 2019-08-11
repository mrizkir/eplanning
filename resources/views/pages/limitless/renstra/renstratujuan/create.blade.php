@extends('layouts.limitless.l_main')
@section('page_title')
    RENSTRA TUJUAN  {{HelperKegiatan::getRENSTRATahunMulai()}} - {{HelperKegiatan::getRENSTRATahunAkhir()}}
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RENSTRA TUJUAN TAHUN {{HelperKegiatan::getRENSTRATahunMulai()}} - {{HelperKegiatan::getRENSTRATahunAkhir()}}  
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.renstra.renstratujuan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RENSTRA</a></li>
    <li><a href="{!!route('renstratujuan.index')!!}">TUJUAN</a></li>
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
                        <a href="{!!route('renstratujuan.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'RENSTRA\RENSTRATujuanController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                            
                <div class="form-group">
                    <label class="col-md-2 control-label">MISI RPJMD:</label> 
                    <div class="col-md-10">
                        <select name="PrioritasKabID" id="PrioritasKabID" class="select">
                            <option></option>
                            @foreach ($daftar_misi as $k=>$item)
                                <option value="{{$k}}">{{$item}}</option>
                            @endforeach
                        </select>                                
                    </div>
                </div>   
                <div class="form-group">
                    <label class="col-md-2 control-label">TUJUAN RPJMD:</label> 
                    <div class="col-md-10">
                        <select name="PrioritasTujuanKabID" id="PrioritasTujuanKabID" class="select">
                            <option></option>                           
                        </select>                                
                    </div>
                </div>   
                <div class="form-group">
                    <label class="col-md-2 control-label">SASARAN RPJMD:</label> 
                    <div class="col-md-10">
                        <select name="PrioritasSasaranKabID" id="PrioritasSasaranKabID" class="select">
                            <option></option>                           
                        </select>                                
                    </div>
                </div>   
                <div class="form-group">
                    {{Form::label('Kd_RenstraTujuan','KODE TUJUAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Kd_RenstraTujuan','',['class'=>'form-control','placeholder'=>'Kode Tujuan','maxlength'=>'4'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Nm_RenstraTujuan','NAMA TUJUAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Nm_RenstraTujuan','',['class'=>'form-control','placeholder'=>'Nama Tujuan'])}}
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
    AutoNumeric.multiple(['#Kd_RenstraTujuan'], {
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
    $('#PrioritasKabID.select').select2({
        placeholder: "PILIH MISI",
        allowClear:true
    });
    $('#PrioritasTujuanKabID.select').select2({
        placeholder: "PILIH TUJUAN",
        allowClear:true
    });
    $('#PrioritasSasaranKabID.select').select2({
        placeholder: "PILIH SASARAN",
        allowClear:true
    });
    $(document).on('change','#PrioritasKabID',function(ev) {
        ev.preventDefault();
        PrioritasKabID=$(this).val();        
        $.ajax({
            type:'get',
            url: '{{route("rpjmdtujuan.index")}}/getdaftartujuanrpjmd/'+PrioritasKabID,
            dataType: 'json',
            data: {
                "_token": token,
                "PrioritasKabID": PrioritasKabID,
            },
            success:function(result)
            {   
                var daftar_tujuan = result.daftar_tujuan;
                var listitems='<option></option>';
                $('#PrioritasSasaranKabID').html(listitems);
                $.each(daftar_tujuan,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });
                $('#PrioritasTujuanKabID').html(listitems);
            },
            error:function(xhr, status, error)
            {   
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    }); 
    $(document).on('change','#PrioritasTujuanKabID',function(ev) {
        ev.preventDefault();
        PrioritasTujuanKabID=$(this).val();        
        $.ajax({
            type:'get',
            url: url_current_page+'/getdaftarsasaranrpjmd/'+PrioritasTujuanKabID,
            dataType: 'json',
            data: {
                "_token": token,
                "PrioritasTujuanKabID": PrioritasTujuanKabID,
            },
            success:function(result)
            {   
                var daftar_sasaran = result.daftar_sasaran;
                var listitems='<option></option>';
                $.each(daftar_sasaran,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });
                $('#PrioritasSasaranKabID').html(listitems);
            },
            error:function(xhr, status, error)
            {   
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });     
    $(document).on('change','#PrioritasSasaranKabID',function(ev) {
        ev.preventDefault();
        PrioritasSasaranKabID=$(this).val();        
        $.ajax({
            type:'get',
            url: url_current_page+'/getkodetujuan/'+PrioritasSasaranKabID,
            dataType: 'json',
            data: {
                "_token": token,
                "PrioritasSasaranKabID": PrioritasSasaranKabID,
            },
            success:function(result)
            {   
                const element = AutoNumeric.getAutoNumericElement('#Kd_RenstraTujuan');
                element.set(result.Kd_RenstraTujuan);                     
                $('#Nm_Sasaran').val(result.Nm_Sasaran);
            },
            error:function(xhr, status, error)
            {   
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    }); 
    $('#frmdata').validate({
        ignore: [],
        rules: {
            PrioritasKabID : {
                required: true,
                valueNotEquals: 'none'
            },
            Kd_RenstraTujuan : {
                required: true,
            },
            Nm_RenstraTujuan : {
                required: true,
                minlength: 2
            }
        },
        messages : {
            PrioritasKabID : {
                required: "Mohon untuk di pilih karena ini diperlukan.",
                valueNotEquals: "Mohon untuk di pilih karena ini diperlukan.",      
            },
            Kd_RenstraTujuan : {
                required: "Mohon untuk di isi karena ini diperlukan.",
            },
            Nm_RenstraTujuan : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            }
        }      
    });   
});
</script>
@endsection