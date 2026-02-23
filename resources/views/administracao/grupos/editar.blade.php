<x-app title="Editar Grupo">
    @include('administracao.grupos.form', [
    'grupo' => $grupo,
    'route' => route('administracao.grupos.atualizar', $grupo),
    'method' => 'PUT',
    ])
</x-app>