@extends('layouts.app')

@section('template_fastload_css')
    .login-page[_ngcontent-ygg-3]{background-color:#143557;height:100vh;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-ms-flex-align:center;align-items:center;display:-webkit-box;display:-ms-flexbox;display:flex}form[_ngcontent-ygg-3]{text-align:center}.myinput[_ngcontent-ygg-3]{background-color:#143557;color:#fff}[_ngcontent-ygg-3]::-webkit-input-placeholder{color:#fff}[_ngcontent-ygg-3]:-ms-input-placeholder{color:#95cdf1}[_ngcontent-ygg-3]:-moz-placeholder, [_ngcontent-ygg-3]::-moz-placeholder{color:#f1a7a7;opacity:1}
@endsection
@section('content')
    <div _ngcontent-ygg-3="" class="login-page">
        <form method="POST" action="{{ route('login') }}" _ngcontent-ygg-3="" class="ng-untouched ng-pristine ng-valid">
            @csrf
            <div _ngcontent-ygg-3="" class="form-group" align="center">
                <img _ngcontent-ygg-3="" id="welcome" src="images/background.png" width="300px" height="200">
            </div>
            <div _ngcontent-ygg-3="" class="form-group" align="center">
                <input _ngcontent-ygg-3="" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} myinput ng-untouched ng-pristine ng-valid" name="email" value="{{ old('email') }}" required autofocus placeholder="Email Address" style="width:300px" type="text">
                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div> 
            <div _ngcontent-ygg-3="" class="form-group" align="center"> 
                <input _ngcontent-ygg-3="" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} myinput ng-untouched ng-pristine ng-valid" name="password" required placeholder="Password" style="width:300px" type="password">
                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <input _ngcontent-ygg-3="" class="btn btn-primary" style="width:300px; text-transform:none; font-size:15px; color:#fff;" type="submit" value="Login" width="500"><br><br>

            <a href="/admin/login" style="color: #fff">Login Width Hotel Admin</a>
        </form>
    </div>
@endsection
