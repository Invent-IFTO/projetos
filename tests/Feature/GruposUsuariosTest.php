<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GruposUsuariosTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionsSeeder::class);
        // Additional setup code can go here
    }

    /**
     * A basic feature test example.
     */

    const ROUTE_PREFIX = 'administracao.grupos.';

    const URL_PREFIX = 'administracao/grupos/';


    public function test_bloquear_tela_listar_grupos_sem_login(): void
    {
        $response = $this->get(route(self::ROUTE_PREFIX . 'listar'));
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
    public function test_bloquear_tela_listar_grupos_logado_sem_permissao(): void
    {
        $response = $this->actingAs(User::factory()->create())->get(route(self::ROUTE_PREFIX . 'listar'));
        $response->assertStatus(403);
    }
    public function test_permitir_tela_listar_grupos_logado_com_permissao(): void
    {
        $user = User::factory()->create();
        $user->syncPermissions(['Grupos: listar']);
        $response = $this->actingAs($user )->get(route(self::ROUTE_PREFIX . 'listar'));
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }

    public function test_bloquear_tela_criar_grupos_nao_logado(): void
    {
        $response = $this->get(route(self::ROUTE_PREFIX . 'criar'));
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_bloquear_tela_criar_grupos_logado_sem_permissao(): void
    {
        $response = $this->actingAs(User::factory()->create())->get(route(self::ROUTE_PREFIX . 'criar'));
        $response->assertStatus(403);
    }

    public function test_permitir_tela_criar_grupos_logado_com_permissao(): void
    {
        $user = User::factory()->create();
        $user->syncPermissions(['Grupos: listar','Grupos: criar']);
        $response = $this->actingAs($user )->get(route(self::ROUTE_PREFIX . 'criar'));
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }

    public function test_bloquear_inserir_grupo_nao_logado(): void
    {
        $response = $this->post(route(self::ROUTE_PREFIX . 'salvar'), [
            'name' => 'Teste Grupo',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_bloquear_inserir_grupo_logado_sem_permissao(): void
    {
        $response = $this->actingAs(User::factory()->create())->post(route(self::ROUTE_PREFIX . 'salvar'), [
            'name' => 'Teste Grupo',
        ]);
        $response->assertStatus(403);
    }

    public function test_permitir_inserir_grupo_logado_com_permissao(): void
    {
        $user = User::factory()->create();
        $user->syncPermissions(['Grupos: listar','Grupos: criar']);
        $response = $this->actingAs($user )->post(route(self::ROUTE_PREFIX . 'salvar'), [
            'name' => 'Teste Grupo',
            'permissions' => Permission::all()->pluck('name')->toArray(),
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('roles', [
            'name' => 'Teste Grupo',
        ]);
        $this->assertDatabaseCount('role_has_permissions', Permission::count());
    }

    public function test_bloquear_inserir_grupo_logado_com_permissao_sem_name(): void
    {
        $user = User::factory()->create();
        $user->syncPermissions(['Grupos: listar','Grupos: criar']);
        $response = $this->actingAs($user )->post(route(self::ROUTE_PREFIX . 'salvar'), [
            'name' => '', // Nome inválido
            'permissions' => Permission::all()->pluck('name')->toArray(),
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }

    public function test_bloquear_inserir_grupo_logado_com_permissao_sem_permissions(): void
    {
        $user = User::factory()->create();
        $user->syncPermissions(['Grupos: listar','Grupos: criar']);
        $response = $this->actingAs($user )->post(route(self::ROUTE_PREFIX . 'salvar'), [
            'name' => 'Teste Grupo',
            'permissions' => [], // Sem permissões
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['permissions']);
        $this->assertDatabaseMissing('roles', [
            'name' => 'Teste Grupo',
        ]);

    }


    public function test_bloquear_tela_editar_grupo_nao_logado(): void
    {
        $response = $this->get(route(self::ROUTE_PREFIX . 'editar', ['grupo' => Role::create(['name' => 'Grupo Teste'])]));
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_bloquear_tela_editar_grupo_logado_sem_permissao(): void
    {
        $response = $this->actingAs(User::factory()->create())->get(route(self::ROUTE_PREFIX . 'editar', ['grupo' => Role::create(['name' => 'Grupo Teste'])]));
        $response->assertStatus(403);
    }

    public function test_permitir_tela_editar_grupo_logado_com_permissao(): void
    {
        $user = User::factory()->create();
        $user->syncPermissions(['Grupos: listar','Grupos: editar']);
        $response = $this->actingAs($user )->get(route(self::ROUTE_PREFIX . 'editar', ['grupo' => Role::create(['name' => 'Grupo Teste'])]));
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }

    public function test_bloquear_atualizar_grupo_nao_logado(): void
    {
        $grupo = Role::create(['name' => 'Grupo Teste']);
        $response = $this->put(route(self::ROUTE_PREFIX . 'atualizar', ['grupo' => $grupo]), [
            'name' => 'Nome Atualizado',
            'permissions' => Permission::all()->pluck('name')->toArray(),
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
    public function test_bloquear_atualizar_grupo_logado_sem_permissao(): void
    {
        $grupo = Role::create(['name' => 'Grupo Teste']);
        $response = $this->actingAs(User::factory()->create())->put(route(self::ROUTE_PREFIX . 'atualizar', ['grupo' => $grupo]), [
            'name' => 'Nome Atualizado',
            'permissions' => Permission::all()->pluck('name')->toArray(),
        ]);
        $response->assertStatus(403);
    }

    public function test_permitir_atualizar_grupo_logado_com_permissao(): void
    {
        $user = User::factory()->create();
        $user->syncPermissions(['Grupos: editar', 'Grupos: listar']);
        $grupo = Role::create(['name' => 'Grupo Teste']);
        $response = $this->actingAs($user )->put(route(self::ROUTE_PREFIX . 'atualizar', ['grupo' => $grupo]), [
            'name' => 'Nome Atualizado',
            'permissions' => Permission::all()->pluck('name')->toArray(),
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('roles', [
            'id' => $grupo->id,
            'name' => 'Nome Atualizado',
        ]);
        $this->assertDatabaseCount('role_has_permissions', Permission::count());
    }

    public function test_bloquear_atualizar_grupo_logado_com_permissao_sem_name(): void
    {
        $user = User::factory()->create();
        $user->syncPermissions(['Grupos: listar','Grupos: editar']);
        $grupo = Role::create(['name' => 'Grupo Teste']);
        $response = $this->actingAs($user )->put(route(self::ROUTE_PREFIX . 'atualizar', ['grupo' => $grupo]), [
            'name' => '', // Nome inválido
            'permissions' => Permission::all()->pluck('name')->toArray(),
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }

    public function test_bloquear_atualizar_grupo_logado_com_permissao_sem_permissions(): void
    {
        $user = User::factory()->create();
        $user->syncPermissions(['Grupos: listar','Grupos: editar']);
        $grupo = Role::create(['name' => 'Grupo Teste']);
        $response = $this->actingAs($user )->put(route(self::ROUTE_PREFIX . 'atualizar', ['grupo' => $grupo]), [
            'name' => 'Nome Atualizado',
            'permissions' => [], // Sem permissões
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['permissions']);
        $this->assertDatabaseHas('roles', [
            'id' => $grupo->id,
            'name' => $grupo->name, // Nome não alterado
        ]);

    }

    public function test_bloquear_deletar_grupo_nao_logado(): void
    {
        $grupo = Role::create(['name' => 'Grupo Teste']);
        $response = $this->delete(route(self::ROUTE_PREFIX . 'deletar', ['grupo' => $grupo]));
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_bloquear_deletar_grupo_logado_sem_permissao(): void
    {
        $grupo = Role::create(['name' => 'Grupo Teste']);
        $response = $this->actingAs(User::factory()->create())->delete(route(self::ROUTE_PREFIX . 'deletar', ['grupo' => $grupo]));
        $response->assertStatus(403);
    }

    public function test_permitir_deletar_grupo_logado_com_permissao(): void
    {
        $user = User::factory()->create();
        $user->syncPermissions(['Grupos: listar','Grupos: deletar']);
        $grupo = Role::create(['name' => 'Grupo Teste']);
        $response = $this->actingAs($user )->delete(route(self::ROUTE_PREFIX . 'deletar', ['grupo' => $grupo]));
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('roles', [
            'id' => $grupo->id,
        ]);
    }

    public function test_bloquear_deletar_proprio_grupo_logado_com_permissao(): void
    {
        $user = User::factory()->create();
        $user->syncPermissions(['Grupos: listar','Grupos: deletar']);
        $grupo = Role::create(['name' => 'Grupo Teste']);
        $user->assignRole($grupo);
        $response = $this->actingAs($user )->delete(route(self::ROUTE_PREFIX . 'deletar', ['grupo' => $grupo]));
        $response->assertStatus(302);
        $response->assertSessionHas('warning');
        $this->assertDatabaseHas('roles', [
            'id' => $grupo->id,
        ]);
    }


}
