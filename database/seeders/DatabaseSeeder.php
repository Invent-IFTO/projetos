<?php

namespace Database\Seeders;

use App\Models\Projeto;
use App\Models\ProjetoEquipe;
use App\Models\ProjetoMembro;
use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        for($i = 1; $i < 10; $i++){
            User::factory()->create(["email" => "user$i@example.com"]);
        }

        $user = User::factory()->create([
            'name' => 'Joaquim Martins Scavone',
            'email' => 'joaquim.scavone@ifto.edu.br',
        ]);

        // $user = User::find(7);

        // Projeto::create([
        //     'titulo' => 'IService',
        //     'descricao' => 'Este é um projeto de exemplo para demonstração.',
        //     'criador_id' => $user->id,
        // ]);

        // ProjetoEquipe::create([
        //     'projeto_id' => 1,
        //     'descricao' => 'Equipe de Gestão',]);

        // ProjetoMembro::create([
        //     'equipe_id' => 1,
        //     'projeto_id' => 1,
        //     'user_id' => $user->id,
        //     'lider_equipe' => true,
        // ]);
        $this->call(PermissionsSeeder::class);
        Role::findOrCreate('Gerente')->givePermissionTo(Permission::all());
        
        Role::findOrCreate('Aluno');
        foreach($user::all() as $cliente){
                $cliente->syncRoles(['Aluno']);
                // ProjetoMembro::create([
                // 'equipe_id' => 1,
                // 'projeto_id' => 1,
                // 'user_id' => $user->id,
                // 'lider_equipe' => true,
                // ]);
        }
        $user->syncRoles(['Gerente']);
        $this->call(ConfigsSeeder::class);
    }
}
