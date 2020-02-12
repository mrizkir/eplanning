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
    @include('pages.limitless.rkpd.usulanrenja.info')
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
                        <a href="{!!route(Helper::getNameOfPage('show'),['uuid'=>$renja->RenjaID])!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        {!! Form::open(['url'=>route(Helper::getNameOfPage('update'),$renja->RenjaID),'method'=>'put','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                        
        {{Form::hidden('UrsID', $UrsID_selected,['id'=>'UrsID'])}}      
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
                    {{Form::label('PrgID','NAMA PROGRAM',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('PrgID', $daftar_program, $renja->PrgID,['class'=>'select','id'=>'PrgID'])}}
                        <span class="help-block">Bila program tidak ada, silahkan dicek di Mapping Program -> OPD</span>  
                    </div>
                </div>      
                <div class="form-group">
                    {{Form::label('KgtID','NAMA SUB KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('KgtID', $daftar_kegiatan, $renja->KgtID,['class'=>'select','id'=>'KgtID'])}}
                        <span class="help-block">Bila kegiatan tidak ada, barangkali sudah di inputkan. Prinsipnya satu sub kegiatan tidak bisa digunakan oleh OPD/SKPD yang sama.</span>              
                    </div>
                </div>
            </fieldset>                 
            <fieldset class="content-group">
                <legend class="text-bold">INDIKATOR KINERJA</legend>
                <div class="form-group">
                    {{Form::label('NamaIndikator','KELUARAN (OUTPUT) KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('NamaIndikator',$renja['NamaIndikator'],['rows'=>3,'class'=>'form-control','placeholder'=>'KELUARAN (OUTPUT) KEGIATAN'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Sasaran_Uraian','HASIL (OUTCOME) KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Sasaran_Uraian',$renja['Sasaran_Uraian'],['rows'=>3,'class'=>'form-control','placeholder'=>'KELUARAN (OUTCOME) KEGIATAN'])}}
                    </div>
                </div>
            </fieldset>
            <fieldset class="content-group">
                <legend class="text-bold">TARGET KINERJA DAN KERANGKA PENDANAAN</legend>
                <div class="form-group">
                    {{Form::label('Target','JUMLAH KELUARAN (OUTPUT) KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Target',Helper::formatAngka($renja['Target']),['class'=>'form-control','placeholder'=>'KELUARAN KEGIATAN'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Sasaran_Angka','JUMLAH HASIL (OUTCOME) KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Sasaran_Angka',Helper::formatAngka($renja['Sasaran_Angka']),['class'=>'form-control','placeholder'=>'HASIL KEGIATAN'])}}                            
                    </div>                   
                </div>  
                <div class="form-group">
                    <label form="NilaiSebelum" class="control-label col-md-2">PAGU DANA (TAHUN PERENCANAAN ({{HelperKegiatan::getTahunPerencanaan()-1}})</label>
                    <div class="col-md-10">
                        {{Form::text('NilaiSebelum',$renja['NilaiSebelum'],['class'=>'form-control','placeholder'=>'PAGU DANA N-1','id'=>'NilaiSebelum'])}}                            
                    </div>
                </div>          
                <div class="form-group">
                    <label form="NilaiUsulan" class="control-label col-md-2">PAGU DANA</label>
                    <div class="col-md-10">
                        {{Form::text('NilaiUsulan',$renja['NilaiUsulan'],['class'=>'form-control','placeholder'=>'NILAI USULAN (TA)','id'=>'NilaiUsulan','readonly'=>true])}}                            
                        <span class="help-block">Jumlah Pagu Dana ini akan terisi secara otomatis saat menginput / mengupdate / menghapus rincian kegiatan.</span>              
                    </div>
                </div>          
            </fieldset>
            <fieldset class="content-group">
                <legend class="text-bold">PERKIRAAN MAJU (TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()+1}})</legend>
                <div class="form-group">
                    {{Form::label('Sasaran_AngkaSetelah','JUMLAH HASIL (OUTCOME) KEGIATAN N+1',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Sasaran_AngkaSetelah',Helper::formatAngka($renja['Sasaran_AngkaSetelah']),['class'=>'form-control','placeholder'=>'JUMLAH HASIL KEGIATAN  N+1'])}}                            
                    </div>
                </div> 
                <div class="form-group">
                    {{Form::label('Sasaran_AngkaSetelah','URAIAN HASIL (OUTCOME) KEGIATAN N+1',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Sasaran_UraianSetelah',$renja['Sasaran_UraianSetelah'],['rows'=>3,'class'=>'form-control','placeholder'=>'URAIAN HASIL (OUTCOME) KEGIATAN N+1'])}}
                    </div>
                </div> 
                <div class="form-group">
                    {{Form::label('Sasaran_AngkaSetelah','PAGU DANA KEGIATAN N+1',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('NilaiSetelah',$renja['NilaiSetelah'],['class'=>'form-control','placeholder'=>'PAGU DANA KEGIATAN N+1','id'=>'NilaiSetelah'])}}
                    </div>
                </div> 
            </fieldset>
            <fieldset class="content-group">
                <legend class="text-bold">LAINNYA</legend>
                    <div class="form-group">
                    {{Form::label('SumberDanaID','SUMBER DANA',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('SumberDanaID', $sumber_dana, $renja['SumberDanaID'],['class'=>'form-control','id'=>'SumberDanaID'])}}
                    </div>
                </div> 
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN / CATATAN PENTING',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Descr',$renja['Descr'],['rows'=>3,'class'=>'form-control','placeholder'=>'KETERANGAN'])}}
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
    $('#PrgID.select').select2({
        placeholder: "PILIH NAMA PROGRAM",
        allowClear:true
    });
    $('#KgtID.select').select2({
        placeholder: "PILIH NAMA SUB KEGIATAN",
        allowClear:true
    });   
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
    
    $(document).on('change','#PrgID',function(ev) {
        ev.preventDefault();
        PrgID=$(this).val();        
        $.ajax({
            type:'post',
            url: '{{route(Helper::getNameOfPage("pilihusulankegiatan"))}}',
            dataType: 'json',
            data: {
                "_token": token,
                "PrgID": PrgID,
            },
            success:function(result)
            {   
                var daftar_kegiatan = result.daftar_kegiatan;
                var listitems='<option></option>';
                $.each(daftar_kegiatan,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });
                $('#KgtID').html(listitems);
            },
            error:function(xhr, status, error)
            {   
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });
    $('#frmdata').validate({
        ignore:[],
        rules: {
            UrsID : {
                required: true
            },  
            PrgID : {
                required: true
            },
            KgtID : {
                required: true
            },         
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
            UrsID : {
                required: "Mohon untuk di pilih urusan untuk kegiatan ini.",                
            },            
            PrgID : {
                required: "Mohon untuk di pilih program nama kegiatan.",                
            },
            KgtID : {
                required: "Mohon untuk di pilih nama kegiatan.",                
            },
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