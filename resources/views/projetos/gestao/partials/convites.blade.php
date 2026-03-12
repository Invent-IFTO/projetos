@php
    $heads = [
        ['label' => 'E-mail', 'no-export' => true, 'class' => 'text-center'],
        ['label' => 'Data', 'no-export' => true, 'class' => 'text-center'],
        ['label' => 'Responsável', 'no-export' => true, 'class' => 'text-center'],
        ['label' => 'Expira', 'no-export' => true, 'class' => 'text-center'],
        ['label' => 'Ações', 'no-export' => true, 'class' => 'text-center'],
    ];

    // Configuração do DataTables com tradução
    $config = \App\Helpers\DataTablesHelper::getConfig([
        'order' => [[0, 'asc']], // Ordenar por equipe (coluna 0) crescente
        'columnDefs' => [
            ['orderable' => false, 'targets' => [4]] // Desabilitar ordenação nas colunas de ações e botões
        ]
    ]);
@endphp


<x-adminlte.tool.datatable id="convites-table" :heads="$heads" :config="$config" head-theme="dark" striped bordered hoverable>
    @if($projeto->convites()->count())
        @foreach($projeto->convites()->orderBy('created_at', 'desc')->get() as $convite)
            <tr>
                <td>{{ $convite->email }}</td>
                <td>{{ $convite->created_at->format('d/m/Y') }}</td>
                <td>{{ $convite->responsavel->nome }}</td>
                <td class="text-center">
                    @if($membro->gerente_projeto)
                        <i class="fa-solid fa-check-circle text-success py-3"></i>
                    @else
                        <x-invent::button-confirm-modal title="Confirmar Promoção a Gerente"
                            message="Tem certeza que deseja tornar {{ $membro->nome }} gerente do projeto?"
                            confirm-button-text="Sim, tornar gerente" cancel-button-text="Cancelar"
                            confirm-button-class="btn-success" cancel-button-class="btn-secondary"
                            :url="route('projetos.membros.promover.gerente', ['projeto' => $projeto, 'membro' => $membro])"
                            icon="fa-solid fa-times-circle text-secondary" label="" theme="link" method="PUT"
                            modal-theme="primary" />
                    @endif
                </td>
                <td class="text-center">
                    @if($membro->lider_equipe)
                        <i class="fa-solid fa-check-circle text-success py-3"></i>
                    @else
                        <x-invent::button-confirm-modal title="Confirmar Promoção a Líder"
                            message="Tem certeza que deseja tornar {{ $membro->nome }} líder da equipe?"
                            confirm-button-text="Sim, tornar líder" cancel-button-text="Cancelar"
                            confirm-button-class="btn-success" cancel-button-class="btn-secondary"
                            :url="route('projetos.membros.promover.lider', ['projeto' => $projeto, 'membro' => $membro])"
                            icon="fa-solid fa-times-circle text-secondary" label="" theme="link" method="PUT" />
                    @endif
                </td>
                <td class="text-center">{{ $membro->atividades()->count() }}</td>


                <td>
                    <div class="d-flex justify-content-around w-100">
                        <x-invent::button-dynamic-modal
                            url="{{ route('projetos.membros.editar', ['projeto' => $projeto, 'membro' => $membro]) }}"
                            icon="fa-solid fa-pencil" label="" theme="" class="rounded-circle btn-outline-primary" />

                        <x-invent::button-confirm-modal title="Confirmar Remoção"
                            message="Tem certeza que deseja remover {{ $membro->nome }} do projeto?"
                            confirm-button-text="Sim, remover" cancel-button-text="Cancelar"
                            confirm-button-class="btn-danger" cancel-button-class="btn-secondary"
                            :url="route('projetos.membros.remover', ['projeto' => $projeto, 'membro' => $membro])"
                            icon="fa-solid fa-trash text-danger" label="" theme="" method="DELETE"
                            class="btn-outline-danger rounded-circle" />
                    </div>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" class="text-center">{{ __('Nenhum convite em aberto.') }}</td>
        </tr>
    @endif
</x-adminlte.tool.datatable>