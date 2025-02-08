<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\User;
use App\Repositories\CustomerRepository;
use Illuminate\Database\Seeder;

class UserUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createRootUser();
        $this->createAdminUser();
        $this->createVisitorUser();
        $this->createUser();
        $this->updateShop();
        $this->updateRider();
    }

    private function createRootUser()
    {
        $user = User::where('email', 'root@example.com')->first();

        if ($user) {
            $user->update([
                'email' => 'root@readyecommerce.com',
                'is_active' => true,
            ]);
        }
    }

    private function createAdminUser()
    {
        $adminUser = User::where('email', 'admin@example.com')->first();

        if ($adminUser) {
            $adminUser->update([
                'email' => 'admin@readyecommerce.com',
                'is_active' => true,
            ]);
        }
    }

    private function createVisitorUser()
    {
        $visitorUser = User::where('email', 'visitor@example.com')->first();

        if ($visitorUser) {
            $visitorUser->update([
                'email' => 'visitor@readyecommerce.com',
                'is_active' => true,
            ]);
        }
    }

    private function createUser()
    {
        $user = User::where('email', 'user@example.com')->first();

        if ($user) {
            $user->update([
                'email' => 'user@readyecommerce.com',
                'is_active' => true,
            ]);
        } else {
            $user = User::where('email', 'user@readyecommerce.com')->first();

            if (! $user) {
                $user = User::factory()->create([
                    'name' => 'Demo User',
                    'email' => 'user@readyecommerce.com',
                    'phone' => '01000000405',
                    'is_active' => true,
                ]);
            } else {
                $user->update([
                    'phone' => '01000000405',
                    'is_active' => true,
                ]);
            }

            $user->assignRole(Roles::CUSTOMER->value);

            CustomerRepository::storeByRequest($user);
        }
    }

    private function updateShop()
    {
        $user = User::where('email', 'amazon@example.org')->first();

        if ($user) {
            $user->update([
                'email' => 'shop@readyecommerce.com',
                'is_active' => true,
            ]);
        }
    }

    private function updateRider()
    {
        $user = User::where('phone', '01234567899')->first();
        if ($user) {
            $user->update([
                'phone' => '01700000000',
                'is_active' => true,
            ]);
        }
    }
}
