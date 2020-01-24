@extends('layouts.limitless.l_main')
@section('page_title')
    BANTUAN SOSIAL DAN HIBAH
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        BANTUAN SOSIAL DAN HIBAH TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.aspirasi.bansoshibah.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">ASPIRASI</a></li>
    <li><a href="{!!route('bansoshibah.index')!!}">BANTUAN SOSIAL DAN HIBAH</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA BANTUAN SOSIAL DAN HIBAH
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('bansoshibah.create')}}" class="btn btn-info btn-icon heading-btn btnTambah" title="Tambah Data Bantuan Sosial dan Hibah">
                        <i class="icon-googleplus5"></i>
                    </a>
                    @if ($data->Privilege==0)
                    <a href="{{route('bansoshibah.edit',['uuid'=>$data->BansosHibahID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data Bantuan Sosial dan Hibah">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data Bantuan Sosial dan Hibah" data-id="{{$data->BansosHibahID}}" data-url="{{route('bansoshibah.index')}}" class="btn btn-danger btn-icon heading-btn btnDelete">
                        <i class='icon-trash'></i>
                    </a>
                    @endif                    
                    <a href="{!!route('bansoshibah.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>BANSOSHIBAHID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->BansosHibahID}}</p>
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
</div>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $(".btnDelete").click(function(ev) {
        if (confirm('Apakah Anda ingin menghapus Data Bantuan Sosial dan Hibah ini ?')) {
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