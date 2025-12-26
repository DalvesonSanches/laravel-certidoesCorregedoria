<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="tallstackui_darkTheme()" 
      x-bind:class="{ 'dark': darkTheme }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script>
            // Sincroniza a classe ANTES do Alpine carregar
            // O TallStackUI usa a chave 'dark-theme' (com hífen)
            const isDark = localStorage.getItem('dark-theme') === 'true' || 
                         (!('dark-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Scripts do TallStackUI e Vite -->
        <tallstackui:script /> 
        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <title>{{ $title ?? 'Corregedoria | Corpo de Bombeiros Militar do Amapá' }}</title>
    </head>

   <body 
    x-bind:class="{ 'dark bg-gray-900 text-gray-100': darkTheme, 'bg-gray-50 text-gray-900': !darkTheme }"
    class="min-h-screen flex flex-col antialiased transition-colors duration-200">
         <x-dialog />
        {{-- Cabeçalho --}}
        <x-layouts.header />

        {{-- Navbar responsiva com tema --}}
        {{--  <x-layouts.navbar /> --}}

        {{-- Conteúdo principal --}}
        <main class="flex-1 max-w-7xl mx-auto px-4 py-6">
            {{ $slot }}
        </main>

        {{-- Rodapé --}}
        <x-layouts.footer />

        @livewireScripts
    </body>
</html>
