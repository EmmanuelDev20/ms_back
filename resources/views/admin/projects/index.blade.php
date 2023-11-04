@extends('adminlte::page')

@section('title', 'Configuración general')

@section('content_header')
    <h1>Listado de proyectos</h1>
@stop

@section('content')
    {{-- <p>Listado de proyectos</p> --}}

    <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Subtítulo</th>
            <th scope="col">Descripción</th>
            <th scope="col">Trabajo realizado</th>
            <th scope="col">Imagen principal</th>
            <th scope="col">Controles</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($projects as $project)
                <tr>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->subtitle }}</td>
                    <td>{{ $project->description }}</td>
                    <td>{!! html_entity_decode($project->work_made) !!}</td>

                    <td class="w-25">
                        @if ($project->url_main_image)
                            <img src="{{asset($project->url_main_image)}}" class="w-100 mb-4" alt="Responsive image">
                        @endif
                    </td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <a href="{{ route('project.edit', $project)}}" type="button" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10px" fill="none" viewBox="0 0 24 24" stroke="#FFF">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                            <form class="inline-block" action="{{ route('project.destroy', $project) }}" method="POST">
                                @csrf
                                @method('delete')
                                <button class="btn btn-primary" type="submit">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="10px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                  </svg>
                                </button>
                              </form>
                          </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
      </table>


    @push('js')
        <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

        <script>
            ClassicEditor
                .create( document.querySelector( '#about_us_description' ) )
                .catch( error => {
                    console.error( error );
                } );
        </script>
    @endpush

@stop
