@props(['atividade'])
<div class="row">
    <div class="col">
        <h5>Projeto: {{ $atividade->projeto->titulo }}</h5>
    </div>
    @if($atividade->trashed())
        <div class="col text-center">
            <h6 class="badge badge-danger">Atividade Excluída em {{ $atividade->deleted_at->format('d/m/Y H:i') }}</h6>
        </div>
    @endif
    <div class="col text-right">
        <h6>Atividade #{{ $atividade->id }}</h6>
    </div>
</div>
<div class="row">

    <div class="col-4">
        <h6 class="">Registrada por: {{ $atividade->membro->user->name }}</h6>
    </div>
    <div class="col">
        <h6 class="text-center">em {{ $atividade->created_at->format('d/m/Y H:i') }}</h6>
    </div>
    <div class="col">
        <h6 class="text-right">Atualizada em: {{ $atividade->updated_at->format('d/m/Y H:i') }}</h6>
    </div>
    <div class="col-12">
        <hr>
    </div>
</div>