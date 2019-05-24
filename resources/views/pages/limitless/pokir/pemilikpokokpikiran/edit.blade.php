@extends('layouts.limitless.l_main')
@section('page_title')
    PEMILIK POKOK PIKIRAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        PEMILIK POKOK PIKIRAN TAHUN PERENCANAAN {{config('eplanning.tahun_perencanaan')}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.pokir.pemilikpokokpikiran.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('pemilikpokokpikiran.index')!!}">PEMILIK POKOK PIKIRAN</a></li>
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
                        <a href="{!!route('pemilikpokokpikiran.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>['Pokir\PemilikPokokPikiranController@update',$data->PemilikPokokID],'method'=>'put','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}        
                <div class="form-group">
                    {{Form::label('Kd_PK','KODE',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Kd_PK',$data->Kd_PK,['class'=>'form-control','placeholder'=>'Kode Pemilik Pokok Pikiran'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('NmPk','NAMA',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('NmPk',$data->NmPk,['class'=>'form-control','placeholder'=>'Nama Pemilik Pokok Pikiran'])}}
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