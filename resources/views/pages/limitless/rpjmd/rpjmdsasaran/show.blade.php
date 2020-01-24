@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD SASARAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RPJMD SASARAN TAHUN {{HelperKegiatan::getRPJMDTahunAwal()}} - {{HelperKegiatan::getRPJMDTahunAkhir()}}  
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.rpjmd.rpjmdsasaran.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RPJMD</a></li>
    <li><a href="{!!route('rpjmdstrategi.index')!!}">SASARAN</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA RPJMD SASARAN
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('rpjmdsasaran.edit',['uuid'=>$data->PrioritasSasaranKabID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data RPJMD Sasaran">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data RPJMD Sasaran" data-id="{{$data->PrioritasSasaranKabID}}" data-url="{{route('rpjmdsasaran.index')}}" class="btn btn-danger btn-icon heading-btn btnDeleteSasaran">
                        <i class='icon-trash'></i>
                    </a>
                    <a href="{!!route('rpjmdsasaran.create')!!}" class="btn btn-primary btn-info heading-btn btnEdit" title="Tambah RPJMD Sasaran">
                        <i class="icon-googleplus5"></i>
                    </a>
                    <a href="{!!route('rpjmdsasaran.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PrioritasSasaranKabID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->PrioritasSasaranKabID}}</p>
                                </div>                            
                            </div>                        
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KODE SASARAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Kd_Sasaran}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA SASARAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_Sasaran}}</p>
                                </div>                            
                            </div>  
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KODE TUJUAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Kd_Tujuan}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA TUJUAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_Tujuan}}</p>
                                </div>                            
                            </div>     
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. BUAT / TGL. UBAH: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$data->created_at)}} / {{Helper::tanggal('d/m/Y H:m',$data->updated_at)}}</p>
                                </div>                            
                            </div>                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>      
</div>
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                TAMBAH INDIKATOR SASARAN DAN TARGET CAPAIAN
            </h5>            
        </div>
        <div class="panel-body">            
            {!! Form::open(['action'=>'RPJMD\RPJMDSasaranController@store1','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                              
                {{Form::hidden('PrioritasSasaranKabID',$data->PrioritasSasaranKabID)}}                                
                <div class="form-group">
                    {{Form::label('NamaIndikator','NAMA INDIKATOR',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('NamaIndikator','',['class'=>'form-control','placeholder'=>'NAMA INDIKATOR'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Satuan','SATUAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Satuan','',['class'=>'form-control','placeholder'=>'SATUAN'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('KondisiAwal','KONDISI KINERJA AWAL ('.(HelperKegiatan::getRPJMDTahunAwal()).')',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('KondisiAwal','',['class'=>'form-control','placeholder'=>'KONDISI KINERJA AWAL'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('N1','N1',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('N1','',['class'=>'form-control','placeholder'=>'N1'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('N2','N2',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('N2','',['class'=>'form-control','placeholder'=>'N2'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('N3','N3',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('N3','',['class'=>'form-control','placeholder'=>'N3'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('N4','N4',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('N4','',['class'=>'form-control','placeholder'=>'N4'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('N5','N5',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('N5','',['class'=>'form-control','placeholder'=>'N5'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('KondisiAkhir','KONDISI AKHIR RPJMD ('.(HelperKegiatan::getRPJMDTahunAkhir()+1).')',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('KondisiAkhir','',['class'=>'form-control','placeholder'=>'KONDISI AKHIR RPJMD'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Descr','',['class'=>'form-control','placeholder'=>'KETERANGAN','rows' => 2, 'cols' => 40])}}
                    </div>
                </div>
                <div class="col-md-10 col-md-offset-2">                        
                    {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}                                            
                </div>                
            {!! Form::close()!!}                      
        </div>
    </div>
    <div class="panel panel-flat border-top-lg border-top-info border-bottom-info" id="divdatatableindikatorsasaran">
        @include('pages.limitless.rpjmd.rpjmdsasaran.datatableindikatorsasaran')
    </div> 
</div>
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $(document).on('click',".btnDeleteSasaran", function(ev) {
        if (confirm('Apakah Anda ingin menghapus Data RPJMD Sasaran ini ?')) {
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
    $("#divdatatableindikatorsasaran").on("click",".btnDelete", function(){
        if (confirm('Apakah Anda ingin menghapus Data Indikator Sasaran ini ?')) {
            let url_ = $(this).attr("data-url");
            let id = $(this).attr("data-id");
            $.ajax({            
                type:'post',
                url:url_+'/'+id,
                dataType: 'json',
                data: {
                    "_method": 'DELETE',
                    "_token": token,
                    "id": id,
                    'indikatorsasaran':true
                },
                success:function(result){ 
                    if (result.success==1){
                        $('#divdatatableindikatorsasaran').html(result.datatable);                        
                    }else{
                        console.log("Gagal menghapus data indikator Sasaran dengan id "+id);
                    }                    
                },
                error:function(xhr, status, error){
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        }        
    });
    $('#frmdata').validate({
        ignore:[],
        rules: {
            NamaIndikator : {
                required: true
            },  
            Satuan : {
                required: true
            },
            KondisiAwal : {
                required: true
            },         
            N1 : {
                required: true
            },         
            N2 : {
                required: true
            },         
            N3 : {
                required: true
            },         
            N4 : {
                required: true
            },         
            N5 : {
                required: true
            },         
            KondisiAkhir : {
                required: true,
            }           
        },
        messages : {
            NamaIndikator : {
                required: "Mohon untuk di isi nama indikator."              
            },        
            Satuan : {
                required: "Mohon untuk di isi satuan."                
            },
            KondisiAwal : {
                required: "Mohon untuk di isi kondisi kinerja awal"                    
            },
            N1 : {
                required: "Mohon untuk di isi kondisi N1"                    
            },
            N2 : {
                required: "Mohon untuk di isi kondisi N2"                    
            },
            N3 : {
                required: "Mohon untuk di isi kondisi N3"                    
            },
            N4 : {
                required: "Mohon untuk di isi kondisi N4"                    
            },
            N5 : {
                required: "Mohon untuk di isi kondisi N5"                    
            },
            KondisiAkhir : {
                required: "Mohon untuk di isi kondisi akhir RPJMD"                            
            }            
        }      
    });
});
</script>
@endsection