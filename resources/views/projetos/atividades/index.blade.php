<x-app :title=" $projeto->titulo . ' - Atividades'">
    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    @php
        $heads = [
            'ID',
            'Equipe',
            'Usuário',
            'Horário',
            ['label' => 'Última Atividade'],
            ['label' => 'Atividade Atual'],
            ['label' => 'Obstruções'],
            ['label' => 'Ações'],
        ];

        // Configuração do DataTables com tradução
        $config = \App\Helpers\DataTablesHelper::getConfig([
            'order' => [[2, 'desc']], // Ordenar por horário (coluna 2) decrescente
            'columnDefs' => [
                ['orderable' => false, 'targets' => [6]] // Desabilitar ordenação na coluna de ações
            ]
        ]);
    @endphp
    <div class="row my-2">
        @if($gerente)
            <div class="col">
                <span class="badge badge-success">Gerente de Projeto</span>
            </div>
        @endif
        @if($equipe ?? false)
            <div class="col-6">
                <h6>
                    <i class="fa-solid fa-user-group"></i> Equipe: <strong>{{$equipe->descricao}}</strong>
                    <span class="badge badge-info">Líder de Equipe</span>
                </h6>
                @if($equipe?->lider?->id == $membro->id)

                @endif
            </div>
        @endif
        <div class="col text-right">
            <x-invent::button-dynamic-modal :url="route('projetos.atividades.nova', ['projeto' => $projeto])"
                icon="fa-solid fa-plus" label="Nova Atividade" theme="primary" />
        </div>
    </div>
    <div class="row">
        <div class="col">
            <x-adminlte-datatable id="atividades" :heads="$heads" :config="$config" head-theme="dark" striped bordered
                hoverable>
                @if($projeto->atividades()->withTrashed()->count() > 0)
                    @foreach($projeto->atividades()->withTrashed()->get() as $atividade)
                        @if($atividade->trashed() && (auth()->user()->id === $atividade->membro->user_id || auth()->user()->id === $projeto->gerente?->id))
                            <tr>
                                <td class="text-secondary"><del>{{ $atividade->id }}</del></td>
                                <td class="text-secondary"><del>{{ $atividade->membro->equipe?->descricao ?? '-' }}</del></td>
                                <td class="text-secondary"><del>{{ $atividade->membro->user->name }}</del></td>
                                <td class="text-secondary"><del>{{ $atividade->created_at->format('d/m/Y H:i') }}</del></td>
                                <td class="text-secondary" title="{{ $atividade->anterior }}">
                                    <del>{{ Str::limit($atividade->anterior, 50) }}</del>
                                </td>
                                <td class="text-secondary" title="{{ $atividade->atual }}">
                                    <del>{{ Str::limit($atividade->atual, 50) }}</del>
                                </td>
                                <td class="text-secondary" title="{{ $atividade->problemas }}">
                                    <del>{{ Str::limit($atividade->problemas, 50) }}</del>
                                </td>
                                <td>

                                    <x-invent::button-dynamic-modal :url="route('projetos.atividades.show', ['projeto' => $projeto, 'atividade' => $atividade])" icon="fa-solid fa-eye" label="" theme="link"
                                        class="btn-xs text-info" title="Abrir" />

                                    @can('restore', $atividade)
                                        <x-invent::button-confirm-modal confirmTheme="warning" icon="fa-solid fa-undo"
                                            theme="transparent" class="btn-xs text-secondary" method="put"
                                            message="Tem certeza que deseja restaurar esta atividade?"
                                            title="Confirmação de Restauração" confirm-theme="success" header-theme="danger"
                                            url="{{ route('projetos.atividades.restaurar', ['projeto' => $projeto, 'atividade' => $atividade]) }}" />
                                    @endcan

                                    @can('forceDelete', $atividade)
                                        <x-invent::button-confirm-modal confirmTheme="danger" icon="fa-solid fa-trash-arrow-up"
                                            theme="transparent" class="btn-xs text-danger bg-transparent" method="delete"
                                            message="Tem certeza que deseja confirmar a exclusão desta atividade? (ATENÇÃO essa ação não pode ser desfeita)"
                                            title="Confirmação de Exclusão" confirm-theme="danger" header-theme="danger"
                                            confirm-icon="fa-solid fa-trash-arrow-up" label=""  modal-theme="danger"
                                            url="{{ route('projetos.atividades.confirme_delete', ['projeto' => $projeto, 'atividade' => $atividade]) }}" />
                                    @endcan
                                </td>
                            </tr>
                        @elseif($atividade->trashed() === false)
                            <tr>
                                <td>{{ $atividade->id }}</td>
                                <td>{{ $atividade->membro->equipe?->descricao ?? '-' }}</td>
                                <td>{{ $atividade->membro->user->name }}</td>
                                <td>{{ $atividade->created_at->format('d/m/Y H:i') }}</td>
                                <td title="{{ $atividade->anterior }}">{{ Str::limit($atividade->anterior, 50) }}</td>
                                <td title="{{ $atividade->atual }}">{{ Str::limit($atividade->atual, 50) }}</td>
                                <td title="{{ $atividade->problemas }}">{{ Str::limit($atividade->problemas, 50) }}</td>
                                <td>

                                    <x-invent::button-dynamic-modal :url="route('projetos.atividades.show', ['projeto' => $projeto, 'atividade' => $atividade])" icon="fa-solid fa-eye" label="" theme="link"
                                        class="btn-xs text-info" title="Abrir" />

                                    @can('responsavel_edit', $atividade)
                                        <x-invent::button-dynamic-modal :url="route('projetos.atividades.editar', ['projeto' => $projeto, 'atividade' => $atividade])" icon="fa-solid fa-edit" label="" theme="link"
                                            class="btn-xs text-primary" title="Editar" />
                                    @endcan

                                    @can('delete', $atividade)
                                        <x-invent::button-confirm-modal confirmTheme="danger" icon="fa-solid fa-trash"
                                            theme="transparent" class="btn-xs text-danger bg-transparent" method="delete"
                                            message="Tem certeza que deseja excluir esta atividade?" title="Confirmação de Exclusão"
                                            confirm-theme="danger" header-theme="danger" confirm-icon="fa-solid fa-trash" label=""
                                            url="{{ route('projetos.atividades.deletar', ['projeto' => $projeto, 'atividade' => $atividade]) }}" 
                                            modal-theme="danger"
                                            />
                                    @endcan
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center">{{ __('Nenhuma atividade encontrada.') }}</td>
                    </tr>
                @endif
            </x-adminlte-datatable>
        </div>
    </div>

</x-app>