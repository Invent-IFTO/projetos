<x-app title="Projetos">
    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    @php
        $heads = [
            'ID',
            'Título',
            'Descrição',
            'Criado por',
            'Horário',
            'Equipes',
            'Membros',
            ['label' => 'Ações', 'width' => 10, 'no-export' => true, 'class' => 'text-center'],
        ];

        // Configuração do DataTables com tradução
        $config = \App\Helpers\DataTablesHelper::getConfig([
            'order' => [[4, 'desc']], // Ordenar por horário (coluna 4) decrescente
            'columnDefs' => [
                ['orderable' => false, 'targets' => [7]] // Desabilitar ordenação na coluna de ações
            ]
        ]);
    @endphp
    @can('Projetos: listar')
        <div class="row py-2">
            <div class="col text-right">
                <x-invent::button-dynamic-modal url="#" icon="fa-solid fa-plus" label="Novo Projeto" theme="primary" />
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col">
            <x-adminlte-datatable id="atividades" :heads="$heads" :config="$config" head-theme="dark" striped bordered
                hoverable>
                @if(count($projetos) > 0)
                    @foreach($projetos as $projeto)
                        <tr>
                            <td>{{ $projeto->id }}</td>
                            <td>{{ $projeto->titulo}}</td>
                            <td title="{{ $projeto->descricao }}">{{ Str::limit($projeto->descricao, 50) }}</td>
                            <td>{{ $projeto->criador->name }}</td>
                            <td>{{ $projeto->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $projeto->equipes->count() }}</td>
                            <td>{{ $projeto->membros->count() }}</td>
                            <td>

                              
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center">{{ __('Nenhum projeto encontrado.') }}</td>
                    </tr>
                @endif
            </x-adminlte-datatable>
        </div>
    </div>

</x-app>