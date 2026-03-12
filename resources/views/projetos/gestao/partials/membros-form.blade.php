@props(['membro' => null, 'projeto'])
<div class="row">
    <div class="col-12">
        <x-adminlte.form.select2 id="select-equipe" name="equipe" id="equipe-tipo" label="Equipe" :config="['tags' => true, 'placeholder' => 'Selecione ou digite o nome da equipe', 'allowClear' => true]" required>
            <x-adminlte.form.options :options="$projeto->equipes->pluck('descricao', 'descricao')->toArray()"
                empty-option="Selecione ou digite o nome da equipe" :selected="$membro?->equipe?->descricao" />
        </x-adminlte.form.select2>
    </div>
    <div class="col-6">
        <x-adminlte-input-switch name="lider" data-on-text="Sim" data-off-text="Não" data-on-color="success"
            data-off-color="danger" :isChecked="$membro?->lider_equipe" label="Líder de equipe" id="lider" />
    </div>
    <div class="col-6">
        <x-adminlte-input-switch name="gerente" data-on-text="Sim" data-off-text="Não" data-on-color="success"
            data-off-color="danger" :isChecked="$membro?->gerente_projeto" label="Gerente de projeto" id="gerente" />
    </div>
</div>