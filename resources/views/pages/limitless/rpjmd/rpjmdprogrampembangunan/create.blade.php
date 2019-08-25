@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD PROGRAM PEMBANGUNAN DAERAH
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RPJMD PROGRAM PEMBANGUNAN DAERAH PERIODE {{HelperKegiatan::getRPJMDTahunMulai()}} - {{HelperKegiatan::getRPJMDTahunAkhir()+1}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rpjmd.rpjmdprogrampembangunan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">RPJMD</a></li>
    <li><a href="{!!route('rpjmdprogrampembangunan.index')!!}">PROGRAM PEMBANGUNAN DAERAH</a></li>
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
                        <a href="{!!route('rpjmdprogrampembangunan.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        {!! Form::open(['action'=>'RPJMD\RPJMDProgramPembangunanController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
        <div class="panel-body">
            <div class="form-group">
                {{Form::label('PrioritasSasaranKabID','SASARAN RPJMD',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <select name="PrioritasSasaranKabID" id="PrioritasSasaranKabID" class="select">
                        <option></option>
                        @foreach ($daftar_sasaran as $k=>$item)
                            <option value="{{$k}}">{{$item}}</option>
                        @endforeach
                    </select>  
                </div>
            </div>
            <div class="form-group">
                {{Form::label('PrioritasIndikatorSasaranID','INDIKATOR SASARAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <select name="PrioritasIndikatorSasaranID" id="PrioritasIndikatorSasaranID" class="select">
                        <option></option>
                    </select>  
                </div>
            </div>            
        </div>
        <div class="panel-body">         
            <div class="form-group">
                {{Form::label('UrsID','URUSAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <select name="UrsID" id="UrsID" class="select">
                        <option></option>
                        @foreach ($daftar_urusan as $k=>$item)
                            <option value="{{$k}}"">{{$item}}</option>
                        @endforeach
                    </select>  
                </div>
            </div>
            <div class="form-group">
                {{Form::label('PrgID','PROGRAM',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <select name="PrgID" id="PrgID" class="select">
                        <option></option>
                    </select>  
                </div>
            </div> 
        </div>
        <div class="panel-body">            
            <div class="form-group">
                {{Form::label('PaguDanaN1','PAGU DANA TAHUN '.HelperKegiatan::getRPJMDTahunMulai(),['class'=>'control-label col-md-2'])}}
                <div class="col-md-8">
                    {{Form::text('PaguDanaN1','',['class'=>'form-control','placeholder'=>'PAGU DANA'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('PaguDanaN2','PAGU DANA TAHUN '.(HelperKegiatan::getRPJMDTahunMulai()+1),['class'=>'control-label col-md-2'])}}
                <div class="col-md-8">
                    {{Form::text('PaguDanaN2','',['class'=>'form-control','placeholder'=>'PAGU DANA'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('PaguDanaN3','PAGU DANA TAHUN '.(HelperKegiatan::getRPJMDTahunMulai()+2),['class'=>'control-label col-md-2'])}}
                <div class="col-md-8">
                    {{Form::text('PaguDanaN3','',['class'=>'form-control','placeholder'=>'PAGU DANA'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('PaguDanaN4','PAGU DANA TAHUN '.(HelperKegiatan::getRPJMDTahunMulai()+3),['class'=>'control-label col-md-2'])}}
                <div class="col-md-8">
                    {{Form::text('PaguDanaN4','',['class'=>'form-control','placeholder'=>'PAGU DANA'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('PaguDanaN5','PAGU DANA TAHUN '.(HelperKegiatan::getRPJMDTahunMulai()+4),['class'=>'control-label col-md-2'])}}
                <div class="col-md-8">
                    {{Form::text('PaguDanaN5','',['class'=>'form-control','placeholder'=>'PAGU DANA'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('KondisiAkhirPaguDana','KONDISI AKHIR PAGU DANA TAHUN '.(HelperKegiatan::getRPJMDTahunAkhir()+1),['class'=>'control-label col-md-2'])}}
                <div class="col-md-8">
                    {{Form::text('KondisiAkhirPaguDana','',['class'=>'form-control','placeholder'=>'PAGU DANA AKHIR'])}}
                </div>
            </div>                        
            <div class="form-group">
                {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::textarea('Descr','',['class'=>'form-control','placeholder'=>'KETERANGAN','rows' => 2, 'cols' => 40])}}
                </div>
            </div>    
        </div>
        <div class="panel-footer">
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
    AutoNumeric.multiple(['#PaguDanaN1','#PaguDanaN2','#PaguDanaN3','#PaguDanaN4','#PaguDanaN5','#KondisiAkhirPaguDana'],{
                                            allowDecimalPadding: false,
                                            decimalCharacter: ",",
                                            digitGroupSeparator: ".",
                                            unformatOnSubmit: true,
                                            showWarnings:false,
                                            modifyValueOnWheel:false
                                        });
    $('#PrioritasSasaranKabID.select').select2({
        placeholder: "PILIH SASARAN RPJMD",
        allowClear:true
    });
    $('#PrioritasIndikatorSasaranID.select').select2({
        placeholder: "PILIH INDIKATOR SASARAN RPJMD",
        allowClear:true
    });
    $('#UrsID.select').select2({
        placeholder: "PILIH URUSAN",
        allowClear:true
    });
    $('#PrgID.select').select2({
        placeholder: "PILIH PROGRAM",
        allowClear:true
    });
    $(document).on('change','#PrioritasSasaranKabID',function(ev) {
        ev.preventDefault();   
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {              
                "_token": token,  
                "PrioritasSasaranKabID": $('#PrioritasSasaranKabID').val(),
                "create":true
            },
            success:function(result)
            { 
                var daftar_indikatorsasaran = result.daftar_indikatorsasaran;
                var listitems='<option></option>';                
                $.each(daftar_indikatorsasaran,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });
                $('#PrioritasIndikatorSasaranID').html(listitems);        
                $('#UrsID').val(null).trigger("change");
                $('#PrgID').html('<option></option>');    
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });
    $(document).on('change','#UrsID',function(ev) {
        ev.preventDefault();   
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {              
                "_token": token,  
                "UrsID": $('#UrsID').val(),
                "PrioritasSasaranKabID": $('#PrioritasSasaranKabID').val(),
                "create":true
            },
            success:function(result)
            { 
                var daftar_program = result.daftar_program;
                var listitems='<option></option>';
                $.each(daftar_program,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });
                $('#PrgID').html(listitems);                
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
            PrioritasSasaranKabID : {
                required: true
            },                        
            PrioritasIndikatorSasaranID : {
                required: true
            },                        
            PrgID : {
                required: true
            },                        
            KondisiAwal : {
                required: true,
            },
            Satuan : {
                required: true,
            },
            TargetN1 : {
                required: true
            },         
            TargetN2 : {
                required: true
            },         
            TargetN3 : {
                required: true
            },         
            TargetN4 : {
                required: true
            },   
            TargetN5 : {
                required: true
            },
            KondisiAkhirTarget : {
                required: true
            },
            PaguDanaN1 : {
                required: true
            },
            PaguDanaN2 : {
                required: true
            },
            PaguDanaN3 : {
                required: true
            },
            PaguDanaN4 : {
                required: true
            },
            PaguDanaN5 : {
                required: true
            }, 
            KondisiAkhirPaguDana : {
                required: true
            }, 
        },
        messages : {
            PrioritasSasaranKabID : {
                required: "Mohon untuk di pilih RPJMD Sasaran untuk indiaktor ini."                
            },             
            PrioritasIndikatorSasaranID : {
                required: "Mohon untuk di pilih RPJMD Indikator Sasaran untuk indiaktor ini."                
            },             
            PrgID : {
                required: "Mohon untuk di pilih Program Urusan."              
            },
            KondisiAwal : {
                required: "Mohon untuk di isi kondisi awal RPJMD."              
            },
            Satuan : {
                required: "Mohon untuk di isi Satuan."              
            },
            TargetN1 : {
                required: "Mohon untuk di isi target N1 RPJMD",                                       
            },
            TargetN2 : {
                required: "Mohon untuk di isi target N2 RPJMD",                                                      
            },
            TargetN3 : {
                required: "Mohon untuk di isi target N3 RPJMD",                                       
            },
            TargetN4 : {
                required: "Mohon untuk di isi target N4 RPJMD",                                       
            },
            TargetN5 : {
                required: "Mohon untuk di isi target N5 RPJMD",                                       
            },
            KondisiAkhirTarget : {
                required: "Mohon untuk di isi pagu dana akhir RPJMD."                                        
            },     
            PaguDanaN1 : {
                required: "Mohon untuk di isi pagu dana tahun N1."                
            },
            PaguDanaN2 : {
                required: "Mohon untuk di isi pagu dana tahun N2."                    
            },
            PaguDanaN3 : {
                required: "Mohon untuk di isi pagu dana tahun N3."                            
            },
            PaguDanaN4 : {
                required: "Mohon untuk di isi pagu dana tahun N4."                                        
            },
            PaguDanaN5 : {
                required: "Mohon untuk di isi pagu dana tahun N5."                                        
            },
            KondisiAkhirPaguDana : {
                required: "Mohon untuk di isi pagu dana akhir RPJMD."                                        
            }                 
        }      
    });
});
</script>
@endsection