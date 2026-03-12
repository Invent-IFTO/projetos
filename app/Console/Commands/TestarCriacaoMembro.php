<?php

namespace App\Console\Commands;

use App\Models\ProjetoMembro;
use Illuminate\Console\Command;

class TestarCriacaoMembro extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membro:testar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa a criação de membro para verificar se não há loop';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Iniciando teste de criação de membro...');
            
            // Simular dados de teste
            $dados = [
                'user_id' => 1,
                'projeto_id' => 1,
                'equipe_id' => 1,
                'lider_equipe' => true,
                'gerente_projeto' => false,
            ];
            
            $this->info('Dados para criação: ' . json_encode($dados));
            
            // Tentar criar o membro
            $membro = ProjetoMembro::create($dados);
            
            $this->info('✅ Membro criado com sucesso! ID: ' . $membro->id);
            
            // Limpar o teste
            $membro->delete();
            $this->info('🗑️ Registro de teste removido.');
            
        } catch (\Exception $e) {
            $this->error('❌ Erro ao criar membro: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
        }
    }
}