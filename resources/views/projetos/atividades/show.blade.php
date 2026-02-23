<x-adminlte-modal id="modalAtividade" title="Atividade {{ $atividade->id }}" size="xl" theme="primary"
    icon="fas fa-file" v-centered scrollable>
    <div class="container-fluid">
        @include('projetos.atividades.partils.audit', ['atividade' => $atividade])
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label>Qual foi a última atividade realizada?</label>
                    <div class="bg-white border form-control" style="min-height:100px">{{ $atividade->anterior }}</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label>Qual atividade você pretende realizar hoje?</label>
                    <div class="bg-white border form-control" style="min-height:100px">{{ $atividade->atual }}</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label>Tem alguém ou alguma coisa empedindo seu progresso?</label>
                    <div class="bg-white border form-control" style="min-height:100px">{{ $atividade->problemas }}</div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-between w-100">
            <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal" />
        </div>
    </x-slot>

</x-adminlte-modal>