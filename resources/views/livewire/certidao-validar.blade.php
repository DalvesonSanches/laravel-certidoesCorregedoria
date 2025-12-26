<div class="max-w-xl mx-auto mt-10">

    {{-- ❌ CERTIDÃO INVÁLIDA --}}
    @if($erro)
        <x-card class="border-red-400 bg-red-50 dark:bg-red-950/20 dark:border-red-700">
            <x-slot:header>
                <div class="flex items-center gap-2 text-red-700 dark:text-red-500 font-bold text-lg">
                    <x-icon name="x-circle" class="w-6 h-6" />
                    <span>Certidão Inválida</span>
                </div>
            </x-slot:header>

            <div class="mt-2 text-gray-800 dark:text-gray-200">
                <p class="leading-relaxed">
                    {{ $erro }}
                </p>
            </div>
            
            <x-slot:footer>
                <div class="flex justify-center">
                    <x-button
                        outline
                        icon="arrow-path"
                        wire:navigate
                        href="{{ route('certidao-consulta') }}"
                        color="red"
                        class="dark:text-red-400 dark:border-red-400"
                    >
                        Tentar novamente
                    </x-button>
                </div>
            </x-slot:footer>
        </x-card>

    @else
    {{-- ✅ CERTIDÃO VÁLIDA --}}
        <x-card class="border-green-400 bg-green-50 dark:bg-green-950/20 dark:border-green-700">
            <x-slot:header>
                <div class="flex items-center gap-2 text-green-700 dark:text-green-500 font-bold text-lg">
                    <x-icon name="check-circle" class="w-6 h-6" />
                    <span>Certidão Válida</span>
                </div>
            </x-slot:header>

            <div class="grid grid-cols-1 gap-4 text-gray-800 dark:text-gray-200">
                <div>
                    <strong class="text-green-900 dark:text-green-400">Código de autenticidade:</strong><br>
                    <span class="break-all text-sm font-mono bg-white/50 dark:bg-black/20 p-1 rounded">
                        {{ $certidao->cod_autenticidade }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-green-900 dark:text-green-400">Número:</strong><br>
                        {{ $certidao->numero_certidao }}
                    </div>

                    <div>
                        <strong class="text-green-900 dark:text-green-400">Validade:</strong><br>
                        {{ date('d/m/Y', strtotime($certidao->data_validade)) }}
                    </div>
                </div>

                <div>
                    <strong class="text-green-900 dark:text-green-400">Nome:</strong><br>
                    {!! $certidao->militar_nome !!}
                </div>

                <div class="flex justify-center mt-6">
                    <x-button
                        icon="arrow-down-tray"
                        color="green"
                        target="_blank"
                        href="{{ route('certidao.pdf', ['codigo' => $certidao->cod_autenticidade]) }}"
                        class="dark:bg-green-700 dark:text-white"
                    >
                        Download da certidão
                    </x-button>
                </div>
            </div>
        </x-card>

    @endif

</div>