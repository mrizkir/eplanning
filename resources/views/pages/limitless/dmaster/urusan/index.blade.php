@extends('layouts.limitless.l_main')
@section('page_title')
    URUSAN
@endsection
@section('page_header')
    <i class="fa fa-lock"></i>
    URUSAN  
@endsection
@section('page-info')
    @include('pages.limitless.dmaster.urusan.info')
@endsection
@section('page_breadcrumb')
    <li class="active">URUSAN</li>
@endsection
@section('page_content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-search"></i> 
                    Pencarian Data
                </h3>
            </div>
            {!! Form::open(['action'=>'DMaster\UrusanController@search','method'=>'post','class'=>'form-horizontal','id'=>'frmsearch','name'=>'frmsearch'])!!}                
                <div class="box-body">                
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kriteria :</label> 
                        <div class="col-md-10">
                            {{Form::select('cmbKriteria', ['replaceit'=>'replaceit','nama'=>'replaceit'], isset($search['kriteria'])?$search['kriteria']:'replaceit',['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="form-group" id="divKriteria">
                        <label class="col-md-2 control-label">Isi Kriteria :</label>                                                    
                        <div class="col-md-10">                            
                            {{Form::text('txtKriteria',isset($search['isikriteria'])?$search['isikriteria']:'',['class'=>'form-control','placeholder'=>'Isi Kriteria Pencarian','id'=>'txtKriteria'])}}                                                                  
                        </div>
                    </div>                                                     
                </div>
                <div class="box-footer">
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            {{ Form::button('<i class="fa fa-search"></i> Cari', ['type' => 'submit', 'class' => 'btn btn-primary', 'id'=>'btnSearch'] )  }}                            
                            <a id="btnReset" href="javascript:;" title="Reset Pencarian" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>                            
                        </div>
                    </div>    
                </div>
            {!! Form::close()!!}
        </div>
    </div>       
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.dmaster.urusan.datatable')
    </div>
</div>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {  
    $("#divdatatable").on("click",".btnDelete", function(){
        if (confirm('Apakah Anda ingin menghapus Data Urusan ini ?')) {
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
                        console.log("Gagal menghapus data Urusan dengan id "+id);
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