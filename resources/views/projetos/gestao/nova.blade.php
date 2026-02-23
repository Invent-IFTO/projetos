<x-adminlte-modal id="modalAtividade" title="Nova Atividade" size="xl" theme="primary" icon="fas fa-file" v-centered scrollable>
    <form action="{{ route('projetos.atividades.criar', ['projeto' => $projeto]) }}" method="POST" id="atividade-form">
        @csrf
        <div class="container-fluid">
            @include('projetos.atividades.partils.form', ['atividade' => null])
        </div>
    </form>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-between w-100">
            <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal" />
            <x-adminlte-button theme="primary" label="Salvar" type="submit" form="atividade-form" />
        </div>
    </x-slot>
</x-adminlte-modal>