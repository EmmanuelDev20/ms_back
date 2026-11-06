@extends('adminlte::page')

@section('title', 'Crear proyecto')

@section('content_header')
    <h1>Crear nuevo proyecto</h1>
@stop

@push('css')
    <style>
        .title-spanish { font-size:1.5rem; font-weight:bold; border-top:1px solid #aaa; padding-top:1rem; }
        .error { font-size:12px; font-weight:bold; color:rgb(200,0,0); }

        /* ── Preview grid ── */
        .preview-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .preview-card {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
        }
        .preview-card__thumb { position: relative; }
        .preview-card__thumb img { width:100%; height:150px; object-fit:cover; display:block; }
        .preview-card__remove {
            position:absolute; top:6px; right:6px;
            background:rgba(200,0,0,.82); color:#fff;
            border:none; border-radius:50%;
            width:24px; height:24px; font-size:15px;
            line-height:22px; text-align:center; cursor:pointer; padding:0;
        }

        /* ── Per-image layout picker ── */
        .img-layout-picker {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            padding: 6px;
            background: #f8f9fa;
        }
        .lbtn {
            border: 2px solid #dee2e6;
            border-radius: 5px;
            background: #fff;
            padding: 3px 4px;
            cursor: pointer;
            color: #6c757d;
            line-height: 0;
            transition: border-color .15s, color .15s;
        }
        .lbtn:hover { border-color: #adb5bd; color: #343a40; }
        .lbtn.active { border-color: #007bff; color: #007bff; }
    </style>
@endpush

@section('content')
    <form method="POST" action="{{ route('project.store') }}" enctype="multipart/form-data" id="createForm">
        @csrf

        <div class="custom-file mb-4">
            <input type="file" accept="image/*" class="custom-file-input" id="url_main_image" name="url_main_image">
            <label class="custom-file-label" for="url_main_image">Elegir imagen principal</label>
            @error('url_main_image')
                <div class="error">* Por favor seleccione un archivo válido.</div>
            @enderror
        </div>

        <div id="main-image-preview" class="mb-3"></div>

        <h2 class="title-spanish">Información en español</h2>

        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" value="{{ old('name') }}" id="name" name="name" placeholder="Nombre">
            @error('name')<div class="error">* Debes ingresar un nombre.</div>@enderror
        </div>
        <div class="form-group">
            <label for="subtitle">Subtítulo</label>
            <input type="text" class="form-control" value="{{ old('subtitle') }}" id="subtitle" name="subtitle" placeholder="Subtítulo">
        </div>
        <div class="form-group">
            <label for="description">Descripción</label>
            <input type="text" class="form-control" value="{{ old('description') }}" id="description" name="description" placeholder="Descripción">
        </div>
        <div class="form-group">
            <label for="work_made">Lista de trabajos realizados</label>
            <textarea id="work_made" name="work_made" cols="30" rows="10">{{ old('work_made') }}</textarea>
        </div>

        <h2 class="title-spanish">Información en inglés</h2>

        <div class="form-group">
            <label for="name_english">Nombre</label>
            <input type="text" value="{{ old('name_english') }}" class="form-control" id="name_english" name="name_english" placeholder="Nombre">
            @error('name_english')<div class="error">* Debes ingresar un nombre.</div>@enderror
        </div>
        <div class="form-group">
            <label for="subtitle_english">Subtítulo</label>
            <input type="text" value="{{ old('subtitle_english') }}" class="form-control" id="subtitle_english" name="subtitle_english" placeholder="Subtítulo">
        </div>
        <div class="form-group">
            <label for="description_english">Descripción</label>
            <input type="text" value="{{ old('description_english') }}" class="form-control" id="description_english" name="description_english" placeholder="Descripción">
        </div>
        <div class="form-group">
            <label for="work_made_english">Lista de trabajos realizados</label>
            <textarea id="work_made_english" name="work_made_english" cols="30" rows="10">{{ old('work_made_english') }}</textarea>
        </div>

        <h2 class="title-spanish">Imágenes del proyecto</h2>
        <p class="text-muted small mb-2">Seleccioná las imágenes y elegí el layout de cada una.</p>

        <div class="form-group">
            <label for="gallery_images">Imágenes de la galería (puede seleccionar varias)</label>
            <input type="file" accept="image/*" class="form-control-file" id="gallery_images" name="gallery_images[]" multiple>
            @error('gallery_images.*')<div class="error">* Archivo inválido.</div>@enderror
        </div>

        {{-- Hidden layout inputs are injected here by JS --}}
        <div id="layout-inputs"></div>

        <div id="preview-grid" class="preview-grid"></div>

        <button type="submit" class="btn btn-primary d-block mb-4">Guardar proyecto</button>
        <div class="pb-5"></div>
    </form>
@stop

@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor.create(document.querySelector('#work_made')).catch(console.error);
        ClassicEditor.create(document.querySelector('#work_made_english')).catch(console.error);

        document.getElementById('url_main_image').addEventListener('change', function () {
            var preview = document.getElementById('main-image-preview');
            preview.innerHTML = '';
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" style="max-width:300px;border-radius:8px;">';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // ── Layout icons ──────────────────────────────────────────────────
        var LAYOUT_ICONS = {
            '':      '<rect x="1" y="1" width="44" height="30" rx="3" fill="currentColor" opacity=".5"/>',
            '2l-1r': '<rect x="1" y="1" width="21" height="14" rx="2" fill="currentColor"/><rect x="1" y="17" width="21" height="14" rx="2" fill="currentColor"/><rect x="24" y="1" width="21" height="30" rx="2" fill="currentColor" opacity=".75"/>',
            '1l-2r': '<rect x="1" y="1" width="21" height="30" rx="2" fill="currentColor" opacity=".75"/><rect x="24" y="1" width="21" height="14" rx="2" fill="currentColor"/><rect x="24" y="17" width="21" height="14" rx="2" fill="currentColor"/>',
            '1t-2b': '<rect x="1" y="1" width="44" height="14" rx="2" fill="currentColor" opacity=".75"/><rect x="1" y="17" width="21" height="14" rx="2" fill="currentColor"/><rect x="24" y="17" width="21" height="14" rx="2" fill="currentColor"/>',
            '2t-1b': '<rect x="1" y="1" width="21" height="14" rx="2" fill="currentColor"/><rect x="24" y="1" width="21" height="14" rx="2" fill="currentColor"/><rect x="1" y="17" width="44" height="14" rx="2" fill="currentColor" opacity=".75"/>',
            '3col':  '<rect x="1" y="1" width="13" height="30" rx="2" fill="currentColor"/><rect x="17" y="1" width="12" height="30" rx="2" fill="currentColor"/><rect x="32" y="1" width="13" height="30" rx="2" fill="currentColor"/>',
        };
        var LAYOUT_LABELS = {
            '': 'Full width', '2l-1r': '2 izq · 1 der', '1l-2r': '1 izq · 2 der',
            '1t-2b': '1 arr · 2 abajo', '2t-1b': '2 arr · 1 abajo', '3col': '3 columnas',
        };

        var galleryFiles = [];
        var galleryLayouts = [];

        var galleryInput = document.getElementById('gallery_images');

        galleryInput.addEventListener('change', function (e) {
            galleryFiles = galleryFiles.concat(Array.from(e.target.files));
            // extend layouts array with '' for new files
            Array.from(e.target.files).forEach(function() { galleryLayouts.push(''); });
            this.value = '';
            renderPreview();
        });

        function renderPreview() {
            var grid = document.getElementById('preview-grid');
            var inputsDiv = document.getElementById('layout-inputs');
            grid.innerHTML = '';
            inputsDiv.innerHTML = '';

            galleryFiles.forEach(function (file, index) {
                // hidden layout input
                var hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'gallery_layouts[]';
                hidden.value = galleryLayouts[index] || '';
                hidden.id = 'layout_input_' + index;
                inputsDiv.appendChild(hidden);

                // card
                var card = document.createElement('div');
                card.className = 'preview-card';

                // thumb
                var thumbDiv = document.createElement('div');
                thumbDiv.className = 'preview-card__thumb';

                var img = document.createElement('img');
                img.alt = '';
                var reader = new FileReader();
                reader.onload = (function(i) { return function(e) { i.src = e.target.result; }; })(img);
                reader.readAsDataURL(file);
                thumbDiv.appendChild(img);

                var removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'preview-card__remove';
                removeBtn.innerHTML = '&times;';
                removeBtn.addEventListener('click', (function(idx) { return function() { removeImage(idx); }; })(index));
                thumbDiv.appendChild(removeBtn);
                card.appendChild(thumbDiv);

                // layout picker
                var picker = document.createElement('div');
                picker.className = 'img-layout-picker';
                Object.keys(LAYOUT_ICONS).forEach(function(lv) {
                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'lbtn' + ((galleryLayouts[index] === lv) ? ' active' : '');
                    btn.dataset.layout = lv;
                    btn.title = LAYOUT_LABELS[lv];
                    btn.innerHTML = '<svg width="46" height="32" viewBox="0 0 46 32">' + LAYOUT_ICONS[lv] + '</svg>';
                    btn.addEventListener('click', (function(idx, lval) { return function() {
                        galleryLayouts[idx] = lval;
                        document.getElementById('layout_input_' + idx).value = lval;
                        picker.querySelectorAll('.lbtn').forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                    }; })(index, lv));
                    picker.appendChild(btn);
                });
                card.appendChild(picker);
                grid.appendChild(card);
            });

            syncFileInput();
        }

        function removeImage(index) {
            galleryFiles.splice(index, 1);
            galleryLayouts.splice(index, 1);
            renderPreview();
        }

        function syncFileInput() {
            var dt = new DataTransfer();
            galleryFiles.forEach(function(f) { dt.items.add(f); });
            galleryInput.files = dt.files;
        }
    </script>
@endpush
