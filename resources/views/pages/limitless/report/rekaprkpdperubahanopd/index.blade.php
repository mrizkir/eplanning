@extends('layouts.limitless.l_report')
@section('page_title')
    REKAP RKPD PERUBAHAN OPD
@endsection
@section('page_content')
<div class="row">      
    <div class="col-md-12" id="divfilter">
        <div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"><i class="icon-file-empty position-left"></i> REKAP RKPD PERUBAHAN OPD TAHUN PERENCANAAN {{config('eplanning.tahun_perencanaan')}}</h5>
                <div class="heading-elements">                       
                    <ul class="icons-list">
                        <li><a data-action="collapse"></a></li> 
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-2 control-label">OPD / SKPD :</label> 
                        <div class="col-md-10">
                            <select name="OrgID" id="OrgID" class="select">
                                <option></option>
                                @foreach ($daftar_opd as $k=>$item)
                                    <option value="{{$k}}"{{$k==$filters['OrgID']?' selected':''}}>{{$item}}</option>
                                @endforeach
                            </select>                              
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">UNIT KERJA :</label> 
                        <div class="col-md-10">
                            <select name="SOrgID" id="SOrgID" class="select">
                                <option></option>
                                @foreach ($daftar_unitkerja as $k=>$item)
                                    <option value="{{$k}}"{{$k==$filters['SOrgID']?' selected':''}}>{{$item}}</option>
                                @endforeach
                            </select>                              
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.report.rekaprkpdperubahanopd.datatable')
    </div>
</div>
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
    //styling select
    $('#OrgID.select').select2({
        placeholder: "PILIH OPD / SKPD",
        allowClear:true
    }); 
    $('#SOrgID.select').select2({
        placeholder: "PILIH UNIT KERJA",
        allowClear:true
    });  
    $(document).on('change','#OrgID',function(ev) {
        ev.preventDefault();   
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {                
                "_token": token,
                "OrgID": $('#OrgID').val(),
            },
            success:function(result)
            { 
                var daftar_unitkerja = result.daftar_unitkerja;
                var listitems='<option></option>';
                $.each(daftar_unitkerja,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });
                
                $('#SOrgID').html(listitems);           
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });     
    });
    $(document).on('change','#SOrgID',function(ev) {
        ev.preventDefault();   
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {                
                "_token": token,
                "SOrgID": $('#SOrgID').val(),
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
</script>
@endsection