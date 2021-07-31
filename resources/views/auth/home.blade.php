@extends('layout.inventario')
@section('content')
    <section class="content-header">
        <h1>
            Bienvenido
            {{-- <small>Dashboard</small> --}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        </ol>
    </section>


    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Sesion Iniciada como <b>{{ auth()->user()->name }}</b></h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                    <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                    <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                Bienvenido a Archivos UTEM, una aplicación para compartir documentos académicos de forma segura con el resto de la comunidad.
                Todo aporte a este gran repositorio servirá para crear una gran biblioteca en los momentos de estudios de todos dentro de la Universidad.
                <br><br>
                <b> Como usuario, puedes subir todos los documentos que quieras! Además de modificar o borrar los documentos que hayas compartido.</b>
                <br><br>
                Esta iniciativa es parte de un Trabajo de Título para la carrera de Ingeniería Civil en Computación mención Informática, por lo que cualquier consulta o mejora,
                por favor de hacer un Pull Request al repositorio de <a href="https://github.com/FabianAlvaradoDonoso/ProyectoTitulo" target="_blank"><b>Github</b></a>
                <br><br>
                En caso de faltar información sobre alguna carrera o curso específico que no esté en la lista, por favor comunícate con
                <a href="mailto:fabian.alvaradod@utem.cl"><b>fabian.alvaradod@utem.cl</b></a> para poder resolverlo.
            </div>
        </div>
    </section>
@endsection
