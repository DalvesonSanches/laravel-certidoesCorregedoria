<div class="container mx-auto px-4 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Card 1 --}}
        <x-card class="flex flex-col items-center justify-center text-center p-6 space-y-3 hover:shadow-lg transition rounded-2xl cursor-pointer"
            wire:navigate href="{{ route('certidao-nova') }}"> 
            <i class="fa-regular fa-file-lines text-5xl text-blue-500" > </i>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Nova Certidão</h2>
            <p class="text-gray-600 dark:text-gray-300">
                Clique para iniciar o procedimento de requisição de uma nova certidão de nada consta da Corregedoria do CBMAP.
            </p>
        </x-card>

        {{-- Card 2 --}}
        <x-card class="flex flex-col items-center justify-center text-center p-6 space-y-3 hover:shadow-lg transition rounded-2xl cursor-pointer"
            wire:navigate href="{{ route('certidao-consulta') }}">
            <i class="fa-solid fa-magnifying-glass text-5xl text-green-500" > </i>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Consultar Certidão</h2>
            <p class="text-gray-600 dark:text-gray-300">
                Clique para conferir a autenticidade de uma certidão de nada consta da Corregedoria do CBMAP.
            </p>
        </x-card>
    </div>
</div>