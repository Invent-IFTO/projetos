<x-app title="Meu Perfil">
    <div class="container-fluid">
        <div class="row">
            <div class="col-2">
                <div class="d-flex justify-content-center align-items-center"
                    style="width:3cm; height: 4cm; border:2px solid red;">FOTO</div>
            </div>
            <div class="col-10">
                <div class="row">
                    <div class="col-12">
                        <x-adminlte-input name="nome" placeholder="Digite seu nome" label="Nome" value="{{ auth()->user()->name }}" disabled/>
                    </div>
                    <div class="col-12">
                        <x-adminlte-input name="nome_social" placeholder="Digite seu nome social" label="Nome Social" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
                <div class="col">
                     <x-adminlte-input name="tel" placeholder="Digite um telefone" label="Telefone" cols="40" rows="10"/>
                     <div name="" id="" class="bg-white p-4">
                        <ul>
                            <li>
                                     (63) 9 9999-9999  (OBS)
                            </li>
                            <li>
                                   (63) 9 9999-9999  (OBS) <i class="fa-brands fa-whatsapp"></i>  
                            </li>
                        </ul>
                       
                        
                     </div>
                </div>
                 <div class="col">
                     <x-adminlte-input name="tel" placeholder="Rede Social" label="Social Mídia" cols="40" rows="10" >
                        <x-slot:prependSlot>
                            <div class="input-group-text">
                                <select name="" id="">
                                    <option value="whatsapp"><i class="fa-brands fa-whatsapp"></i> WhatsApp</option>
                                    <option value="facebook"><i class="fa-brands fa-facebook"></i> Facebook</option>
                                    <option value="instagram"><i class="fa-brands fa-instagram"></i> Instagram</option>
                                    <option value="twitter"><i class="fa-brands fa-twitter"></i> Twitter</option>
                                    <option value="linkedin"><i class="fa-brands fa-linkedin"></i> LinkedIn</option>
                                </select>
                            </div>
                        </x-slot:prependSlot>
                    </x-adminlte-input>
                     <div name="" id="" class="bg-white p-4">
                        <ul>
                            <li>
                                     (63) 9 9999-9999  (OBS)
                            </li>
                            <li>
                                   (63) 9 9999-9999  (OBS) <i class="fa-brands fa-whatsapp"></i>  
                            </li>
                        </ul>
                       
                        
                     </div>
                </div>
        </div>
    </div>
</x-app>