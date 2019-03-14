@extends('layouts.limitless.l_main')
@section('page_title')
    USULAN DESA / KELURAHAN
@endsection
@section('page_header')
    <i class="icon-cube position-left"></i>
    <span class="text-semibold">
        USULAN DESA / KELURAHAN (MUSREN DESA) TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}  
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.musrenbang.aspirasimusrendesa.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">ASPIRASI / USULAN</a></li>
    <li><a href="{!!route('aspirasimusrendesa.index')!!}">DESA / KELURAHAN (MUSREN DESA)</a></li>
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
                        <a href="{!!route('aspirasimusrendesa.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>['Musrenbang\AspirasiMusrenDesaController@update',$data->UsulanDesaID],'method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}        
                {{Form::hidden('_method','PUT')}}
                <div class="form-group" id="divPmDesaID">
                    {{Form::label('PmDesaID','NAMA DESA / KELURAHAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <select name="PmDesaID" id="PmDesaID" class="select">
                            <option></option>
                            @foreach ($daftar_desa as $k=>$item)
                                <option value="{{$k}}" {{$data['PmDesaID'] == $k?' selected':''}}>{{$item}}</option>
                            @endforeach
                        </select>                                                
                    </div>
                </div>                 
                <div class="form-group">
                    {{Form::label('NamaKegiatan','NAMA KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('NamaKegiatan',$data['NamaKegiatan'],['class'=>'form-control','placeholder'=>'NAMA KEGIATAN'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Output','OUTPUT / HASIL',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Output',$data['Output'],['class'=>'form-control','placeholder'=>'OUTPUT / HASIL'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Lokasi','LOKASI KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">                        
                        {{Form::text('Lokasi',$data['Lokasi'],['class'=>'form-control','placeholder'=>'LOKASI KEGIATAN'])}}
                    </div>
                </div>                                      
                <div class="form-group">
                    {{Form::label('NilaiUsulan','NILAI USULAN ANGGARAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('NilaiUsulan',Helper::formatUang($data['NilaiUsulan']),['class'=>'form-control','placeholder'=>'NILAI USULAN ANGGARAN'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Target_Angka','TARGET (VOLUME)',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-6">
                                {{Form::text('Target_Angka',$data['Target_Angka'],['class'=>'form-control','placeholder'=>'ANGKA TARGET'])}}
                            </div>
                            <div class="col-md-6">
                                {{Form::text('Target_Uraian',$data['Target_Uraian'],['class'=>'form-control','placeholder'=>'TARGET URAIAN'])}}                                
                            </div>
                        </div>                                               
                    </div>
                </div>           
                <div class="form-group">
                    {{Form::label('Prioritas','PRIORITAS',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('Prioritas', Helper::getDaftarPrioritas(),$data['Prioritas'],['class'=>'form-control','id'=>'Prioritas'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Jeniskeg','JENIS KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">                        
                        <div class="checkbox checkbox-switch">
                            {{Form::checkbox('Jeniskeg','1',$data['Jeniskeg'],['class'=>'switch','data-on-text'=>'FISIK','data-off-text'=>'NON-FISIK'])}}                                     
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('SumberDanaID','SUMBER DANA',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('SumberDanaID', $sumber_dana,$data['SumberDanaID'],['class'=>'form-control','id'=>'KUrsID'])}}
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Descr',$data['Descr'],['class'=>'form-control','placeholder'=>'KETERANGAN'])}}
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
<div class="content">
    <div class="panel panel-flat bg-purple-300">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-help position-left"></i> 
                PETUNJUK
            </h5>
        </div>
        <div class="panel-body">
            <h4>PRIORITAS</h4>
            <p>
                1. DARURAT - Kegiatan yang sangat mendesak untuk dilaksanakan (darurat) karena jika tidak segera dilaksanakan akan membawa dampak
                yang bersifat multiplier (mengakibatkan kerugian langsung yang lebih besar pada warga desa) atau kegiatan tersebut mampu mengangkat
                potensi-potensi masyarakat sehingga lebih meningkatkan kesejahteraan.  
            </p>
            <p>
                2. REHABILITASI/REVITALISASI - Kegiatan penting yang tidak secara langsung membawa dampak pada warga desa.
            </p>
            <p>
                3. JANGKA PANJANG - Kegiatan yang membawa dampak jangka panjang akan tetapi keberadaanya adalah suatu keniscayaan.
            </p>
        </div>
    </div>
</div>
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/switch.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $("#frmdata").on('change','#PmDesaID', function(){
        $('#divPmDesaID').removeClass('has-error');
        $('#PmDesaID-error').remove();
    });
    $(".switch").bootstrapSwitch();
    //styling select
    $('.select').select2({
        placeholder: "PILIH DESA / KELURAHAN",
        allowClear:true
    });    
    AutoNumeric.multiple(['#NilaiUsulan'],[{
                                            allowDecimalPadding: false,
                                            decimalCharacter: ",",
                                            digitGroupSeparator: ".",
                                            unformatOnSubmit: true,
                                            showWarnings:false,
                                            modifyValueOnWheel:false
                                        }]);
    AutoNumeric.multiple(['#Target_Angka'], {
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
    $('#frmdata').validate({
        ignore:[],
        rules: {
            PmDesaID : {
                required: true
            },           
            NamaKegiatan : {
                required: true,
                minlength: 5
            },
            Output : {
                required: true
            },
            Lokasi : {
                required: true
            },
            NilaiUsulan : {
                required: true,                
            },
            Target_Uraian : {
                required: true
            },
            Target_Angka : {
                required: true
            },
            Prioritas : {
                valueNotEquals : 'none'   
            },    
            SumberDanaID : {
                valueNotEquals : 'none'           
            },         
        },
        messages : {
            PmDesaID : {
                required: "Mohon untuk di pilih desa apa untuk kegiatan ini.",                
            },            
            NamaKegiatan : {
                required: "Mohon untuk di isi nama kegiatan.",                
            },
            Output : {
                required: "Mohon untuk di isi output kegiatan.",                
            },
            Lokasi : {
                required: "Mohon untuk di isi lokasi kegiatan.",                
            },
            NilaiUsulan : {
                required: "Mohon untuk di isi nilai usulan (Rp).",                
            },
            Target_Uraian : {
                required: "Mohon untuk di isi deskripsi target.",                
            },
            Target_Angka : {
                required: "Mohon untuk di isi target angka.",                
            },
            Prioritas : {
                valueNotEquals: "Mohon untuk di pilih prioritas kegiatan.",                
            },
            SumberDanaID : {
                valueNotEquals: "Mohon untuk di pilih sumber dana untuk kegiatan ini."                
            }

        }      
    });   
});
</script>
@endsection