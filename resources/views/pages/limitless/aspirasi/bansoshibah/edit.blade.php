@extends('layouts.limitless.l_main')
@section('page_title')
    BANTUAN SOSIAL DAN HIBAH
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        BANTUAN SOSIAL DAN HIBAH TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.aspirasi.bansoshibah.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">ASPIRASI</a></li>
    <li><a href="{!!route('bansoshibah.index')!!}">BANTUAN SOSIAL DAN HIBAH</a></li>
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
                        <a href="{!!route('bansoshibah.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        {!! Form::open(['action'=>['Aspirasi\BansosHibahController@update',$data->BansosHibahID],'method'=>'put','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
        <div class="panel-body">            
            <div class="form-group">
                {{Form::label('PemilikBansosHibahID','NAMA PEMILIK',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::select('PemilikBansosHibahID', $daftar_pemilik,$data->PemilikBansosHibahID,['class'=>'form-control','id'=>'PemilikBansosHibahID'])}}
                </div>
            </div>                               
        </div>        
        <div class="panel-body">                                
            <div class="form-group">
                {{Form::label('NamaUsulanKegiatan','NAMA KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::textarea('NamaUsulanKegiatan',$data->NamaUsulanKegiatan,['class'=>'form-control','placeholder'=>'NAMA KEGIATAN','rows'=>2])}}
                </div>
            </div>               
            <div class="form-group">
                {{Form::label('Output','OUTPUT / KELUARAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::textarea('Output',$data->Output,['class'=>'form-control','placeholder'=>'OUTPUT / KELUARAN','rows'=>2])}}
                </div>
            </div>             
            <div class="form-group">
                {{Form::label('Sasaran_Angka','TARGET',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            {{Form::text('Sasaran_Angka',$data->Sasaran_Angka,['class'=>'form-control','placeholder'=>'ANGKA TARGET'])}}
                        </div>
                        <div class="col-md-6">
                            {{Form::textarea('Sasaran_Uraian',$data->Sasaran_Uraian,['class'=>'form-control','placeholder'=>'URAIAN TARGET','rows'=>3,'id'=>'Sasaran_Uraian'])}}
                        </div>
                    </div>
                </div>
            </div>   
            @unlessrole('dewan')          
            <div class="form-group">
                {{Form::label('NilaiUsulan','NILAI USULAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('NilaiUsulan',$data->NilaiUsulan,['class'=>'form-control','placeholder'=>'NILAI USULAN'])}}
                </div>
            </div>            
            @else
            {{Form::hidden('NilaiUsulan',$data->NilaiUsulan,['id'=>'NilaiUsulan'])}}
            @endunlessrole   
            <div class="form-group">
                {{Form::label('Prioritas','PRIORITAS',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::select('Prioritas', HelperKegiatan::getDaftarPrioritas(),$data->Prioritas,['class'=>'form-control','id'=>'Prioritas'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::textarea('Descr',$data->Descr,['class'=>'form-control','placeholder'=>'KETERANGAN / CATATAN PENTING','rows'=>2])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Jeniskeg','JENIS KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::select('Jeniskeg', [1=>'BANTUAN SOSIAL',2=>'HIBAH'],$data->Jeniskeg,['class'=>'form-control','id'=>'Jeniskeg'])}}
                </div>
            </div>
        </div>          
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-2 control-label">KECAMATAN :</label> 
                    <div class="col-md-10">
                        <select name="PmKecamatanID" id="PmKecamatanID" class="select">
                            <option></option>
                            @foreach ($daftar_kecamatan as $k=>$item)
                                <option value="{{$k}}"{{$k==$data->PmKecamatanID?' selected':''}}>{{$item}}</option>
                            @endforeach
                        </select>                              
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">DESA :</label> 
                    <div class="col-md-10">
                        <select name="PmDesaID" id="PmDesaID" class="select">
                            <option></option>         
                            @foreach ($daftar_desa as $k=>$item)
                                <option value="{{$k}}"{{$k==$data->PmDesaID?' selected':''}}>{{$item}}</option>
                            @endforeach                                              
                        </select>                            
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Lokasi','KETERANGAN LOKASI KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Lokasi',$data->Lokasi,['class'=>'form-control','placeholder'=>'KETERANGAN LOKASI KEGIATAN','rows'=>2])}}
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
<script src="{!!asset('themes/limitless/assets/js/switch.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    AutoNumeric.multiple(['#Sasaran_Angka'], {
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
    AutoNumeric.multiple(['#NilaiUsulan'],{
                            allowDecimalPadding: false,
                            decimalCharacter: ",",
                            digitGroupSeparator: ".",
                            unformatOnSubmit: true,
                            showWarnings:false,
                            modifyValueOnWheel:false
                        });

    $('#PmKecamatanID.select').select2({
        placeholder: "PILIH KECAMATAN",
        allowClear:true
    }); 
    $('#PmDesaID.select').select2({
        placeholder: "PILIH DESA / KELURAHAN",
        allowClear:true
    }); 
    $(document).on('change','#PmKecamatanID',function(ev) {
        ev.preventDefault();
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {                
                "_token": token,
                "PmKecamatanID": $('#PmKecamatanID').val()                
            },
            success:function(result)
            { 
                var daftar_desa = result.daftar_desa;
                var listitems='<option></option>';
                $.each(daftar_desa,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });
                $('#PmDesaID').html(listitems);
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });
    $('#frmdata').validate({
        ignore: [],
        rules: {            
            PemilikPokokID : {
                required: true,
                valueNotEquals: 'none'
            },
            NamaUsulanKegiatan : {
                required: true
            },
            Output : {
                required: true
            },
            Sasaran_Angka : {
                required: true          
            },
            Sasaran_Uraian : {
                required: true
            },
            Prioritas : {
                required: true,
                valueNotEquals: 'none'
            },
            PmKecamatanID : {
                required: true,
                valueNotEquals: 'none'
            },
            Lokasi : {
                required: true                
            }
        },
        messages : {
            PemilikPokokID : {
                required: "Mohon untuk di pilih pemilik pokok pikiran.",
                valueNotEquals: "Mohon untuk di pilih pemilik pokok pikiran.",                
            },            
            NamaUsulanKegiatan : {
                required: "Mohon untuk di isi nama kegiatan.",                
            },
            Output : {
                required: "Mohon untuk di isi output kegiatan.",                
            },
            Sasaran_Angka : {
                required: "Mohon untuk di isi angka target kegiatan.",                
            },
            Sasaran_Uraian : {
                required: "Mohon untuk di isi uraian target kegiatan.",                
            },
            Prioritas : {
                required: "Mohon untuk di pilih nilai prioritas kegiatan.",                
                valueNotEquals: "Mohon untuk di nilai prioritas kegiatan.",                 
            }, 
            PmKecamatanID : {
                required: "Mohon untuk di pilih kecamatan kegiatan ini dilaksanakan.",                
                valueNotEquals: "Mohon untuk di pilih kecamatan kegiatan ini dilaksanakan.",                
            },
            Lokasi : {
                required: "Mohon untuk di isi lokasi detaik kegiatan.", 
            }
        }      
    });   
});
</script>
@endsection