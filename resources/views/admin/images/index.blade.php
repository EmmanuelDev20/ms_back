@extends('adminlte::page')

@section('title', 'Imágenes del proyecto')

@section('content_header')
    <h1 class="main-title">Imágenes del proyecto {{ $project->name }}</h1>
@stop

@push('css')
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <style>
        .main-title { font-weight: bold; }

        /* ── Layout Picker ── */
        .layout-section-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .layout-picker {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .layout-card {
            cursor: pointer;
            border: 3px solid #dee2e6;
            border-radius: 10px;
            padding: 0.6rem;
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
            text-align: center;
            width: 130px;
        }
        .layout-card:hover { border-color: #6c757d; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        .layout-card.active { border-color: #007bff; box-shadow: 0 2px 10px rgba(0,123,255,.3); }
        .layout-card span { display: block; font-size: .75rem; color: #555; margin-top: .4rem; }
        .layout-card svg { display: block; margin: 0 auto; }

        /* ── Images grid ── */
        .images-title {
            font-size: 2rem;
            margin-top: 3rem;
            margin-bottom: 2rem;
            font-weight: bold;
        }
        .images-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            padding-bottom: 10rem;
        }
        .image-container { position: relative; }
        .image-container img { border-radius: 10px; }
        .controls-images { position: absolute; bottom: 0; right: 0; }

        .alert-success-custom {
            background: #d4edda; border: 1px solid #c3e6cb;
            color: #155724; padding: .75rem 1.25rem;
            border-radius: .25rem; margin-bottom: 1rem;
        }
    </style>
@endpush

@section('content')

    @if(session('success'))
        <div class="alert-success-custom">{{ session('success') }}</div>
    @endif

    {{-- ── Layout Picker ── --}}
    <h2 class="layout-section-title">Collage Layout</h2>
    <p class="text-muted mb-3">Elegí cómo se agrupan las imágenes (grupos de 3). Las fotos se asignan por posición.</p>

    <div class="layout-picker" id="layoutPicker">

        {{-- None --}}
        <div class="layout-card {{ $project->layout === null ? 'active' : '' }}" data-layout="">
            <svg width="90" height="60" viewBox="0 0 90 60">
                <rect x="0"  y="0"  width="90" height="18" rx="3" fill="#adb5bd"/>
                <rect x="0"  y="21" width="90" height="18" rx="3" fill="#adb5bd"/>
                <rect x="0"  y="42" width="90" height="18" rx="3" fill="#adb5bd"/>
            </svg>
            <span>Sin layout</span>
        </div>

        {{-- 2l-1r --}}
        <div class="layout-card {{ $project->layout === '2l-1r' ? 'active' : '' }}" data-layout="2l-1r">
            <svg width="90" height="60" viewBox="0 0 90 60">
                <rect x="0"  y="0"  width="43" height="28" rx="3" fill="#adb5bd"/>
                <rect x="0"  y="32" width="43" height="28" rx="3" fill="#adb5bd"/>
                <rect x="47" y="0"  width="43" height="60" rx="3" fill="#6c757d"/>
            </svg>
            <span>2 izq · 1 der</span>
        </div>

        {{-- 1l-2r --}}
        <div class="layout-card {{ $project->layout === '1l-2r' ? 'active' : '' }}" data-layout="1l-2r">
            <svg width="90" height="60" viewBox="0 0 90 60">
                <rect x="0"  y="0"  width="43" height="60" rx="3" fill="#6c757d"/>
                <rect x="47" y="0"  width="43" height="28" rx="3" fill="#adb5bd"/>
                <rect x="47" y="32" width="43" height="28" rx="3" fill="#adb5bd"/>
            </svg>
            <span>1 izq · 2 der</span>
        </div>

        {{-- 1t-2b --}}
        <div class="layout-card {{ $project->layout === '1t-2b' ? 'active' : '' }}" data-layout="1t-2b">
            <svg width="90" height="60" viewBox="0 0 90 60">
                <rect x="0"  y="0"  width="90" height="30" rx="3" fill="#6c757d"/>
                <rect x="0"  y="34" width="43" height="26" rx="3" fill="#adb5bd"/>
                <rect x="47" y="34" width="43" height="26" rx="3" fill="#adb5bd"/>
            </svg>
            <span>1 arriba · 2 abajo</span>
        </div>

        {{-- 2t-1b --}}
        <div class="layout-card {{ $project->layout === '2t-1b' ? 'active' : '' }}" data-layout="2t-1b">
            <svg width="90" height="60" viewBox="0 0 90 60">
                <rect x="0"  y="0"  width="43" height="26" rx="3" fill="#adb5bd"/>
                <rect x="47" y="0"  width="43" height="26" rx="3" fill="#adb5bd"/>
                <rect x="0"  y="30" width="90" height="30" rx="3" fill="#6c757d"/>
            </svg>
            <span>2 arriba · 1 abajo</span>
        </div>

        {{-- 3col --}}
        <div class="layout-card {{ $project->layout === '3col' ? 'active' : '' }}" data-layout="3col">
            <svg width="90" height="60" viewBox="0 0 90 60">
                <rect x="0"  y="0" width="27" height="60" rx="3" fill="#adb5bd"/>
                <rect x="31" y="0" width="27" height="60" rx="3" fill="#adb5bd"/>
                <rect x="62" y="0" width="28" height="60" rx="3" fill="#adb5bd"/>
            </svg>
            <span>3 columnas</span>
        </div>

    </div>

    <form id="layoutForm" action="{{ route('project.setLayout', $project) }}" method="POST">
        @csrf
        <input type="hidden" name="layout" id="layoutInput" value="{{ $project->layout }}">
        <button type="submit" class="btn btn-primary mb-4">Guardar layout</button>
    </form>

    {{-- ── Dropzone ── --}}
    <form action="{{ route('images.store', $project) }}" method="POST" enctype="multipart/form-data" id="image-upload" class="dropzone">
        @csrf
    </form>

    @if (count($images) > 0)
        <h1 class="images-title">Imágenes agregadas</h1>
        <div class="images-container" id="sortableItems">
            @foreach ($images as $image)
                <div class="image-container" data-id="{{ $image->id }}">
                    <img src="{{ asset($image->url) }}" class="w-100" alt="Imagen {{ $image->position }}">
                    <div class="btn-group controls-images">
                        <form class="inline-block"
                            action="{{ route('images.destroy', ['image' => $image, 'project' => $project]) }}"
                            method="POST">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
        new Dropzone("#image-upload", { thumbnailWidth: 200, acceptedFiles: ".jpg,.jpeg,.png" })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        new Sortable(sortableItems, {
            animation: 150,
            store: {
                set: function(sortable) {
                    axios.post("{{ route('admin.sortImages') }}", { sorts: sortable.toArray() })
                        .catch(err => console.log(err))
                }
            }
        });

        // Layout picker
        document.querySelectorAll('.layout-card').forEach(function(card) {
            card.addEventListener('click', function() {
                document.querySelectorAll('.layout-card').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('layoutInput').value = this.dataset.layout;
            });
        });
    </script>
@endpush
