@extends('layouts.limitless.l_main')
@section('page_title')
    TAHUN PERENCANAAN / ANGGARAN
@endsection
@section('page_header')
    <i class="icon-calendar2 position-left"></i>
    <span class="text-semibold">
        TAHUN PERENCANAAN / ANGGARAN
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.ta.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">ANEKA DATA</a></li>
    <li class="active">TAHUN PERENCANAAN / ANGGARAN</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.dmaster.ta.datatable')
    </div>
</div>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {  
    $("#divdatatable").on("click",".btnDelete", function(){
        if (confirm('Apakah Anda ingin menghapus Data Tahun Perencanaan / Anggaran ini ?')) {
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
                        console.log("Gagal menghapus data Tahun Perencanaan / Anggaran dengan id "+id);
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