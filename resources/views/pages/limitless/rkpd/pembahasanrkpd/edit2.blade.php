@extends('layouts.limitless.l_main')
@section('page_title')
    {{$page_title}}
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        {{$page_title}} TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.pembahasanrkpd.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">WORKFLOW</a></li>
    <li><a href="{!!route(Helper::getNameOfPage('index'))!!}">{{$page_title}}</a></li>
    <li class="active">UBAH DATA RINCIAN KEGIATAN DARI MUSRENBANG KECAMATAN</li>
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                UBAH DATA RINCIAN KEGIATAN DARI MUSRENBANG KECAMATAN
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>               
                        <a href="{!!route(Helper::getNameOfPage('show'),['uuid'=>$rkpd->RenjaID])!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        {!! Form::open(['url'=>route(Helper::getNameOfPage('update2'),$rkpd->RKPDRincID),'method'=>'put','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                                      
        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-2 control-label">POSISI ENTRI: </label>
                <div class="col-md-10">
                    <p class="form-control-static">
                        <span class="label border-left-primary label-striped">{{$page_title}}</span>
                    </p>
                </div>                            
            </div>   
            <div class="form-group">
                {{Form::label('PMProvID','PROVINSI',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <p class="form-control-static">{{$rkpd->Nm_Prov}}</p>
                </div>
            </div>       
            <div class="form-group">
                {{Form::label('PmKotaID','KAB. / KOTA',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <p class="form-control-static">{{$rkpd->Nm_Kota}}</p>
                </div>
            </div>  
            <div class="form-group">
                <label class="col-md-2 control-label">KECAMATAN</label> 
                <div class="col-md-10">
                    <p class="form-control-static">{{ucwords($rkpd->Nm_Kecamatan)}}</p>
                </div>
            </div>  
            <div class="form-group">
                <label class="col-md-2 control-label">USULAN KEGIATAN</label> 
                <div class="col-md-10">
                    <p class="form-control-static">{{ucwords($rkpd->NamaKegiatan)}}</p>
                </div>
            </div>                          
        </div>        
        <div class="panel-body">                    
            <div class="form-group">
                {{Form::label('No','NOMOR',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('No',$rkpd->No,['class'=>'form-control','placeholder'=>'NOMOR URUT KEGIATAN','readonly'=>true])}}
                </div>
            </div>    
            <div class="form-group">
                {{Form::label('Uraian','NAMA/URAIAN KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Uraian',$rkpd->Uraian,['class'=>'form-control','placeholder'=>'NAMA ATAU URAIAN KEGIATAN'])}}
                </div>
            </div>        
            <div class="form-group">
                {{Form::label('Sasaran_Angka','SASARAN KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            {{Form::text('Sasaran_Angka',Helper::formatAngka($rkpd->Sasaran_Angka),['class'=>'form-control','placeholder'=>'ANGKA SASARAN'])}}
                        </div>
                        <div class="col-md-6">
                            {{Form::textarea('Sasaran_Uraian',$rkpd->Sasaran_Uraian,['class'=>'form-control','placeholder'=>'URAIAN SASARAN','rows'=>3,'id'=>'Sasaran_Uraian'])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Target','TARGET (%)',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Target',Helper::formatAngka($rkpd->Target),['class'=>'form-control','placeholder'=>'TARGET'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Jumlah','NILAI USULAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Jumlah',$rkpd->Jumlah,['class'=>'form-control','placeholder'=>'NILAI USULAN'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Descr',$rkpd->Descr,['class'=>'form-control','placeholder'=>'KETERANGAN / CATATAN PENTING'])}}
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
        @include('pages.limitless.rkpd.pembahasanrkpd.datatablerinciankegiatan')         
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
    AutoNumeric.multiple(['#No','#Sasaran_Angka'], {
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
    AutoNumeric.multiple(['#Target'], {
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

    AutoNumeric.multiple(['#Jumlah'],{
                                            allowDecimalPadding: false,
                                            decimalCharacter: ",",
                                            digitGroupSeparator: ".",
                                            unformatOnSubmit: true,
                                            showWarnings:false,
                                            modifyValueOnWheel:false
                                        });

    $("#divdatatablerinciankegiatan").on("click",".btnDelete", function(){
        if (confirm('Apakah Anda ingin menghapus Data Rincian Kegiatan {{$page_title}} ini ?')) {
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
                        console.log("Gagal menghapus data rincian kegiatan {{$page_title}} dengan id "+id);
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
            No : {
                required: true
            },
            Uraian : {
                required: true
            },
            Sasaran_Angka : {
                required: true
            },
            Sasaran_Uraian : {
                required: true
            },
            Jumlah : {
                required: true
            },
            Target : {
                required: true
            },
            Prioritas : {
                valueNotEquals: 'none'
            } 
        },
        messages : {
            No : {
                required: "Mohon untuk di isi Nomor rincian kegiatan."
            },
            Uraian : {
                required: "Mohon untuk di isi uraian rincian kegiatan."
            },
            Sasaran_Angka : {
                required: "Mohon untuk di isi angka sasaran rincian kegiatan."
            },
            Sasaran_Uraian : {
                required: "Mohon untuk di isi sasaran rincian kegiatan."
            },
            Target : {
                required: "Mohon untuk di isi target rincian kegiatan."
            },
            Jumlah : {
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