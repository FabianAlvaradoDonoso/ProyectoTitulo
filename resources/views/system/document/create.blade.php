@extends('layout.inventario')
@section('content')
    <section class="content-header">
        <h1>
            Documentos
            <small>Nuevo</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li><a href="{{route('document.index')}}">Documentos</a></li>
            <li class="active">Nuevo</li>
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
                <h3 class="box-title">Subida de un nuevo documento</h3>
            </div>

            <div class="box-body">
                <div class="form">
                    <form class="form-validate form-horizontal " role="form" method="POST" action="{{route('document.store')}}" novalidate="novalidate" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group {{ ($errors->first('nombre')) ? 'has-error'  :''}}">
                            <label for="nombre" class="col-sm-2 control-label">Nombre</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre del ramo" value="{{Request::old('nombre')}}">
                            </div>
                        </div>

                        <div class="form-group {{ ($errors->first('ramos')) ? 'has-error'  :''}}">
                            <label for="ramos" class="control-label col-lg-2">Pertenece a los ramos (Multiselect)</label>
                            <div class="col-lg-10">
                                <select class="form-control " name="ramos[]" id="ramos[]" multiple="multiple">
                                    @foreach ($courses as $course)
                                        <option value="{{$course->id}}">{{$course->name}}</option>
                                    @endforeach

                                </select>
                                @if ($errors->has('ramos'))
                                    <label for="ramos" class="control-label text-left text-danger">{{$errors->first('ramos')}}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputFile" class="control-label col-lg-2">Subir Archivo</label>
                            <div class="col-lg-10">
                                <input type="file" id="documentFile" name="documentFile" class="form-control">
                            </div>

                        </div>

                        <div class="box-footer">
                            <div class="pull-right">
                                <a href="{{ route('document.index') }}"class="btn btn-danger">Cancelar</a>
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
