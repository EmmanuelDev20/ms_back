@extends('adminlte::page')

@section('title', 'Configuración general')

@section('content_header')
    <h1>Configuración general</h1>
@stop

@section('content')
    <form method="POST" action="{{ route('config.update', $config)}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h2 class="title-spanish">Información en español</h2>

        <div class="form-group">
            <label for="home_description">Párrafo del principio</label>
            <input type="text" value="{{ $spanish_data->home_description }}" class="form-control" id="home_description" name="home_description" aria-describedby="emailHelp"
                placeholder="Texto principal">
            @error('home_description')
                <div class="">* Este campo es obligatorio.</div>
            @enderror
        </div>


        <div class="form-group">
            <label for="about_us_description">Descripción del acerca de nosotros</label>
            <textarea
                id="about_us_description"
                name="about_us_description"
                cols="30"
                rows="10">{{ $spanish_data->about_us_description }}</textarea>
            @error('about_us_description')
                <div class="">* Este campo es obligatorio.</div>
            @enderror
        </div>

        <div class="divider"></div>

        <h2 class="title-spanish">Información en inglés</h2>

        <div class="form-group">
            <label for="home_description_english">Párrafo del principio</label>
            <input type="text" value="{{ $english_data->home_description }}" class="form-control" id="home_description_english" name="home_description_english" aria-describedby="emailHelp"
                placeholder="Texto principal">
            @error('home_description_english')
                <div class="">* Este campo es obligatorio.</div>
            @enderror
        </div>


        <div class="form-group">
            <label for="about_us_description_english">Descripción del acerca de nosotros</label>
            <textarea
                id="about_us_description_english"
                name="about_us_description_english"
                cols="30"
                rows="10">{{ $english_data->about_us_description }}</textarea>
            @error('about_us_description_english')
                <div class="">* Este campo es obligatorio.</div>
            @enderror
        </div>

        <div class="divider"></div>

        <div class="custom-file mb-4">
            <input type="file" accept="image/*" class="custom-file-input" id="home_image" name="home_image">
            <label class="custom-file-label" for="home_image">Elegir imagen principal</label>
            @error('home_image')
            <div class="">Por favor seleccione una archivo válido.</div>
            @enderror
        </div>

        <img src="{{asset($config->home_image)}}" class="w-25 mb-4" alt="Responsive image">

        <div class="custom-file mb-4">
            <input type="file" accept="image/*" name="first_image" class="custom-file-input" id="first_image">
            <label class="custom-file-label" for="first_image">Segunda imagen del home</label>
            @error('first_image')
                <div class="">Por favor seleccione una archivo válido.</div>
            @enderror
        </div>

        <img src="{{asset($config->first_image)}}" class="w-25 mb-4" alt="Responsive image">

        <div class="custom-file mb-4">
            <input type="file" accept="image/*" name="second_image" class="custom-file-input" id="second_image">
            <label class="custom-file-label" for="second_image">Tercera imagen del home</label>
            @error('second_image')
                <div class="">Por favor seleccione una archivo válido.</div>
            @enderror
        </div>

        <img src="{{asset($config->second_image)}}" class="w-25 mb-4" alt="Responsive image">

        <div class="custom-file mb-4">
            <input type="file" accept="image/*" name="third_image" class="custom-file-input" id="third_image">
            <label class="custom-file-label" for="third_image">Cuarta imagen del home</label>
            @error('third_image')
                <div class="">Por favor seleccione una archivo válido.</div>
            @enderror
        </div>

        <img src="{{asset($config->third_image)}}" class="w-25 mb-4" alt="Responsive image">

        <div class="custom-file mb-4">
            <input type="file" accept="image/*" name="about_image" class="custom-file-input" id="about_image">
            <label class="custom-file-label" for="about_image">Imagen del acerca de nosotros</label>
            @error('about_image')
                <div class="">Por favor seleccione una archivo válido.</div>
            @enderror
        </div>

        <img src="{{asset($config->about_image)}}" class="w-25 mb-4" alt="Responsive image">

        <button type="submit" class="btn btn-primary d-block mb-4">Guardar cambios</button>
        <div class="pb-5">
        </div>
    </form>

    @push('css')
        <style>
            .title-spanish {
                font-size: 1.5rem;
                font-weight: bold;
                padding-top: 1rem
            }

            .divider {
                border-top: 1px solid gray;
                margin-top: 3rem;
                padding-bottom: 3rem;
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
                .create( document.querySelector( '#about_us_description' ) )
                .catch( error => {
                    console.error( error );
                } );

            ClassicEditor
                .create( document.querySelector( '#about_us_description_english' ) )
                .catch( error => {
                    console.error( error );
                } );
        </script>
    @endpush

@stop
