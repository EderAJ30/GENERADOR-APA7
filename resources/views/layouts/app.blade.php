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
        <span class="text-xl font-black tracking-tighter text-white">
          REFERENCIAS<span class="text-blue-500">ICO</span>
        </span>
        <div class="flex items-center gap-6 text-sm font-medium">
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
    <div class="max-w-4xl mx-auto px-4 text-center text-xs text-slate-500 uppercase tracking-widest">
      <p class="mb-6">&copy; {{ date('Y') }} Referencias ICO - Equipo 1</p>
      
      <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <li>- Avalos Juarez Eder</li>
        <li>- Hernandez Ruiz Paula Mabel</li>
        <li>- Lara Martinez Christian Gael</li>
        <li>- Maldonado Amaro Jorge Mauricio</li>
      </ul>
    </div>
  </footer>
</body>

</html>