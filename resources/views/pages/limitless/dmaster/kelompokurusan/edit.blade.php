@extends('layouts.limitless.l_main')
@section('page_title')
    KELOMPOKURUSAN
@endsection
@section('page_header')
    <i class="fa fa-lock"></i> 
    KELOMPOKURUSAN
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.kelompokurusan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('kelompokurusan.index')!!}">KELOMPOKURUSAN</a></li>
    <li class="active">UBAH DATA</li>
@endsection
@section('page_content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-pencil"></i> UBAH DATA
                </h3>
                <div class="box-tools">
                    <a href="{!!route('kelompokurusan.index')!!}" class="btn btn-default" title="keluar">
                        <i class="fa fa-close"></i>
                    </a>
                </div>
            </div>
            {!! Form::open(['action'=>['DMaster\KelompokUrusanController@update',$data->kelompokurusan_id],'method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}        
                <div class="box-body">
                    {{Form::hidden('_method','PUT')}}
                    <div class="form-group">
                        {{Form::label('replaceit','replaceit',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            {{Form::text('replaceit',$data[''],['class'=>'form-control','placeholder'=>'replaceit'])}}
                        </div>                
                    </div>            
                </div>
                <div class="box-footer">
                    <div class="form-group">            
                        <div class="col-md-12 col-md-offset-2">                        
                            {{ Form::button('<i class="fa fa-save"></i> Simpan', ['type' => 'submit', 'class' => 'btn btn-primary'] )  }}
                        </div>
                    </div>     
                </div>
            {!! Form::close()!!}
        </div>
    </div>   
</div>   
@endsection
@section('page_asset_js')
<script src="{!!asset('default/assets/jquery-validation/dist/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('default/assets/jquery-validation/dist/additional-methods.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
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