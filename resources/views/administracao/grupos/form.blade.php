    <x-adminlte-card>
        <form action="{{ $route }}" method="POST" id="form-group">
            @csrf
            @method($method)
                <x-adminlte-input name="name" label="Nome do Grupo" placeholder="Digite o nome do grupo" required 
                    :value="old('name', $grupo?->name ?? '')"/>
            <div class="form-group">
                <label for="permissoes">Permissões do Grupo</label>
                <div class="border rounded p-3 {{ $errors->has('permissions') ? 'border-danger' : '' }}">
                    @foreach (config('systemPermissions') as $key => $permissoes)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="" data-class="{{ $key }}">
                                    {{ $key }}
                                    <button class="btn btn-link" type="button" onclick="javascript:check('{{ $key }}')"><i
                                            class="fa-solid fa-square-check"></i> Marcar Todos</button>
                                    <button class="btn btn-link" type="button" onclick="javascript:uncheck('{{ $key }}')"><i
                                            class="fa-regular fa-square"></i> Desmarcar Todos</button>
                                </h5>

                            </div>
                            <div class="card-body">
                                <div class="container-fluid">
                                    <div class="row p-4">
                                        @foreach ($permissoes as $subpermissao)
                                            <div class="col">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input {{ $key }}" name="permissions[]"
                                                        value="{{ $key . ': ' . $subpermissao }}"
                                                        id="{{ $key . '-' . $subpermissao }}"
                                                        {{ in_array($key . ': ' . $subpermissao, old('permissions', $grupo?->permissions->pluck('name')->toArray() ?? [])) ? 'checked' : '' }} />
                                                    <label class="form-check-label"
                                                        for="{{ $key . '-' . $subpermissao }}">{{ $key . ': ' . $subpermissao }}</label>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('permissions')
                    <div class="invalid-feedback d-block text-bold">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </form>
        <x-slot:footerSlot>
            <div class="d-flex justify-content-between mb-2">
                <a href="{{ route('administracao.grupos.listar') }}" class="btn btn-default">Cancelar</a>
                <button form="form-group" type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </x-slot:footerSlot>
    </x-adminlte-card>
    @push('js')
        <script>
            function check(className) {
                $('input.' + className).prop('checked', true);
            }

            function uncheck(className) {
                $('input.' + className).prop('checked', false);
            }
        </script>
    @endpush
