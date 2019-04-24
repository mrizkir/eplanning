@extends('layouts.limitless.l_main')
@section('page_title')
    USULAN PRA RENJA OPD/SKPD
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        USULAN PRA RENJA OPD/SKPD TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.usulanprarenjaopd.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">ASPIRASI / USULAN</a></li>
    <li><a href="{!!route('usulanprarenjaopd.index')!!}">USULAN PRA RENJA OPD/SKPD</a></li>
    <li class="active">TAMBAH DATA RINCIAN KEGIATAN DARI MUSRENBANG KECAMATAN</li>
@endsection
@section('page_sidebar')
    @include('pages.limitless.rkpd.usulanprarenjaopd.l_sidebar_prarenja_create')
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                TAMBAH DATA RINCIAN KEGIATAN DARI MUSRENBANG KECAMATAN
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>               
                        <a href="{!!route('usulanprarenjaopd.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        {!! Form::open(['action'=>'RKPD\UsulanPraRenjaOPDController@store2','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                              
        {{Form::hidden('RenjaID',$renja->RenjaID,['id'=>'RenjaID'])}}
        {{Form::hidden('PmDesaID','',['id'=>'PmDesaID'])}}
        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-2 control-label">POSISI ENTRI: </label>
                <div class="col-md-10">
                    <p class="form-control-static">
                        <span class="label border-left-primary label-striped">USULAN PRA RENJA OPD / SKPD</span>
                    </p>
                </div>                            
            </div>   
            <div class="form-group">
                {{Form::label('PMProvID','PROVINSI',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::select('PMProvID', $daftar_provinsi,config('globalsettings.default_provinsi'),['class'=>'form-control','id'=>'PMProvID'])}}
                </div>
            </div>       
            <div class="form-group">
                {{Form::label('PmKotaID','KAB. / KOTA',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::select('PmKotaID', $daftar_kota_kab,config('globalsettings.defaul_kota_atau_kab'),['class'=>'form-control','id'=>'PMProvID'])}}
                </div>
            </div>  
            <div class="form-group">
                <label class="col-md-2 control-label">KECAMATAN</label> 
                <div class="col-md-10">
                    <select name="PmKecamatanID" id="PmKecamatanID" class="select">
                        <option></option>          
                        @foreach ($daftar_kecamatan as $k=>$item)
                            <option value="{{$k}}">{{$item}}</option>
                        @endforeach              
                    </select>                         
                </div>
            </div>  
            <div class="form-group">
                <label class="col-md-2 control-label">USULAN KEGIATAN</label> 
                <div class="col-md-10">
                    <select name="UsulanKecID" id="UsulanKecID" class="select">
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
                {{Form::label('Sasaran_Angka1','SASARAN KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            {{Form::text('Sasaran_Angka1','',['class'=>'form-control','placeholder'=>'ANGKA SASARAN'])}}
                        </div>
                        <div class="col-md-6">
                            {{Form::textarea('Sasaran_Uraian1','',['class'=>'form-control','placeholder'=>'URAIAN SASARAN','rows'=>3,'id'=>'Sasaran_Uraian1'])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Target1','TARGET (%)',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Target1','',['class'=>'form-control','placeholder'=>'TARGET'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Jumlah1','NILAI USULAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Jumlah1','',['class'=>'form-control','placeholder'=>'NILAI USULAN'])}}
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
        @include('pages.limitless.rkpd.usulanprarenjaopd.datatablerinciankegiatan')         
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
    AutoNumeric.multiple(['#No','#Sasaran_Angka1'], {
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
    AutoNumeric.multiple(['#Target1'], {
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

    AutoNumeric.multiple(['#Jumlah1'],{
                                            allowDecimalPadding: false,
                                            decimalCharacter: ",",
                                            digitGroupSeparator: ".",
                                            unformatOnSubmit: true,
                                            showWarnings:false,
                                            modifyValueOnWheel:false
                                        });
    //styling select
    $('#PMProvID.select').select2({
        placeholder: "PILIH PROVINSI",
        allowClear:true
    }); 
    $('#PmKotaID.select').select2({
        placeholder: "PILIH KABUPATEN / KOTA",
        allowClear:true
    }); 
    $('#PmKecamatanID.select').select2({
        placeholder: "PILIH KECAMATAN",
        allowClear:true
    }); 
    $('#UsulanKecID.select').select2({
        placeholder: "PILIH USULAN KEGIATAN DARI MUSRENBANG KECAMATAN",
        allowClear:true
    });
    $("#frmdata :input").not('[name=PmKecamatanID],[name=UsulanKecID]').prop("disabled", true);
    $(document).on('change','#PmKecamatanID',function(ev) {
        ev.preventDefault();
        var PmKecamatanID=$('#PmKecamatanID').val();
        if (PmKecamatanID == '')
        {
            $("#frmdata :input").not('[name=PmKecamatanID],[name=UsulanKecID]').prop("disabled", true);
            $('#PmDesaID').val('');
            $('#Uraian').val('');
            $('#Sasaran_Angka1').val('');
            $('#Sasaran_Uraian1').val('');
            $('#Target1').val('');
            $('#Jumlah1').val('');
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
                    "PmKecamatanID": PmKecamatanID,
                    "RenjaID": $('#RenjaID').val(),
                    "create2":true
                },
                success:function(result)
                {   
                    var daftar_uraian = result.daftar_uraian;
                    var listitems='<option></option>';
                    $.each(daftar_uraian,function(key,value){
                        listitems+='<option value="' + key + '">'+value+'</option>';                    
                    });
                    $('#UsulanKecID').html(listitems);
                },
                error:function(xhr, status, error){
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        }
    }); 
    $(document).on('change','#UsulanKecID',function(ev) {
        ev.preventDefault();
        var UsulanKecID=$('#UsulanKecID').val();
        if (UsulanKecID == '')
        {
            $("#frmdata :input").not('[name=PmKecamatanID],[name=UsulanKecID]').prop("disabled", true);
            $('#PmDesaID').val('');
            $('#Uraian').val('');
            $('#Sasaran_Angka1').val('');
            $('#Sasaran_Uraian1').val('');
            $('#Target1').val('');
            $('#Jumlah1').val('');
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
                    "UsulanKecID": UsulanKecID,
                    "create2":true
                },
                success:function(result)
                {             
                    console.log(result);             
                    $('#PmDesaID').val(result.data_kegiatan.PmDesaID);
                    $('#Uraian').val(result.data_kegiatan.Uraian);   
                    AutoNumeric.getAutoNumericElement('#Sasaran_Angka1').set(result.data_kegiatan.Sasaran_Angka1);               
                    $('#Sasaran_Uraian1').val(result.data_kegiatan.Sasaran_Uraian1);                    
                    AutoNumeric.getAutoNumericElement('#Target1').set(100);               
                    AutoNumeric.getAutoNumericElement('#Jumlah1').set(result.data_kegiatan.NilaiUsulan);  

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
});
</script>
@endsection