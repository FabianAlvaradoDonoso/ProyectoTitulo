@extends('layouts.app')

@section('content')
    <div class="register-box" >
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible" id="success-alert"><button type="button" class="close"
                    data-dismiss="alert" aria-hidden="true">×</button>
                <p><i class="icon fa fa-check"></i>
                    {{ $message }}
                </p>
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert alert-error alert-dismissible" id="error-alert"><button type="button" class="close"
                    data-dismiss="alert" aria-hidden="true">×</button>
                <p><i class="icon fa fa-error"></i>
                    {{ $message }}
                </p>
            </div>
        @endif
        <div class="register-logo">
            <a href="../../index2.html" style="color: rgb(255,255,255)"><b style="color: rgb(255,255,255)">Archivos</b>UTEM</a>
        </div>

        <div class="register-box-body">
            <p class="login-box-msg">Registro nuevo miembro</p>

            <form method="POST" action="{{ route('registerUser') }}">
                @csrf

                <div class="form-group has-feedback">
                    <input id="name" type="text" placeholder="Nombre Completo"
                        class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}"
                        required autocomplete="name" autofocus>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group has-feedback">
                    <input id="email" type="email" placeholder="Email"
                        class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"
                        required autocomplete="email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group has-feedback">
                    <input id="password" type="password" placeholder="Password"
                        class="form-control @error('password') is-invalid @enderror" name="password" required
                        autocomplete="new-password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group has-feedback">
                    <input id="password-confirm" type="password" placeholder="Repetir password" class="form-control"
                        name="password_confirmation" required autocomplete="new-password">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                        <a href="{{ route('login') }}" class="text-center btn btn-primary btn-block btn-flat">Login</a>
                    </div>
                    <div class="col-xs-4"></div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-success btn-block btn-flat">Registrarse</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.form-box -->
    </div>
@endsection
