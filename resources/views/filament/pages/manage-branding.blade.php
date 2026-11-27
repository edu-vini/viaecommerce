<x-filament-panels::page>
    {{-- Módulos de Paletas Pré-Cadastradas em 1 Clique --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 dark:border-gray-800">
            <div>
                <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-heroicon-m-swatch class="w-5 h-5 text-primary-500" />
                    Paletas de Cores Pré-Cadastradas (1-Clique)
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Selecione um conceito visual harmônico pronto. A paleta atualizará imediatamente os Color Pickers abaixo.
                </p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                Design System Ativo
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- 1. Elisie Bordeaux --}}
            <div class="border border-gray-200 dark:border-gray-800 p-4 hover:border-[#720403] transition flex flex-col justify-between bg-stone-50/50 dark:bg-gray-800/50">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-sm text-stone-900 dark:text-white">Elisie Bordeaux</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 bg-[#720403] text-white">Original</span>
                    </div>
                    <p class="text-xs text-stone-500 mb-3">Vinho bordeaux profundo, dourado champanhe e linho nobre.</p>
                    <div class="flex items-center gap-1.5 mb-4">
                        <div class="w-6 h-6 border border-black/10" style="background-color: #720403" title="Primária: #720403"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #c49a45" title="Acento: #c49a45"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #1c1917" title="Secundária: #1c1917"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #faf8f5" title="Superfície: #faf8f5"></div>
                    </div>
                </div>
                <button type="button" wire:click="applyPreset('default')"
                        class="w-full text-center py-2 px-3 text-xs font-bold uppercase tracking-wider text-white bg-[#720403] hover:bg-[#580302] transition">
                    Aplicar Esta Paleta
                </button>
            </div>

            {{-- 2. Minimal Dark --}}
            <div class="border border-gray-200 dark:border-gray-800 p-4 hover:border-black transition flex flex-col justify-between bg-zinc-50/50 dark:bg-gray-800/50">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-sm text-stone-900 dark:text-white">Minimal Dark</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 bg-black text-white">Noir</span>
                    </div>
                    <p class="text-xs text-stone-500 mb-3">Estética editorial contemporânea, preto profundo e alta costura.</p>
                    <div class="flex items-center gap-1.5 mb-4">
                        <div class="w-6 h-6 border border-black/10" style="background-color: #18181b" title="Primária: #18181b"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #720403" title="Acento: #720403"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #09090b" title="Secundária: #09090b"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #fdfdfd" title="Superfície: #fdfdfd"></div>
                    </div>
                </div>
                <button type="button" wire:click="applyPreset('minimal_dark')"
                        class="w-full text-center py-2 px-3 text-xs font-bold uppercase tracking-wider text-white bg-[#18181b] hover:bg-black transition">
                    Aplicar Esta Paleta
                </button>
            </div>

            {{-- 3. Clean Blue --}}
            <div class="border border-gray-200 dark:border-gray-800 p-4 hover:border-[#1e3a8a] transition flex flex-col justify-between bg-blue-50/30 dark:bg-gray-800/50">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-sm text-stone-900 dark:text-white">Clean Blue</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 bg-[#1e3a8a] text-white">Royal</span>
                    </div>
                    <p class="text-xs text-stone-500 mb-3">Azul marinho clássico e ciano vibrante para um visual fresco e limpo.</p>
                    <div class="flex items-center gap-1.5 mb-4">
                        <div class="w-6 h-6 border border-black/10" style="background-color: #1e3a8a" title="Primária: #1e3a8a"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #38bdf8" title="Acento: #38bdf8"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #0f172a" title="Secundária: #0f172a"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #f8fafc" title="Superfície: #f8fafc"></div>
                    </div>
                </div>
                <button type="button" wire:click="applyPreset('clean_blue')"
                        class="w-full text-center py-2 px-3 text-xs font-bold uppercase tracking-wider text-white bg-[#1e3a8a] hover:bg-[#172554] transition">
                    Aplicar Esta Paleta
                </button>
            </div>

            {{-- 4. Emerald Green --}}
            <div class="border border-gray-200 dark:border-gray-800 p-4 hover:border-[#064e3b] transition flex flex-col justify-between bg-emerald-50/30 dark:bg-gray-800/50">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-sm text-stone-900 dark:text-white">Emerald Green</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 bg-[#064e3b] text-white">Botânico</span>
                    </div>
                    <p class="text-xs text-stone-500 mb-3">Verde esmeralda sofisticado com detalhes em ouro velho reluzente.</p>
                    <div class="flex items-center gap-1.5 mb-4">
                        <div class="w-6 h-6 border border-black/10" style="background-color: #064e3b" title="Primária: #064e3b"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #d4af37" title="Acento: #d4af37"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #062016" title="Secundária: #062016"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #f7fcf9" title="Superfície: #f7fcf9"></div>
                    </div>
                </div>
                <button type="button" wire:click="applyPreset('emerald_green')"
                        class="w-full text-center py-2 px-3 text-xs font-bold uppercase tracking-wider text-white bg-[#064e3b] hover:bg-[#022c22] transition">
                    Aplicar Esta Paleta
                </button>
            </div>

            {{-- 5. Sunset Orange --}}
            <div class="border border-gray-200 dark:border-gray-800 p-4 hover:border-[#9a3412] transition flex flex-col justify-between bg-orange-50/30 dark:bg-gray-800/50">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-sm text-stone-900 dark:text-white">Sunset Orange</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 bg-[#9a3412] text-white">Terracota</span>
                    </div>
                    <p class="text-xs text-stone-500 mb-3">Tons terrosos calorosos, sol poente mediterrâneo e toque artesanal.</p>
                    <div class="flex items-center gap-1.5 mb-4">
                        <div class="w-6 h-6 border border-black/10" style="background-color: #9a3412" title="Primária: #9a3412"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #ea580c" title="Acento: #ea580c"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #271b14" title="Secundária: #271b14"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #fffaf5" title="Superfície: #fffaf5"></div>
                    </div>
                </div>
                <button type="button" wire:click="applyPreset('sunset_orange')"
                        class="w-full text-center py-2 px-3 text-xs font-bold uppercase tracking-wider text-white bg-[#9a3412] hover:bg-[#7c2d12] transition">
                    Aplicar Esta Paleta
                </button>
            </div>

            {{-- 6. Pastel Pink --}}
            <div class="border border-gray-200 dark:border-gray-800 p-4 hover:border-[#8e3646] transition flex flex-col justify-between bg-pink-50/30 dark:bg-gray-800/50">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-sm text-stone-900 dark:text-white">Pastel Pink</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 bg-[#8e3646] text-white">Marsala</span>
                    </div>
                    <p class="text-xs text-stone-500 mb-3">Rosa chá envelhecido, marsala delicado e estética ultra feminina.</p>
                    <div class="flex items-center gap-1.5 mb-4">
                        <div class="w-6 h-6 border border-black/10" style="background-color: #8e3646" title="Primária: #8e3646"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #c49a45" title="Acento: #c49a45"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #2c1a1f" title="Secundária: #2c1a1f"></div>
                        <div class="w-6 h-6 border border-black/10" style="background-color: #fefafb" title="Superfície: #fefafb"></div>
                    </div>
                </div>
                <button type="button" wire:click="applyPreset('pastel_pink')"
                        class="w-full text-center py-2 px-3 text-xs font-bold uppercase tracking-wider text-white bg-[#8e3646] hover:bg-[#722735] transition">
                    Aplicar Esta Paleta
                </button>
            </div>
        </div>
    </div>

    {{-- Formulário de Customização e Color Pickers --}}
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 flex items-center justify-end gap-4">
            <a href="{{ url('/') }}" target="_blank"
               class="px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-stone-700 bg-stone-100 hover:bg-stone-200 transition">
                Visualizar Loja
            </a>

            <x-filament::button type="submit" size="lg" color="primary">
                Salvar Alterações de Identidade Visual
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
