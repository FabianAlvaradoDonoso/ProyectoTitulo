@extends('layout.inventario')
@section('content')
    <section class="content-header">
        <h1>
            Usuario
            <small>Editar</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Usuario</a></li>
            <li class="active">Editar</li>
        </ol>
    </section>




    <section class="content">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible" id="success-alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <p><i class="icon fa fa-check"></i>
                    {{ $message }}
                </p>
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert alert-error alert-dismissible" id="error-alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <p><i class="icon fa fa-error"></i>
                    {{ $message }}
                </p>
            </div>
        @endif



        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edición datos de Usuario</h3>
            </div>

            <div class="box-body">
                <div class="form">
                    <form class="form-validate form-horizontal " role="form" method="POST" action="{{route('update_user', $usuario->_id)}}" novalidate="novalidate">
                        @csrf
                        {{method_field('PUT')}}

                        {{-- Nombre --}}
                        <div class="form-group {{ ($errors->first('nombre')) ? 'has-error'  :''}}">
                            <label for="nombre" class="col-sm-2 control-label">Nombre</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Máximo 50 caracteres" value="{{$usuario->name}}">
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="form-group {{ ($errors->first('correo')) ? 'has-error'  :''}}">
                            <label for="correo" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="correo" name="correo" placeholder="Máximo 100 caracteres" value="{{$usuario->email}}">
                            </div>
                        </div>

                        {{-- Pregunta por cambiar contraseña --}}
                        <div class="form-group {{ ($errors->first('mantener')) ? 'has-error'  :''}}">
                            <label for="mantener" class="col-sm-2 control-label">Cambiar Contraseña</label>
                            <div class="checkbox col-lg-10" >
                                <label>
                                    <input type="checkbox" name="mantener" id="mantener">
                                </label>
                            </div>
                        </div>

                        {{-- Contraseña antigua --}}
                        <div class="form-group {{ ($errors->first('passOld')) ? 'has-error'  :''}}" name='old' id='old'>
                            <label for="passOld" class="col-sm-2 control-label">Contraseña Actual</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="passOld" name="passOld" placeholder="Password" value="">
                            </div>
                        </div>

                        {{-- Contraseña nueva --}}
                        <div class="form-group {{ ($errors->first('passNew')) ? 'has-error'  :''}}" name='new' id='new'>
                            <label for="passNew" class="col-sm-2 control-label">Contraseña</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="passNew" name="passNew" placeholder="Mínimo 6 caracteres" value="">
                            </div>
                        </div>

                        <div class="box-footer">
                            <div class="pull-right">
                                <a href="{{ route('dashboard') }}"class="btn btn-danger">Cancelar</a>
                                <button class="btn btn-primary ml-3" type="submit">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $("#old").hide();
        $("#new").hide();
        $("#mantener").change(function() {
            if ( $(this).is(':checked') ) {
                $("#old").show();
                $("#new").show();
            } else {
                $("#old").hide();
                $("#new").hide();
            }
        });
    </script>
@endsection
