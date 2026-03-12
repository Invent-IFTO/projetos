@php
    $heads = [
      
        ['label' => 'Equipe', 'no-export' => true, 'class' => 'text-center'],
        ['label' => 'Líder', 'no-export' => true, 'class' => 'text-center'],
        ['label' => 'Atividades', 'no-export' => true, 'class' => 'text-center'],
        ['label' => 'Ações', 'no-export' => true, 'class' => 'text-center'],
    ];

    // Configuração do DataTables com tradução
    $config = \App\Helpers\DataTablesHelper::getConfig([
        'order' => [[0, 'asc']], // Ordenar por equipe (coluna 0) crescente
        'columnDefs' => [
            ['orderable' => false, 'targets' => [3]] // Desabilitar ordenação nas colunas de ações e botões
        ]
    ]);
@endphp

<x-adminlte.tool.datatable id="equipes-table" :heads="$heads" :config="$config" head-theme="dark" striped bordered
    hoverable>
    @if($projeto->equipes()->count())
        @foreach($projeto->equipes()->orderBy('id')->orderBy('created_at', 'desc')->get() as $equipe)
            <tr data-equipe="{{ $equipe->descricao }}">
                <td>{{ $equipe->descricao }}</td>
                <td>{{ $equipe->lider?->nome }}</td>
                <td class="text-center">{{ $equipe->atividades()->count() }}</td>
                <td>
                    <div class="d-flex justify-content-around w-100">
                        <x-invent::button-dynamic-modal
                            url="{{ route('projetos.equipe.editar', ['projeto' => $projeto, 'equipe' => $equipe]) }}"
                            icon="fa-solid fa-pencil" label="" theme="" class="rounded-circle text-primary mr-2" />
                        <x-invent::button-dynamic-modal
                            url="{{ route('projetos.equipe.remover', ['projeto' => $projeto, 'equipe' => $equipe]) }}"
                            icon="fa-solid fa-trash" label="" theme="" class="rounded-circle text-danger mr-2" />

                    </div>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="4" class="text-center">{{ __('Nenhuma equipe encontrada.') }}</td>
        </tr>
    @endif
</x-adminlte.tool.datatable>