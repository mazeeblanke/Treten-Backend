<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // App\User::unsetEventDispatcher();
        $role = Role::firstOrCreate(['name' => 'admin'], ['name' => 'admin']);
        $user = factory(App\User::class)->create([
            'email' => 'admin@treten.com',
            'status' => 'active'
        ]);

        $user->assignRole($role);
    }
}
