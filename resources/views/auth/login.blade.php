@extends('layouts.default.l_login')
@section('page_title')
    LOGIN
@endsection
@section('page_content')
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>SIMRENBANGDA </b>KABUPATEN BINTAN</a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">Masukan username dan password untuk masuk ke dalam Portal EKampus</p>          
        <form action="{{route('login')}}" method="post" name="frmdlogin" id="frmlogin">
            @csrf
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Username" name="username" id="username" value="{{ old('username') }}" >
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>       
            <div class="row">     
                <div class="col-md-offset-8 col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
                </div>            
            </div>
            @if ($errors->has('username'))
            <div class="row">     
                <div class="col-xs-12">     
                    <div class="box-body">  
                        <div class="alert alert-danger text-center" style="margin-bottom:0px">                
                            {{ $errors->first('username') }}                        
                        </div>
                    </div>
                </div>            
            </div>                
            @endif            
        </form>
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
    $('#frmlogin').validate({
        rules: {
            username : {
                required: true
            },
            password : {
                required: true
            }
        },
        messages : {
            username : {
                required: "Mohon untuk di isi username karena diperlukan."
            },
            password : {
                required: "Mohon untuk di isi password karena diperlukan."
            }
        }        
    });   
});
</script>
@endsection