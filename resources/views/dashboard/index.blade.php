@extends('layouts.app')

@section('content')

<!-- Notificación de Éxito -->
@if(session('success'))
<div class="mb-6 p-4 bg-green-500/10 border border-green-500/50 rounded-xl flex items-center justify-between">
  <div class="flex items-center gap-3">
    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    <span class="text-green-400 text-sm font-medium">{{ session('success') }}</span>
  </div>
  <button onclick="this.parentElement.remove()" class="text-green-400/50 hover:text-green-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
    </svg></button>
</div>
@endif

<!-- Notificación de Error -->
@if($errors->has('error'))
<div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-xl flex items-center justify-between">
  <div class="flex items-center gap-3">
    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    <span class="text-red-400 text-sm font-medium">{{ $errors->first('error') }}</span>
  </div>
  <button onclick="this.parentElement.remove()" class="text-red-400/50 hover:text-red-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
    </svg></button>
</div>
@endif

<div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

  <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8 pb-6 border-b border-white/5">
    <div>
      <h2 class="text-3xl font-black tracking-tight text-white">Mi Colección</h2>
      <p class="text-slate-400 text-sm mt-1">
        Hola, <span class="text-blue-400 font-medium">{{ Auth::user()->nombre_usuario }}</span> • Gestiona tu biblioteca personal
      </p>
    </div>

    <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
      <form action="{{ route('dashboard') }}" method="GET" class="w-full sm:w-auto min-w-[300px]">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por título, autor o DOI en mi colección..."
          class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition text-sm">
      </form>

      <form action="{{ route('logout') }}" method="POST" class="m-0 w-full sm:w-auto">
        @csrf
        <button type="submit" class="w-full sm:w-auto flex justify-center items-center gap-2 px-4 py-2.5 bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-400 rounded-xl text-sm font-semibold transition active:scale-[0.98]">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          Cerrar Sesión
        </button>
      </form>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($colecciones as $coleccion)
    @php $ref = $coleccion->referencia; @endphp
    <div class="group relative flex flex-col">
      <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-0 group-hover:opacity-15 transition duration-500"></div>

      <div class="relative flex-1 bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6 flex flex-col justify-between shadow-xl">

        <div>
          <div class="flex justify-between items-start gap-4 mb-3">
            <span class="inline-block bg-blue-500/10 text-blue-300 border border-blue-500/20 px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider">
              {{ $ref->tipo_referencia->nombre ?? 'N/D' }}
            </span>
            <span class="text-slate-500 text-xs font-medium">
              {{ \Carbon\Carbon::parse($coleccion->fecha_agregado)->format('d/m/Y') }}
            </span>
          </div>

          <h3 class="text-base font-bold text-slate-100 tracking-tight leading-snug line-clamp-2" title="{{ $ref->titulo }}">
            {{ $ref->titulo }}
          </h3>

          <p class="text-xs text-slate-400 mt-2 line-clamp-2">
            @php
            $autores = $ref->referencia_autores->sortBy('orden')->map(function($ra) {
            return $ra->autor->apellidos . ', ' . substr($ra->autor->nombre, 0, 1) . '.';
            })->implode('; ');
            @endphp
            <span class="text-slate-300 font-medium">{{ $autores ?: 'Sin autor registrado' }}</span>
            <span class="mx-1.5 text-slate-600">•</span>
            <span>{{ $ref->anio_publicacion }}</span>
          </p>

          @if($coleccion->comentario_personal)
          <div class="mt-4 p-3 bg-amber-500/5 border border-amber-500/10 text-amber-300/90 rounded-xl text-xs leading-relaxed">
            <span class="font-bold text-amber-400 uppercase text-[9px] tracking-wider block mb-0.5">Mi nota personal:</span>
            {{ $coleccion->comentario_personal }}
          </div>
          @endif
        </div>

        <div class="mt-6 pt-4 border-t border-white/5 flex flex-col gap-3">

          @if($ref->archivos->isNotEmpty())
          @foreach($ref->archivos as $archivo)
          <a href="{{ route('archivo.descargar', $archivo->id_archivo) }}"
            target="_blank"
            class="w-full flex items-center justify-center gap-2 bg-blue-500/10 hover:bg-blue-500/20 backdrop-blur-md text-blue-400 hover:text-blue-300 text-xs font-semibold py-2.5 px-4 rounded-xl border border-blue-500/30 hover:border-blue-500/50 shadow-lg shadow-blue-500/5 transition duration-300 active:scale-[0.99] group/btn">

            <svg class="w-4 h-4 text-blue-400 group-hover/btn:text-blue-300 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>

            <span>Ver {{ strtoupper($archivo->formato) }}</span>
          </a>
          @endforeach
          @else
          <button type="button" disabled
            class="w-full flex items-center justify-center gap-2 bg-black/20 border border-white/5 text-slate-600 text-xs font-semibold py-2.5 px-4 rounded-xl cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
            Sin Archivo
          </button>
          @endif

          <div class="flex justify-center gap-2 mt-1">
            <button onclick="toggleModal('modal-view-{{ $ref->id_referencia }}')" class="flex-1 p-2 bg-blue-600/10 hover:bg-blue-600/20 text-blue-400 rounded-lg transition border border-blue-500/20 flex justify-center items-center" title="Ver Formatos">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>

            <button onclick="toggleModal('modal-edit-{{ $ref->id_referencia }}')" class="flex-1 p-2 bg-white/5 hover:bg-white/10 text-slate-400 rounded-lg transition border border-white/10 flex justify-center items-center" title="Editar">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
              </svg>
            </button>

            <button onclick="toggleModal('modal-delete-{{ $ref->id_referencia }}')" class="flex-1 p-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg transition border border-red-500/20 flex justify-center items-center" title="Eliminar">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>

        </div>

      </div>
    </div>

    <!-- MODAL DE CREACIÓN de referenciaxd -->
    <div id="modal-create" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 sm:p-6">
      <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('modal-create')"></div>

      <div class="relative w-full max-w-4xl bg-[#0a0a0f] border border-white/10 rounded-2xl shadow-2xl flex flex-col max-h-[90vh]">

        <div class="p-6 border-b border-white/10 flex justify-between items-center bg-white/5 shrink-0">
          <h3 class="text-2xl font-bold text-white">Añadir Nueva Referencia</h3>
          <button type="button" onclick="toggleModal('modal-create')" class="text-slate-400 hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar">
          @if ($errors->any())
          <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-xl text-red-400 text-sm">
            <ul class="list-disc pl-5 space-y-1">
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <form id="form-create-referencia" action="{{ route('referencias.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div>
              <h4 class="text-blue-400 text-sm font-bold uppercase tracking-wider mb-4 border-b border-white/5 pb-2">Información Principal</h4>
              <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                <div class="md:col-span-12">
                  <label class="block text-sm font-medium text-slate-300 mb-1">Título de la Referencia <span class="text-blue-500">*</span></label>
                  <input type="text" name="titulo" value="{{ old('titulo') }}" required class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 transition">
                </div>

                <div class="md:col-span-12">
                  <label class="block text-sm font-medium text-slate-300 mb-1">Autor(es) <span class="text-xs text-slate-500">(Separar por comas)</span> <span class="text-blue-500">*</span></label>
                  <input type="text" name="autores_text" value="{{ old('autores_text') }}" placeholder="Ej. Pressman Roger, Sommerville Ian" required class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 transition">
                </div>

                <div class="md:col-span-4">
                  <label class="block text-sm font-medium text-slate-300 mb-1">Tipo de Fuente <span class="text-blue-500">*</span></label>
                  <select name="id_tipo_referencia" required class="tom-select-single" placeholder="Seleccionar...">
                    <option value="" disabled {{ old('id_tipo_referencia') ? '' : 'selected' }}></option>
                    @foreach($catalogos['tipos'] as $tipo)
                    <option value="{{ $tipo->id_tipo_referencia }}" {{ old('id_tipo_referencia') == $tipo->id_tipo_referencia ? 'selected' : '' }}>{{ $tipo->nombre }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="md:col-span-4">
                  <label class="block text-sm font-medium text-slate-300 mb-1">Año <span class="text-blue-500">*</span></label>
                  <input type="number" name="anio_publicacion" value="{{ old('anio_publicacion', date('Y')) }}" required min="1500" max="2100" class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 transition">
                </div>

                <div class="md:col-span-4">
                  <label class="block text-sm font-medium text-slate-300 mb-1">Fecha de Consulta/Exacta</label>
                  <input type="date" name="fecha_exacta" value="{{ old('fecha_exacta') }}" class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-slate-300 focus:border-blue-500 transition style-color-scheme-dark">
                </div>
              </div>
            </div>

            <div>
              <h4 class="text-blue-400 text-sm font-bold uppercase tracking-wider mb-4 border-b border-white/5 pb-2">Detalles de Publicación</h4>
              <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                <div class="md:col-span-3">
                  <label class="block text-sm font-medium text-slate-300 mb-1">Volumen</label>
                  <input type="text" name="volumen" value="{{ old('volumen') }}" placeholder="Ej. 12" class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 transition">
                </div>
                <div class="md:col-span-3">
                  <label class="block text-sm font-medium text-slate-300 mb-1">Número</label>
                  <input type="text" name="numero" value="{{ old('numero') }}" placeholder="Ej. 4" class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 transition">
                </div>
                <div class="md:col-span-3">
                  <label class="block text-sm font-medium text-slate-300 mb-1">Páginas</label>
                  <input type="text" name="paginas" value="{{ old('paginas') }}" placeholder="Ej. 100-125" class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 transition">
                </div>

                <div class="md:col-span-3">
                  <label class="block text-sm font-medium text-slate-300 mb-1">Editorial / Inst.</label>
                  <input type="text" name="editorial" value="{{ old('editorial') }}" placeholder="Ej. McGraw Hill" class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 transition">
                </div>
              </div>
            </div>

            <div>
              <h4 class="text-blue-400 text-sm font-bold uppercase tracking-wider mb-4 border-b border-white/5 pb-2">Clasificación e Identificadores</h4>
              <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                <div class="md:col-span-4">
                  <label class="block text-sm font-medium text-slate-300 mb-1">ISBN / ISSN</label>
                  <input type="text" name="isbn_issn" value="{{ old('isbn_issn') }}" placeholder="Ej. 978-3..." class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 transition">
                </div>
                <div class="md:col-span-8">
                  <label class="block text-sm font-medium text-slate-300 mb-1">DOI / Enlace (URL)</label>
                  <div class="flex gap-3">
                    <input type="text" name="doi" value="{{ old('doi') }}" placeholder="DOI: 10.1000/182" class="w-1/3 bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 transition">
                    <input type="url" name="url" value="{{ old('url') }}" placeholder="URL: https://..." class="w-2/3 bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 transition">
                  </div>
                </div>

                <div class="md:col-span-6">
                  <label class="block text-sm font-medium text-slate-300 mb-1">Materia(s) <span class="text-blue-500">*</span></label>
                  <select name="materias[]" multiple required class="tom-select-multiple" placeholder="Buscar materias...">
                    @foreach($catalogos['materias'] as $materia)
                    <option value="{{ $materia->id_materia }}" {{ in_array($materia->id_materia, old('materias', [])) ? 'selected' : '' }}>{{ $materia->nombre }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="md:col-span-6">
                  <label class="block text-sm font-medium text-slate-300 mb-1">Tema(s) <span class="text-xs text-slate-500">(Separar por comas)</span></label>
                  <input type="text" name="temas_text" value="{{ old('temas_text') }}" placeholder="Ej. Redes, Ciberseguridad, IoT" class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 transition">
                </div>
              </div>
            </div>

            <div>
              <h4 class="text-blue-400 text-sm font-bold uppercase tracking-wider mb-4 border-b border-white/5 pb-2">Archivo y Colección Personal</h4>
              <div class="grid grid-cols-1 md:grid-cols-12 gap-5">

                <div class="md:col-span-6">
                  <label class="block text-sm font-medium text-slate-300 mb-1">Documento Adjunto <span class="text-xs text-slate-500">(PDF, Máx 20MB)</span></label>
                  <input type="file" name="archivo" accept="application/pdf" class="w-full bg-black/30 border border-white/10 rounded-xl px-3 py-2 text-white focus:border-blue-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-600/20 file:text-blue-400 hover:file:bg-blue-600/30 cursor-pointer">
                </div>

                <div class="md:col-span-6">
                  <label class="block text-sm font-medium text-slate-300 mb-1">Nota Personal <span class="text-xs text-slate-500">(Visible solo en tu dashboard)</span></label>
                  <input type="text" name="comentario_personal" value="{{ old('comentario_personal') }}" placeholder="Ej. Ideal para el marco teórico..." class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 transition">
                </div>

              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-300 mb-1">Resumen</label>
              <textarea name="resumen" rows="3" class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 transition resize-none">{{ old('resumen') }}</textarea>
            </div>

          </form>
        </div>

        <div class="p-6 border-t border-white/10 bg-white/5 shrink-0 flex justify-end gap-3">
          <button type="button" onclick="toggleModal('modal-create')" class="px-6 py-2.5 text-slate-300 bg-black/50 hover:bg-black border border-white/10 rounded-xl transition">Cancelar</button>
          <button type="submit" form="form-create-referencia" class="px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-500 rounded-xl shadow-[0_0_15px_rgba(37,99,235,0.4)] transition">Guardar Referencia</button>
        </div>
      </div>
    </div>

    <div id="modal-view-{{ $ref->id_referencia }}" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/80 backdrop-blur-md" onclick="toggleModal('modal-view-{{ $ref->id_referencia }}')"></div>

      <div class="relative w-full max-w-3xl bg-[#0d1117] border border-white/10 rounded-2xl shadow-2xl flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div class="p-6 border-b border-white/10 flex justify-between items-center bg-white/5 shrink-0">
          <div>
            <h3 class="text-xl font-bold text-white leading-tight">{{ $ref->titulo }}</h3>
            <p class="text-blue-400 text-[10px] mt-1 uppercase tracking-widest font-bold">{{ $ref->tipo_referencia->nombre }}</p>
          </div>
          <button onclick="toggleModal('modal-view-{{ $ref->id_referencia }}')" class="text-slate-400 hover:text-white transition p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Cuerpo con Scroll -->
        <div class="p-8 overflow-y-auto custom-scrollbar space-y-6">
          @php
          $autores = $ref->referencia_autores->sortBy('orden')->values();
          $total = $autores->count();
          $arrAPA = [];
          $arrIEEE = [];

          foreach($autores as $idx => $ra) {
          $nombreAPA = $ra->autor->apellidos . ', ' . substr($ra->autor->nombre, 0, 1) . '.';
          $nombreIEEE = substr($ra->autor->nombre, 0, 1) . '. ' . $ra->autor->apellidos;

          if ($total > 1 && $idx === $total - 1) {
          $arrAPA[] = '& ' . $nombreAPA;
          $arrIEEE[] = 'and ' . $nombreIEEE;
          } else {
          $arrAPA[] = $nombreAPA;
          $arrIEEE[] = $nombreIEEE;
          }
          }

          $autoresAPA = implode($total > 2 ? ', ' : ' ', $arrAPA);
          $autoresIEEE = implode(', ', $arrIEEE);

          $tipoSlug = Str::slug($ref->tipo_referencia->nombre ?? 'libro');
          @endphp

          <div class="space-y-2">
            <div class="flex justify-between items-center">
              <span class="text-[10px] font-black text-slate-500 uppercase tracking-tighter">APA 7th Edition</span>
              <button onclick="copyToClipboard('apa-{{ $ref->id_referencia }}')" class="text-blue-500 text-xs font-bold hover:text-blue-400">COPIAR</button>
            </div>
            <div id="apa-{{ $ref->id_referencia }}" class="p-4 bg-white/5 border border-white/5 rounded-xl text-slate-200 text-sm leading-relaxed">
              @if($tipoSlug === 'libro')
              {{ $autoresAPA }} ({{ $ref->anio_publicacion }}). <span class="italic text-white">{{ $ref->titulo ?? '[Título no disponible]' }}</span>. {{ $ref->editorial ? $ref->editorial->nombre . '.' : '' }} {{ $ref->doi ? 'https://doi.org/'.$ref->doi : $ref->url }}
              @else
              {{ $autoresAPA }} ({{ $ref->anio_publicacion }}). {{ $ref->titulo ?? '[Título no disponible]' }}. <span class="italic text-white">{{ $ref->editorial->nombre ?? '[Revista no especificada]' }}</span>{!! $ref->volumen ? ', <span class="italic">'.$ref->volumen.'</span>' : '' !!}{{ $ref->numero ? '(' . $ref->numero . ')' : '' }}{{ $ref->paginas ? ', ' . $ref->paginas : '' }}. {{ $ref->doi ? 'https://doi.org/'.$ref->doi : $ref->url }}
              @endif
            </div>
          </div>

          <div class="space-y-2">
            <div class="flex justify-between items-center">
              <span class="text-[10px] font-black text-slate-500 uppercase tracking-tighter">IEEE Style (Engineering)</span>
              <button onclick="copyToClipboard('ieee-{{ $ref->id_referencia }}')" class="text-blue-500 text-xs font-bold hover:text-blue-400">COPIAR</button>
            </div>
            <div id="ieee-{{ $ref->id_referencia }}" class="p-4 bg-white/5 border border-white/5 rounded-xl text-slate-200 text-sm leading-relaxed">
              @if($tipoSlug === 'libro')
              {{ $autoresIEEE }}, <span class="italic text-white">{{ $ref->titulo ?? '[Título no disponible]' }}</span>. {{ $ref->editorial ? $ref->editorial->nombre . ', ' : '' }}{{ $ref->anio_publicacion }}. [Online]. Available: {{ $ref->url ?? 'https://doi.org/'.$ref->doi }}
              @else
              {{ $autoresIEEE }}, "{{ $ref->titulo ?? '[Título no disponible]' }}," <span class="italic text-white">{{ $ref->editorial->nombre ?? '[Revista]' }}</span>{{ $ref->volumen ? ', vol. ' . $ref->volumen : '' }}{{ $ref->numero ? ', no. ' . $ref->numero : '' }}{{ $ref->paginas ? ', pp. ' . $ref->paginas : '' }}, {{ $ref->anio_publicacion }}. [Online]. Available: {{ $ref->url ?? 'https://doi.org/'.$ref->doi }}
              @endif
            </div>
          </div>

          <div class="space-y-2">
            <div class="flex justify-between items-center">
              <span class="text-[10px] font-black text-slate-500 uppercase tracking-tighter">Chicago Style</span>
              <button onclick="copyToClipboard('chicago-{{ $ref->id_referencia }}')" class="text-blue-500 text-xs font-bold hover:text-blue-400">COPIAR</button>
            </div>
            <div id="chicago-{{ $ref->id_referencia }}" class="p-4 bg-white/5 border border-white/5 rounded-xl text-slate-200 text-sm leading-relaxed">
              @if($tipoSlug === 'libro')
              {{ $autoresAPA }}. <span class="italic text-white">{{ $ref->titulo ?? '[Título no disponible]' }}</span>. {{ $ref->editorial ? $ref->editorial->nombre . ', ' : '' }}{{ $ref->anio_publicacion }}.
              @else
              {{ $autoresAPA }}. "{{ $ref->titulo ?? '[Título no disponible]' }}." <span class="italic text-white">{{ $ref->editorial->nombre ?? '[Revista]' }}</span> {{ $ref->volumen ?? '' }}{{ $ref->numero ? ', no. ' . $ref->numero : '' }} ({{ $ref->anio_publicacion }}){{ $ref->paginas ? ': ' . $ref->paginas : '' }}.
              @endif
            </div>
          </div>

          <div class="space-y-2">
            <div class="flex justify-between items-center">
              <span class="text-[10px] font-black text-slate-500 uppercase tracking-tighter">Harvard Style</span>
              <button onclick="copyToClipboard('harvard-{{ $ref->id_referencia }}')" class="text-blue-500 text-xs font-bold hover:text-blue-400">COPIAR</button>
            </div>
            <div id="harvard-{{ $ref->id_referencia }}" class="p-4 bg-white/5 border border-white/5 rounded-xl text-slate-200 text-sm leading-relaxed">
              @php
              $primerAutor = $autores->first();
              $apellidoHarvard = $primerAutor->autor->apellidos ?? 'N.N';
              $inicialHarvard = isset($primerAutor->autor->nombre) ? substr($primerAutor->autor->nombre, 0, 1) . '.' : '';
              @endphp
              @if($tipoSlug === 'libro')
              {{ $apellidoHarvard }}, {{ $inicialHarvard }} ({{ $ref->anio_publicacion }}) <span class="italic text-white">'{{ $ref->titulo }}'</span>. {{ $ref->editorial ? $ref->editorial->nombre . '.' : '' }}
              @else
              {{ $apellidoHarvard }}, {{ $inicialHarvard }} ({{ $ref->anio_publicacion }}) '{{ $ref->titulo }}', <span class="italic text-white">{{ $ref->editorial->nombre ?? '[Revista]' }}</span>{{ $ref->volumen ? ', ' . $ref->volumen : '' }}{{ $ref->numero ? '(' . $ref->numero . ')' : '' }}{{ $ref->paginas ? ', pp. ' . $ref->paginas : '' }}.
              @endif
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-white/10 bg-white/5 rounded-b-2xl flex justify-end shrink-0">
          <button onclick="toggleModal('modal-view-{{ $ref->id_referencia }}')" class="px-8 py-2 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition border border-white/10">Cerrar</button>
        </div>
      </div>
    </div>

    <div id="modal-delete-{{ $ref->id_referencia }}" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('modal-delete-{{ $ref->id_referencia }}')"></div>

      <div class="relative w-full max-w-md bg-[#0a0a0f] border border-white/10 rounded-2xl shadow-2xl p-8 m-4 text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-500/20 border border-red-500/30 mb-6">
          <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">¿Eliminar Referencia?</h3>
        <p class="text-sm text-slate-400 mb-8">Esta acción es irreversible. El archivo PDF asociado y las relaciones con materias y autores también serán eliminados de la base de datos.</p>

        <div class="flex justify-center gap-3">
          <button onclick="toggleModal('modal-delete-{{ $ref->id_referencia }}')" class="flex-1 px-4 py-3 text-slate-300 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition">Cancelar</button>

          <!-- Formulario que ejecuta el DELETE en Laravel -->
          <form action="{{ route('referencias.destroy', $ref->id_referencia) }}" method="POST" class="flex-1">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full h-full px-4 py-3 text-white bg-red-600 hover:bg-red-500 border border-red-500 rounded-xl shadow-[0_0_15px_rgba(220,38,38,0.3)] transition">Sí, eliminar</button>
          </form>
        </div>
      </div>
    </div>

    <div id="modal-edit-{{ $ref->id_referencia }}" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/80 backdrop-blur-md" onclick="toggleModal('modal-edit-{{ $ref->id_referencia }}')"></div>

      <div class="relative w-full max-w-4xl bg-[#0d1117] border border-white/10 rounded-2xl shadow-2xl flex flex-col max-h-[90vh]">

        <div class="p-6 border-b border-white/10 flex justify-between items-center bg-white/5 shrink-0">
          <h3 class="text-xl font-bold text-white">Editar Referencia</h3>
          <button onclick="toggleModal('modal-edit-{{ $ref->id_referencia }}')" class="text-slate-400 hover:text-white transition p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        @php
        $autoresString = $ref->referencia_autores->sortBy('orden')->map(function($ra) {
        return trim($ra->autor->apellidos . ' ' . $ra->autor->nombre);
        })->implode(', ');

        $temasString = $ref->temas->pluck('nombre')->implode(', ');

        $materiasIds = $ref->materias->pluck('id_materia')->toArray();
        @endphp

        <form action="{{ route('referencias.update', $ref->id_referencia) }}" method="POST" class="overflow-y-auto custom-scrollbar flex-1">
          @csrf
          @method('PUT')

          <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="md:col-span-2">
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Título de la Obra *</label>
              <input type="text" name="titulo" value="{{ old('titulo', $ref->titulo) }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
            </div>

            <div class="md:col-span-2">
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Autores * <span class="normal-case font-normal text-slate-500">(Separados por coma: Apellidos Nombre)</span></label>
              <input type="text" name="autores_text" value="{{ old('autores_text', $autoresString) }}" placeholder="Ej. Pressman Roger, Sommerville Ian" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tipo de Referencia *</label>
              <select name="id_tipo_referencia" required class="w-full bg-[#0d1117] border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition appearance-none">
                @foreach($catalogos['tipos'] as $tipo)
                <option value="{{ $tipo->id_tipo_referencia }}" {{ (old('id_tipo_referencia', $ref->id_tipo_referencia) == $tipo->id_tipo_referencia) ? 'selected' : '' }}>
                  {{ $tipo->nombre }}
                </option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Año *</label>
              <input type="number" name="anio_publicacion" value="{{ old('anio_publicacion', $ref->anio_publicacion) }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition">
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Editorial</label>
              <input type="text" name="editorial" value="{{ old('editorial', $ref->editorial->nombre ?? '') }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition">
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">DOI</label>
              <input type="text" name="doi" value="{{ old('doi', $ref->doi) }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition">
            </div>

            <div class="md:col-span-2">
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">URL Web</label>
              <input type="url" name="url" value="{{ old('url', $ref->url) }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition">
            </div>

            <div class="md:col-span-2">
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Temas (Keywords)</label>
              <input type="text" name="temas_text" value="{{ old('temas_text', $temasString) }}" placeholder="Ej. Ingeniería, Software, Agile" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition">
            </div>

            <div class="md:col-span-2">
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Materias *</label>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-3 bg-white/5 p-4 rounded-xl border border-white/10">
                @foreach($catalogos['materias'] as $materia)
                <label class="flex items-center space-x-3 cursor-pointer">
                  <input type="checkbox" name="materias[]" value="{{ $materia->id_materia }}"
                    {{ in_array($materia->id_materia, old('materias', $materiasIds)) ? 'checked' : '' }}
                    class="form-checkbox h-4 w-4 text-blue-500 rounded border-white/20 bg-black/50 focus:ring-blue-500 focus:ring-offset-gray-900">
                  <span class="text-sm text-slate-300">{{ $materia->nombre }}</span>
                </label>
                @endforeach
              </div>
            </div>

          </div>

          <div class="p-6 border-t border-white/10 bg-white/5 rounded-b-2xl flex justify-end gap-3 shrink-0">
            <button type="button" onclick="toggleModal('modal-edit-{{ $ref->id_referencia }}')" class="px-6 py-2 text-slate-400 hover:text-white transition">Cancelar</button>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition shadow-[0_0_15px_rgba(37,99,235,0.3)]">Guardar Cambios</button>
          </div>
        </form>
      </div>
    </div>

    @empty
    <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-white/5 border border-white/10 rounded-2xl p-12 text-center backdrop-blur-xl">
      <svg class="w-12 h-12 text-slate-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
      </svg>
      <h4 class="text-xl font-bold text-slate-200">
        @if(request('search'))
        No se encontraron resultados para "{{ request('search') }}"
        @else
        Tu colección está vacía
        @endif
      </h4>
      <p class="text-slate-400 text-sm mt-2 max-w-sm mx-auto">Explora el catálogo público para agregar referencias de investigación a tu biblioteca.</p>
      @if(request('search'))
      <a href="{{ route('dashboard') }}" class="inline-block mt-5 px-5 py-2.5 bg-white/10 hover:bg-white/20 text-xs font-bold uppercase tracking-wider rounded-xl transition">Limpiar Búsqueda</a>
      @else
      <a href="{{ route('referencias.index') }}" class="inline-block mt-5 px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-xs font-bold uppercase tracking-wider rounded-xl transition shadow-lg shadow-blue-600/20">Ir al Catálogo</a>
      @endif
    </div>
    @endforelse
  </div>

  <div class="mt-8 pt-4 border-t border-white/5">
    {{ $colecciones->links() }}
  </div>

</div>

<script>
  function toggleModal(modalID) {
    const modal = document.getElementById(modalID);
    if (modal.classList.contains('hidden')) {
      modal.classList.remove('hidden');
    } else {
      modal.classList.add('hidden');
    }
  }

  function initTomSelects() {
    document.querySelectorAll('.tom-select-single').forEach((el) => {
      if (!el.tomselect) {
        new TomSelect(el, {
          create: false,
          sortField: {
            field: "text",
            direction: "asc"
          }
        });
      }
    });

    document.querySelectorAll('.tom-select-multiple').forEach((el) => {
      if (!el.tomselect) {
        new TomSelect(el, {
          plugins: ['remove_button'],
          create: false,
          maxOptions: null
        });
      }
    });
  }

  function toggleModal(modalID) {
    const modal = document.getElementById(modalID);
    if (modal.classList.contains('hidden')) {
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      initTomSelects();
    } else {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    }
  }

  function copyToClipboard(elementId) {
    const text = document.getElementById(elementId).innerText;
    navigator.clipboard.writeText(text).then(() => {
      alert('Cita copiada al portapapeles');
    }).catch(err => {
      console.error('Error al copiar: ', err);
    });
  }
</script>

@endsection