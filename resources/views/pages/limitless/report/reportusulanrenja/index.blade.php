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
    @include('pages.limitless.report.reportusulanrenja.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">WORKFLOW</a></li>
    <li class="active">{{$page_title}}</li>
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
                        <label class="col-md-2 control-label">STATUS KEGIATAN :</label> 
                        <div class="col-md-10">
                            <select name="cmbStatusKegiatan" id="cmbStatusKegiatan" class="form-control">                                
                                @foreach ($status_kegiatan as $k=>$item)                                    
                                    <option value="{{$k}}"{{$k==$filters['status_kegiatan']?' selected':''}}>{{$item}}</option>
                                @endforeach
                            </select>                              
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.report.reportusulanrenja.datatable')
    </div>        
</div>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {  
    $(document).on('change','#cmbStatusKegiatan',function(ev) {
        ev.preventDefault();           
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {                
                "_token": token,
                "status_kegiatan": $('#cmbStatusKegiatan').val(),
            },
            success:function(result)
            { 
                console.log(result);
            },
            error:function(xhr, status, error){
                console.log(xhr);
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });     
    });
});
</script>
@endsection