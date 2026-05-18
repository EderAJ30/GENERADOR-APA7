<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Referencias ICO</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex flex-col min-h-screen bg-slate-900 text-white">

  <nav class="sticky top-0 z-50 bg-white/5 backdrop-blur-md border-b border-white/10 shadow-2xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        <a href="{{ route('referencias.index') }}" class="text-xl font-black tracking-tighter text-white hover:text-gray-200 transition">
          REFERENCIAS<span class="text-blue-500">ICO</span>
        </a>

        <div class="flex items-center gap-6 text-sm font-medium">
          <a href="{{ route('referencias.index') }}" class="text-slate-300 hover:text-white transition">
            Inicio
          </a>

          @auth
          @auth
          <a href="{{ route('dashboard') }}"
            title="Ir a mi Dashboard"
            class="relative flex items-center justify-center h-10 px-4 rounded-full bg-blue-500/10 hover:bg-blue-500/20 backdrop-blur-md border border-blue-500/30 hover:border-blue-500/50 shadow-lg shadow-blue-500/10 transition duration-300 active:scale-95 group">

            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-500/0 to-purple-500/0 group-hover:from-blue-500/10 group-hover:to-purple-500/10 transition duration-300 pointer-events-none"></div>

            <span class="text-xs font-black tracking-wider text-blue-400 group-hover:text-blue-300 transition-colors uppercase select-none">
              {{ explode(' ', trim(Auth::user()->nombre_usuario))[0] }}
            </span>
          </a>
          @endauth
          @else
          <a href="{{ route('login') }}" class="text-slate-300 hover:text-white transition">
            Iniciar Sesión
          </a>
          @endauth
        </div>
      </div>
    </div>
  </nav>

  <main class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      @yield('content')
    </div>
  </main>

  <footer class="mt-auto py-8 border-t border-white/5 bg-black/20 backdrop-blur-sm">
    <div class="max-w-4xl mx-auto px-4 text-center text-slate-500 uppercase tracking-widest">
      <p class="mb-6 text-xs">&copy; {{ date('Y') }} Referencias ICO - Equipo 1</p>

      <ul class="flex flex-wrap justify-center gap-x-8 gap-y-3 text-[10px]">
        <li>Avalos Juarez Eder</li>
        <li>Hernandez Ruiz Paula Mabel</li>
        <li>Lara Martinez Christian Gael</li>
        <li>Maldonado Amaro Jorge Mauricio</li>
        <li>Jaimes Ruiz Xchel</li>
      </ul>
    </div>
  </footer>
</body>

</html>