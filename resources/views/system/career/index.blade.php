@extends('layout.inventario')
@section('content')
    <section class="content-header">
        <h1>
            Carreras
            <small>Listado</small>
            @if (Auth::check() && auth()->user()->is_admin == 1)
                <a href="{{route('career.create')}}" class="btn btn-info btn-sm"><i class="fa fa-plus"></i></a>
            @endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Carreras</li>
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
                <h3 class="box-title">Listado</h3>
            </div>

            <div class="box-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 40%">Nombre</th>
                                <th>Código</th>
                                <th style="width: 20%">Número de ramos</th>
                                <th style="width: 20%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($careers as $career)
                                @php
                                    $count = 0;
                                @endphp
                                @foreach ($career_courses as $pivot)
                                    @if ($career->_id == $pivot->career)
                                        @php
                                            $count++;
                                        @endphp
                                    @endif
                                @endforeach
                                <tr>
                                    <td>{{$career->name}}</td>
                                    <td>{{$career->code}}</td>
                                    <td>{{$count}}</td>
                                    <td class="text-center">
                                        @if (Auth::check() && auth()->user()->is_admin == 1)
                                            <div class="btn-group">
                                                <a href="{{route('career.edit', $career->_id)}}" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#eliminar_career-{{$career->_id}}"><i class="fa fa-trash"></i>
                                            </div>
                                        @else
                                            Sin permisos
                                        @endif
                                    </td>
                                </tr>
                                <div class="modal modal-danger fade" id="eliminar_career-{{$career->_id}}" style="display: none;">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title">Eliminar</h4>
                                            </div>
                                            <div class="modal-body">
                                                    <strong> Si presiona continuar los datos no podrán ser recuperados.</strong><br> ¿Esta seguro que desea eliminar "{{($career->name)}}"?
                                            </div>
                                            <div class="modal-footer">
                                                <form class="" action="{{route('career.destroy', $career->_id)}}" method="post">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                    @csrf
                                                    {{method_field('DELETE')}}
                                                    <button type="submit" class="btn btn-danger">Continuar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
        $(function () {
            $('#example1').DataTable({
            "language": {
                        "emptyTable":			"No hay datos disponibles en la tabla.",
                        "info":		   			"Del _START_ al _END_ de _TOTAL_ ",
                        "infoEmpty":			"Mostrando 0 registros de un total de 0.",
                        "infoFiltered":			"(filtrados de un total de _MAX_ registros)",
                        "infoPostFix":			"(actualizados)",
                        "lengthMenu":			"Mostrar _MENU_ registros",
                        "loadingRecords":		"Cargando...",
                        "processing":			"Procesando...",
                        "search":				"",
                        "searchPlaceholder":	"Buscar",
                        "zeroRecords":			"No se han encontrado coincidencias.",
                        "paginate": {
                            "first":			"Primera",
                            "last":				"Última",
                            "next":				"Siguiente",
                            "previous":			"Anterior"
                        },
                        "aria": {
                            "sortAscending":	"Ordenación ascendente",
                            "sortDescending":	"Ordenación descendente"
                        }
                    },
                    "lengthMenu":				[[5, 10, 20, 25, 50, -1], [5, 10, 20, 25, 50, "Todos"]],
                    "iDisplayLength":			10,
                    "columns" : [
                        {"data": 0},
                        {"data": 1},
                        {"data": 2},
                        {"data": 3},
                    ],
                pageLength: 10,

            });
        })
    </script>
@endsection
