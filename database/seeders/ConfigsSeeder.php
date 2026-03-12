<?php

namespace Database\Seeders;

use App\Models\Projeto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Projeto::setConfig('Convite expira em quantas horas', 'hours_expire', 48);
    }
}
