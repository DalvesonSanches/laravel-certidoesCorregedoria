<x-layouts.app>
    <div class="max-w-xl mx-auto mt-10">

        {{-- ❌ CERTIDÃO INVÁLIDA --}}
        @if($erro)
            <x-alert color="red" light>
                <x-slot:title>
                    Certidão inválida
                </x-slot:title>

                <p class="mt-3 text-gray-700">
                    {{ $erro }}
                </p>
            </x-alert>
        @else
        {{-- ✅ CERTIDÃO VÁLIDA --}}
            <x-alert color="green" light>
                <x-slot:title>
                    Certidão válida
                </x-slot:title>

                <div class="grid grid-cols-1 gap-4 mt-4 text-gray-700">
                    <div>
                        <strong>Código de autenticidade:</strong><br>
                        <span class="break-all text-sm">
                            {{ $certidao->cod_autenticidade }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <strong>Número:</strong><br>
                            {{ $certidao->numero_certidao }}
                        </div>

                        <div>
                            <strong>Validade:</strong><br>
                            {{ date('d/m/Y', strtotime($certidao->data_validade)) }}
                        </div>
                    </div>

                    <div>
                        <strong>Nome:</strong><br>
                        {!! $certidao->militar_nome !!}
                    </div>

                    <div class="flex justify-center mt-6">
                        <x-button
                            icon="arrow-down-tray"
                            color="green"
                            light
                            target="_blank"
                            href="{{ route('certidao.pdf', ['codigo' => $certidao->cod_autenticidade]) }}"
                        >
                            Download da certidão
                        </x-button>
                    </div>
                </div>
            </x-alert>
        @endif

    </div>
</x-layouts.app>