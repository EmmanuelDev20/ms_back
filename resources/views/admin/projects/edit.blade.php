@extends('adminlte::page')

@section('title', 'Editar proyecto')

@section('content_header')
    <h1>Editar proyecto</h1>
@stop

@push('css')
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet">
    <style>
        .title-spanish { font-size:1.5rem; font-weight:bold; border-top:1px solid #aaa; padding-top:1rem; }
        .error { font-size:12px; font-weight:bold; color:rgb(200,0,0); }

        /* ── Block list ── */
        #blocksContainer { display:flex; flex-direction:column; gap:1.25rem; margin-bottom:2rem; }

        .block-card {
            border: 2px solid #dee2e6;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
        }

        .block-header {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .6rem 1rem;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            flex-wrap: wrap;
        }

        .block-type-label {
            font-size: .78rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: .04em;
            flex: 0 0 auto;
        }

        /* layout picker inside header */
        .block-layout-picker { display:flex; gap:4px; flex:1; flex-wrap:wrap; }
        .lbtn {
            border: 2px solid #dee2e6; border-radius:5px;
            background: #fff; padding:3px 4px;
            cursor:pointer; color:#6c757d; line-height:0;
            transition: border-color .15s, color .15s;
        }
        .lbtn:hover { border-color:#adb5bd; color:#343a40; }
        .lbtn.active { border-color:#007bff; color:#007bff; }

        .btn-del-block {
            margin-left:auto; flex:0 0 auto;
            background:none; border:none; color:#dc3545;
            font-size:.85rem; cursor:pointer; padding:2px 6px;
            border-radius:4px;
        }
        .btn-del-block:hover { background:#dc3545; color:#fff; }

        /* ── Images row inside block ── */
        .block-images {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            padding: .75rem 1rem;
        }

        .block-img-item {
            position: relative;
            width: 140px;
        }
        .block-img-item img {
            width:140px; height:100px; object-fit:cover;
            border-radius:6px; display:block;
        }
        .btn-del-img {
            position:absolute; top:4px; right:4px;
            background:rgba(200,0,0,.82); color:#fff;
            border:none; border-radius:50%;
            width:22px; height:22px; font-size:14px;
            line-height:20px; text-align:center;
            cursor:pointer; padding:0;
        }
        .btn-del-img:hover { background:rgba(200,0,0,1); }

        /* ── Per-block dropzone ── */
        .block-dropzone-wrap { padding:.5rem 1rem 1rem; }
        .block-dropzone-wrap .dropzone {
            min-height:60px; border:2px dashed #dee2e6;
            border-radius:8px; font-size:.85rem;
        }

        /* ── Full-width block preview ── */
        .block-fw-img { width:100%; max-height:200px; object-fit:cover; display:block; border-radius:0; }

        /* ── Add block button ── */
        #addBlockBtn {
            width:100%; padding:.75rem;
            border:2px dashed #6c757d; border-radius:12px;
            background:none; color:#6c757d; font-size:1rem;
            cursor:pointer; transition:all .15s;
        }
        #addBlockBtn:hover { border-color:#007bff; color:#007bff; background:#f0f7ff; }

        /* ── Modal for new block ── */
        .modal-backdrop-custom {
            display:none; position:fixed; inset:0;
            background:rgba(0,0,0,.5); z-index:9998; align-items:center; justify-content:center;
        }
        .modal-backdrop-custom.show { display:flex; }
        .modal-box {
            background:#fff; border-radius:12px; padding:2rem;
            width:420px; max-width:95vw; z-index:9999;
        }
        .modal-box h5 { margin-bottom:1.25rem; font-weight:700; }
        .layout-picker-modal { display:flex; flex-wrap:wrap; gap:.75rem; }
        .layout-opt {
            cursor:pointer; border:3px solid #dee2e6; border-radius:10px;
            padding:.5rem; text-align:center; width:110px;
            transition:border-color .15s;
        }
        .layout-opt:hover { border-color:#007bff; }
        .layout-opt span { display:block; font-size:.73rem; color:#555; margin-top:.3rem; }
        .modal-cancel { margin-top:1.25rem; background:none; border:none; color:#6c757d; cursor:pointer; }
    </style>
@endpush

@section('content')

    {{-- ─── Project form ─────────────────────────────────────────────────── --}}
    <form method="POST" action="{{ route('project.update', $project) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="custom-file mb-4">
            <input type="file" accept="image/*" class="custom-file-input" id="url_main_image" name="url_main_image">
            <label class="custom-file-label" for="url_main_image">Elegir imagen principal</label>
        </div>
        <div id="main-image-preview" class="mb-3">
            @if ($project->url_main_image)
                <img src="{{ asset($project->url_main_image) }}" style="max-width:300px;border-radius:8px;">
            @endif
        </div>

        <h2 class="title-spanish">Información en español</h2>
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" class="form-control" value="{{ $spanish_data->name }}" name="name" placeholder="Nombre">
        </div>
        <div class="form-group">
            <label>Subtítulo</label>
            <input type="text" class="form-control" value="{{ $spanish_data->subtitle }}" name="subtitle">
        </div>
        <div class="form-group">
            <label>Descripción</label>
            <input type="text" class="form-control" value="{{ $spanish_data->description }}" name="description">
        </div>
        <div class="form-group">
            <label>Lista de trabajos realizados</label>
            <textarea id="work_made" name="work_made" cols="30" rows="10">{!! html_entity_decode($spanish_data->work_made) !!}</textarea>
        </div>

        <h2 class="title-spanish">Información en inglés</h2>
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" class="form-control" value="{{ $english_data->name }}" name="name_english">
        </div>
        <div class="form-group">
            <label>Subtítulo</label>
            <input type="text" class="form-control" value="{{ $english_data->subtitle }}" name="subtitle_english">
        </div>
        <div class="form-group">
            <label>Descripción</label>
            <input type="text" class="form-control" value="{{ $english_data->description }}" name="description_english">
        </div>
        <div class="form-group">
            <label>Lista de trabajos realizados</label>
            <textarea id="work_made_english" name="work_made_english" cols="30" rows="10">{!! html_entity_decode($english_data->work_made) !!}</textarea>
        </div>

        <button type="submit" class="btn btn-primary d-block mb-4">Guardar cambios</button>
    </form>

    {{-- ─── Image blocks (AJAX, outside main form) ─────────────────────── --}}
    <h2 class="title-spanish mt-4">Imágenes del proyecto</h2>
    <p class="text-muted small mb-3">
        Cada <strong>bloque</strong> define cómo se muestran sus imágenes: full width o un collage.<br>
        Agregá bloques, subí imágenes a cada uno y elegí el layout del bloque.
    </p>

    @php
    // Group images into blocks
    $blocksData  = [];
    $seenBlocks  = [];
    foreach ($project->images as $img) {
        if ($img->block_id === null) {
            // standalone full-width image → its own block
            $blocksData[] = ['type' => 'fw', 'image' => $img, 'block_id' => null];
        } else {
            $bid = $img->block_id;
            if (!isset($seenBlocks[$bid])) {
                $seenBlocks[$bid] = count($blocksData);
                $blocksData[] = ['type' => 'collage', 'layout' => $img->layout, 'block_id' => $bid, 'images' => []];
            }
            $blocksData[$seenBlocks[$bid]]['images'][] = $img;
        }
    }

    $layoutIcons = [
        ''      => '<rect x="1" y="1" width="44" height="30" rx="3" fill="currentColor" opacity=".5"/>',
        '2l-1r' => '<rect x="1" y="1" width="21" height="14" rx="2" fill="currentColor"/><rect x="1" y="17" width="21" height="14" rx="2" fill="currentColor"/><rect x="24" y="1" width="21" height="30" rx="2" fill="currentColor" opacity=".75"/>',
        '1l-2r' => '<rect x="1" y="1" width="21" height="30" rx="2" fill="currentColor" opacity=".75"/><rect x="24" y="1" width="21" height="14" rx="2" fill="currentColor"/><rect x="24" y="17" width="21" height="14" rx="2" fill="currentColor"/>',
        '1t-2b' => '<rect x="1" y="1" width="44" height="14" rx="2" fill="currentColor" opacity=".75"/><rect x="1" y="17" width="21" height="14" rx="2" fill="currentColor"/><rect x="24" y="17" width="21" height="14" rx="2" fill="currentColor"/>',
        '2t-1b' => '<rect x="1" y="1" width="21" height="14" rx="2" fill="currentColor"/><rect x="24" y="1" width="21" height="14" rx="2" fill="currentColor"/><rect x="1" y="17" width="44" height="14" rx="2" fill="currentColor" opacity=".75"/>',
        '3col'  => '<rect x="1" y="1" width="13" height="30" rx="2" fill="currentColor"/><rect x="17" y="1" width="12" height="30" rx="2" fill="currentColor"/><rect x="32" y="1" width="13" height="30" rx="2" fill="currentColor"/>',
    ];
    $layoutLabels = [
        '' => 'Full width', '2l-1r' => '2 izq · 1 der', '1l-2r' => '1 izq · 2 der',
        '1t-2b' => '1 arr · 2 abajo', '2t-1b' => '2 arr · 1 abajo', '3col' => '3 columnas',
    ];
    @endphp

    <div id="blocksContainer">
    @foreach($blocksData as $block)

        @if($block['type'] === 'fw')
        {{-- ── Full-width single image block ── --}}
        <div class="block-card" data-block-type="fw" data-image-id="{{ $block['image']->id }}">
            <div class="block-header">
                <span class="block-type-label">Full Width</span>
                <button type="button" class="btn-del-block"
                    data-delete-url="{{ route('images.destroy', ['image' => $block['image'], 'project' => $project]) }}"
                    data-block-type="fw"
                    title="Eliminar este bloque">
                    🗑 Eliminar bloque
                </button>
            </div>
            <div class="block-images">
                <div class="block-img-item" data-image-id="{{ $block['image']->id }}">
                    <img src="{{ asset($block['image']->url) }}" alt="">
                    <button type="button" class="btn-del-img"
                        data-delete-url="{{ route('images.destroy', ['image' => $block['image'], 'project' => $project]) }}">&times;</button>
                </div>
            </div>
        </div>

        @else
        {{-- ── Collage block ── --}}
        @php $curLayout = $block['layout'] ?? '' @endphp
        <div class="block-card" data-block-type="collage" data-block-id="{{ $block['block_id'] }}">
            <div class="block-header">
                <span class="block-type-label">Collage</span>
                <div class="block-layout-picker"
                    data-block-id="{{ $block['block_id'] }}"
                    data-url="{{ route('images.setBlockLayout', ['project' => $project, 'blockId' => $block['block_id']]) }}">
                    @foreach($layoutIcons as $lval => $iconPaths)
                    @if($lval !== '')
                    <button type="button" class="lbtn{{ $curLayout === $lval ? ' active' : '' }}"
                        data-layout="{{ $lval }}" title="{{ $layoutLabels[$lval] }}">
                        <svg width="46" height="32" viewBox="0 0 46 32">{!! $iconPaths !!}</svg>
                    </button>
                    @endif
                    @endforeach
                </div>
                <button type="button" class="btn-del-block"
                    data-delete-url="{{ route('images.destroyBlock', ['project' => $project, 'blockId' => $block['block_id']]) }}"
                    data-block-type="collage"
                    title="Eliminar bloque completo">
                    🗑 Eliminar bloque
                </button>
            </div>
            <div class="block-images" id="imgs-{{ $block['block_id'] }}">
                @foreach($block['images'] as $img)
                <div class="block-img-item" data-image-id="{{ $img->id }}">
                    <img src="{{ asset($img->url) }}" alt="">
                    <button type="button" class="btn-del-img"
                        data-delete-url="{{ route('images.destroy', ['image' => $img, 'project' => $project]) }}">&times;</button>
                </div>
                @endforeach
            </div>
            <div class="block-dropzone-wrap">
                <form class="dropzone block-dz"
                    id="dz-{{ $block['block_id'] }}"
                    action="{{ route('images.storeInBlock', ['project' => $project, 'blockId' => $block['block_id']]) }}"
                    data-block-id="{{ $block['block_id'] }}"
                    data-layout="{{ $curLayout }}">
                    @csrf
                </form>
            </div>
        </div>
        @endif

    @endforeach
    </div>

    <button id="addBlockBtn" type="button">+ Agregar bloque</button>

    {{-- ── Modal for choosing new block type ── --}}
    <div class="modal-backdrop-custom" id="blockModal">
        <div class="modal-box">
            <h5>¿Qué tipo de bloque?</h5>
            <div class="layout-picker-modal" id="modalLayoutPicker">

                <div class="layout-opt" data-layout="" style="width:130px;">
                    <svg width="90" height="60" viewBox="0 0 90 60">
                        <rect x="0" y="0" width="90" height="18" rx="3" fill="#adb5bd"/>
                        <rect x="0" y="21" width="90" height="18" rx="3" fill="#adb5bd"/>
                        <rect x="0" y="42" width="90" height="18" rx="3" fill="#adb5bd"/>
                    </svg>
                    <span>Full Width</span>
                </div>

                <div class="layout-opt" data-layout="2l-1r">
                    <svg width="90" height="60" viewBox="0 0 90 60">
                        <rect x="0" y="0" width="43" height="28" rx="3" fill="#adb5bd"/>
                        <rect x="0" y="32" width="43" height="28" rx="3" fill="#adb5bd"/>
                        <rect x="47" y="0" width="43" height="60" rx="3" fill="#6c757d"/>
                    </svg>
                    <span>2 izq · 1 der</span>
                </div>

                <div class="layout-opt" data-layout="1l-2r">
                    <svg width="90" height="60" viewBox="0 0 90 60">
                        <rect x="0" y="0" width="43" height="60" rx="3" fill="#6c757d"/>
                        <rect x="47" y="0" width="43" height="28" rx="3" fill="#adb5bd"/>
                        <rect x="47" y="32" width="43" height="28" rx="3" fill="#adb5bd"/>
                    </svg>
                    <span>1 izq · 2 der</span>
                </div>

                <div class="layout-opt" data-layout="1t-2b">
                    <svg width="90" height="60" viewBox="0 0 90 60">
                        <rect x="0" y="0" width="90" height="30" rx="3" fill="#6c757d"/>
                        <rect x="0" y="34" width="43" height="26" rx="3" fill="#adb5bd"/>
                        <rect x="47" y="34" width="43" height="26" rx="3" fill="#adb5bd"/>
                    </svg>
                    <span>1 arr · 2 abajo</span>
                </div>

                <div class="layout-opt" data-layout="2t-1b">
                    <svg width="90" height="60" viewBox="0 0 90 60">
                        <rect x="0" y="0" width="43" height="26" rx="3" fill="#adb5bd"/>
                        <rect x="47" y="0" width="43" height="26" rx="3" fill="#adb5bd"/>
                        <rect x="0" y="30" width="90" height="30" rx="3" fill="#6c757d"/>
                    </svg>
                    <span>2 arr · 1 abajo</span>
                </div>

                <div class="layout-opt" data-layout="3col">
                    <svg width="90" height="60" viewBox="0 0 90 60">
                        <rect x="0" y="0" width="27" height="60" rx="3" fill="#adb5bd"/>
                        <rect x="31" y="0" width="27" height="60" rx="3" fill="#adb5bd"/>
                        <rect x="62" y="0" width="28" height="60" rx="3" fill="#adb5bd"/>
                    </svg>
                    <span>3 columnas</span>
                </div>
            </div>
            <button type="button" class="modal-cancel" id="modalCancel">Cancelar</button>
        </div>
    </div>

@stop

@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>

    <script>
    var CSRF    = '{{ csrf_token() }}';
    var PROJECT = '{{ $project->id }}';
    var STORE_IN_BLOCK_BASE = '{{ url('admin/proyectos/' . $project->id . '/blocks') }}';
    var DELETE_IMG_BASE = '';

    // ── Main image preview ────────────────────────────────────────────────
    document.getElementById('url_main_image').addEventListener('change', function () {
        var p = document.getElementById('main-image-preview');
        p.innerHTML = '';
        if (this.files && this.files[0]) {
            var r = new FileReader();
            r.onload = function(e) { p.innerHTML = '<img src="' + e.target.result + '" style="max-width:300px;border-radius:8px;">'; };
            r.readAsDataURL(this.files[0]);
        }
    });

    ClassicEditor.create(document.querySelector('#work_made')).catch(console.error);
    ClassicEditor.create(document.querySelector('#work_made_english')).catch(console.error);

    // ── Helpers ───────────────────────────────────────────────────────────
    function csrfFetch(url, method, data) {
        return fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(data || {})
        });
    }

    function buildImgItem(imageId, imageUrl, deleteUrl) {
        var div = document.createElement('div');
        div.className = 'block-img-item';
        div.dataset.imageId = imageId;
        div.innerHTML =
            '<img src="' + imageUrl + '" alt="">' +
            '<button type="button" class="btn-del-img" data-delete-url="' + deleteUrl + '">&times;</button>';
        return div;
    }

    // ── Delete single image ───────────────────────────────────────────────
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-del-img');
        if (!btn) return;
        if (!confirm('¿Eliminar esta imagen?')) return;
        var item  = btn.closest('.block-img-item');
        var block = btn.closest('.block-card');
        csrfFetch(btn.dataset.deleteUrl, 'POST', { _method: 'DELETE' })
            .then(function(r) {
                if (!r.ok) return;
                item.remove();
                // if block is fw type, remove whole block card
                if (block && block.dataset.blockType === 'fw') block.remove();
            });
    });

    // ── Delete block ──────────────────────────────────────────────────────
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-del-block');
        if (!btn) return;
        if (!confirm('¿Eliminar este bloque y todas sus imágenes?')) return;
        var block = btn.closest('.block-card');
        if (btn.dataset.blockType === 'fw') {
            // single image delete
            csrfFetch(btn.dataset.deleteUrl, 'POST', { _method: 'DELETE' })
                .then(function(r) { if (r.ok) block.remove(); });
        } else {
            csrfFetch(btn.dataset.deleteUrl, 'DELETE')
                .then(function(r) { if (r.ok) block.remove(); });
        }
    });

    // ── Block layout picker ───────────────────────────────────────────────
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.block-layout-picker .lbtn');
        if (!btn) return;
        var picker = btn.closest('.block-layout-picker');
        picker.querySelectorAll('.lbtn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        var layout = btn.dataset.layout;
        // update data-layout on all dropzones in this block
        var block = btn.closest('.block-card');
        block.querySelectorAll('.block-dz').forEach(function(dz) { dz.dataset.layout = layout; });
        csrfFetch(picker.dataset.url, 'PATCH', { layout: layout });
    });

    // ── Init existing block dropzones ─────────────────────────────────────
    Dropzone.autoDiscover = false;

    function initBlockDz(formEl) {
        new Dropzone(formEl, {
            paramName: 'file',
            acceptedFiles: '.jpg,.jpeg,.png',
            headers: { 'X-CSRF-TOKEN': CSRF },
            params: function() { return { layout: formEl.dataset.layout || '' }; },
            success: function(file, response) {
                var imgsRow = document.getElementById('imgs-' + formEl.dataset.blockId);
                if (imgsRow) imgsRow.appendChild(buildImgItem(response.id, response.url, response.deleteUrl));
                this.removeFile(file);
            }
        });
    }

    document.querySelectorAll('.block-dz').forEach(initBlockDz);

    // ── Add new block ──────────────────────────────────────────────────────
    var modal = document.getElementById('blockModal');
    document.getElementById('addBlockBtn').addEventListener('click', function () {
        modal.classList.add('show');
    });
    document.getElementById('modalCancel').addEventListener('click', function () {
        modal.classList.remove('show');
    });
    modal.addEventListener('click', function(e) {
        if (e.target === modal) modal.classList.remove('show');
    });

    document.querySelectorAll('.layout-opt').forEach(function(opt) {
        opt.addEventListener('click', function () {
            modal.classList.remove('show');
            var layout   = this.dataset.layout;
            var blockId  = Date.now();
            addNewBlock(blockId, layout);
        });
    });

    function addNewBlock(blockId, layout) {
        var isFw      = (layout === '');
        var container = document.getElementById('blocksContainer');

        var card = document.createElement('div');
        card.className = 'block-card';
        card.dataset.blockType = isFw ? 'fw' : 'collage';
        card.dataset.blockId   = blockId;

        if (isFw) {
            // Full width — just a dropzone, once 1 image uploaded the block becomes a fw block
            card.innerHTML =
                '<div class="block-header">' +
                '<span class="block-type-label">Full Width</span>' +
                '<button type="button" class="btn-del-block" data-block-type="new-empty" title="Cancelar bloque">✕ Cancelar</button>' +
                '</div>' +
                '<div class="block-images" id="imgs-' + blockId + '"></div>' +
                '<div class="block-dropzone-wrap">' +
                '<form class="dropzone block-dz" id="dz-' + blockId + '" data-block-id="' + blockId + '" data-layout="" data-fw="1"' +
                ' action="' + STORE_IN_BLOCK_BASE + '/' + blockId + '/images">' +
                '<input type="hidden" name="_token" value="' + CSRF + '">' +
                '</form></div>';
        } else {
            var pickerHtml = buildPickerHtml(layout, blockId);
            card.innerHTML =
                '<div class="block-header">' +
                '<span class="block-type-label">Collage</span>' +
                pickerHtml +
                '<button type="button" class="btn-del-block" data-block-type="new-empty" title="Cancelar bloque">✕ Cancelar</button>' +
                '</div>' +
                '<div class="block-images" id="imgs-' + blockId + '"></div>' +
                '<div class="block-dropzone-wrap">' +
                '<form class="dropzone block-dz" id="dz-' + blockId + '" data-block-id="' + blockId + '" data-layout="' + layout + '"' +
                ' action="' + STORE_IN_BLOCK_BASE + '/' + blockId + '/images">' +
                '<input type="hidden" name="_token" value="' + CSRF + '">' +
                '</form></div>';
        }

        container.appendChild(card);

        // Cancel empty block
        card.querySelector('.btn-del-block').addEventListener('click', function() {
            if (this.dataset.blockType === 'new-empty') card.remove();
        });

        var formEl = card.querySelector('.block-dz');
        initBlockDz(formEl);
    }

    var LAYOUT_ICONS = {
        '2l-1r': '<rect x="1" y="1" width="21" height="14" rx="2" fill="currentColor"/><rect x="1" y="17" width="21" height="14" rx="2" fill="currentColor"/><rect x="24" y="1" width="21" height="30" rx="2" fill="currentColor" opacity=".75"/>',
        '1l-2r': '<rect x="1" y="1" width="21" height="30" rx="2" fill="currentColor" opacity=".75"/><rect x="24" y="1" width="21" height="14" rx="2" fill="currentColor"/><rect x="24" y="17" width="21" height="14" rx="2" fill="currentColor"/>',
        '1t-2b': '<rect x="1" y="1" width="44" height="14" rx="2" fill="currentColor" opacity=".75"/><rect x="1" y="17" width="21" height="14" rx="2" fill="currentColor"/><rect x="24" y="17" width="21" height="14" rx="2" fill="currentColor"/>',
        '2t-1b': '<rect x="1" y="1" width="21" height="14" rx="2" fill="currentColor"/><rect x="24" y="1" width="21" height="14" rx="2" fill="currentColor"/><rect x="1" y="17" width="44" height="14" rx="2" fill="currentColor" opacity=".75"/>',
        '3col':  '<rect x="1" y="1" width="13" height="30" rx="2" fill="currentColor"/><rect x="17" y="1" width="12" height="30" rx="2" fill="currentColor"/><rect x="32" y="1" width="13" height="30" rx="2" fill="currentColor"/>',
    };
    var LAYOUT_LABELS = {
        '2l-1r':'2 izq · 1 der','1l-2r':'1 izq · 2 der',
        '1t-2b':'1 arr · 2 abajo','2t-1b':'2 arr · 1 abajo','3col':'3 columnas'
    };

    function buildPickerHtml(activeLayout, blockId) {
        var url = STORE_IN_BLOCK_BASE.replace('/blocks', '/blocks/' + blockId + '/layout').replace('/images/', '/');
        var html = '<div class="block-layout-picker" data-block-id="' + blockId + '" data-url="' + STORE_IN_BLOCK_BASE + '/' + blockId + '/layout">';
        Object.keys(LAYOUT_ICONS).forEach(function(lv) {
            html += '<button type="button" class="lbtn' + (lv === activeLayout ? ' active' : '') + '" data-layout="' + lv + '" title="' + LAYOUT_LABELS[lv] + '">';
            html += '<svg width="46" height="32" viewBox="0 0 46 32">' + LAYOUT_ICONS[lv] + '</svg></button>';
        });
        html += '</div>';
        return html;
    }
    </script>
@endpush
