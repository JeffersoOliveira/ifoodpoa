<x-filament-panels::page>

    <div class="container mx-auto p-6 bg-gray-100 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Detalhes da Manutenção</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informações Gerais -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Informações Gerais</h2>
                <ul class="text-gray-600">
                    <li><strong>ID:</strong> {{ $maintenance->id }}</li>
                    <li><strong>Criado:</strong> {{ $maintenance->attendant->name }}</li>
                    <li><strong>Patrimônio:</strong> {{ $maintenance->bike->patrimony }}</li>
                    <li><strong>Status:</strong> {{ $maintenance->status->getLabel() }} </li>
                    <li><strong>Data de Criação:</strong> {{ $maintenance->created_at->format('d/m/Y H:i') }} </li>
                    <li><strong>Finalizado:</strong> {{ $maintenance->repair->updated_at->format('d/m/Y H:i') }}</li>
                </ul>
            </div>

            <!-- Mecânico Responsável -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Mecânico Responsável</h2>
                {{-- @if ($maintenance->mechanic) --}}
                <ul class="text-gray-600">
                    <li><strong>Nome:</strong> {{ $maintenance->repair->mechanic->name }} </li>
                    <li><strong>E-mail:</strong> {{ $maintenance->repair->mechanic->email }} </li>
                    {{-- <li><strong>Telefone:</strong> </li> --}}
                </ul>
                {{-- @else --}}
                {{-- <p class="text-gray-500">Nenhum mecânico atribuído.</p> --}}
                {{-- @endif --}}
            </div>
        </div>

        <!-- Histórico do CheckList -->
        <div class="bg-white p-4 rounded-lg  shadow mt-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Histórico do CheckList</h2>
            @if ($maintenance->checkList)
                <table class="table-auto w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-300 text-gray-900">
                            {{-- <th class="px-4 py-2 border">ID</th> --}}
                            <th class="px-4 py-2 border">Itens</th>
                            {{-- <th class="px-4 py-2 border">Itens Reparados</th> --}}
                            <th class="px-4 py-2 border">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($maintenance->checkList->check_list as $key => $value)
                            @if ($key !== 'statusAnterior' and $key != 'pecasFaltando')
                                <tr class="hover:bg-gray-100 text-gray-900">
                                    <td class="px-4 py-2 border">{{ ucwords($key) }}</td>
                                    <td class="px-4 py-2 border">
                                        @if ($value == 0)
                                            <x-heroicon-o-check-circle class="w-8 h-8 text-green-500 mt-1" />
                                        @elseif($value == 1)
                                            <x-heroicon-o-x-circle class="w-8 h-8 text-red-500 mt-1" />
                                        @endif
                                    </td>
                                    {{-- <td class="px-4 py-2 border">teste</td> --}}
                                    {{-- <td class="px-4 py-2 border">teste</td> --}}
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <div class=" text-gray-700 py-6 flex flex-col">
                    <span class="text-lg font-semibold text-gray-700 mb-2">Descrição:</span>

                    <div class="border border-gray-300 rounded-sm p-3 ">
                        <span>{{ $maintenance->checkList->description }}</span>

                    </div>
                </div>
            @else
                <p class="text-gray-600">Nenhum checklist registrado.</p>
            @endif
        </div>

        <!-- Histórico de Reparos -->
        <div class="bg-white p-4 rounded-lg shadow mt-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Histórico de Reparos</h2>
            @if ($maintenance->repair['repaired'])
                <table class="table-auto w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-gray-900">
                            {{-- <th class="px-4 py-2 border">ID</th> --}}
                            <th class="px-4 py-2 border">Item</th>
                            <th class="px-4 py-2 border">Itens Reparados</th>
                            {{-- <th class="px-4 py-2 border">Data</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @dump($maintenance->repair['repaired']); --}}
                        @foreach ($maintenance->repair['repaired'] as $key => $values)
                            {{-- @dump($key, $values); --}}

                            @if ($key != 'statusAnterior' and $key != 'pecasFaltando')
                                <tr class="hover:bg-gray-100 text-gray-900">
                                    <td class="px-4 py-2 border">{{ ucwords($key) }}</td>
                                    @if (is_array($values) && !empty($values))
                                        <td class="px-4 py-2 border">
                                            {{ ucwords(str_replace('_', ' ', implode(', ', $values))) }}
                                            {{-- {{ implode(', ', $values) }} --}}
                                        </td>
                                    @else
                                        <td class="px-4 py-2 border">
                                        </td>
                                    @endif
                                    {{-- <td class="px-4 py-2 border">{{ $value }}</td> --}}
                                    {{-- <td class="px-4 py-2 border">teste</td> --}}
                                    {{-- <td class="px-4 py-2 border">teste</td> --}}
                                </tr>
                            @endif
                        @endforeach

                    </tbody>


                </table>
            @else
                <p class="text-gray-600">Nenhum reparo registrado.</p>
            @endif
        </div>

        <!-- Botão Voltar -->
        {{-- <div class="mt-6">
            <a href="" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Voltar para a Lista
            </a>
        </div> --}}
    </div>



    {{-- @dump($maintenance->repair, $maintenance, $maintenance->bike, $maintenance->checkList, $maintenance->attendant) --}}

</x-filament-panels::page>
