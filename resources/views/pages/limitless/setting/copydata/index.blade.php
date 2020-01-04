@extends('layouts.limitless.l_main')
@section('page_title')
    COPY DATA
@endsection
@section('page_header')
    <i class="icon-copy3 position-left"></i>
    <span class="text-semibold">
        COPY DATA  PERENCANAAN KE TAHUN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.setting.copydata.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="#">DATA EPLANNING</a></li>
    <li class="active">COPY DATA</li>
@endsection
@section('page_content')
<div class="row">  
<div class="col-md-12" id="divfilter">
        <div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"><i class="icon-bookmark2 position-left"></i> Filter Data</h5>
                <div class="heading-elements">                       
                    <ul class="icons-list">
                        <li><a data-action="collapse"></a></li> 
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Data dari Tahun :</label> 
                        <div class="col-md-10">
                            {{Form::select('TACd', \App\Models\DMaster\TAModel::pluck('TACd','TACd'), $TA, ['placeholder' => 'PILIH TAHUN PERENCANAAN','class'=>'form-control','id'=>'TACd'])}}                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.setting.copydata.datatable')
    </div>
</div>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $(document).on('change','#TACd',function(ev) {
        ev.preventDefault();   
        alert('Hello');
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {                
                "_token": token,
                "TA": $('#TA').val(),
            },
            success:function(result)
            { 
                
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
