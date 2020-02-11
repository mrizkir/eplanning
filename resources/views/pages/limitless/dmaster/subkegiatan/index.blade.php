@extends('layouts.limitless.l_main')
@section('page_title')
    SUB KEGIATAN
@endsection
@section('page_header')
    <i class="icon-code position-left"></i>
    <span class="text-semibold">
        SUB KEGIATAN TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}  
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.subkegiatan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">DATA</a></li>
    <li class="active">SUB KEGIATAN</li>
@endsection
@section('page_content')
<div class="row">
    <div class="col-md-12" id="divfilter">
        <div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"><i class="icon-bookmark2 position-left"></i> FILTER DATA</h5>
                <div class="heading-elements">                       
                    <ul class="icons-list">
                        <li><a data-action="collapse"></a></li> 
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-2 control-label">PROGRAM :</label> 
                        <div class="col-md-10">
                            <select name="PrgID" id="PrgID" class="select">
                                <option></option>
                                @foreach ($daftar_program as $k=>$item)
                                    <option value="{{$k}}"{{$k==$filter_prgid_selected?' selected':''}}>{{$item}}</option>
                                @endforeach
                            </select>                              
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title">
                    <i class="icon-search4 position-left"></i>
                    PENCARIAN DATA
                </h5>
            </div>
            <div class="panel-body">
                {!! Form::open(['action'=>'DMaster\SubKegiatanController@search','method'=>'post','class'=>'form-horizontal','id'=>'frmsearch','name'=>'frmsearch'])!!}                                
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kriteria :</label> 
                        <div class="col-md-10">
                            {{Form::select('cmbKriteria', ['kode_subkegiatan'=>'KODE SUB KEGIATAN','SubKgtNm'=>'NAMA SUB KEGIATAN'], isset($search['kriteria'])?$search['kriteria']:'kode_kegiatan',['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="form-group" id="divKriteria">
                        <label class="col-md-2 control-label">Isi Kriteria :</label>                                                    
                        <div class="col-md-10">                            
                            {{Form::text('txtKriteria',isset($search['isikriteria'])?$search['isikriteria']:'',['class'=>'form-control','placeholder'=>'Isi Kriteria Pencarian','id'=>'txtKriteria'])}}                                                                  
                        </div>
                    </div>                                                     
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            {{ Form::button('<b><i class="icon-search4"></i></b> Cari', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs', 'id'=>'btnSearch'] )  }}                            
                            <a id="btnReset" href="javascript:;" title="Reset Pencarian" class="btn btn-default btn-labeled btn-xs">
                                <b><i class="icon-reset"></i></b> Reset
                            </a>                           
                        </div>
                    </div>  
                {!! Form::close()!!}
            </div>
        </div>
    </div>       
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.dmaster.subkegiatan.datatable')
    </div>
</div>
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {  
    //styling select
    $('#PrgID.select').select2({
        placeholder: "PILIH PROGRAM",
        allowClear:true
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
                "index": true,
            },
            success:function(result)
            { 
                $('#divdatatable').html(result.datatable);
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });     
    });
    $("#divdatatable").on("click",".btnDelete", function(){
        if (confirm('Apakah Anda ingin menghapus Data Sub Kegiatan ini ?')) {
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
                },
                success:function(result){ 
                    if (result.success==1){
                        $('#divdatatable').html(result.datatable);                        
                    }else{
                        console.log("Gagal menghapus data Sub Kegiatan dengan id "+id);
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