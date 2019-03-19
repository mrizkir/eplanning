@extends('layouts.limitless.l_main')
@section('page_title')
    USULAN KECAMATAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        USULAN KECAMATAN TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.musrenbang.aspirasimusrenkecamatan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">ASPIRASI / USULAN</a></li>
    <li><a href="{!!route('aspirasimusrenkecamatan.index')!!}">USULAN KECAMATAN</a></li>
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
                        <a href="{!!route('aspirasimusrenkecamatan.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'Musrenbang\AspirasiMusrenKecamatanController@storeusulankecamatan','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
                <div class="form-group">
                    {{Form::label('UrsID','URUSAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <select name="UrsID" id="UrsID" class="select">
                            <option></option>
                            @foreach ($daftar_urusan as $k=>$item)
                                <option value="{{$k}}">{{$item}}</option>
                            @endforeach
                        </select>                        
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('OrgID','OPD / SKPD PELAKSANA',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <select name="OrgID" id="OrgID" class="select">
                            <option></option>                                
                        </select>                        
                    </div>
                </div>   
                <div class="form-group">
                    <label class="col-md-2 control-label">KECAMATAN :</label> 
                    <div class="col-md-10">
                        <select name="PmKecamatanID" id="PmKecamatanID" class="select">
                            <option></option>
                            @foreach ($daftar_kecamatan as $k=>$item)
                                <option value="{{$k}}"{{$k==$filters['PmKecamatanID']?' selected':''}}>{{$item}}</option>
                            @endforeach
                        </select>                              
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('NamaKegiatan','NAMA KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('NamaKegiatan','',['class'=>'form-control','placeholder'=>'NAMA KEGIATAN'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Output','OUTPUT / HASIL',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Output','',['class'=>'form-control','placeholder'=>'OUTPUT / HASIL'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Lokasi','LOKASI KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">                        
                        {{Form::text('Lokasi','',['class'=>'form-control','placeholder'=>'LOKASI KEGIATAN'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('NilaiUsulan','NILAI USULAN ANGGARAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('NilaiUsulan','',['class'=>'form-control','placeholder'=>'NILAI USULAN ANGGARAN'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Target_Angka','TARGET (VOLUME)',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-6">
                                {{Form::text('Target_Angka','',['class'=>'form-control','placeholder'=>'ANGKA TARGET'])}}
                            </div>
                            <div class="col-md-6">
                                {{Form::text('Target_Uraian','',['class'=>'form-control','placeholder'=>'TARGET URAIAN'])}}                                
                            </div>
                        </div>                                               
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('Prioritas','PRIORITAS',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('Prioritas', Helper::getDaftarPrioritas(),'none',['class'=>'form-control','id'=>'Prioritas'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Jeniskeg','JENIS KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">                        
                        <div class="checkbox checkbox-switch">
                            {{Form::checkbox('Jeniskeg','1',0,['class'=>'switch','data-on-text'=>'FISIK','data-off-text'=>'NON-FISIK'])}}                                     
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('SumberDanaID','SUMBER DANA',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('SumberDanaID', $sumber_dana, '',['class'=>'form-control','id'=>'KUrsID'])}}
                    </div>
                </div>           
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Descr','',['class'=>'form-control','placeholder'=>'KETERANGAN'])}}
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
<script src="{!!asset('themes/limitless/assets/js/switch.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $(".switch").bootstrapSwitch();
    //styling select
    $('#UrsID.select').select2({
        placeholder: "PILIH URUSAN",
        allowClear:true
    });
    $('#OrgID.select').select2({
        placeholder: "PILIH OPD / SKPD",
        allowClear:true
    });
    $('#PmKecamatanID.select').select2({
        placeholder: "PILIH KECAMATAN",
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
    $(document).on('change','#UrsID',function(ev) {
        ev.preventDefault();   
        $.ajax({
            type:'post',
            url: url_current_page +'/filterurusan',
            dataType: 'json',
            data: {                
                "_token": token,
                "UrsID": $('#UrsID').val(),
            },
            success:function(result)
            { 
                console.log(result);
                var daftar_organisasi = result.daftar_organisasi;
                var listitems='<option></option>';
                $.each(daftar_organisasi,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });
                $('#OrgID').html(listitems);
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });     
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