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
    @include('pages.limitless.rkpd.pembahasanrkpdp.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">WORKFLOW</a></li>
    <li><a href="{!!route(Helper::getNameOfPage('index'))!!}">{{$page_title}}</a></li>
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
                        <a href="{!!route(Helper::getNameOfPage('show'),['uuid'=>$rkpd->RKPDID])!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        {!! Form::open(['url'=>route(Helper::getNameOfPage('update'),$rkpd->RKPDID),'method'=>'put','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                        
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
                <label class="col-md-2 control-label">OPD / SKPD: </label>
                <div class="col-md-10">
                    <p class="form-control-static">
                        <span class="label border-left-primary label-striped">[{{$organisasi->kode_organisasi}}] {{$organisasi->OrgNm}}</span>
                    </p>
                </div>                            
            </div>                          
            <div class="form-group">
                <label class="col-md-2 control-label">UNIT KERJA: </label>
                <div class="col-md-10">
                    <p class="form-control-static">
                        <span class="label border-left-primary label-striped">[{{$organisasi->kode_suborganisasi}}] {{$organisasi->SOrgNm}}</span>
                    </p>
                </div>                            
            </div>
            <fieldset class="content-group">
                <legend class="text-bold">PROGRAM / KEGIATAN</legend>
                <div class="form-group">
                    {{Form::label('UrsID','NAMA URUSAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <p class="form-control-static">{{$rkpd->Nm_Bidang}}</p>           
                    </div>
                </div> 
                <div class="form-group">
                    {{Form::label('PrgID','NAMA PROGRAM',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <p class="form-control-static">[{{$rkpd->kode_program}}] {{$rkpd->PrgNm}}</p>
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('KgtID','NAMA SUB KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <p class="form-control-static">[{{$rkpd->kode_subkegiatan}}]{{$rkpd->SubKgtNm}}</p>
                    </div>
                </div>
            </fieldset>
            <fieldset class="content-group">
                <legend class="text-bold">INDIKATOR KINERJA</legend>
                <div class="form-group">
                    {{Form::label('NamaIndikator','KELUARAN (OUTPUT) KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('NamaIndikator',$rkpd['NamaIndikator'],['rows'=>3,'class'=>'form-control','placeholder'=>'KELUARAN (OUTPUT) KEGIATAN'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Sasaran_Uraian','HASIL (OUTCOME) KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Sasaran_Uraian',$rkpd['Sasaran_Uraian'],['rows'=>3,'class'=>'form-control','placeholder'=>'KELUARAN (OUTCOME) KEGIATAN'])}}
                    </div>
                </div>
            </fieldset>   
            <fieldset class="content-group">
                <legend class="text-bold">TARGET KINERJA DAN KERANGKA PENDANAAN</legend>
                <div class="form-group">
                    {{Form::label('Target','JUMLAH KELUARAN (OUTPUT) KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Target',Helper::formatAngka($rkpd['Target']),['class'=>'form-control','placeholder'=>'KELUARAN KEGIATAN'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Sasaran_Angka','JUMLAH HASIL (OUTCOME) KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Sasaran_Angka',Helper::formatAngka($rkpd['Sasaran_Angka']),['class'=>'form-control','placeholder'=>'HASIL KEGIATAN'])}}                            
                    </div>                   
                </div>  
                <div class="form-group">
                    <label form="NilaiSebelum" class="control-label col-md-2">PAGU DANA (TAHUN PERENCANAAN ({{HelperKegiatan::getTahunPerencanaan()-1}})</label>
                    <div class="col-md-10">
                        {{Form::text('NilaiSebelum',$rkpd['NilaiSebelum'],['class'=>'form-control','placeholder'=>'PAGU DANA N-1','id'=>'NilaiSebelum'])}}                            
                    </div>
                </div>          
                <div class="form-group">
                    <label form="NilaiUsulan" class="control-label col-md-2">PAGU DANA</label>
                    <div class="col-md-10">
                        {{Form::text('NilaiUsulan',$rkpd['NilaiUsulan'],['class'=>'form-control','placeholder'=>'NILAI USULAN (TA)','id'=>'NilaiUsulan','readonly'=>true])}}                            
                        <span class="help-block">Jumlah Pagu Dana ini akan terisi secara otomatis saat menginput / mengupdate / menghapus rincian kegiatan.</span>              
                    </div>
                </div>          
            </fieldset>      
            <fieldset class="content-group">
                <legend class="text-bold">PERKIRAAN MAJU (TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()+1}})</legend>
                <div class="form-group">
                    {{Form::label('Sasaran_AngkaSetelah','JUMLAH HASIL (OUTCOME) KEGIATAN N+1',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Sasaran_AngkaSetelah',$rkpd['Sasaran_AngkaSetelah'],['class'=>'form-control','placeholder'=>'JUMLAH HASIL KEGIATAN  N+1'])}}                            
                    </div>
                </div> 
                <div class="form-group">
                    {{Form::label('Sasaran_AngkaSetelah','URAIAN HASIL (OUTCOME) KEGIATAN N+1',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Sasaran_UraianSetelah',$rkpd['Sasaran_UraianSetelah'],['rows'=>3,'class'=>'form-control','placeholder'=>'URAIAN HASIL (OUTCOME) KEGIATAN N+1'])}}
                    </div>
                </div> 
                <div class="form-group">
                    {{Form::label('Sasaran_AngkaSetelah','PAGU DANA KEGIATAN N+1',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('NilaiSetelah',$rkpd['NilaiSetelah'],['class'=>'form-control','placeholder'=>'PAGU DANA KEGIATAN N+1','id'=>'NilaiSetelah'])}}
                    </div>
                </div> 
            </fieldset>
            <fieldset class="content-group">
                <legend class="text-bold">LAINNYA</legend>
                    <div class="form-group">
                    {{Form::label('SumberDanaID','SUMBER DANA',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('SumberDanaID', $sumber_dana, $rkpd['SumberDanaID'],['class'=>'form-control','id'=>'SumberDanaID'])}}
                    </div>
                </div> 
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN / CATATAN PENTING',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Descr',$rkpd['Descr'],['rows'=>3,'class'=>'form-control','placeholder'=>'KETERANGAN'])}}
                    </div>
                </div>                  
            </fieldset> 
        </div>
        <div class="panel-body">
            <div class="form-group">            
                <div class="col-md-10 col-md-offset-2">                        
                    {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}                        
                </div>
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
    AutoNumeric.multiple(['#Sasaran_Angka','#Sasaran_AngkaSetelah'], {
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

    AutoNumeric.multiple(['#NilaiSebelum','#NilaiUsulan','#NilaiSetelah'],{
                                            allowDecimalPadding: false,
                                            decimalCharacter: ",",
                                            digitGroupSeparator: ".",
                                            unformatOnSubmit: true,
                                            showWarnings:false,
                                            modifyValueOnWheel:false
                                        });
                                        $('#frmdata').validate({
        ignore:[],
        rules: {       
            Sasaran_Angka : {
                required: true,
            },
            Sasaran_Uraian : {
                required: true
            },
            Sasaran_AngkaSetelah : {
                required: true
            },
            Sasaran_UraianSetelah : {
                required: true
            },
            Target : {
                required: true
            },
            NilaiSebelum : {
                required: true
            },
            NilaiUsulan : {
                required: true
            },
            NilaiSetelah : {
                required: true
            },
            NamaIndikator : {
                required: true,                
            },
            SumberDanaID : {
                valueNotEquals: 'none'
            }         
        },
        messages : {            
            Sasaran_Angka : {
                required: "Mohon untuk di isi angka sasaran kegiatan.",                
            },
            Sasaran_Uraian : {
                required: "Mohon untuk di isi uraian sasaran kegiatan.",                
            },
            Sasaran_AngkaSetelah : {
                required: "Mohon untuk di isi angka sasaran kegiatan (N+1).",                
            },
            Sasaran_UraianSetelah : {
                required: "Mohon untuk di isi uraian sasaran kegiatan (N+1).",                
            },
            Target : {
                required: "Mohon untuk di isi persentase target kegiatan.",                
            },
            NilaiSebelum : {
                required: "Mohon untuk di isi nilai (TA-1).",                
            },
            NilaiUsulan : {
                required: "Mohon untuk di isi nilai (TA).",                
            },
            NilaiSetelah : {
                required: "Mohon untuk di isi nilai (TA+1).",                
            },
            NamaIndikator : {
                required: "Mohon untuk di isi indikator kegiatan.",                
            },
            SumberDanaID : {
                valueNotEquals: "Mohon untuk di pilih sumber dana untuk kegiatan ini."                
            }

        }      
    });  
});
</script>
@endsection