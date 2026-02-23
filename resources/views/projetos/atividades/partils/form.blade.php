@props(['atividade'])
<div class="row">
    <div class="col">
        <x-adminlte-textarea name="anterior" label="Qual foi a última atividade realizada?" rows="5"
            placeholder="Descreva a última atividade realizada..." required
            enable-old-support="true">{{ $atividade?->anterior ?? '' }}</x-adminlte-textarea>
    </div>
</div>
<div class="row">
    <div class="col"><x-adminlte-textarea name="atual" label="Qual atividade você pretende realizar hoje?"
            placeholder="Descreva a atividade atual..." required rows="5"
            enable-old-support="true">{{ $atividade?->atual ?? '' }}</x-adminlte-textarea>
    </div>
</div>
<div class="row">
    <div class="col"><x-adminlte-textarea name="problemas" label="Tem alguém ou alguma coisa empedindo seu progresso?"
            placeholder="Descreva as obstruções encontradas..." required rows="5"
            enable-old-support="true">{{ $atividade?->problemas ?? '' }}</x-adminlte-textarea>
    </div>
</div>