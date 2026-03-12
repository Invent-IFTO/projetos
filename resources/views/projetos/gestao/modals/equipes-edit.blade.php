<x-adminlte.tool.modal id="modal-equipes" title="{{ $projeto->titulo }} - Alterar Equipe" size="xl" theme="primary"
    vCentered>
    <form action="{{ route('projetos.equipe.alterar', ['projeto' => $projeto, 'equipe' => $equipe]) }}" method="POST"
        id="form-alterar-equipe">
        @method('PUT')
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <x-adminlte.form.input label="Descrição" name="descricao" value="{{ $equipe->descricao }}" required />
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                  
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th class="text-center">Líder de Equipe</th>
                                <th class="text-center">Atividades</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach($equipe->membros()->orderBy('created_at', 'desc')->get() as $membro)
                            <tr>
                                <td>{{ $membro->nome }}</td>
                                <td class="text-center">
                                    @if($membro->lider_equipe)
                                        <i class="fa-solid fa-check-circle text-success py-3"></i>
                                    @else
                                        <i class="fa-solid fa-times-circle text-danger py-3"></i>
                                    @endif
                                </td>
                                <td class="text-center">{{ $membro->atividades()->count() }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-around w-100">
                                        <x-invent::button-dynamic-modal
                                        url="{{ route('projetos.membros.editar', ['projeto' => $projeto, 'membro' => $membro]) }}"
                                        icon="fa-solid fa-pencil" label="" theme="" class="rounded-circle text-primary mr-2" />
                                        <x-invent::button-confirm-modal title="Confirmar Remoção"
                                        message="Tem certeza que deseja remover {{ $membro->nome }} da equipe {{ $equipe->descricao }}?"
                                        confirm-button-text="Sim, remover da equipe" cancel-button-text="Cancelar"
                                        confirm-button-class="btn-danger" cancel-button-class="btn-secondary"
                                        :url="route('projetos.membros.remover', ['projeto' => $projeto, 'membro' => $membro])"
                                        icon="fa-solid fa-trash" label="" theme="link" method="DELETE" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <table class="table table-striped">
                        <thead></thead>
                    </table>
                </div>
            </div>
        </div>
        <x-slot:footerSlot>
            <div class="d-flex justify-content-between w-100">
                <x-adminlte-button type="button" theme="secondary" label="Cancelar" data-dismiss="modal"
                    icon="fa-solid fa-ban" />
                <x-adminlte-button type="submit" form="form-alterar-equipe" theme="primary" label="Salvar Alterações"
                    icon="fa-solid fa-save" />
            </div>
        </x-slot:footerSlot>
    </form>
</x-adminlte.tool.modal>
@stack('js')