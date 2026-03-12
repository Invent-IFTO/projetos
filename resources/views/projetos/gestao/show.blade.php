@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

<x-app title="{{ $projeto->titulo }} - DashBoard">
    <x-adminlte.widget.card>
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <x-adminlte.widget.card title="Dados do projeto" theme="primary" class="card-outline">
                        <form action="{{ route('projetos.alterar', $projeto) }}" method="POST" id="projeto-form">
                        </form>
                        @csrf
                        @method('PUT')
                        <h6 class="p-2"> <strong>Gerente do Projeto: </strong> <br>{{ $projeto->gerente()?->nome }}</h6>
                        <h6 class="p-2"> <strong>Data de Início: </strong>
                            <br>{{ $projeto->created_at->format('d/m/Y') }}</h6>
                        @include('projetos.gestao.partials.form')
                        <x-slot name="footerSlot">
                            <div class="d-flex justify-content-between w-100">
                                <x-adminlte-button theme="warning" label="Voltar" icon="fa-solid fa-chevron-left" />
                                <x-adminlte-button theme="primary" label="Salvar" type="submit" form="projeto-form"
                                    icon="fa-solid fa-save" />
                            </div>
                        </x-slot>
                        </form>
                    </x-adminlte.widget.card>
                </div>
                <div class="col-6">
                    <x-adminlte.widget.card title="Equipes e Membros" theme="primary" class="card-outline">
                        <x-slot:toolsSlot>
                            <ul class="nav nav-pills ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#membros" data-toggle="tab">Membros</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#equipes" data-toggle="tab">Equipes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#convites" data-toggle="tab">Convites</a>
                                </li>
                            </ul>
                        </x-slot:toolsSlot>
                        <div class="tab-content">
                            <div class="tab-pane active" id="membros">
                                @include('projetos.gestao.partials.membros', ['projeto' => $projeto])
                            </div>
                            <div class="tab-pane" id="equipes">
                                @include('projetos.gestao.partials.equipes', ['projeto' => $projeto])
                            </div>
                            <div class="tab-pane" id="convites">
                                @include('projetos.gestao.partials.convites', ['projeto' => $projeto])
                            </div>
                        </div>


                        <x-slot:footerSlot>
                            @can('Projetos: membros')
                                <div class="row py-2">
                                    <div class="col text-right">
                                        <x-invent::button-dynamic-modal
                                            url="{{ route('projetos.membros.novo', ['projeto' => $projeto]) }}"
                                            icon="fa-solid fa-user-plus mr-1" label="Novo Membro" theme="primary" />
                                    </div>
                                </div>
                            @endcan
                        </x-slot:footerSlot>
                        </x-adminlte-widget.card>
                </div>
            </div>

        </div>
    </x-adminlte.widget.card>
</x-app>