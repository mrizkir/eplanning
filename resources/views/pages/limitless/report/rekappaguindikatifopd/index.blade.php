@extends('layouts.limitless.l_main')
@section('page_title')
    REKAP PAGU INDIKATIF OPD
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        REKAP PAGU INDIKATIF OPD TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}  
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.report.rekappaguindikatifopd.info')
@endsection
@section('page_breadcrumb')
    <li class="active">REKAP PAGU INDIKATIF OPD</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.report.rekappaguindikatifopd.datatable')
    </div>
</div>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {  
    $('#divdatatable').on('click','#data .reload',function(ev){
        ev.preventDefault();
        var id = $(this).attr('id');
        var data = $("#frmdata").serialize();
        $.ajax({
            type:'post',
            url: url_current_page +'/'+id,
            dataType: 'json',
            data: {                
                "_token": token,
                "_method": 'PUT'
            },
            success:function(result)
            { 
                window.location.replace(url_current_page);
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });
});
</script>
@endsection