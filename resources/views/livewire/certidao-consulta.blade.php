<div>
    {{-- DEBUG (opcional, agora SEM erro) --}}
    {{-- @dump($mostrarResultado, $certidao) --}}
    @if(!$mostrarResultado)
    {{-- Envolva o bloco principal com o form --}}
        <form wire:submit="consultar">
            <div class="w-full mx-auto mt-6 space-y-6 border border-gray-300 bg-white rounded-lg p-4 dark:bg-gray-900 dark:border-gray-600">
                <!-- TÍTULO: ocupa 100% e fica alinhado à esquerda -->
                <p class="w-full text-left text-gray-600 font-bold text-2xl px-4">
                    Consultar Certidão
                </p>

                <!-- Card -->
                <x-card  class="w-full md:w-[550px] mx-auto">
                        <div class="mb-6">
                        <x-card class="border-yellow-400 bg-yellow-50 dark:bg-yellow-950/20 dark:border-yellow-700">
                                <x-slot:header>
                                    <div class="flex items-center gap-2 text-yellow-700 dark:text-yellow-500 font-bold">
                                        <x-icon name="exclamation-triangle" class="w-5 h-5" />
                                        <span>Atenção</span>
                                    </div>
                                </x-slot:header>

                                <div class="text-sm leading-relaxed text-yellow-900 dark:text-yellow-200">
                                    Informe o código de autenticidade da certidão, o mesmo encontra-se ao lado do <strong>QRCode</strong> impresso na certidão.
                                    <br><br>
                                    O código deve ser informado da mesma maneira que está impresso (respeitando <strong>letras maiúsculas e minúsculas</strong>).
                                </div>
                            </x-card>
                        </div>

                    {{-- codigo de autenticidade --}}
                    <x-input
                        label="Autenticidade"
                        icon="cog"
                        placeholder="Ex: uW8kULxJO6aI4pNSms9f0ojTY32PhM7Kt1vziAbe"
                        hint="Digite o código de autenticidade da certidão"
                        wire:model="codigoAutenticidade"
                        id="codigoAutenticidade"
                        maxlength="40"
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

                            {{-- IMPORTANTE: type="submit" para o formulário entender o Enter --}}                          
                            <x-button primary
                                type="submit"
                                wire:click="consultar"
                                wire:target="consultar"
                                spinner="consultar"
                                icon="check"
                                color="green"
                                class="dark:bg-green-700 dark:text-white">
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
                    <div class="bg-white p-6 rounded shadow flex items-center gap-3 dark:bg-gray-800 dark:border-gray-700">
                        <!-- Adicionado dark:border-white e dark:border-t-transparent -->
                        <div class="animate-spin inline-block w-9 h-9 border-2 rounded-full border-gray-700 border-t-transparent dark:border-white dark:border-t-transparent"></div>
                        
                        <div class="text-gray-900 dark:text-gray-100">Processando... aguarde.</div>
                    </div>
                </div>

            </div>
        </form>
    @endif

    @if($mostrarResultado)
        <div class="w-full max-w-xl mx-auto mt-10">
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
                            {{ $codigoAutenticidade }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <strong class="text-green-900 dark:text-green-400">Número:</strong><br>
                            {{ $certidao->numero_certidao }}
                        </div>

                        <div>
                            <strong class="text-green-900 dark:text-green-400">Validade:</strong><br>
                            {{ \Carbon\Carbon::parse($certidao->data_validade)->format('d/m/Y') }}
                        </div>
                    </div>

                    <div>
                        <strong class="text-green-900 dark:text-green-400">Nome:</strong><br>
                        {!! $certidao->militar_nome !!}
                    </div>

                    <div class="flex flex-wrap justify-center gap-4 mt-6">
                        <x-button
                            outline
                            icon="arrow-path"
                            wire:navigate
                            href="{{ route('certidao-consulta') }}"
                            color="blue"
                            class="dark:text-blue-400 dark:border-blue-400"
                        >
                            Nova consulta
                        </x-button>
                    
                        <x-button
                            icon="arrow-down-tray"
                            color="green"
                            href="{{ route('certidao.pdf', $certidao->cod_autenticidade) }}"
                            target="_blank"
                            class="dark:bg-green-700 dark:text-white"
                        >
                            Download
                        </x-button>
                    </div>
                </div>
            </x-card>

        </div>
    @endif
</div>