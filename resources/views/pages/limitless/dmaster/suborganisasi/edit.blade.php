@extends('layouts.limitless.l_main')
@section('page_title')
    UNIT KERJA
@endsection
@section('page_header')
    <i class="icon-office position-left"></i>
    <span class="text-semibold"> 
        UNIT KERJA TAHUN {{config('eplanning.tahun_perencanaan')}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.organisasi.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">DATA</a></li>
    <li><a href="{!!route('organisasi.index')!!}">UNIT KERJA</a></li>
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
                        <a href="{!!route('organisasi.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>['DMaster\SubOrganisasiController@update',$data->SOrgID],'method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}        
                {{Form::hidden('_method','PUT')}}
                <div class="form-group">
                    {{Form::label('OrgID','URUSAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <select name="OrgID" id="OrgID" class="select">
                            <option></option>
                            @foreach ($daftar_opd as $k=>$item)
                                <option value="{{$k}}" {{$data->OrgID == $k?' selected':''}}>{{$item}}</option>
                            @endforeach
                        </select>                        
                    </div>
                </div>         
                <div class="form-group">
                    {{Form::label('SOrgCd','KODE SKPD / OPD',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('SOrgCd',$data['SOrgCd'],['class'=>'form-control','placeholder'=>'KODE SKPD / OPD','maxlength'=>4])}}
                        {{Form::hidden('kode_organisasi','none',['id'=>'kode_organisasi'])}}
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('SOrgNm','NAMA SKPD / OPD',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('SOrgNm',$data['SOrgNm'],['class'=>'form-control','placeholder'=>'NAMA SKPD / OPD'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Alamat','ALAMAT',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Alamat',$data['Alamat'],['class'=>'form-control','placeholder'=>'ALAMAT SKPD / OPD'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Descr',$data['Descr'],['class'=>'form-control','placeholder'=>'KETERANGAN','rows' => 2, 'cols' => 40])}}
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
<script src="{!!asset('themes/limitless/assets/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/uniform.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
     //styling select
     $('.select').select2({
        placeholder: "PILIH URUSAN",
        allowClear:true
    });
    $('#frmdata').validate({
        ignore:[],
        rules: {
            UrsID : {
                required : true,
            },
            OrgCd : {
                required: true,  
                number: true,
                maxlength: 4              
            },
            OrgNm : {
                required: true,
                minlength: 5      
            },
            Alamat : {
                required: true,
                minlength: 5      
            }
        },
        messages : {
            UrsID : {
                required: "Mohon dipilih Urusan !"
            },
            OrgCd : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                number: "Mohon input dengan tipe data bilangan bulat",
                maxlength: "Nilai untuk Kode Urusan maksimal 4 digit"
            },
            OrgNm : {
                required: "Mohon untuk di isi karena ini diperlukan.",  
                minlength : "Mohon di isi minimal 5 karakter atau lebih."          
            },
            Alamat : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 5 karakter atau lebih."
            }
        }           
    });  
    $(document).on('change','#UrsID',function(ev) {
        ev.preventDefault();  
        UrsID=$(this).val();
        if (UrsID == 'none')
        {
            $("#frmdata :input").not('[name=UrsID]').prop("disabled", true);
            $("#kode_organisasi").val('none');  
        }
        else
        {
            $("#frmdata *").prop("disabled", false);
        }
    });   
});
</script>
@endsection