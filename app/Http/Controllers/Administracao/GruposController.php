<?php

namespace App\Http\Controllers\Administracao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class GruposController extends Controller
{

    public function listar()
    {
        return view('administracao.grupos.listar', ['grupos' => Role::all()]);
    }
    /**
     * Show the form for creating the resource.
     */
    public function criar()
    {
        return view('administracao.grupos.criar', ['grupos' => Role::all()]);
    }


    /**
     * Store the newly created resource in storage.
     */
    public function salvar(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:250|unique:roles,name',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string|exists:permissions,name'
        ], [
            'name.required' => 'O nome do grupo é obrigatório.',
            'name.min' => 'O nome do grupo deve ter pelo menos 3 caracteres.',
            'name.max' => 'O nome do grupo não pode exceder 250 caracteres.',
            'name.unique' => 'Já existe um grupo com este nome.',
            'permissions.required' => 'Você deve selecionar pelo menos uma permissão para o grupo.',
            'permissions.min' => 'Você deve selecionar pelo menos uma permissão para o grupo.',
            'permissions.*.exists' => 'Uma das permissões selecionadas não é válida.'
        ]);

        $grupo = Role::create(['name' => $request->name]);
        $grupo->syncPermissions($request->permissions);
        return redirect()->route('administracao.grupos.listar')->with('success', 'Grupo criado com sucesso!');

    }

    /**
     * Show the form for editing the resource.
     */
    public function editar(Role $grupo)
    {
        return view('administracao.grupos.editar', ['grupos' => Role::all(), 'grupo' => $grupo]);
    }

    /**
     * Update the resource in storage.
     */
    public function atualizar(Role $grupo, Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:250|unique:roles,name,' . $grupo->id,
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string|exists:permissions,name'
        ], [
            'name.required' => 'O nome do grupo é obrigatório.',
            'name.min' => 'O nome do grupo deve ter pelo menos 3 caracteres.',
            'name.max' => 'O nome do grupo não pode exceder 250 caracteres.',
            'name.unique' => 'Já existe um grupo com este nome.',
            'permissions.required' => 'Você deve selecionar pelo menos uma permissão para o grupo.',
            'permissions.min' => 'Você deve selecionar pelo menos uma permissão para o grupo.',
            'permissions.*.exists' => 'Uma das permissões selecionadas não é válida.'
        ]);
        
        $grupo->name = $request->name;
        $grupo->save();
        $grupo->syncPermissions($request->permissions);
        return redirect()->route('administracao.grupos.listar')->with('success', 'Grupo alterado com sucesso!');

    }

    /**
     * Remove the resource from storage.
     */
    public function deletar(Role $grupo)
    {
        $authUser = auth()->user();
        if($authUser->hasRole($grupo->name) && $authUser->roles->count() == 1){
            return redirect()->route('administracao.grupos.listar')->with('warning', 'Não é possível deletar o grupo, pois você está nele e ele é seu único grupo ativo!');
        }
        $grupo->delete();
        return redirect()->route('administracao.grupos.listar')->with('success', 'Grupo deletado com sucesso!');
    }
}
