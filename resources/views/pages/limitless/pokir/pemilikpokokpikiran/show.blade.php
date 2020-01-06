@extends('layouts.limitless.l_main')
@section('page_title')
    PEMILIK POKOK PIKIRAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        PEMILIK POKOK PIKIRAN TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.pokir.pemilikpokokpikiran.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">POKIR / RESES</a></li>
    <li><a href="{!!route('pemilikpokokpikiran.index')!!}">PEMILIK POKOK PIKIRAN</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA PEMILIK POKOK PIKIRAN
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('pemilikpokokpikiran.create')}}" class="btn btn-info btn-icon heading-btn btnTambah" title="Tambah Data Pemilik Pokok Pikiran">
                        <i class="icon-googleplus5"></i>
                    </a>
                    <a href="{{route('pemilikpokokpikiran.edit',['id'=>$data->PemilikPokokID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data Pemilik Pokok Pikiran">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data Pemilik Pokok Pikiran" data-id="{{$data->PemilikPokokID}}" data-url="{{route('pemilikpokokpikiran.index')}}" class="btn btn-danger btn-icon heading-btn btnDelete">
                        <i class='icon-trash'></i>
                    </a>
                    <a href="{!!route('pemilikpokokpikiran.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PemilikPokokID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->PemilikPokokID}}</p>
                                </div>                            
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KODE: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Kd_PK}}</p>
                                </div>                            
                            </div>    
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->NmPk}}</p>
                                </div>                            
                            </div>    
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
                             <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KETERANGAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Descr}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. BUAT: </strong></label>
                                <div class="col-md-8"> 
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$data->updated_at)}}</p>
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
    <div class="col-md-12" id="divdatatable">
        <div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h6 class="panel-title">&nbsp;</h6>
                </div>
            </div>

            @if (count($daftar_pokir) > 0)
            <div class="table-responsive"> 
                <table id="data" class="table table-striped table-hover">
                    <thead>
                        <tr class="bg-teal-700">
                            <th width="55">NO</th>
                            <th width="400">
                                <a class="column-sort text-white" id="col-NamaUsulanKegiatan" href="#">
                                    NAMA KEGIATAN  
                                </a>                                             
                            </th> 
                            <th>
                                <a class="column-sort text-white" id="col-Lokasi" href="#">
                                    LOKASI  
                                </a>                                             
                            </th> 
                            <th>
                                RKPD FINAL
                            </th>
                        </tr>
                    </thead>
                    <tbody>                  
                        @php
                            $jumlah_masuk=0;
                        @endphp  
                        @foreach ($daftar_pokir as $key=>$item)
                            <tr>
                                <td>
                                    {{ $key + 1 }}    
                                </td> 
                                <td>{{$item->NamaUsulanKegiatan}}</td>
                                <td>{{$item->Lokasi}}</td>
                                <td>
                                    @php
                                        if (strlen($item->RKPDRincID)>0)
                                        {
                                            $jumlah_masuk+=1;
                                        }
                                    @endphp
                                    {{$item->RKPDRincID}}
                                </td>
                            </tr>
                        @endforeach                    
                    </tbody>
                    <tfoot>
                        <tr class="bg-grey-300" style="font-weight:bold">
                            <td colspan="2" class="text-right">TOTAL</td>
                            <td colspan="2">{{$daftar_pokir->count()}}</td>                             
                        </tr>
                        <tr class="bg-grey-300" style="font-weight:bold">
                            <td colspan="2" class="text-right">TOTAL MASUK</td>
                            <td colspan="2">{{$jumlah_masuk}}</td>                             
                        </tr>
                        <tr class="bg-grey-300" style="font-weight:bold">
                            <td colspan="2" class="text-right">TIDAK MASUK</td>
                            <td colspan="2">{{$daftar_pokir->count()-$jumlah_masuk}}</td>                             
                        </tr>
                    </tfoot>
                </table>
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
</div>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $(".btnDelete").click(function(ev) {
        if (confirm('Apakah Anda ingin menghapus Data Pemilik Pokok Pikiran ini ?')) {
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