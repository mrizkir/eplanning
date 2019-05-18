@extends('layouts.limitless.l_main')
@section('page_title')
    VERIFIKASI TAPD
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        VERIFIKASI TAPD TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.verifikasirenja.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('verifikasirenja.index')!!}">VERIFIKASI TAPD</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                VERIFIKASI TAPD
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>               
                        <a href="{!!route('verifikasirenja.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        {!! Form::open(['action'=>['RKPD\VerifikasiRenjaController@update',$renja->RenjaRincID],'method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                                      
        {{Form::hidden('_method','PUT')}}
        <div class="panel-body">     
            <div class="form-group">
                <label class="col-md-2 control-label">POSISI ENTRI: </label>
                <div class="col-md-10">
                    <p class="form-control-static">
                        <span class="label border-left-primary label-striped">VERIFIKASI TAPD</span>
                    </p>
                </div>                            
            </div> 
            <div class="form-group">
                {{Form::label('No','NOMOR',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('No',$renja->No,['class'=>'form-control','placeholder'=>'NOMOR URUT KEGIATAN','disabled'=>true])}}
                </div>
            </div> 
            <div class="form-group">
                {{Form::label('Uraian','NAMA/URAIAN KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Uraian',$renja->Uraian,['class'=>'form-control','placeholder'=>'NAMA ATAU URAIAN KEGIATAN','disabled'=>$renja->Privilege==1])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Sasaran_Angka5','SASARAN KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            {{Form::text('Sasaran_Angka5',Helper::formatAngka($renja->Sasaran_Angka5),['class'=>'form-control','placeholder'=>'ANGKA SASARAN','disabled'=>$renja->Privilege==1])}}
                        </div>
                        <div class="col-md-6">
                            {{Form::textarea('Sasaran_Uraian5',$renja->Sasaran_Uraian5,['class'=>'form-control','placeholder'=>'URAIAN SASARAN','rows'=>3,'disabled'=>$renja->Privilege==1])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Target5','TARGET (%)',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Target5',Helper::formatAngka($renja->Target5),['class'=>'form-control','placeholder'=>'TARGET','disabled'=>$renja->Privilege==1])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Jumlah5','NILAI USULAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Jumlah5',Helper::formatUang($renja->Jumlah5),['class'=>'form-control','placeholder'=>'NILAI USULAN','disabled'=>$renja->Privilege==1])}}
                </div>
            </div>   
            <div class="form-group">
                <label class="col-md-2 control-label">STATUS :</label> 
                <div class="col-md-10">
                    {{Form::select('cmbStatus', HelperKegiatan::getStatusKegiatan(), $renja->Status,['class'=>'form-control','disabled'=>$renja->Privilege==1])}}
                </div>
            </div>    
            <div class="form-group">
                {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Descr',$renja->Descr,['class'=>'form-control','placeholder'=>'KETERANGAN / CATATAN PENTING','disabled'=>$renja->Privilege==1])}}
                </div>
            </div>            
        </div>        
        <div class="panel-footer">
            @if ($renja->Privilege==0)
            <div class="col-md-10 col-md-offset-2">                        
                {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}                                       
            </div>    
            @else
            <p>Sudah diproses tombol simpan di non-aktifkan</p>
            @endif            
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
    AutoNumeric.multiple(['#No','#Sasaran_Angka5'], {
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
    AutoNumeric.multiple(['#Target5'], {
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

    AutoNumeric.multiple(['#Jumlah5'],{
                                            allowDecimalPadding: false,
                                            decimalCharacter: ",",
                                            digitGroupSeparator: ".",
                                            unformatOnSubmit: true,
                                            showWarnings:false,
                                            modifyValueOnWheel:false
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
            Sasaran_Angka5 : {
                required: true
            },
            Sasaran_Uraian5 : {
                required: true
            },
            Jumlah5 : {
                required: true
            },
            Target5 : {
                required: true
            }
        },
        messages : {
            No : {
                required: "Mohon untuk di isi Nomor rincian kegiatan."
            },
            Uraian : {
                required: "Mohon untuk di isi uraian rincian kegiatan."
            },
            Sasaran_Angka5 : {
                required: "Mohon untuk di isi angka sasaran rincian kegiatan."
            },
            Sasaran_Uraian5 : {
                required: "Mohon untuk di isi sasaran rincian kegiatan."
            },
            Target5 : {
                required: "Mohon untuk di isi target rincian kegiatan."
            },
            Jumlah5 : {
                required: "Mohon untuk di isi nilai usulan rincian kegiatan."
            }
        }      
    });   
});
</script>
@endsection