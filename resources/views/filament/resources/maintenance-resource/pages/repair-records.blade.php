<x-filament-panels::page>
    @if ($maintenance['status']->value !== 'completed')
        <div class="flex justify-between">
            <h1>Ordenar Manutenção: {{ $maintenance->id }}</h1>
            {{-- <button class="bg-green-800 py-2 px-6 rounded-md  font-bold">Iniciar Manutenção</button> --}}
        </div>

        <div class="flex justify-between text-2xl px-4 py-5 bg-gray-300 dark:bg-gray-950">
            <div class="">
                <p><strong>Bike:</strong> {{ ucfirst($maintenance->bike->patrimony) }}</p>
            </div>
            <div class="">
                <p><strong>Atendente:</strong> {{ $maintenance->attendant->name }}</p>
            </div>
            <div class="">
                <strong>Status:</strong>
                <span class="">{{ $maintenance->status->getLabel() }}</span>
            </div>
        </div>

        <div class="border border-gray-600 dark:border-gray-200 rounded px-8 space-y-2">

            <div class="flex flex-row flex-wrap space-x-4 items-start justify-center p-4">
                @php
                    $checkList = $maintenance->checklist['check_list'];
                    ksort($checkList);
                @endphp
                @foreach ($checkList as $key => $value)
                    @if ($key !== 'statusAnterior' and $key !== 'pecasFaltando')
                        <div class="flex flex-col items-center w-1/3 ml-4  md:ml-0 md:w-auto">
                            <strong>{{ ucfirst($key) }}:</strong>
                            @if ($value == 0)
                                <x-heroicon-o-check-circle class="w-8 h-8 text-green-500 mt-1" />
                            @elseif($value == 1)
                                <x-heroicon-o-x-circle class="w-8 h-8 text-red-500 mt-1" />
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Exibe "Description" em uma linha separada abaixo -->
            {{-- @if (isset($maintenance->check_list['Description'])) --}}
            <div class="pt-4  pb-4 border-t border-gray-600 dark:border-gray-200">
                <strong>{{ __('Descrição') }}:</strong>
                <p class="ml-4 text-xl">{{ $maintenance->checkList->description }}</p>
            </div>
            {{-- @endif --}}
        </div>

        <form wire:submit.prevent="submit" class="p-4">
            {{ $this->form }}
            @if ($maintenance->repair()->exists())
                <x-filament::button @class(['mt-4']) type="submit">
                    Finalizar Manutenção
                </x-filament::button>
            @endif
        </form>
    @else
        <p class="text-gray-600 text-2xl rounded-md p-4">Reparo já finalizado.</p>
    @endif

    @dump($maintenance['status'], $maintenance->checkList['check_list'], $itemsRepaired, $maintenance->repair()->exists())

</x-filament-panels::page>
