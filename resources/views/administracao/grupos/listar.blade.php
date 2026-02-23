<x-app title="Gerenciar Grupos">
    <div class="container-fluid">
        <div class="row">
            <div class="col text-right">
                @can('Grupos: criar')
                    <a href="{{ route('administracao.grupos.criar') }}" class="btn btn-primary mb-3">
                        <i class="fas fa-plus"></i> Novo Grupo
                    </a>
                @endcan
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($grupos->isEmpty())
                            <tr>
                                <td colspan="2" class="text-center">Nenhum grupo encontrado.</td>
                            </tr>
                        @else
                            @foreach($grupos as $grupo)
                                <tr>
                                    <td>{{ $grupo->name }}</td>
                                    <td>
                                        @can('Grupos: editar')
                                            <a href="{{ route('administracao.grupos.editar', ['grupo' => $grupo->id]) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                        @endcan
                                        @can('Grupos: deletar')
                                            <x-invent::button-confirm-modal label="Deletar"
                                                :url="route('administracao.grupos.deletar', ['grupo' => $grupo->id])" method="DELETE"
                                                icon="fa-solid fa-trash" title="Confirmação de exclusão" class="btn-sm"
                                                theme="danger"
                                                message="Tem certeza que deseja deletar o grupo '{{ $grupo->name }}'?"
                                                 />
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app>