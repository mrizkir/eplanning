@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD INDIKASI RENCANA PROGRAM
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RPJMD INDIKASI RENCANA PROGRAM PERIODE {{HelperKegiatan::getRPJMDTahunMulai()}} - {{HelperKegiatan::getRPJMDTahunAkhir()+1}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rpjmd.rpjmdindikatorkinerja.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">RPJMD</a></li>
    <li><a href="{!!route('rpjmdindikatorkinerja.index')!!}">INDIKASI RENCANA PROGRAM</a></li>
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
                        <a href="{!!route('rpjmdindikatorkinerja.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        {!! Form::open(['action'=>'RPJMD\RPJMDIndikatorKinerjaController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
        <div class="panel-body">
            <div class="form-group">
                {{Form::label('OrgIDRPJMD','NAMA OPD / SKPD',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <select name="OrgIDRPJMD" id="OrgIDRPJMD" class="select">
                        <option></option>
                        @foreach ($daftar_opd as $k=>$item)
                            <option value="{{$k}}">{{$item}}</option>
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
                    <span class="help-block">Daftar Program diambil dari "Mapping Program->OPD/SKPD [3]". Nilai "Pagu Dana N1" s.d "Kondisi Akhir Pagu Dana" di ambil dari Menu Nomor [7], kecuali untuk program semua urusan di isi sendiri.</span>              
                </div>
            </div>      
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{Form::label('NamaIndikator','NAMA INDIKATOR',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            {{Form::textarea('NamaIndikator','',['class'=>'form-control','placeholder'=>'NAMA INDIKATOR','rows' => 2, 'cols' => 40])}}
                        </div>
                    </div>
                     <div class="form-group">
                        {{Form::label('KondisiAwal','KONDISI KINERJA AWAL ('.(HelperKegiatan::getRPJMDTahunAwal()).')',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            {{Form::text('KondisiAwal','',['class'=>'form-control','placeholder'=>'KONDISI KINERJA AWAL'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('Satuan','SATUAN',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            {{Form::text('Satuan','',['class'=>'form-control','placeholder'=>'SATUAN'])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">                
                <div class="col-md-6">
                    <div class="form-group">
                        {{Form::label('TargetN1','TARGET TAHUN '.(HelperKegiatan::getRPJMDTahunMulai()),['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('TargetN1','',['class'=>'form-control','placeholder'=>'TARGET TAHUN KE 1'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('TargetN2','TARGET TAHUN '.(HelperKegiatan::getRPJMDTahunMulai()+1),['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('TargetN2','',['class'=>'form-control','placeholder'=>'TARGET TAHUN KE 2'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('TargetN3','TARGET TAHUN '.(HelperKegiatan::getRPJMDTahunMulai()+2),['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('TargetN3','',['class'=>'form-control','placeholder'=>'TARGET TAHUN KE 3'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('TargetN4','TARGET TAHUN '.(HelperKegiatan::getRPJMDTahunMulai()+3),['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('TargetN4','',['class'=>'form-control','placeholder'=>'TARGET TAHUN KE 4'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('TargetN5','TARGET TAHUN '.(HelperKegiatan::getRPJMDTahunMulai()+4),['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('TargetN5','',['class'=>'form-control','placeholder'=>'TARGET TAHUN KE 5'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('KondisiAkhirTarget','KONDISI AKHIR TARGET '.(HelperKegiatan::getRPJMDTahunAkhir()+1),['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('KondisiAkhirTarget','',['class'=>'form-control','placeholder'=>'TARGET TAHUN KE 5'])}}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">                   
                    <div class="form-group">
                        {{Form::label('PaguDanaN1','PAGU DANA TAHUN '.HelperKegiatan::getRPJMDTahunMulai(),['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('PaguDanaN1',0,['class'=>'form-control','placeholder'=>'PAGU DANA TAHUN KE 1','readonly'=>true])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('PaguDanaN2','PAGU DANA TAHUN '.(HelperKegiatan::getRPJMDTahunMulai()+1),['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('PaguDanaN2',0,['class'=>'form-control','placeholder'=>'PAGU DANA TAHUN KE 2','readonly'=>true])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('PaguDanaN3','PAGU DANA TAHUN '.(HelperKegiatan::getRPJMDTahunMulai()+2),['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('PaguDanaN3',0,['class'=>'form-control','placeholder'=>'PAGU DANA TAHUN KE 3','readonly'=>true])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('PaguDanaN4','PAGU DANA TAHUN '.(HelperKegiatan::getRPJMDTahunMulai()+3),['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('PaguDanaN4',0,['class'=>'form-control','placeholder'=>'PAGU DANA TAHUN KE 4','readonly'=>true])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('PaguDanaN5','PAGU DANA TAHUN '.(HelperKegiatan::getRPJMDTahunMulai()+4),['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('PaguDanaN5',0,['class'=>'form-control','placeholder'=>'PAGU DANA TAHUN KE 5','readonly'=>true])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('KondisiAkhirPaguDana','KONDISI AKHIR PAGU DANA TAHUN '.(HelperKegiatan::getRPJMDTahunAkhir()+1),['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('KondisiAkhirPaguDana',0,['class'=>'form-control','placeholder'=>'PAGU DANA AKHIR TAHUN '.(HelperKegiatan::getRPJMDTahunAkhir()+1),'readonly'=>true])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">                    
                    <div class="form-group">
                        {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            {{Form::textarea('Descr','',['class'=>'form-control','placeholder'=>'KETERANGAN','rows' => 2, 'cols' => 40])}}
                        </div>
                    </div>
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
    $('#OrgIDRPJMD.select').select2({
        placeholder: "PILIH OPD / SKPD",
        allowClear:true
    });
    $('#PrgID.select').select2({
        placeholder: "PILIH PROGRAM",
        allowClear:true
    });
    $(document).on('change','#OrgIDRPJMD',function(ev) {
        ev.preventDefault();   
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {              
                "_token": token,  
                "OrgIDRPJMD": $('#OrgIDRPJMD').val(),
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
    $(document).on('change','#PrgID',function(ev) {
        ev.preventDefault();   
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {              
                "_token": token,  
                "PrgID": $('#PrgID').val(),
                "create":true
            },
            success:function(result)
            { 
                daftar_pagu = result.daftar_pagu;
                const PaguDanaN1 = AutoNumeric.getAutoNumericElement('#PaguDanaN1');
                PaguDanaN1.set(daftar_pagu.PaguDanaN1);

                const PaguDanaN2 = AutoNumeric.getAutoNumericElement('#PaguDanaN2');
                PaguDanaN2.set(daftar_pagu.PaguDanaN2);

                const PaguDanaN3 = AutoNumeric.getAutoNumericElement('#PaguDanaN3');
                PaguDanaN3.set(daftar_pagu.PaguDanaN3);

                const PaguDanaN4 = AutoNumeric.getAutoNumericElement('#PaguDanaN4');
                PaguDanaN4.set(daftar_pagu.PaguDanaN4);

                const PaguDanaN5 = AutoNumeric.getAutoNumericElement('#PaguDanaN5');
                PaguDanaN5.set(daftar_pagu.PaguDanaN5);

                const KondisiAkhirPaguDana = AutoNumeric.getAutoNumericElement('#KondisiAkhirPaguDana');
                KondisiAkhirPaguDana.set(daftar_pagu.KondisiAkhirPaguDana);
              

                $('#PaguDanaN1').prop('readonly',result.readonly);
                $('#PaguDanaN2').prop('readonly',result.readonly);
                $('#PaguDanaN3').prop('readonly',result.readonly);
                $('#PaguDanaN4').prop('readonly',result.readonly);
                $('#PaguDanaN5').prop('readonly',result.readonly);
                $('#KondisiAkhirPaguDana').prop('readonly',result.readonly);                               
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
            OrgIDRPJMD : {
                required: true
            },          
            PrgID : {
                required: true
            },             
            NamaIndikator : {
                required: true,
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
            OrgIDRPJMD : {
                required: "Mohon untuk di pilih OPD / SKPD untuk indiaktor ini."                
            },            
            PrgID : {
                required: "Mohon untuk di pilih Program untuk indikator ini."                
            }, 
            NamaIndikator : {
                required: "Mohon untuk di isi nama indikator."              
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