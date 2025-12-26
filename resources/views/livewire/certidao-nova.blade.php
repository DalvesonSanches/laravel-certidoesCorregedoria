<div>
    @if (!$sucesso)
    {{-- Envolva o bloco principal com o form --}}
        <form wire:submit="solicitar">
            <div class="w-full mx-auto mt-6 space-y-6 border border-gray-300 bg-white rounded-lg p-4 dark:bg-gray-900 dark:border-gray-600">

                <!-- TÍTULO -->
                <p class="w-full text-left text-gray-600 font-bold text-2xl px-4">
                    Nova Certidão
                </p>

                <!-- Card -->
                <x-card class="w-full md:w-[550px] mx-auto">

                    <div class="mb-6">
                        <x-card class="border-yellow-400 bg-yellow-50 dark:bg-yellow-950/20 dark:border-yellow-700">
                            <x-slot:header>
                                <div class="flex items-center gap-2 text-yellow-700 dark:text-yellow-500 font-bold">
                                    <x-icon name="exclamation-triangle" class="w-5 h-5" />
                                    <span>Atenção</span>
                                </div>
                            </x-slot:header>

                            <div class="text-sm leading-relaxed text-yellow-900 dark:text-yellow-200">
                                As informações impressas na certidão são as informações constantes no sistema de RH do Corpo de Bombeiros Militar do Amapá, caso encontre alguma divergência procure a DRH para realizar a correção.
                                <br><br>
                                <strong>Seu cadastro na DRH deverá possuir obrigatoriamente:</strong> CPF, RGM, nome completo, posto/graduação, quadro e o seu nome de guerra, sem essas informações a certidão não poderá ser gerada.
                            </div>
                        </x-card>

                    </div>

                    {{-- INPUT CPF --}}
                    <x-input
                        label="CPF"
                        icon="users"
                        placeholder="000.000.000-00"
                        hint="Digite o número do seu cpf"
                        x-data
                        x-mask="999.999.999-99"
                        {{-- No Livewire 3, 'defer' é o padrão, mas 'live' ou 'blur' são comuns. 
                            Se não for validar em tempo real, deixe apenas wire:model="cpf" --}}
                        wire:model="cpf"
                        maxlength="14"
                    />

                    <x-slot:footer>
                        <div class="flex justify-center">

                            <x-button
                                class="mr-4"
                                outline
                                wire:navigate
                                href="{{ route('home') }}"
                                icon="arrow-left"
                                color="yellow"
                                light
                            >
                                Voltar
                            </x-button>

                            {{-- IMPORTANTE: type="submit" para o formulário entender o Enter --}}
                            <x-button
                                type="submit"
                                primary
                                wire:click="solicitar"
                                wire:target="solicitar"
                                spinner="solicitar"
                                icon="check"
                                color="green"
                                class="dark:bg-green-700 dark:text-white"
                            >
                                Solicitar
                            </x-button>

                        </div>
                    </x-slot:footer>

                </x-card>

                {{-- Overlay global de loading --}}
                <div
                    wire:loading.flex
                    wire:target="solicitar"
                    class="fixed inset-0 z-50 items-center justify-center bg-black/30"
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

    @if ($sucesso)
        <div class="w-full max-w-xl mx-auto mt-10">
            <x-card class="border-green-400 bg-green-50 dark:bg-green-950/20 dark:border-green-700">
                <x-slot:header>
                    <div class="flex items-center gap-2 text-green-700 dark:text-green-500 font-bold text-lg">
                        <x-icon name="check-circle" class="w-6 h-6" />
                        <span>Sucesso!</span>
                    </div>
                </x-slot:header>

                <div class="mt-2">
                    <div class="text-gray-800 dark:text-gray-200">
                        <p class="font-semibold text-xl text-green-900 dark:text-green-400">
                            {!! $militar->nome_formatado !!}
                        </p>

                        <p class="mt-2 mb-6">
                            Sua certidão foi gerada com sucesso. Clique em <strong>download</strong> para baixar o arquivo.
                        </p>

                        <div class="flex flex-wrap justify-center gap-4 mt-4">
                            <x-button
                                outline
                                wire:navigate
                                href="{{ route('certidao-nova') }}"
                                icon="plus"
                                color="blue"
                                class="dark:text-blue-400 dark:border-blue-400"
                            >
                                Nova Certidão
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
                </div>
            </x-card>

        </div>
    @endif
</div>