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
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Joaquim Martins Scavone',
            'email' => 'joaquim.scavone@ifto.edu.br',
            'password' => Hash::make(value: 'projetos@ifto'),
        ]);

        Projeto::create([
            'titulo' => 'IService',
            'descricao' => 'Este é um projeto de exemplo para demonstração.',
            'criador_id' => $user->id,
        ]);

        ProjetoEquipe::create([
            'projeto_id' => 1,
            'descricao' => 'Equipe de Gestão',]);
        ProjetoMembro::create([
            'equipe_id' => 1,
            'projeto_id' => 1,
            'user_id' => $user->id,
            'lider_equipe' => true,
        ]);
        $this->call(PermissionsSeeder::class);
        Role::create(['name' => 'Desenvolvedor'])->givePermissionTo(Permission::all());
        $user->syncRoles(['Desenvolvedor']);
    }
}
