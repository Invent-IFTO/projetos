<x-adminlte-modal id="modalAtividade" title="Editar Atividade" size="xl" theme="primary" icon="fas fa-file" v-centered scrollable>
    <form action="{{ route('projetos.atividades.alterar', ['projeto' => $projeto, 'atividade' => $atividade]) }}" method="POST" id="atividade-form">
        @csrf
        @method('PUT')
        <div class="container-fluid">
            @include('projetos.atividades.partils.audit', ['atividade' => $atividade])
            @include('projetos.atividades.partils.form', ['atividade' => $atividade])
        </div>
    </form>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-between w-100">
            <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal" />
            <x-adminlte-button theme="primary" label="Salvar" type="submit" form="atividade-form" />
        </div>
    </x-slot>
</x-adminlte-modal>