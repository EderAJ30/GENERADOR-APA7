<!DOCTYPE html>
<html lang="es" class="h-full bg-[#090d16]">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear Cuenta - REFERENCIASICO</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-full flex items-center justify-center p-4 overflow-y-auto relative selection:bg-blue-500/30 py-12">

  <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[140px] pointer-events-none"></div>

  <div class="relative w-full max-w-lg group my-auto">
    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-15 group-hover:opacity-25 transition duration-1000"></div>

    <div class="relative bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-8 flex flex-col">

      <div class="text-center mb-6">
        <span class="text-2xl font-black tracking-tighter text-white">
          REFERENCIAS<span class="text-blue-500">ICO</span>
        </span>
        <p class="text-slate-400 text-xs mt-2 uppercase tracking-widest">Registro de Nuevo Usuario</p>
      </div>

      <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
          <label for="nombre_usuario" class="block text-slate-300 text-xs font-semibold uppercase tracking-wider mb-1">Nombre(s)</label>
          <input id="nombre_usuario" type="text" name="nombre_usuario" value="{{ old('nombre_usuario') }}" required autofocus
            class="w-full bg-black/30 border {{ $errors->has('nombre_usuario') ? 'border-red-500/50 focus:ring-red-500/30' : 'border-white/10 focus:ring-blue-500/50' }} rounded-xl px-4 py-2.5 text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 transition text-sm">
          @error('nombre_usuario') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="paterno_usuario" class="block text-slate-300 text-xs font-semibold uppercase tracking-wider mb-1">Apellido Paterno</label>
            <input id="paterno_usuario" type="text" name="paterno_usuario" value="{{ old('paterno_usuario') }}" required
              class="w-full bg-black/30 border {{ $errors->has('paterno_usuario') ? 'border-red-500/50 focus:ring-red-500/30' : 'border-white/10 focus:ring-blue-500/50' }} rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 transition text-sm">
            @error('paterno_usuario') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
          <div>
            <label for="materno_usuario" class="block text-slate-300 text-xs font-semibold uppercase tracking-wider mb-1">Apellido Materno</label>
            <input id="materno_usuario" type="text" name="materno_usuario" value="{{ old('materno_usuario') }}" required
              class="w-full bg-black/30 border {{ $errors->has('materno_usuario') ? 'border-red-500/50 focus:ring-red-500/30' : 'border-white/10 focus:ring-blue-500/50' }} rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 transition text-sm">
            @error('materno_usuario') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div>
          <label for="email" class="block text-slate-300 text-xs font-semibold uppercase tracking-wider mb-1">Correo Electrónico</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required
            class="w-full bg-black/30 border {{ $errors->has('email') ? 'border-red-500/50 focus:ring-red-500/30' : 'border-white/10 focus:ring-blue-500/50' }} rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 transition text-sm">
          @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="password" class="block text-slate-300 text-xs font-semibold uppercase tracking-wider mb-1">Contraseña</label>
            <input id="password" type="password" name="password" required
              class="w-full bg-black/30 border {{ $errors->has('password') ? 'border-red-500/50 focus:ring-red-500/30' : 'border-white/10 focus:ring-blue-500/50' }} rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 transition text-sm">
            @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
          <div>
            <label for="password_confirmation" class="block text-slate-300 text-xs font-semibold uppercase tracking-wider mb-1">Confirmar</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
              class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition text-sm">
          </div>
        </div>

        <div class="pt-3">
          <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-4 rounded-xl transition duration-300 shadow-lg shadow-blue-600/20 hover:shadow-blue-500/40 text-sm tracking-wide border border-blue-500/30 active:scale-[0.99]">
            Registrar Cuenta
          </button>
        </div>
      </form>

      <div class="text-center mt-5 pt-4 border-t border-white/5">
        <p class="text-xs text-slate-400">
          ¿Ya tienes una cuenta?
          <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-medium transition ml-1">Inicia sesión aquí</a>
        </p>
      </div>

    </div>
  </div>

</body>

</html>