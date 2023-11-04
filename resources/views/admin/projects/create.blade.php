@extends('adminlte::page')

@section('title', 'Configuración general')

@section('content_header')
    <h1>Configuración general</h1>
@stop

@section('content')
    <p>Bienvenido al panel administrativo.</p>

    <form method="POST" action="{{ route('project.store')}}" enctype="multipart/form-data">
        @csrf

        <div class="custom-file mb-4">
            <input type="file" accept="image/*" class="custom-file-input" id="url_main_image" name="url_main_image">
            <label class="custom-file-label" for="url_main_image">Elegir imagen principal</label>
            @error('url_main_image')
            <div class="">Por favor seleccione una archivo válido.</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp"
                placeholder="Nombre">
        </div>

        <div class="form-group">
            <label for="subtitle">Subtítulo</label>
            <input type="text" class="form-control" id="subtitle" name="subtitle" aria-describedby="emailHelp"
                placeholder="Subtítulo">
        </div>

        <div class="form-group">
            <label for="description">Descripción</label>
            <input type="text" class="form-control" id="description" name="description" aria-describedby="emailHelp" placeholder="Subtítulo">
        </div>

        <div class="form-group">
            <label for="work_made">Lista de trabajos realizados</label>
            <textarea
                id="work_made"
                name="work_made"
                cols="30"
                rows="10">
            </textarea>
        </div>

        <button type="submit" class="btn btn-primary d-block mb-4">Guardar cambios</button>
        <div class="pb-5">
        </div>
    </form>

    @push('js')
        <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

        <script>
            ClassicEditor
                .create( document.querySelector( '#work_made' ) )
                .catch( error => {
                    console.error( error );
                } );
        </script>
    @endpush

@stop
