<div class="container-fluid">
    <div class="row">
        <div class="col">
            <x-adminlte.form.input name="titulo" label="Título do Projeto" placeholder="Digite o título do projeto"
                value="{{ $projeto?->titulo ?? '' }}" required enabled-old-support="true" />
        </div>
    </div>
    <div class="row">
        <div class="col">
            <x-adminlte.form.textarea name="descricao" rows="5" label="Descrição do Projeto"
                placeholder="Digite a descrição do projeto" required
                enabled-old-support="true">{{ $projeto?->descricao ?? '' }} 
            </x-adminlte.form.textarea>
        </div>
    </div>
</div>