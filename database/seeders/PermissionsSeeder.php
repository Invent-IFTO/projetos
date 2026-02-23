<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = config('systemPermissions');
        foreach ($permissions as $key => $permission) {
            foreach($permission as $subPermission) {
                \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $key . ': ' . $subPermission]);
            }
           
        }
    }
}
