<footer class="bg-gray-100 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 mt-10">
    <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-2 gap-10 text-gray-700 dark:text-gray-300">

        <!-- COLUNA 1 - INSTITUCIONAL -->
        <div>
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fa-solid fa-building text-primary-600"></i>
                Institucional
            </h3>

            <ul class="space-y-2 text-sm">
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-fire text-primary-600 mt-1"></i>
                    Corpo de Bombeiros Militar do Amapá
                </li>

                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-globe text-primary-600 mt-1"></i>
                    <a href="https://bombeiros.portal.ap.gov.br/" 
                       class="hover:underline" target="_blank">
                        https://bombeiros.portal.ap.gov.br/
                    </a>
                </li>

                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-map-location-dot text-primary-600 mt-1"></i>
                    Rua Hamilton Silva, nº 1647, bairro Santa Rita - Cep: 68.900-068, Macapá - AP
                </li>

                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-code text-primary-600 mt-1"></i>
                    Desenvolvido por Centro de Tecnologia da Informação - CETI/CBMAP
                </li>
            </ul>
        </div>

        <!-- COLUNA 2 - ACESSO -->
        <div>
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fa-solid fa-link text-primary-600"></i>
                Acesso
            </h3>

            <ul class="space-y-2 text-sm">

                <li class="flex items-center gap-2">
                    <i class="fa-solid fa-house text-primary-600"></i>
                    <a wire:navigate href="{{ route('home') }}" class="hover:underline">
                        Início
                    </a>
                </li>

                <li class="flex items-center gap-2">
                    <i class="fa-solid fa-file-circle-plus text-primary-600"></i>
                    <a wire:navigate href="{{ route('certidao-nova') }}" class="hover:underline">
                        Nova Certidão
                    </a>
                </li>

                <li class="flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass text-primary-600"></i>
                    <a wire:navigate href="{{ route('certidao-consulta') }}" class="hover:underline">
                        Consultar Certidão
                    </a>
                </li>

            </ul>
        </div>

    </div>

    <!-- LINHA FINAL -->
    <div class="text-center text-xs py-4 text-gray-500 dark:text-gray-500 border-t border-gray-200 dark:border-gray-700">
        &copy; {{ date('Y') }} Corpo de Bombeiros Militar do Amapá — Todos os direitos reservados.
        
        <div class="flex justify-center gap-6 mt-6">
            <a href="#" class="hover:text-[#bb2a2a] transition">
                <i class="fa-brands fa-facebook-f text-lg"></i>
            </a>

            <a href="#" class="hover:text-[#bb2a2a] transition">
                <i class="fa-brands fa-x-twitter text-lg"></i>
            </a>

            <a href="#" class="hover:text-[#bb2a2a] transition">
                <i class="fa-brands fa-youtube text-lg"></i>
            </a>

        </div>
    </div>
</footer>
