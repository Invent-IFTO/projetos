<x-app title="Novo Grupo">
    @include('administracao.grupos.form', 
    ['grupo' => null,
    'route' => route('administracao.grupos.salvar'),
    'method' => 'POST'
    ])
</x-app>