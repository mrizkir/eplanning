@extends('layouts.default.l_main')
@section('page_title')
    USER ROLES
@endsection
@section('page_header')
    <i class="fa fa-users"></i> 
    USER ROLES   
@endsection
@section('page-info')
    @include('pages.default.setting.roles.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li class="active">USER ROLES</li>
@endsection
@section('page_content')
<div class="row">      
    <div class="col-md-12" id="divdatatable">
        @include('pages.default.setting.roles.datatable')
    </div>
</div>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {  
    $("#divdatatable").on("click",".btnDelete", function(){
        if (confirm('Apakah Anda ingin menghapus Data Role ini ?')) {
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
                        console.log("Gagal menghapus data Roles dengan id "+id);
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