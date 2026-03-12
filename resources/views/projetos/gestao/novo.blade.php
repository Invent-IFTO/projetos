<x-adminlte-modal id="modalProjeto" title="Novo Projeto" size="xl" theme="primary" icon="fas fa-file" v-centered
    scrollable>
    <form action="{{ route('projetos.criar') }}" method="POST" id="projeto-form">
        @csrf
        <div class="container-fluid">
            @include('projetos.gestao.partials.form')
        </div>
    </form>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-between w-100">
            <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal" />
            <x-adminlte-button theme="primary" label="Salvar" type="submit" form="projeto-form" />
        </div>
    </x-slot>
</x-adminlte-modal>
@stack('js')