@extends('layouts.app')

@section('content')

    <div class="login-box">
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
        <div class="login-logo" style="color: rgb(255,255,255)">
            <a href="{{ route('dashboard') }}" style="color: rgb(255,255,255)"><b style="color: rgb(255,255,255)">Archivos</b>UTEM</a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">Accede para comenzar sesión</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group has-feedback">
                    <input id="email" type="email" placeholder="Email"
                        class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"
                        required autocomplete="email" autofocus>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input id="password" type="password" placeholder="Password"
                        class="form-control @error('password') is-invalid @enderror" name="password" required
                        autocomplete="current-password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <a href="{{ route('register') }}"
                            class="text-center btn btn-primary btn-block btn-flat">Registrarse</a>
                    </div>
                    <div class="col-xs-4"></div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-success btn-block btn-flat">Acceder</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-xs-12">
                        <p class="login-box-msg">En caso de olvidar contraseña, mandar correo a:
                            <a href="mailto:fabian.alvaradod@utem.cl" target="_blank">fabian.alvaradod@utem.cl</a>
                        </p>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
