<nav x-data="{ open:false }" 
     class="bg-white dark:bg-gray-800 shadow border-b border-gray-200 dark:border-gray-700">
    
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        {{-- MENU DESKTOP --}}
        <div class="hidden md:flex items-center gap-8">

            <a wire:navigate href="{{ route('home') }}" 
               class="flex items-center gap-2 hover:text-primary-600 text-gray-700 dark:text-gray-200">
                <i class="fa-regular fa-house text-2xl leading-none"></i>
                Início
            </a>

            <a wire:navigate href="{{ route('certidao-nova') }}" 
               class="flex items-center gap-2 hover:text-primary-600 text-gray-700 dark:text-gray-200">
                <i class="fa-regular fa-file-lines text-2xl leading-none"></i>
                Nova Certidão 
            </a>

            <a wire:navigate href="{{ route('certidao-consulta') }}" 
               class="flex items-center gap-2 hover:text-primary-600 text-gray-700 dark:text-gray-200">
               <i class="fa-solid fa-magnifying-glass text-2xl leading-none"></i>
                Consultar Certidão
            </a>
        </div>

        {{-- AÇÃO À DIREITA (theme toggle + hamburger) --}}
        <div class="flex items-center gap-3">

            {{-- TEMA DARK/LIGHT
            <x-theme.toggle /> --}}

            {{-- HAMBURGUER (mobile) --}}
            <button 
                @click="open = !open"
                class="md:hidden p-2 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="fa-solid fa-bars text-2xl leading-none"></i>
            </button>
        </div>
    </div>

    {{-- MENU MOBILE --}}
    <div 
        x-show="open"
        x-transition.origin.top
        class="md:hidden bg-white dark:bg-gray-800 px-4 py-4 space-y-3 border-t border-gray-200 dark:border-gray-700">

        <a wire:navigate href="{{ route('home') }}" 
           class="flex items-center gap-2 text-gray-700 dark:text-gray-200 hover:text-primary-600">
            <i class="fa-regular fa-house text-2xl leading-none"></i>
            Início
        </a>

        <a wire:navigate href="{{ route('certidao-nova') }}" 
           class="flex items-center gap-2 text-gray-700 dark:text-gray-200 hover:text-primary-600">
            <i class="fa-regular fa-file-lines text-2xl leading-none"></i>
            Nova Certidão
        </a>

        <a wire:navigate href="{{ route('certidao-consulta') }}" 
           class="flex items-center gap-2 text-gray-700 dark:text-gray-200 hover:text-primary-600">
           <i class="fa-solid fa-magnifying-glass text-2xl leading-none"></i>
            Consultar Certidão
        </a>

    </div>

</nav>