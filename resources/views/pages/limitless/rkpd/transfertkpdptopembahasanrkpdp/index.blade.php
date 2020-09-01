@extends('layouts.limitless.l_main')
@section('page_title')
    TRANSFER RKPD PERUBAHAN --> PEMBAHASAN RKPD PERUBAHAN
@endsection
@section('page_header')
    <i class="icon-office position-left"></i>
    <span class="text-semibold">
        TRANSFER RKPD PERUBAHAN --> PEMBAHASAN RKPD PERUBAHAN TAHUN {{HelperKegiatan::getTahunPerencanaan()}}  
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.transferrkpdptopembahasanrkpdp.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">RKPD</a></li>
    <li><a href="#">TRANSFER DATA</a></li>
    <li class="active"> TRANSFER RKPD PERUBAHAN --> PEMBAHASAN RKPD PERUBAHAN</li>
@endsection
@section('page_content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title">
                    <i class="icon-search4 position-left"></i>
                    PENCARIAN DATA
                </h5>
            </div>
            <div class="panel-body">
                {!! Form::open(['action'=>'RKPD\TransferRKPDPTOPembahasanRKPDP@search','method'=>'post','class'=>'form-horizontal','id'=>'frmsearch','name'=>'frmsearch'])!!}                                
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kriteria :</label> 
                        <div class="col-md-10">
                            {{Form::select('cmbKriteria', ['kode_organisasi'=>'KODE ORGANISASI','OrgNm'=>'NAMA ORGANISASI'], isset($search['kriteria'])?$search['kriteria']:'kode_organisasi',['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="form-group" id="divKriteria">
                        <label class="col-md-2 control-label">Isi Kriteria :</label>                                                    
                        <div class="col-md-10">                            
                            {{Form::text('txtKriteria',isset($search['isikriteria'])?$search['isikriteria']:'',['class'=>'form-control','placeholder'=>'Isi Kriteria Pencarian','id'=>'txtKriteria'])}}                                                                  
                        </div>
                    </div>                                                     
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            {{ Form::button('<b><i class="icon-search4"></i></b> Cari', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs', 'id'=>'btnSearch'] )  }}                            
                            <a id="btnReset" href="javascript:;" title="Reset Pencarian" class="btn btn-default btn-labeled btn-xs">
                                <b><i class="icon-reset"></i></b> Reset
                            </a>                           
                        </div>
                    </div>  
                {!! Form::close()!!}
            </div>
        </div>
    </div>       
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.rkpd.transferrkpdptopembahasanrkpdp.datatable')
    </div>
</div>
@endsection