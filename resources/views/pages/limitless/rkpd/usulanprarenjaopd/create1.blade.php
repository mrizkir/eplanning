@extends('layouts.limitless.l_main')
@section('page_title')
    USULAN PRA RENJA OPD/SKPD
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        USULAN PRA RENJA OPD/SKPD TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.usulanprarenjaopd.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">ASPIRASI / USULAN</a></li>
    <li><a href="{!!route('usulanprarenjaopd.index')!!}">USULAN PRA RENJA OPD/SKPD</a></li>
    <li class="active">TAMBAH DATA INDIKATOR KEGIATAN</li>
@endsection
@section('page_sidebar')
    @include('layouts.limitless.l_sidebar_prarenja')
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                TAMBAH DATA INDIKATOR KEGIATAN
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>               
                        <a href="{!!route('usulanprarenjaopd.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'RKPD\UsulanPraRenjaOPDController@store1','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                              
                <div class="form-group">    
                    <div class="form-group">
                        <label class="col-md-2 control-label">POSISI ENTRI: </label>
                        <div class="col-md-10">
                            <p class="form-control-static">USULAN PRA RENJA OPD / SKPD</p>
                        </div>                            
                    </div>    
                    <div class="form-group">
                        {{Form::label('UrsID','INDIKATOR KINERJA',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            <select name="UrsID" id="UrsID" class="select">
                                <option></option>
                                @foreach ($daftar_indikatorkinerja as $k=>$item)
                                    <option value="{{$k}}">{{$item}}</option>
                                @endforeach
                            </select>                        
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-2 control-label">NAMA INDIKATOR: </label>
                        <div class="col-md-10">
                            <p class="form-control-static" id="pNamaIndikator">-</p>
                        </div>                            
                    </div>  
                    <div class="form-group">
                        <label class="col-md-2 control-label">TARGET ANGKA: </label>
                        <div class="col-md-10">
                            <p class="form-control-static" id="pTargetAngka">-</p>
                        </div>                            
                    </div>    
                    <div class="form-group">
                        <label class="col-md-2 control-label">TARGET URAIAN: </label>
                        <div class="col-md-10">
                            <p class="form-control-static" id="pTargetUraian">-</p>
                        </div>                            
                    </div>  
                    <div class="form-group">
                        <label class="col-md-2 control-label">TAHUN (RPJMD): </label>
                        <div class="col-md-10">
                            <p class="form-control-static">{{config('globalsettings.tahun_perencanaan')}}</p>
                        </div>                            
                    </div>  
                    <div class="col-md-10 col-md-offset-2">                        
                        {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}
                        <a href="{{route('usulanprarenjaopd.create2',['id'=>$renja->RenjaID])}}">
                            <b><i class="icon-forward3" class="btn btn-info btn-labeled btn-xs"></i></b> SKIP
                        </a>                        
                    </div>
                </div>
            {!! Form::close()!!}
        </div>
    </div>
    <div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
        <div class="panel-heading">
            <div class="panel-title">
                <h5>DAFTAR INDIKATOR KEGIATAN</h5>
            </div>
            <div class="heading-elements">
                
            </div>
        </div>
        @if (count($data) > 0)
        <div class="table-responsive"> 
            <table id="data" class="table table-striped table-hover">
                <thead>
                    <tr class="bg-teal-700">
                        <th width="55">NO</th>     
                        <th width="150">
                            <a class="column-sort text-white" id="col-kode_kegiatan" data-order="{{$direction}}" href="#">
                                KODE KEGIATAN                                                                       
                            </a>
                        </th>                
                        <th width="400">
                            <a class="column-sort text-white" id="col-KgtNm" data-order="{{$direction}}" href="#">
                                NAMA KEGIATAN                                                                       
                            </a>
                        </th> 
                        <th width="300">
                            <a class="column-sort text-white" id="col-Uraian" data-order="{{$direction}}" href="#">
                                NAMA URAIAN                                                                       
                            </a>
                        </th>
                        <th width="200">
                            <a class="column-sort text-white" id="col-Sasaran_Angka1" data-order="{{$direction}}" href="#">
                                SASARAN  
                            </a>                                             
                        </th> 
                        <th width="120">                        
                            TARGET (%)                        
                        </th> 
                        <th width="150" class="text-right">
                            <a class="column-sort text-white" id="col-Jumlah1" data-order="{{$direction}}" href="#">
                                NILAI  
                            </a>                                             
                        </th> 
                        <th width="80">
                            <a class="column-sort text-white" id="col-Status" data-order="{{$direction}}" href="#">
                                STATUS  
                            </a>                                             
                        </th> 
                        <th width="120">AKSI</th>
                    </tr>
                </thead>
                <tbody>                    
                @foreach ($data as $key=>$item)
                    <tr>
                        <td>
                            {{ ($data->currentpage()-1) * $data->perpage() + $key + 1 }}    
                        </td>
                        <td>{{$item->kode_kegiatan}}</td>
                        <td>{{$item->KgtNm}}</td>
                        <td>{{$item->Uraian}}</td>
                        <td>{{Helper::formatAngka($item->Sasaran_Angka1)}} {{$item->Sasaran_Uraian1}}</td>
                        <td>{{$item->Target1}}</td>
                        <td class="text-right">{{Helper::formatuang($item->Jumlah1)}}</td>
                        <td>{{$item->Status}}</td>
                        <td>
                            <ul class="icons-list">
                                <li class="text-primary-600">
                                    <a class="btnShow" href="{{route('usulanprarenjaopd.show',['id'=>$item->usulanprarenjaopd_id])}}" title="Detail Data UsulanPraRenjaOPD">
                                        <i class='icon-eye'></i>
                                    </a>  
                                </li>
                                <li class="text-primary-600">
                                    <a class="btnEdit" href="{{route('usulanprarenjaopd.edit',['id'=>$item->usulanprarenjaopd_id])}}" title="Ubah Data UsulanPraRenjaOPD">
                                        <i class='icon-pencil7'></i>
                                    </a>  
                                </li>
                                <li class="text-danger-600">
                                    <a class="btnDelete" href="javascript:;" title="Hapus Data UsulanPraRenjaOPD" data-id="{{$item->usulanprarenjaopd_id}}" data-url="{{route('usulanprarenjaopd.index')}}">
                                        <i class='icon-trash'></i>
                                    </a> 
                                </li>
                            </ul>
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
                Belum ada data yang bisa ditampilkan. Mohon pilih terlebih dahulu OPD dan Unit Kerja
            </div>
        </div>   
        @endif            
    </div>
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
    $('#UrsID.select').select2({
        placeholder: "PILIH INDIKATOR KINERJA (RPMJD)",
        allowClear:true
    });
    $('#PrgID.select').select2({
        placeholder: "PILIH NAMA PROGRAM",
        allowClear:true
    });
    $('#KgtID.select').select2({
        placeholder: "PILIH NAMA KEGIATAN",
        allowClear:true
    });
    $('#frmdata').validate({
        rules: {
            replaceit : {
                required: true,
                minlength: 2
            }
        },
        messages : {
            replaceit : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            }
        }      
    });   
});
</script>
@endsection