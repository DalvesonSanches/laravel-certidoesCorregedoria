<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? 'Corregedoria | Corpo de Bombeiros Militar do Amapá' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <tallstackui:script /> 

    </head>

    <body class="min-h-screen flex flex-col antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
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
