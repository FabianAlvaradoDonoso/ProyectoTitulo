@extends('layout.inventario')
@section('content')
    <section class="content-header">
        <h1>
            {{ $course->name }} ({{$course->code}})
            <small>Listado</small>
            @if (Auth::check())
                <a href="{{ route('document.create') }}" class="btn btn-info btn-sm"><i class="fa fa-plus"></i></a>
            @endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Documentos</li>
        </ol>
    </section>

    <section class="content">
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

        @if (Auth::Check())
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Mis Documentos</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                            title="Remove">
                            <i class="fa fa-times"></i></button>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 40%">Archivo</th>
                                    <th style="width: 10%">Tipo</th>
                                    <th style="width: 10%">Tamaño</th>
                                    <th style="width: 20%">Ramo</th>
                                    <th style="display:none; width:1%">Nombre Ramo</th>
                                    <th style="width: 19%">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documentos as $document)
                                    @if (Auth::Check() && auth()->user()->_id == $document->user)
                                        @if ($document->size >= 1000000)
                                            @php
                                                $tamanio = round($document->size / 1024 / 1024, 2, PHP_ROUND_HALF_UP);
                                                $key = 'MB';
                                            @endphp
                                        @else
                                            @php
                                                $tamanio = round($document->size / 1024, 2, PHP_ROUND_HALF_UP);
                                                $key = 'KB';
                                            @endphp
                                        @endif
                                        <tr>
                                            <td>{{ $document->name }}</td>
                                            <td>{{ $document->type_id }}</td>
                                            <td>{{ $tamanio }} {{ $key }}</td>
                                            <td>{{ $document->code_course }}</td>
                                            <td style="display:none;">{{ $document->name_course }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('document.show', $document->_id) }}" target="_blank" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                                    @if (Auth::check())
                                                        <a href="{{ route('document.edit', $document->_id) }}" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#eliminar_document-{{ $document->_id }}"><i class="fa fa-trash"></i>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal modal-danger fade" id="eliminar_document-{{ $document->_id }}" style="display: none;">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                        <h4 class="modal-title">Eliminar</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <strong> Si presiona continuar los datos no podrán ser recuperados.</strong><br> ¿Esta seguro que desea eliminar {{ $document->name }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form class="" action="{{ route('document.destroy', $document->_id) }}" method="post">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Cancelar</button>
                                                            @csrf
                                                            {{ method_field('DELETE') }}
                                                            <button type="submit" class="btn btn-danger">Continuar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>

                                    @endif

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Todos los documentos</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"> <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"> <i class="fa fa-times"></i></button>
                </div>
            </div>

            <div class="box-body">
                <div class="table-responsive">
                    <table id="example2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 40%">Archivo</th>
                                <th style="width: 10%">Tipo</th>
                                <th style="width: 10%">Tamaño</th>
                                <th style="width: 20%">Ramo</th>
                                <th style="display:none; width:1%">Nombre Ramo</th>
                                <th style="width: 19%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documentos as $document)
                                {{-- @if (Auth::Check() && auth()->user()->_id != $document->user) --}}

                                    @if ($document->size >= 1000000)
                                        @php
                                            $tamanio = round($document->size / 1024 / 1024, 2, PHP_ROUND_HALF_UP);
                                            $key = 'MB';
                                        @endphp
                                    @else
                                        @php
                                            $tamanio = round($document->size / 1024, 2, PHP_ROUND_HALF_UP);
                                            $key = 'KB';
                                        @endphp
                                    @endif
                                    <tr>
                                        <td>{{ $document->name }}</td>
                                        <td>{{ $document->type_id }}</td>
                                        <td>{{ $tamanio }} {{ $key }}</td>
                                        <td>{{ $document->code_course }}</td>
                                        <td style="display:none;">{{ $document->name_course }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('document.show', $document->_id) }}" target="_blank" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                                @if (Auth::check() && (auth()->user()->is_admin == 1 || auth()->user()->_id == $document->user))
                                                    <a href="{{ route('document.edit', $document->_id) }}" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#eliminar_document-{{ $document->_id }}"><i class="fa fa-trash"></i>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal modal-danger fade" id="eliminar_document-{{ $document->_id }}" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span></button>
                                                    <h4 class="modal-title">Eliminar</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <strong> Si presiona continuar los datos no podrán ser recuperados.</strong><br> ¿Esta seguro que desea eliminar {{ $document->name }}?
                                                </div>
                                                <div class="modal-footer">
                                                    <form class="" action="{{ route('document.destroy', $document->_id) }}" method="post">
                                                        <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">Cancelar</button>
                                                        @csrf
                                                        {{ method_field('DELETE') }}
                                                        <button type="submit" class="btn btn-danger">Continuar</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>

                                {{-- @endif --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $(function() {
            $('#example1').DataTable({
                "language": {
                    "emptyTable": "No hay datos disponibles en la tabla.",
                    "info": "Del _START_ al _END_ de _TOTAL_ ",
                    "infoEmpty": "Mostrando 0 registros de un total de 0",
                    "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                    "infoPostFix": " (actualizados).",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "",
                    "searchPlaceholder": "Buscar",
                    "zeroRecords": "No se han encontrado coincidencias.",
                    "paginate": {
                        "first": "Primera",
                        "last": "Última",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": "Ordenación ascendente",
                        "sortDescending": "Ordenación descendente"
                    }
                },
                "lengthMenu": [
                    [5, 10, 20, 25, 50, -1],
                    [5, 10, 20, 25, 50, "Todos"]
                ],
                "iDisplayLength": 10,
                "columns": [{
                        "data": 0
                    },
                    {
                        "data": 1
                    },
                    {
                        "data": 2
                    },
                    {
                        "data": 3
                    },
                    {
                        "data": 4
                    },
                    {
                        "data": 5
                    },
                ],
                pageLength: 10,

            });
        })
        $(function() {
            $('#example2').DataTable({
                "language": {
                    "emptyTable": "No hay datos disponibles en la tabla.",
                    "info": "Del _START_ al _END_ de _TOTAL_ ",
                    "infoEmpty": "Mostrando 0 registros de un total de 0",
                    "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                    "infoPostFix": " (actualizados).",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "",
                    "searchPlaceholder": "Buscar",
                    "zeroRecords": "No se han encontrado coincidencias.",
                    "paginate": {
                        "first": "Primera",
                        "last": "Última",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": "Ordenación ascendente",
                        "sortDescending": "Ordenación descendente"
                    }
                },
                "lengthMenu": [
                    [5, 10, 20, 25, 50, -1],
                    [5, 10, 20, 25, 50, "Todos"]
                ],
                "iDisplayLength": 10,
                "columns": [{
                        "data": 0
                    },
                    {
                        "data": 1
                    },
                    {
                        "data": 2
                    },
                    {
                        "data": 3
                    },
                    {
                        "data": 4
                    },
                    {
                        "data": 5
                    },
                ],
                pageLength: 10,

            });
        })
    </script>
@endsection
