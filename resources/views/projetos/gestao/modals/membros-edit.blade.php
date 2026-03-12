<x-adminlte.tool.modal id="modal-equipes" title="{{ $projeto->titulo }} - Alterar Membro" size="lg" theme="primary"
    vCentered>
    <form action="{{ route('projetos.membros.alterar', ['projeto' => $projeto, 'membro' => $membro]) }}" method="POST"
        id="form-alterar-membro">
        @method('PUT')
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="select-usuario">Usuário</label>
                        <input class="form-control" value="{{ $membro->nome }} ({{ $membro->email }})" disabled />
                    </div>
                </div>
            </div>
            @include('projetos.gestao.partials.membros-form', ['membro' => $membro, 'projeto' => $projeto])
        </div>
        <x-slot:footerSlot>
            <div class="d-flex justify-content-between w-100">
                <x-adminlte-button type="button" theme="secondary" label="Cancelar" data-dismiss="modal"
                    icon="fa-solid fa-ban" />
                <x-adminlte-button type="submit" form="form-alterar-membro" theme="primary" label="Salvar Alterações"
                    icon="fa-solid fa-save" />
            </div>
        </x-slot:footerSlot>
    </form>
</x-adminlte.tool.modal>

@stack('js')