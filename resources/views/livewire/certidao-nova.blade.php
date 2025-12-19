 <div>
    @if (!$sucesso)
        <div class="w-full mx-auto mt-6 space-y-6 border border-gray-300 bg-white rounded-lg p-4">

            <!-- TÍTULO -->
            <p class="w-full text-left text-gray-600 font-bold text-2xl px-4">
                Nova Certidão
            </p>

            <!-- Card -->
            <x-card class="w-full md:w-[550px] mx-auto">

                <div class="mb-6">
                    <x-alert
                        title="Atenção"
                        text="As informações impressas na certidão são as informações constantes no sistema de RH do Corpo de Bombeiros Militar do Amapá, caso encontre alguma divergência procure a DRH para realizar a correção.
                        <br>Seu cadastro na DRH deverá possuir obrigatoriamente o CPF, RGM, nome completo, posto/graduação, quadro e o seu nome de guerra, sem essas informações a certidão não poderá ser gerada."
                        color="yellow"
                        light
                    />
                </div>

                {{-- INPUT CPF --}}
                <x-input
                    label="CPF"
                    icon="users"
                    placeholder="000.000.000-00"
                    hint="Digite o número do seu cpf"
                    x-data
                    x-mask="999.999.999-99"
                    wire:model.defer="cpf"
                    maxlength="14"
                    required
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

                        <x-button
                            primary
                            wire:click="solicitar"
                            wire:target="solicitar"
                            spinner="solicitar"
                            icon="check"
                            color="green"
                            light
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
                <div class="bg-white p-6 rounded shadow flex items-center gap-3">
                    <div class="animate-spin inline-block w-9 h-9 border-2 rounded-full border-gray-700 border-t-transparent"></div>
                    <div>Processando... aguarde.</div>
                </div>
            </div>

        </div>
    @endif

    @if ($sucesso)
        <div class="w-full max-w-xl mx-auto mt-10">
            <x-alert
                title="Sucesso!"
                color="green"
                light
            >
                <p class="font-semibold text-lg">
                    {{--como o nome esta vindo ja formatado o html destacando nome guerra é necessario usar exclamação para o blade entender--}}
                    {!! $militar->nome_formatado !!}
                </p>

                <p class="mb-4">
                    Sua certidão foi gerada com sucesso. Clique em dowload para baixar o arquivo.
                </p>

                <div class="flex justify-center gap-4 mt-4">
                    <x-button
                        outline
                        wire:navigate
                        href="{{ route('certidao-nova') }}"
                        icon="plus"
                        color="blue"
                        light
                    >
                        Nova Certidão
                    </x-button>

                    <x-button
                        icon="arrow-down-tray"
                        color="green"
                        light
                        {{-- no futuro --}}
                        {{-- href="{{ route('certidoes.download', $certidaoId) }}" --}}
                    >
                        Download
                    </x-button>
                </div>


            </x-alert>
        </div>
    @endif
 </div>