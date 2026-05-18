<!DOCTYPE html>
<html lang="es" class="h-full bg-[#090d16]">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión - REFERENCIASICO</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full flex items-center justify-center p-4 overflow-hidden relative selection:bg-blue-500/30">

  <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none"></div>
  <div class="absolute bottom-1/4 left-1/3 w-[300px] h-[300px] bg-purple-600/5 rounded-full blur-[100px] pointer-events-none"></div>

  <div class="relative w-full max-w-md group">
    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-15 group-hover:opacity-25 transition duration-1000"></div>

    <div class="relative bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-8 flex flex-col">

      <div class="text-center mb-8">
        <span class="text-2xl font-black tracking-tighter text-white">
          REFERENCIAS<span class="text-blue-500">ICO</span>
        </span>
      </div>

      <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
          <label for="email" class="block text-slate-300 text-xs font-semibold uppercase tracking-wider mb-2">
            Correo Electrónico
          </label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
            class="w-full bg-black/30 border {{ $errors->has('email') ? 'border-red-500/50 focus:ring-red-500/30' : 'border-white/10 focus:ring-blue-500/50' }} rounded-xl px-4 py-3 text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 transition text-sm">

          @error('email')
          <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
            <span class="font-medium">{{ $message }}</span>
          </p>
          @enderror
        </div>

        <div>
          <div class="flex justify-between items-center mb-2">
            <label for="password" class="block text-slate-300 text-xs font-semibold uppercase tracking-wider">
              Contraseña
            </label>
          </div>
          <input id="password" type="password" name="password" required
            class="w-full bg-black/30 border {{ $errors->has('password') ? 'border-red-500/50 focus:ring-red-500/30' : 'border-white/10 focus:ring-blue-500/50' }} rounded-xl px-4 py-3 text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 transition text-sm">

          @error('password')
          <p class="text-red-400 text-xs mt-1.5">
            <span class="font-medium">{{ $message }}</span>
          </p>
          @enderror
        </div>

        <!-- <div class="flex items-center justify-between pt-1">
          <label class="relative flex items-center cursor-pointer select-none text-slate-400 text-xs hover:text-slate-300 transition">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
              class="mr-2 rounded bg-black/40 border-white/10 text-blue-600 focus:ring-0 focus:ring-offset-0 w-4 h-4 accent-blue-600">
            Mantener sesión iniciada
          </label>
        </div> -->

        <div class="pt-2">
          <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-4 rounded-xl transition duration-300 shadow-lg shadow-blue-600/20 hover:shadow-blue-500/40 text-sm tracking-wide border border-blue-500/30 active:scale-[0.99]">
            Ingresar al Sistema
          </button>
        </div>
      </form>

      <p class="text-xs text-slate-400 text-center mt-4">
        ¿No tienes una cuenta?
        <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 font-medium transition ml-1">Regístrate aquí</a>
      </p>

      <div class="text-center mt-6 pt-4 border-t border-white/5">
        <a href="{{ route('referencias.index') }}" class="text-xs text-slate-500 hover:text-slate-300 transition uppercase tracking-wider">
          ← Volver al Catálogo Público
        </a>
      </div>

    </div>
  </div>

</body>

</html>