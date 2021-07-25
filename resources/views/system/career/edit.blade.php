@extends('layout.inventario')
@section('content')
    <section class="content-header">
        <h1>
            Carreras
            <small>Editar</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li><a href="{{route('career.index')}}">Carreras</a></li>
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
                <h3 class="box-title">Edición de una carrera</h3>
            </div>

            <div class="box-body">
                <div class="form">
                    <form class="form-validate form-horizontal " role="form" method="POST" action="{{route('career.update', $career->id)}}" novalidate="novalidate">
                        @csrf
                        {{method_field('PUT')}}
                        <div class="form-group {{ ($errors->first('nombre')) ? 'has-error'  :''}}">
                            <label for="nombre" class="col-sm-2 control-label">Nombre</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{$career->name}}">
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->first('codigo')) ? 'has-error'  :''}}">
                            <label for="codigo" class="col-sm-2 control-label">Código</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Maximo 15 caracteres" value="{{$career->code}}">
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="pull-right">
                                <a href="{{ route('career.index') }}"class="btn btn-danger">Cancelar</a>
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

    </script>
@endsection
