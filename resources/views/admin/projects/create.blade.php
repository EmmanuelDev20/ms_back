@extends('adminlte::page')

@section('title', 'Configuración general')

@section('content_header')
    <h1>Crear nuevo proyecto</h1>
@stop

@section('content')
    <form method="POST" action="{{ route('project.store')}}" enctype="multipart/form-data">
        @csrf

        <div class="custom-file mb-4">
            <input type="file"
                   accept="image/*"
                   class="custom-file-input"
                   id="url_main_image"
                   name="url_main_image">
            <label class="custom-file-label" for="url_main_image">Elegir imagen principal</label>
            @error('url_main_image')
            <div class="error">* Por favor seleccione una archivo válido.</div>
            @enderror
        </div>

        <h2 class="title-spanish">Información en español</h2>

        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text"
                   class="form-control"
                   value="{{ old('name') }}"
                   id="name"
                   name="name"
                   aria-describedby="emailHelp"
                   placeholder="Nombre">
            @error('name')
            <div class="error">* Debes ingresar un nombre.</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="subtitle">Subtítulo</label>
            <input type="text"
                   class="form-control"
                   value="{{ old('subtitle') }}"
                   id="subtitle"
                   name="subtitle"
                   aria-describedby="emailHelp"
                   placeholder="Subtítulo">
        </div>

        <div class="form-group">
            <label for="description">Descripción</label>
            <input type="text"
                   class="form-control"
                   value="{{ old('description') }}"
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
                {{ old('work_made') }}
            </textarea>
        </div>

        <h2 class="title-spanish">Información en inglés</h2>

        <div class="form-group">
            <label for="name_english">Nombre</label>
            <input type="text"
                   value="{{ old('name_english') }}"
                   class="form-control"
                   id="name_english"
                   name="name_english"
                   aria-describedby="emailHelp"
                   placeholder="Nombre">
            @error('name_english')
            <div class="error">* Debes ingresar un nombre.</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="subtitle_english">Subtítulo</label>
            <input type="text"
                   value="{{ old('subtitle_english') }}"
                   class="form-control"
                   id="subtitle_english"
                   name="subtitle_english"
                   aria-describedby="emailHelp"
                   placeholder="Subtítulo">
        </div>

        <div class="form-group">
            <label for="description_english">Descripción</label>
            <input type="text"
                   value="{{ old('description_english') }}"
                   class="form-control"
                   id="description_english"
                   name="description_english"
                   aria-describedby="emailHelp"
                   placeholder="Subtítulo">
        </div>

        <div class="form-group">
            <label for="work_made_english">Lista de trabajos realizados</label>
            <textarea
                id="work_made_english"
                name="work_made_english"
                cols="30"
                rows="10">
                {{ old('work_made_english') }}
            </textarea>
        </div>

        <button type="submit" class="btn btn-primary d-block mb-4">Guardar cambios</button>
        <div class="pb-5">
        </div>
    </form>

    @push('css')
        <style>
            .title-spanish {
                font-size: 1.5rem;
                font-weight: bold;
                border-top: 1px solid gray;
                padding-top: 1rem
            }

            .error {
                font-size: 12px;
                font-weight: bold;
                color: rgb(200,0,0);
            }
        </style>
    @endpush

    @push('js')
        <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

        <script>
            ClassicEditor
                .create( document.querySelector( '#work_made' ) )
                .catch( error => {
                    console.error( error );
                } );

            ClassicEditor
                .create( document.querySelector( '#work_made_english' ) )
                .catch( error => {
                    console.error( error );
                } );
        </script>
    @endpush

@stop
