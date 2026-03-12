<x-adminlte.tool.modal id="modal-equipes" title="{{ $projeto->titulo }} - Adicionar Novo Membro" size="lg"
    theme="primary" vCentered>
    <form action="{{ route('projetos.membros.criar', ['projeto' => $projeto]) }}" method="POST"
        id="form-alterar-membro">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @php
                        $config_user_select = [
                            'placeholder' => 'Selecione ou digite o email do usuário',
                            'allowClear' => true,
                            'tags' => true
                        ];
                    @endphp
                    <x-adminlte.form.select2 name="usuario" id="select-usuario" label="Usuário"
                        :config="$config_user_select" required>
                        <x-adminlte.form.options :options="$users_disponiveis" empty-option="Selecione o usuário" />
                    </x-adminlte.form.select2>
                </div>
                <div class="col-12 d-none" id="confirma-convite-container">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="confirme_convite" name="confirme_convite" value="1">
                        <label for="confirme_convite">
                            <span class="text-danger">*</span> Confirmar convite: Você confirma o envio de um e-mail
                            para o usuário com um convite para o projeto?
                        </label>
                    </div>
                </div>
            </div>
            @include('projetos.gestao.partials.membros-form', ['membro' => null, 'projeto' => $projeto])
        </div>
        <x-slot:footerSlot>
            <div class="d-flex justify-content-between w-100">
                <x-adminlte-button type="button" theme="secondary" label="Cancelar" data-dismiss="modal"
                    icon="fa-solid fa-ban" />
                <x-adminlte-button type="submit" form="form-alterar-membro" theme="primary" label="Criar Usuário"
                    icon="fa-solid fa-save" />
            </div>
        </x-slot:footerSlot>
    </form>
</x-adminlte.tool.modal>

@push('js')
    <script>
        $(document).ready(function () {
            const selectUsuario = $('#select-usuario');
            const confirmaConviteContainer = $('#confirma-convite-container');
            const confirmaConviteCheckbox = $('#confirme_convite');

            // Lista dos usuários existentes (extraída do PHP)
            const usuariosExistentes = @json(array_keys($users_disponiveis));

            // Função para verificar se é um usuário novo
            function verificarNovoUsuario(valor) {
                // Se o valor está vazio, esconde o campo
                if (!valor) {
                    esconderConfirmaConvite();
                    mostrarCamposLiderancaGerencia();
                    return;
                }

                // Verifica se é um ID de usuário existente
                const isUsuarioExistente = usuariosExistentes.includes(parseInt(valor)) || usuariosExistentes.includes(valor);

                // Se não é um usuário existente E parece ser um email ou texto (não apenas número)
                const isNovoUsuario = !isUsuarioExistente && (valor.includes('@') || isNaN(valor));

                if (isNovoUsuario) {
                    mostrarConfirmaConvite();
                    ocultarCamposLiderancaGerencia();
                } else {
                    esconderConfirmaConvite();
                    mostrarCamposLiderancaGerencia();
                }
            }

            // Função para mostrar o confirmar convite
            function mostrarConfirmaConvite() {
                confirmaConviteContainer.removeClass('d-none');
                confirmaConviteCheckbox.prop('required', true);
                confirmaConviteCheckbox.prop('checked', false);
            }

            // Função para esconder o confirmar convite
            function esconderConfirmaConvite() {
                confirmaConviteContainer.addClass('d-none');
                confirmaConviteCheckbox.prop('required', false);
                confirmaConviteCheckbox.prop('checked', false);
            }

            // Função para ocultar e desmarcar campos de liderança e gerência
            function ocultarCamposLiderancaGerencia() {
                const liderContainer = $('#lider').closest('.col-6');
                const gerenteContainer = $('#gerente').closest('.col-6');

                // Ocultar os containers
                liderContainer.addClass('d-none');
                gerenteContainer.addClass('d-none');

                // Desmarcar os switches
                $('#lider').prop('checked', false);
                $('#gerente').prop('checked', false);

                // Atualizar o estado visual dos switches (necessário para AdminLTE)
                $('#lider').trigger('change');
                $('#gerente').trigger('change');
            }

            // Função para mostrar campos de liderança e gerência
            function mostrarCamposLiderancaGerencia() {
                const liderContainer = $('#lider').closest('.col-6');
                const gerenteContainer = $('#gerente').closest('.col-6');

                // Mostrar os containers
                liderContainer.removeClass('d-none');
                gerenteContainer.removeClass('d-none');
            }

            // Event listeners
            selectUsuario.on('select2:select', function (e) {
                const valor = e.params.data.id;
                verificarNovoUsuario(valor);
            });

            selectUsuario.on('select2:clear', function (e) {
                esconderConfirmaConvite();
            });

            selectUsuario.on('change', function () {
                const valor = $(this).val();
                verificarNovoUsuario(valor);
            });

            // Validação do formulário
            $('#form-alterar-membro').on('submit', function (e) {
                const confirmaConviteVisible = !confirmaConviteContainer.hasClass('d-none');
                const confirmaConviteChecked = confirmaConviteCheckbox.is(':checked');

                if (confirmaConviteVisible && !confirmaConviteChecked) {
                    e.preventDefault();
                    alert('Você precisa confirmar o envio do convite para o novo usuário.');
                    confirmaConviteCheckbox.focus();
                    return false;
                }
            });
        });
    </script>
@endpush
@stack('js')