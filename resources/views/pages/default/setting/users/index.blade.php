@extends('layouts.default.l_main')
@section('page_title')
    USERS
@endsection
@section('page_header')
    <i class="fa fa-lock"></i>
    USERS  
@endsection
@section('page-info')
    @include('pages.default.setting.users.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li class="active">USERS</li>
@endsection
@section('page_content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-search"></i> 
                    PENCARIAN DATA
                </h3>
            </div>
            {!! Form::open(['action'=>'Setting\UsersController@search','method'=>'post','class'=>'form-horizontal','id'=>'frmsearch','name'=>'frmsearch'])!!}                
                <div class="box-body">                
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kriteria :</label> 
                        <div class="col-md-10">
                            {{Form::select('cmbKriteria', ['id'=>'USERID','username'=>'USERNAME','nama'=>'NAMA','email'=>'EMAIL'], isset($search['kriteria'])?$search['kriteria']:'username',['class'=>'form-control'])}}
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
        @include('pages.default.setting.users.datatable')
    </div>
</div>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {  
    $("#divdatatable").on("click",".btnDelete", function(){
        if (confirm('Apakah Anda ingin menghapus Data User ini ?')) {
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
                        console.log("Gagal menghapus data User dengan id "+id);
                    }                    
                },
                error:function(xhr, status, error){
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        }        
    });
    $(document).on('change','#filterroles', function (ev)
    {
        ev.preventDefault();    
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {                
                "_token": token,
                "role_id": $('#filterroles').val(),
            },
            success:function(result)
            {          
                $('#divdatatable').html(result.datatable);                                   
            },
            error:function(xhr, status, error)
            {
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });
});
</script>
@endsection