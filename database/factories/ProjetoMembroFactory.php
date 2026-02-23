<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjetoMembro>
 */
class ProjetoMembroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'projeto_id' => \App\Models\Projeto::factory(),
            'equipe_id' => \App\Models\ProjetoEquipe::factory(),
            'user_id' => \App\Models\User::factory(),
            'lider_equipe' => $this->faker->boolean(20), // 20% chance de ser líder de equipe
            'lider_projeto' => $this->faker->boolean(10), // 10% chance de ser líder de projeto
        ];
    }
}
