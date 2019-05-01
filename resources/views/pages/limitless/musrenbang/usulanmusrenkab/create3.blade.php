@extends('layouts.limitless.l_main')
@section('page_title')
    USULAN MUSRENBANG KABUPATEN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        USULAN MUSRENBANG KABUPATEN TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.musrenbang.usulanmusrenkab.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">ASPIRASI / USULAN</a></li>
    <li><a href="{!!route('usulanmusrenkab.index')!!}">USULAN MUSRENBANG KABUPATEN</a></li>
    <li class="active">TAMBAH DATA RINCIAN KEGIATAN (RESES)</li>
@endsection
@section('page_sidebar')
    @include('pages.limitless.musrenbang.usulanmusrenkab.l_sidebar_prarenja_create')
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                TAMBAH DATA RINCIAN KEGIATAN DARI RESES
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>               
                        <a href="{!!route('usulanmusrenkab.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        {!! Form::open(['action'=>'Musrenbang\UsulanMusrenKabController@store3','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}
        {{Form::hidden('RenjaID',$renja->RenjaID,['id'=>'RenjaID'])}}
        {{Form::hidden('PMProvID',$PMProvID)}}
        {{Form::hidden('PmKotaID',$PmKotaID)}}
        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-2 control-label">POSISI ENTRI: </label>
                <div class="col-md-10">
                    <p class="form-control-static">
                        <span class="label border-left-primary label-striped">USULAN FORUM OPD / SKPD</span>
                    </p>
                </div>                            
            </div>               
            <div class="form-group">
                <label class="col-md-2 control-label">PEMILIK POKIR</label> 
                <div class="col-md-10">
                    <select name="PemilikPokokID" id="PemilikPokokID" class="select">
                        <option></option>          
                        @foreach ($daftar_pemilik as $k=>$item)
                            <option value="{{$k}}">{{$item}}</option>
                        @endforeach 
                    </select>                         
                </div>
            </div>  
            <div class="form-group">
                <label class="col-md-2 control-label">USULAN KEGIATAN</label> 
                <div class="col-md-10">
                    <select name="PokPirID" id="PokPirID" class="select">
                        <option></option>  
                    </select>   
                </div>
            </div>    
        </div>
        <div class="panel-body">                    
            <div class="form-group">
                {{Form::label('No','NOMOR',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('No',$nomor_rincian,['class'=>'form-control','placeholder'=>'NOMOR URUT KEGIATAN','readonly'=>true])}}
                </div>
            </div>    
            <div class="form-group">
                {{Form::label('Uraian','NAMA/URAIAN KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Uraian','',['class'=>'form-control','placeholder'=>'NAMA ATAU URAIAN KEGIATAN'])}}
                </div>
            </div>        
            <div class="form-group">
                {{Form::label('Sasaran_Angka4','SASARAN KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            {{Form::text('Sasaran_Angka4','',['class'=>'form-control','placeholder'=>'ANGKA SASARAN'])}}
                        </div>
                        <div class="col-md-6">
                            {{Form::textarea('Sasaran_Uraian4','',['class'=>'form-control','placeholder'=>'URAIAN SASARAN','rows'=>3,'id'=>'Sasaran_Uraian4'])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Target4','TARGET (%)',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Target4','',['class'=>'form-control','placeholder'=>'TARGET'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Jumlah4','NILAI USULAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Jumlah4','',['class'=>'form-control','placeholder'=>'NILAI USULAN'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Prioritas','PRIORITAS',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::select('Prioritas', HelperKegiatan::getDaftarPrioritas(),'none',['class'=>'form-control','id'=>'Prioritas'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Descr','',['class'=>'form-control','placeholder'=>'KETERANGAN / CATATAN PENTING'])}}
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
    <div class="panel panel-flat border-top-lg border-top-info border-bottom-info" id="divdatatablerinciankegiatan">
        @include('pages.limitless.musrenbang.usulanmusrenkab.datatablerinciankegiatan')         
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
    AutoNumeric.multiple(['#No','#Sasaran_Angka4'], {
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
    AutoNumeric.multiple(['#Target4'], {
                                            allowDecimalPadding: false,
                                            minimumValue:0.00,
                                            maximumValue:100.00,
                                            numericPos:true,
                                            decimalPlaces : 2,
                                            digitGroupSeparator : '',
                                            showWarnings:false,
                                            unformatOnSubmit: true,
                                            modifyValueOnWheel:false
                                        });

    AutoNumeric.multiple(['#Jumlah4'],{
                                            allowDecimalPadding: false,
                                            decimalCharacter: ",",
                                            digitGroupSeparator: ".",
                                            unformatOnSubmit: true,
                                            showWarnings:false,
                                            modifyValueOnWheel:false
                                        });

    $("#frmdata :input").not('[name=PemilikPokokID],[name=PokPirID]').prop("disabled", true);
    $('#PemilikPokokID.select').select2({
        placeholder: "PILIH PEMILIK POKOK PIKIRAN",
        allowClear:true
    }); 
    $('#PokPirID.select').select2({
        placeholder: "PILIH POKOK PIKIRAN",
        allowClear:true
    });
    $(document).on('change','#PemilikPokokID',function(ev) {
        ev.preventDefault();
        var PemilikPokokID=$('#PemilikPokokID').val();
        if (PemilikPokokID == '')
        {
            $("#frmdata :input").not('[name=PemilikPokokID],[name=PokPirID]').prop("disabled", true);
            $('#Uraian').val('');
            $('#Sasaran_Angka4').val('');
            $('#Sasaran_Uraian4').val('');
            $('#Target4').val('');
            $('#Jumlah4').val('');
            $('#Prioritas').val('none');
            $('#Descr').val('');
        }
        else
        {            
            $.ajax({
                type:'post',
                url: url_current_page +'/filter',
                dataType: 'json',
                data: {                
                    "_token": token,
                    "PemilikPokokID": PemilikPokokID,
                    "RenjaID": $('#RenjaID').val(),
                    "create3":true
                },
                success:function(result)
                {                 
                    var daftar_pokir = result.daftar_pokir;
                    var listitems='<option></option>';
                    $.each(daftar_pokir,function(key,value){
                        listitems+='<option value="' + key + '">'+value+'</option>';                    
                    });
                    $('#PokPirID').html(listitems);
                },
                error:function(xhr, status, error){
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        }
    });
    $(document).on('change','#PokPirID',function(ev) {
        ev.preventDefault();
        var PokPirID=$('#PokPirID').val();
        if (PokPirID == '')
        {
            $("#frmdata :input").not('[name=PemilikPokokID],[name=PokPirID]').prop("disabled", true);
            $('#Uraian').val('');
            $('#Sasaran_Angka4').val('');
            $('#Sasaran_Uraian4').val('');
            $('#Target4').val('');
            $('#Jumlah4').val('');
            $('#Prioritas').val('none');
            $('#Descr').val('');
        }
        else
        {
            $("#frmdata *").prop("disabled", false);
            $.ajax({
                type:'post',
                url: url_current_page +'/filter',
                dataType: 'json',
                data: {                
                    "_token": token,
                    "PokPirID": PokPirID,
                    "create3":true
                },
                success:function(result)
                {                    
                    console.log(result.data_kegiatan)  ;    
                    $('#Uraian').val(result.data_kegiatan.Uraian);   
                    AutoNumeric.getAutoNumericElement('#Sasaran_Angka4').set(result.data_kegiatan.Sasaran_Angka4);               
                    $('#Sasaran_Uraian4').val(result.data_kegiatan.Sasaran_Uraian4);                    
                    AutoNumeric.getAutoNumericElement('#Target4').set(100);               
                    AutoNumeric.getAutoNumericElement('#Jumlah4').set(result.data_kegiatan.NilaiUsulan);  

                    $("#Prioritas option").filter(function () {
                        return ($(this).val() == result.data_kegiatan.Prioritas);
                    }).attr('selected', 'selected');                        
                },
                error:function(xhr, status, error){
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        }        
    });
    $("#divdatatablerinciankegiatan").on("click",".btnDelete", function(){
        if (confirm('Apakah Anda ingin menghapus Data Rincian Kegiatan Pra Renja OPD / SKPD ini ?')) {
            let url_ = $(this).attr("data-url");
            let id = $(this).attr("data-id");
            $.ajax({            
                type:'post',
                url:url_+'/'+id,
                dataType: 'json',
                data: {
                    "_method": 'DELETE',
                    "_token": token,
                    "id": id,
                    'rinciankegiatan':true
                },
                success:function(result){ 
                    if (result.success==1){
                        $('#divdatatablerinciankegiatan').html(result.datatable);                        
                    }else{
                        console.log("Gagal menghapus data rincian kegiatan Pra Renja OPD / SKPD dengan id "+id);
                    }                    
                },
                error:function(xhr, status, error){
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        }        
    });
    $('#frmdata').validate({
        ignore: [], 
        rules: {
            PemilikPokokID : {
                required: true
            },
            PokPirID : {
                required: true
            },            
            No : {
                required: true
            },
            Uraian : {
                required: true
            },
            Sasaran_Angka4 : {
                required: true
            },
            Sasaran_Uraian4 : {
                required: true
            },
            Jumlah4 : {
                required: true
            },
            Target4 : {
                required: true
            },
            Prioritas : {
                valueNotEquals: 'none'
            } 
        },
        messages : {
            PemilikPokokID : {
                required: "Mohon untuk dipilih Anggota Dewan (Pemilik Pokok Pikiran)."
            },
            PokPirID : {
                required: "Mohon untuk dipilih Usulan Pokok Pikiran."
            },
            No : {
                required: "Mohon untuk di isi Nomor rincian kegiatan."
            },
            Uraian : {
                required: "Mohon untuk di isi uraian rincian kegiatan."
            },
            Sasaran_Angka4 : {
                required: "Mohon untuk di isi angka sasaran rincian kegiatan."
            },
            Sasaran_Uraian4 : {
                required: "Mohon untuk di isi sasaran rincian kegiatan."
            },
            Target4 : {
                required: "Mohon untuk di isi target rincian kegiatan."
            },
            Jumlah4 : {
                required: "Mohon untuk di isi nilai usulan rincian kegiatan."
            },
            Prioritas : {
                valueNotEquals: "Mohon untuk di pilih prioritas rincian kegiatan."
            }
        }      
    });   
});  
</script>
@endsection