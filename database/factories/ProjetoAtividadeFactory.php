<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjetoAtividade>
 */
class ProjetoAtividadeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'projeto_membro_id' => \App\Models\ProjetoMembro::factory(),
            'anterior' => $this->faker->paragraph(),
            'atual' => $this->faker->paragraph(),
            'problemas' => $this->faker->optional()->paragraph(),
            'status' => $this->faker->randomElement(['pendente', 'verificada']),
            'avaliacao' => $this->faker->optional()->randomFloat(2,0,100),
        ];
    }
}
