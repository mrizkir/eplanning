@extends('layouts.limitless.l_main')
@section('page_title')
    MAPPING PROGRAM KE OPD
@endsection
@section('page_header')
    <i class="icon-link position-left"></i>
    <span class="text-semibold"> 
        MAPPING PROGRAM KE OPD TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.mappingprogramtoopd.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('mappingprogramtoopd.index')!!}">MAPPING PROGRAM KE OPD</a></li>
    <li class="active">TAMBAH DATA</li>
@endsection
@section('page_content')
<div class="row">
    {!! Form::open(['action'=>'DMaster\MappingProgramToOPDController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
    <div class="col-md-12">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">
                    <i class="icon-pencil7 position-left"></i> 
                    TAMBAH DATA
                </h5>
                <div class="heading-elements">
                    <ul class="icons-list">                    
                        <li>               
                            <a href="{!!route('mappingprogramtoopd.index')!!}" data-action="closeredirect" title="keluar"></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        {{Form::label('OrgID','OPD / SKPD',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            <select name="OrgID" id="OrgID" class="select2">
                                <option></option>
                                @foreach ($daftar_opd as $k=>$item)
                                    <option value="{{$k}}"{{$k==$filter_orgid_selected?'selected':''}}>{{$item}}</option>
                                @endforeach
                            </select>                        
                        </div>
                    </div>   
                    <div class="form-group">            
                        <div class="col-md-10 col-md-offset-2">                        
                            {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12" id="divdataprogram">
        @include('pages.limitless.dmaster.mappingprogramtoopd.datatableprogram')
    </div>
    {!! Form::close()!!}
</div>   
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/switch.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    $(".switch").bootstrapSwitch();
    //styling select
    $('.select2').select2({
        placeholder: "PILIH OPD / SKPD",
        allowClear:true
    });
    $('.select').select2({
        allowClear:true
    });
    //change number record of page
    $(document).on('change','#numberRecordPerPageCreate', function (ev)
    {
        ev.preventDefault();    
        $.ajax({
            type:'post',
            url: url_current_page +'/changenumberrecordperpagecreate',
            dataType: 'json',
            data: {                
                "_token": token,
                "numberRecordPerPage": $('#numberRecordPerPageCreate').val(),
            },
            success:function(result)
            {          
                $('#divdataprogram').html(result.datatable);    
                if ($('#divdataprogram' + ' *').hasClass('select')) {
                    //styling select
                    $('.select').select2({
                        allowClear:true
                    });
                }            
                $(".switch").bootstrapSwitch();                  
            },
            error:function(xhr, status, error)
            {
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });
    //paginate table data
    $(document).on('click','#paginationprogram a', function (ev)
    {
        ev.preventDefault();
        var a =  $(this).attr('href').split('?page=');        
        var page = a[1];
        var page_url = a[0]+'/paginatecreate/'+page;        
        if (typeof page !== 'undefined')
        {
            $.ajax({
                type:'get',
                url: page_url,
                dataType: 'json',
                success:function(result)
                {   
                    $('#divdataprogram').html(result.datatable);                     
                    if ($('#divdataprogram' + ' *').hasClass('select')) {
                        //styling select
                        $('.select').select2({
                            allowClear:true
                        });
                    }  
                    $(".switch").bootstrapSwitch();
                },
                error:function(xhr, status, error)
                {
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        }
    });
    $(document).on('change','#filterurusan', function (ev)
    {
        ev.preventDefault();    
        $.ajax({
            type:'post',
            url: url_current_page +'/filtercreate',
            dataType: 'json',
            data: {                
                "_token": token,
                "UrsID": $('#filterurusan').val(),
            },
            success:function(result)
            {          
                $('#divdataprogram').html(result.datatable);                                   
                //styling select
                $('.select').select2({
                    allowClear:true
                });
                $(".switch").bootstrapSwitch();
            },
            error:function(xhr, status, error)
            {
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });   
    $(document).on('change','#OrgID', function (ev)
    {
        ev.preventDefault();        
        $.ajax({
            type:'post',
            url: url_current_page +'/filtercreate',
            dataType:'json',
            data: {
                "_token": token,
                "OrgID": $('#OrgID').val(),
            },
            success:function(result)
            {
                $('#divdataprogram').html(result.datatable);                                   
                //styling select
                $('.select').select2({
                    allowClear:true
                });
                $(".switch").bootstrapSwitch();
            },
            error:function(xhr, status, error)
            {
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    }); 
    $('#frmdata').validate({
        rules: {
            ignore:[],
            OrgID : {
                required : true,
            },
        },
        messages : {
            OrgID : {
                required: "Mohon dipilih OPD / SKPD !"
            },
        }      
    });       
});
</script>
@endsection