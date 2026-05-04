<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Referencias ICO</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">

  <nav class="sticky top-0 z-50 bg-white/5 backdrop-blur-md border-b border-white/10 shadow-2xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        <span class="text-xl font-black tracking-tighter text-white">
          REFERNCIAS<span class="text-blue-500">ICO</span>
        </span>
        <div class="flex items-center gap-6 text-sm font-medium">
          <!-- <a href="#" class="text-slate-300 hover:text-white transition">Mis Referencias</a>
          <a href="#" class="text-slate-300 hover:text-white transition">Busqueda</a> -->
          <!-- <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 p-[1px]">
            <div class="flex h-full w-full items-center justify-center rounded-xl bg-black">
              <span class="text-xs font-bold">EAJ</span>
            </div>
          </div> -->
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
    <div class="text-center text-xs text-slate-500 uppercase tracking-widest">
      &copy; {{ date('Y') }} Referencias ICO - Equipo 1
    </div>
  </footer>
</body>

</html>