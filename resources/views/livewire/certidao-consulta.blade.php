<div>
    {{-- DEBUG (opcional, agora SEM erro) --}}
    {{-- @dump($mostrarResultado, $certidao) --}}
    @if(!$mostrarResultado)
        <div class="w-full mx-auto mt-6 space-y-6 border border-gray-300 bg-white rounded-lg p-4">
            <!-- TÍTULO: ocupa 100% e fica alinhado à esquerda -->
            <p class="w-full text-left text-gray-600 font-bold text-2xl px-4">
                Consultar Certidão
            </p>

            <!-- Card -->
            <x-card  class="w-full md:w-[550px] mx-auto">
                    <div class="mb-6">
                        <x-alert
                            title="Atenção"
                            text="Informe o código de autenticidade da certidão, o mesmo encontra-se ao lado do QRCode impresso na certidão. 
                            Devendo ser informado da mesma maneira que está impresso (letras maiúsculas e minúsculas)."
                            color="yellow" light
                        />
                    </div>

                {{-- codigo de autenticidade --}}
                <x-input
                    label="Autenticidade"
                    icon="cog"
                    placeholder="Ex: uW8kULxJO6aI4pNSms9f0ojTY32PhM7Kt1vziAbe"
                    hint="Digite o código de autenticidade da certidão"
                    wire:model.defer="codigoAutenticidade"
                    id="codigoAutenticidade"
                    maxlength="40"
                    required
                    x-on:input="$el.value = $el.value.replace(/\s+/g, '')"
                    >
                </x-input>


                <x-slot:footer>
                    <div class="flex justify-center">

                        <x-button 
                            class="mr-4" outline
                            wire:navigate href="{{ route('home') }}"
                            {{--wire:click="voltar"
                            spinner="voltar"--}}
                            icon="arrow-left"
                            color="yellow" light>
                            Voltar
                        </x-button>

                        <x-button primary
                            wire:click="consultar"
                            wire:target="consultar"
                            spinner="consultar"
                            icon="check"
                            color="green" light>
                            Consultar
                        </x-button>

                    </div>
                </x-slot:footer>
            </x-card>

            {{-- Overlay global de loading --}}
            <div
                wire:loading.flex
                wire:target="consultar"
                class="fixed inset-0 z-50 items-center justify-center bg-black/30"
                style="display:none;"
            >
                <div class="bg-white p-6 rounded shadow flex items-center gap-3">
                    <div class="animate-spin inline-block w-9 h-9 border-2 rounded-full border-gray-700 border-t-transparent"></div>
                    <div>Processando... aguarde.</div>
                </div>
            </div>

        </div>
    @endif

    @if($mostrarResultado)
        <div class="w-full max-w-xl mx-auto mt-10">
            <x-alert color="green" light>
                <x-slot:title>
                    Certidão válida
                </x-slot:title>

                <div class="grid grid-cols-1 gap-4 mt-4">

                    <div>
                        <strong>Código de autenticidade:</strong><br>
                        <span class="break-all text-sm">{{ $codigoAutenticidade }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <strong>Número:</strong><br>
                            {{ $certidao->numero_certidao }}
                        </div>

                        <div>
                            <strong>Validade:</strong><br>
                            {{ \Carbon\Carbon::parse($certidao->data_validade)->format('d/m/Y') }}
                        </div>
                    </div>

                    <div>
                        <strong>Nome:</strong><br>
                        {!! $certidao->militar_nome !!}
                    </div>

                    <div class="flex justify-center gap-4 mt-6">
                       <x-button
                            outline
                            icon="arrow-path"
                            wire:navigate
                            href="{{ route('certidao-consulta') }}"
                            color="blue"
                            light
                        >
                            Nova consulta
                        </x-button>
                       
                        <x-button
                            icon="arrow-down-tray"
                            color="green"
                            light
                        >
                            Download
                        </x-button>
                    </div>
                </div>
            </x-alert>
        </div>
    @endif
</div>