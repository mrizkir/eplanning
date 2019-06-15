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
    @include('pages.limitless.rkpd.rkpdperubahan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">WORKFLOW</a></li>
    <li><a href="{!!route(Helper::getNameOfPage('index'))!!}">{{$page_title}}</a></li>
    <li class="active">TAMBAH DATA INDIKATOR KEGIATAN</li>
@endsection
@section('page_sidebar')
    @include('pages.limitless.rkpd.rkpdperubahan.l_sidebar_usulan_create')
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                TAMBAH DATA INDIKATOR KEGIATAN
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>               
                        <a href="{!!route(Helper::getNameOfPage('index'))!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['url'=>route(Helper::getNameOfPage('store1')),'method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                              
            {{Form::hidden('RKPDID',$rkpd->RKPDID)}}
                <div class="form-group">    
                    <div class="form-group">
                        <label class="col-md-2 control-label">POSISI ENTRI: </label>
                        <div class="col-md-10">
                            <p class="form-control-static">
                                <span class="label border-left-primary label-striped">{{$page_title}}</span>
                            </p>
                        </div>                            
                    </div>    
                    <div class="form-group">
                        {{Form::label('IndikatorKinerjaID','INDIKATOR KINERJA',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            <select name="IndikatorKinerjaID" id="IndikatorKinerjaID" class="select">
                                <option></option>
                                @foreach ($daftar_indikatorkinerja as $k=>$item)
                                    <option value="{{$k}}">{{$item}}</option>
                                @endforeach
                            </select>                        
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-2 control-label">NAMA INDIKATOR (RPJMD): </label>
                        <div class="col-md-10">
                            <p class="form-control-static" id="pNamaIndikator">-</p>
                        </div>                            
                    </div>  
                    <div class="form-group">
                        <label class="col-md-2 control-label">TARGET TA {{HelperKegiatan::getTahunPerencanaan()}} (RPJMD): </label>
                        <div class="col-md-10">
                            <p class="form-control-static" id="pTargetAngka">-</p>
                        </div>                            
                    </div>  
                    <div class="form-group">
                        <label class="col-md-2 control-label">PAGU DANA TA {{HelperKegiatan::getTahunPerencanaan()}} (RPJMD): </label>
                        <div class="col-md-10">
                            <p class="form-control-static" id="pPaguDana">-</p>
                        </div>                            
                    </div>    
                    <div class="form-group">
                        <label class="col-md-2 control-label">TARGET ANGKA: </label>
                        <div class="col-md-10">
                            {{Form::text('Target_Angka','',['class'=>'form-control','placeholder'=>'TARGET ANGKA KEGIATAN','id'=>'Target_Angka'])}}
                        </div>                            
                    </div> 
                    <div class="form-group">
                        <label class="col-md-2 control-label">TARGET URAIAN: </label>
                        <div class="col-md-10">
                            {{Form::text('Target_Uraian','',['class'=>'form-control','placeholder'=>'TARGET URAIAN KEGIATAN','id'=>'Target_Uraian'])}}
                        </div>                            
                    </div> 
                    <div class="col-md-10 col-md-offset-2">                        
                        {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}                                            
                    </div>
                </div>
            {!! Form::close()!!}
        </div>
    </div>
    <div class="panel panel-flat border-top-lg border-top-info border-bottom-info" id="divdatatableindikatorkinerja">
        @include('pages.limitless.rkpd.rkpdperubahan.datatableindikatorkinerja')         
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
    //styling select
    $('#IndikatorKinerjaID.select').select2({
        placeholder: "PILIH INDIKATOR KINERJA (RPMJD)",
        allowClear:true
    });  
    AutoNumeric.multiple(['#Target_Angka'], {
        allowDecimalPadding: false,
                                            minimumValue:0.00,
                                            maximumValue:99999999999.00,
                                            numericPos:true,
                                            decimalPlaces : 2,
                                            digitGroupSeparator : '',
                                            showWarnings:false,
                                            unformatOnSubmit: true,
                                            modifyValueOnWheel:false
                                        });
    $(document).on('change','#IndikatorKinerjaID',function(ev) {
        ev.preventDefault();
        IndikatorKinerjaID=$(this).val();        
        $.ajax({
            type:'post',
            url: '{{route(Helper::getNameOfPage("pilihindikatorkinerja"))}}',
            dataType: 'json',
            data: {
                "_token": token,
                "IndikatorKinerjaID": IndikatorKinerjaID,
            },
            success:function(result)
            {                   
                $('#pNamaIndikator').html(result.NamaIndikator);
                $('#pTargetAngka').html(result.TargetAngka);
                $('#pPaguDana').html(result.PaguDana);
            },
            error:function(xhr, status, error)
            {   
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });     
    $('#frmdata').validate({
        ignore: [], 
        rules: {
            IndikatorKinerjaID : {
                required: true
            },
            Target_Angka : {
                required: true
            },
            Target_Uraian : {
                required: true
            },
        },
        messages : {
            IndikatorKinerjaID : {
                required: "Mohon dipilih indikator kinerja RPJMD."
            },
            Target_Angka : {
                required: "Mohon untuk di isi target angka."
            },
            Target_Uraian : {
                required: "Mohon untuk di isi target uraian."
            }
        }      
    });   
    $("#divdatatableindikatorkinerja").on("click",".btnDelete", function(){
        if (confirm('Apakah Anda ingin menghapus Data Indikator Kegiatan {{$page_title}} ini ?')) {
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
                    'indikatorkinerja':true
                },
                success:function(result){ 
                    if (result.success==1){
                        $('#divdatatableindikatorkinerja').html(result.datatable);                        
                    }else{
                        console.log("Gagal menghapus data indikator kinerja {{$page_title}} dengan id "+id);
                    }                    
                },
                error:function(xhr, status, error){
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        }        
    });
});
</script>
@endsection