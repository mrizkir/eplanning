@extends('layouts.limitless.l_main')
@section('page_title')
    MAPPING PROGRAM KE OPD / SKPD
@endsection
@section('page_header')
    <i class="icon-link position-left"></i>
    <span class="text-semibold"> 
        MAPPING PROGRAM KE OPD / SKPD TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.mappingprogramtoopd.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="{!!route('mappingprogramtoopd.index')!!}">MAPPING PROGRAM KE OPD / SKPD</a></li>
    <li class="active">TAMBAH DATA</li>
@endsection
@section('page_content')
<div class="row">
    {!! Form::open(['action'=>'DMaster\MappingProgramToOPDController@store','method'=>'post','id'=>'frmdata','name'=>'frmdata'])!!}                              
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
                            <select name="OrgID" id="OrgID" class="select">
                                <option></option>
                                @foreach ($daftar_opd as $k=>$item)
                                    <option value="{{$k}}">{{$item}}</option>
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
    <div class="col-md-12">
        <div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <div class="row">
                        <div class="col-md-1">
                            {!!Form::select('numberRecordPerPage',['1'=>1,'5'=>5,'10'=>10,'15'=>15,'30'=>30,'50'=>50],$numberRecordPerPage,['id'=>'numberRecordPerPage','class'=>'form-control'])!!}                        
                        </div>
                        <div class="col-md-7">    
                            <select id="filterurusan" class="select">
                                <option></option>
                                @foreach ($daftar_urusan as $k=>$item)
                                    <option value="{{$k}}"{{$k==$filter_ursid_selected?'selected':''}}>{{$item}}</option>
                                @endforeach
                            </select>                      
                        </div>
                    </div>            
                </div>
                <div class="heading-elements">
                    <div class="heading-btn">
                        <a href="{!!route('program.create')!!}" class="btn btn-info btn-xs" title="Tambah PROGRAM">
                            <i class="icon-googleplus5"></i>
                        </a>
                    </div>            
                </div>
            </div>
            @if (count($data) > 0)
            <div class="table-responsive"> 
                <table id="data" class="table table-striped table-hover">
                    <thead>
                        <tr class="bg-teal-700">
                            <th width="55">NO</th>
                            <th>
                                <a class="column-sort text-white" id="col-Kode_Program" data-order="{{$direction}}" href="#">
                                    KODE PROGRAM  
                                </a>                                             
                            </th> 
                            <th>
                                <a class="column-sort text-white" id="col-PrgNm" data-order="{{$direction}}" href="#">
                                    NAMA PROGRAM  
                                </a>                                             
                            </th> 
                            <th>
                                <a class="column-sort text-white" id="col-Nm_Urusan" data-order="{{$direction}}" href="#">
                                    URUSAN  
                                </a>                                             
                            </th>
                            <th width="100">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>                    
                    @foreach ($data as $key=>$item)
                        <tr>
                            <td>
                                {{ ($data->currentpage()-1) * $data->perpage() + $key + 1 }}    
                            </td>                  
                            <td>
                                @php
                                    if ($item->Jns==false && $filter_ursid_selected=='none')
                                    {
                                        echo 'n.nn.'.$item->Kd_Prog;
                                    } 
                                    elseif ($item->Jns==false && $filter_ursid_selected!='none') 
                                    {
                                        echo $filter_kode_urusan_selected.'.'.$item->Kd_Prog;
                                    }
                                    else {
                                        echo $item->kode_program;
                                    }   
                                @endphp
                            </td>
                            <td>{{$item->PrgNm}}</td>
                            <td>
                                @php
                                    if (!$item->Jns)
                                    {
                                        echo "SELURUH URUSAN";
                                    } 
                                    else {
                                        echo $item->Nm_Urusan;
                                    }   
                                @endphp
                            </td>
                            <td>
                                
                            </td>
                        </tr>
                    @endforeach                    
                    </tbody>
                </table>               
            </div>
            <div class="panel-body border-top-info text-center" id="paginations">
                {{$data->links('layouts.limitless.l_pagination')}}               
            </div>
            @else
            <div class="panel-body">
                <div class="alert alert-info alert-styled-left alert-bordered">
                    <span class="text-semibold">Info!</span>
                    Belum ada data yang bisa ditampilkan.
                </div>
            </div>   
            @endif            
        </div>                
    </div>
    {!! Form::close()!!}
</div>   
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    //styling select
    $('.select').select2({
        placeholder: "PILIH OPD / SKPD",
        allowClear:true
    });
    $('#frmdata').validate({
        ignore:[],
        rules: {
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