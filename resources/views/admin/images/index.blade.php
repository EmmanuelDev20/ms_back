@extends('adminlte::page')

@section('title', 'Configuración general')

@section('content_header')
    <h1 class="main-title">Imágenes del proyecto {{ $project->name }}</h1>
@stop


@push('css')
    {{-- <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" /> --}}
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <style>
        .main-title {
            font-weight: bold;
        }
        .images-title {
            font-size: 2rem;
            margin-top: 4rem;
            margin-bottom: 2rem;
            font-weight: bold;
        }

        .images-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            padding-bottom: 10rem;
        }

        .image-container {
            position: relative;
        }
        .image-container img {
            border-radius: 10px;
        }
        .controls-images {
            position: absolute;
            bottom: 0;
            right: 0;
        }
    </style>
@endpush

@section('content')

    <form action="{{ route('images.store', $project) }}" method="POST" enctype="multipart/form-data" id="image-upload"
        class="dropzone">
        @csrf
    </form>

    @if (count($images) > 0)
    <h1 class="images-title">Agregadas</h1>

    <div class="images-container">
            @foreach ($images as $image)
                <div class="image-container">
                    <img src="{{ asset($image->url) }}" class="w-100" alt="Responsive image">

                    <div class="btn-group controls-images">

                        {{-- <form class="inline-block" action="{{ route('images.destroy', $image, $project) }}" method="POST"> --}}
                        <form class="inline-block" action="{{ route('images.destroy', [
                            'image' => $image,
                            'project' => $project
                        ])}}" method="POST">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15px" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
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
