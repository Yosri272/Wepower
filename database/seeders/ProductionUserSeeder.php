<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->createRootUser();
        $this->createAdminUser();
        $this->createVisitorUser();
    }

    private function createRootUser()
    {
        $rootUser = User::factory()->create([
            'name' => 'Root',
            'email' => 'root@readyecommerce.com',
            'password' => Hash::make('secret'),
            'phone' => '01000000001',
            'is_active' => true,
        ]);

        $rootUser->assignRole(Roles::ROOT->value);
    }

    private function createAdminUser()
    {
        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@readyecommerce.com',
            'password' => Hash::make('secret'),
            'phone' => '01000000002',
            'is_active' => true,
        ]);

        $adminUser->assignRole(Roles::ADMIN->value);
    }

    private function createVisitorUser()
    {
        $visitorUser = User::factory()->create([
            'name' => 'Visitor',
            'email' => 'visitor@readyecommerce.com',
            'password' => Hash::make('secret'),
            'phone' => '01000000003',
        ]);

        $visitorUser->assignRole(Roles::VISITOR->value);
    }
}
