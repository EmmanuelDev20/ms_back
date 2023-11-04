@extends('adminlte::page')

@section('title', 'Configuración general')

@section('content_header')
    <h1>Editar proyecto</h1>
@stop

@section('content')
    <form method="POST" action="{{ route('project.update', $project)}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="custom-file mb-4">
            <input type="file" accept="image/*" class="custom-file-input" id="url_main_image" name="url_main_image">
            <label class="custom-file-label" for="url_main_image">Elegir imagen principal</label>
            @error('url_main_image')
            <div class="">Por favor seleccione una archivo válido.</div>
            @enderror
        </div>

        @if ($project->url_main_image)
            <img src="{{asset($project->url_main_image)}}" class="w-25 mb-4" alt="Responsive image">
        @endif

        <div class="form-group">
            <label for="name">Nombre</label>
            <input
                type="text"
                class="form-control"
                value="{{ $project->name }}"
                id="name"
                name="name"
                aria-describedby="emailHelp"
                placeholder="Nombre">
        </div>

        <div class="form-group">
            <label for="subtitle">Subtítulo</label>
            <input
                type="text"
                class="form-control"
                value="{{ $project->subtitle }}"
                id="subtitle"
                name="subtitle"
                aria-describedby="emailHelp"
                placeholder="Subtítulo">
        </div>

        <div class="form-group">
            <label for="description">Descripción</label>
            <input
            type="text"
            class="form-control"
            value="{{ $project->subtitle }}"
            id="description"
            name="description"
            aria-describedby="emailHelp"
            placeholder="Subtítulo">
        </div>

        <div class="form-group">
            <label for="work_made">Lista de trabajos realizados</label>
            <textarea
                id="work_made"
                name="work_made"
                cols="30"
                rows="10">
                {!! html_entity_decode($project->work_made) !!}
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
