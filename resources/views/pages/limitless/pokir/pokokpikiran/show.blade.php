@extends('layouts.limitless.l_main')
@section('page_title')
    POKOK PIKIRAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        POKOK PIKIRAN TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.pokir.pokokpikiran.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">POKIR / RESES</a></li>
    <li><a href="{!!route('pokokpikiran.index')!!}">POKOK PIKIRAN</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA POKOK PIKIRAN
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('pokokpikiran.create')}}" class="btn btn-info btn-icon heading-btn btnTambah" title="Tambah Data Pokok Pikiran">
                        <i class="icon-googleplus5"></i>
                    </a>
                    @if ($data->Privilege==0)
                    <a href="{{route('pokokpikiran.edit',['uuid'=>$data->PokPirID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data Pokok Pikiran">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data Pokok Pikiran" data-id="{{$data->PokPirID}}" data-url="{{route('pokokpikiran.index')}}" class="btn btn-danger btn-icon heading-btn btnDelete">
                        <i class='icon-trash'></i>
                    </a>
                    @endif                    
                    <a href="{!!route('pokokpikiran.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>POKPIRID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->PokPirID}}</p>
                                </div>                            
                            </div>    
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA USULAN KEGIATAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->NamaUsulanKegiatan}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TARGET: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatAngka($data->Sasaran_Angka)}} {{$data->Sasaran_Uraian}}</p>
                                </div>                            
                            </div>   
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PRIORITAS: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">
                                        <span class="label label-flat border-pink text-pink-600">
                                            {{HelperKegiatan::getNamaPrioritas($data->Prioritas)}}
                                        </span>
                                    </p>
                                </div>                            
                            </div>    
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. BUAT: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$data->created_at)}}</p>
                                </div>                            
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. UBAH: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$data->updated_at)}}</p>
                                </div>                            
                            </div>                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  LOKASI
                </h5>
                <div class="heading-elements">   
                    
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KECAMATAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_Kecamatan}}</p>
                                </div>                            
                            </div>    
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>DESA </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_Desa}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>LOKASI </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Lokasi}}</p>
                                </div>                            
                            </div>   
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  TRACKING
                </h5>
                <div class="heading-elements">   
                    
                </div>
            </div>
            @if (count($data_tracking) > 0)           
            <div class="table-responsive"> 
                <table id="data" class="table table-striped table-hover">
                    <thead>
                        <tr class="bg-teal-700">
                            <th>
                                NAMA TAHAPAN  
                            </th> 
                            <th>
                                OPD / SKPD
                            </th> 
                            <th>
                                UNIT KERJA
                            </th> 
                            <th>
                                DALAM KEGIATAN
                            </th> 
                            <th>
                                PAGU DANA
                            </th> 
                        </tr>
                    </thead>
                    <tbody>                    
                    @foreach ($data_tracking as $key=>$item)
                        @php
                            switch($item->EntryLvl)
                            {
                                case 0 :
                                    $tahapan = 'PRA RENJA';
                                    $pagudana=$item->Jumlah1;
                                break;
                                case 1 :
                                    $tahapan = 'RAKOR BIDANG';
                                    $pagudana=$item->Jumlah2;
                                break;
                                case 3 :
                                    $tahapan = 'FORUM OPD';
                                    $pagudana=$item->Jumlah3;
                                break;
                                case 4 :
                                    $tahapan = 'MUSRENBANG KABUPATEN';
                                    $pagudana=$item->Jumlah4;
                                break;
                                case 5 :
                                    $tahapan = 'VERIFIKASI RENCANA KERJA (FINAL)';
                                    $pagudana=$item->Jumlah5;
                                break;
                            }
                        @endphp
                        <tr>                                              
                            <td>
                                {{$tahapan}}
                            </td>
                            <td>{{$item->OrgNm}}</td>
                            <td>{{$item->SOrgNm}}</td>
                            <td>{{$item->KgtNm}}</td>
                            <td>{{Helper::formatUang($pagudana)}}</td>                            
                        </tr>
                        <tr class="text-center info">
                            <td colspan="11">                                        
                                <span class="label label-warning label-rounded" style="text-transform: none">
                                    <strong>CREATED:</strong>
                                    {{Helper::tanggal('d/m/Y H:m',$data->created_at)}}
                                </span>                        
                                <span class="label label-warning label-rounded" style="text-transform: none">
                                    <strong>UPDATED:</strong>
                                    {{Helper::tanggal('d/m/Y H:m',$data->updated_at)}}
                                </span>
                            </td>
                        </tr>
                    @endforeach                    
                    </tbody>
                </table>               
            </div>
            @else
            <div class="panel-body">
                <div class="alert alert-info alert-styled-left alert-bordered">
                    <span class="text-semibold">Info!</span>
                    Pokok Pikiran ini belum di Akomodir oleh OPD / SKPD
                </div>
            </div>   
            @endif        
        </div>
    </div>
</div>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $(".btnDelete").click(function(ev) {
        if (confirm('Apakah Anda ingin menghapus Data Pokok Pikiran ini ?')) {
            let url_ = $(this).attr("data-url");
            let id = $(this).attr("data-id");
            let token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({            
                type:'post',
                url:url_+'/'+id,
                dataType: 'json',
                data: {
                    "_method": 'DELETE',
                    "_token": token,
                    "id": id,
                },
                success:function(data){ 
                    window.location.replace(url_);                        
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