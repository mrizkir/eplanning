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
    @include('pages.limitless.report.reportusulanrenja.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">WORKFLOW</a></li>
    <li><a href="{!!route(Helper::getNameOfPage('index'))!!}">{{$page_title}}</a></li>
    <li class="active">UBAH DATA RINCIAN KEGIATAN</li>
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                UBAH DATA RINCIAN KEGIATAN
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>               
                        <a href="{!!route(Helper::getNameOfPage('index'))!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        {!! Form::open(['url'=>route(Helper::getNameOfPage('update2'),$renja->RenjaRincID),'method'=>'put','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                              
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
                {{Form::label('No','NOMOR',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('No',$renja->No,['class'=>'form-control','placeholder'=>'NOMOR URUT KEGIATAN','readonly'=>true])}}
                </div>
            </div>    
            <div class="form-group">
                {{Form::label('Uraian','NAMA/URAIAN KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Uraian',$renja->Uraian,['class'=>'form-control','placeholder'=>'NAMA ATAU URAIAN KEGIATAN'])}}
                </div>
            </div>        
            <div class="form-group">
                {{Form::label('Sasaran_Angka','SASARAN KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            {{Form::text('Sasaran_Angka',Helper::formatAngka($renja->Sasaran_Angka),['class'=>'form-control','placeholder'=>'ANGKA SASARAN'])}}
                        </div>
                        <div class="col-md-6">
                            {{Form::textarea('Sasaran_Uraian',$renja->Sasaran_Uraian,['class'=>'form-control','placeholder'=>'URAIAN SASARAN','rows'=>3,'id'=>'Sasaran_Uraian'])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Target','TARGET (%)',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Target',Helper::formatAngka($renja->Target),['class'=>'form-control','placeholder'=>'TARGET'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Jumlah','NILAI USULAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Jumlah',$renja->Jumlah,['class'=>'form-control','placeholder'=>'NILAI USULAN'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Prioritas','PRIORITAS',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::select('Prioritas', HelperKegiatan::getDaftarPrioritas(),$renja->Prioritas,['class'=>'form-control','id'=>'Prioritas'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Descr',$renja->Descr,['class'=>'form-control','placeholder'=>'KETERANGAN / CATATAN PENTING'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Status','STATUS',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::select('Status', HelperKegiatan::getStatusKegiatan(),$renja->Status,['class'=>'form-control','id'=>'Prioritas'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('transfer_ke','TRANSFER KE',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <div class="checkbox checkbox-switch">
                        {{Form::checkbox('transfer_ke','1',0,['class'=>'switch','data-on-text'=>$label_transfer,'data-off-text'=>'TIDAK'])}}                                     
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
<script src="{!!asset('themes/limitless/assets/js/switch.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $(".switch").bootstrapSwitch();
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