@extends('layouts.limitless.l_main')
@section('page_title')
    PEMILIK BANTUAN SOSIAL DAN HIBAH
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        PEMILIK BANTUAN SOSIAL DAN HIBAH TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.aspirasi.pemilikbansoshibah.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">ASPIRASI</a></li>
    <li><a href="{!!route('pemilikbansoshibah.index')!!}">PEMILIK BANTUAN SOSIAL DAN HIBAH</a></li>
    <li class="active">UBAH DATA</li>
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                UBAH DATA
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>
                        <a href="{!!route('pemilikbansoshibah.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>['Aspirasi\PemilikBansosHibahController@update',$data->PemilikBansosHibahID],'method'=>'put','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}        
                <div class="form-group">
                    {{Form::label('Kd_PK','KODE',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Kd_PK',$data->Kd_PK,['class'=>'form-control','placeholder'=>'Kode Pemilik Bantuan Sosial dan Hibah'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('NmPk','NAMA',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('NmPk',$data->NmPk,['class'=>'form-control','placeholder'=>'Nama Pemilik Bantuan Sosial dan Hibah'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Descr',$data->Descr,['class'=>'form-control','placeholder'=>'KETERANGAN','rows' => 2, 'cols' => 40])}}
                    </div>
                </div>  
                <div class="form-group">            
                    <div class="col-md-10 col-md-offset-2">                        
                        {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] )  }}                        
                    </div>
                </div>
            {!! Form::close()!!}
        </div>
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
    $('#frmdata').validate({
        rules: {
            Kd_PK : {
                required: true,
                minlength: 2
            },
            Nm_Pk : {
                required: true,
                minlength: 2
            }
        },
        messages : {
            Kd_PK : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            },
            Nm_Pk : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            }
        }      
    });   
});
</script>
@endsection