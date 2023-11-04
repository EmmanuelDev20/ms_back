@extends('adminlte::page')

@section('title', 'Configuración general')

@section('content_header')
    <h1>Imágenes del proyecto {{ $project->name }}</h1>
@stop


@push('css')
    {{-- <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" /> --}}
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
    @endpush

    @section('content')

    <form action="{{ route('project.images.store')}}" method="POST" enctype="multipart/form-data" id="image-upload" class="dropzone">
        @csrf
    </form>
    {{-- <form
        class="dropzone"
        method="POST"
        id="my-awesome-dropzone">

        <input type="hidden" name="project_id" class="form-control m-input" value="{{ $project }}">

    </form> --}}

    {{-- <form method="POST" action="{{ route('project.images.update', $project)}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="custom-file mb-4">
            <input
            type="file"
            accept="image/*"
            class="custom-file-input"
            id="url"
            name="url">
            <label class="custom-file-label" for="url">Elegir imagen principal</label>
            @error('url')
            <div class="">Por favor seleccione una archivo válido.</div>
            @enderror
        </div>

        @if ($project->url_main_image)
            <img src="{{asset($project->url_main_image)}}" class="w-25 mb-4" alt="Responsive image">
            @endif

            <button type="submit" class="btn btn-primary d-block mb-4">Guardar cambios</button>
        <div class="pb-5">
        </div>
    </form> --}}

    @stop

    @push('js')
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>

    <script>
        new Dropzone("#image-upload", {
            thumbnailWidth: 200,
            acceptedFiles: ".jpg,.jpeg,.png"
        })
    </script>
    {{-- <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script> --}}

    {{-- <script>
        Dropzone.options.myAwesomeDropzone = {
            url: '{{ route('project.images.update') }}',
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
        };
    </script> --}}
@endpush
