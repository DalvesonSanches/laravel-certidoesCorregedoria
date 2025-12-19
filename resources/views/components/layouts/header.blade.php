<header x-data="{ open: false }" class="bg-[#bb2a2a] text-white py-6 shadow border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Topo: logo + nome + menu -->
        <div class="flex items-center justify-between">
            
            <!-- Logo + Nome -->
            <div class="flex items-center space-x-3">
                <!-- Logo (desktop e mobile com tamanhos diferentes) -->
                <img 
                    src="/images/logo-cbm.png" 
                    alt="Logo CBM" 
                    class="w-auto md:h-13 md:w-auto h-10"
                >

                <!-- Texto exibido SOMENTE no desktop -->
                <span class="hidden md:block font-semibold text-2xl leading-tight text-center">
                    <span class="block text-base">Corpo de Bombeiros Militar</span>
                    <span class="block text-base">do Amapá</span>
                </span>

                <!-- Texto exibido SOMENTE no mobile -->
                <span class="block md:hidden font-semibold text-xl">
                    CBMAP
                </span>
            </div>


            <!-- Botão Hambúrguer (visível em mobile) -->
            <button 
                @click="open = !open"
                class="md:hidden text-white text-3xl focus:outline-none">
                <i :class="open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'"></i>
            </button>

            <!-- MENU DESKTOP -->
            <nav class="hidden md:flex space-x-8 text-sm font-medium">
                <a wire:navigate href="{{ route('home') }}" 
                    class="flex items-center gap-2 hover:text-primary-200 dark:text-gray-200">
                    <i class="fa-regular fa-house text-2xl leading-none"></i> Início
                </a>

                <a wire:navigate href="{{ route('certidao-nova') }}" 
                    class="flex items-center gap-2 hover:text-primary-200 dark:text-gray-200">
                    <i class="fa-regular fa-file-lines text-2xl leading-none"></i> Nova Certidão
                </a>

                <a wire:navigate href="{{ route('certidao-consulta') }}" 
                    class="flex items-center gap-2 hover:text-primary-200 dark:text-gray-200">
                    <i class="fa-solid fa-magnifying-glass text-2xl leading-none"></i> Consultar Certidão
                </a>
            </nav>
        </div>

        <!-- MENU MOBILE -->
        <nav 
            x-show="open"
            x-transition
            class="md:hidden mt-4 bg-[#bb2a2a] rounded-lg p-4 space-y-4 text-sm font-medium">
            
            <a wire:navigate href="{{ route('home') }}" 
                class="flex items-center gap-2 hover:text-primary-200 dark:text-gray-200">
                <i class="fa-regular fa-house text-xl"></i> Início
            </a>

            <a wire:navigate href="{{ route('certidao-nova') }}" 
                class="flex items-center gap-2 hover:text-primary-200 dark:text-gray-200">
                <i class="fa-regular fa-file-lines text-xl"></i> Nova Certidão
            </a>

            <a wire:navigate href="{{ route('certidao-consulta') }}" 
                class="flex items-center gap-2 hover:text-primary-200 dark:text-gray-200">
                <i class="fa-solid fa-magnifying-glass text-xl"></i> Consultar Certidão
            </a>
        </nav>

        <!-- Linha divisória -->
        <div class="border-b border-white/20 mt-6"></div>

        <!-- Conteúdo central -->
        <div class="text-center mt-9 pb-1">
            <h1 class=" font-extrabold tracking-wider drop-shadow-lg">
                <span class="text-6xl hidden md:inline">CORREGEDORIA</span>
                <span class="text-3xl inline md:hidden">CORREGEDORIA</span>
            </h1>

            <p class="mt-4 text-sm opacity-90" id="dataAtual"></p>
        </div>
    </div>
</header>

<script>
    // função para formatar a data
    function atualizarData() {
        const dt = new Date();
        const opcoes = { day: 'numeric', month: 'long', year: 'numeric' };
        const el = document.getElementById('dataAtual');
        if (el) {
            el.innerText = dt.toLocaleDateString('pt-BR', opcoes);
        }
    }

    // Carrega a primeira vez
    atualizarData();

    // Livewire navigation recarrega a data após cada troca de página
    document.addEventListener("livewire:navigated", () => {
        atualizarData();
    });
</script>
