<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(LegalPageSeeder::class);
        $this->call(PaymentGatewaySeeder::class);
        $this->call(SocialLinkSeeder::class);

        if (app()->environment('local')) {
            $this->call(UserSeeder::class);
            $this->call(CustomerSeeder::class);
            $this->call(CategorySeeder::class);
            $this->call(ShopSeeder::class);
            $this->call(BrandSeeder::class);
            $this->call(SizeSeeder::class);
            $this->call(ColorSeeder::class);
            $this->call(ProductSeeder::class);
            $this->call(BannerSeeder::class);
            $this->call(CouponSeeder::class);
            $this->call(AddressSeeder::class);
            $this->call(OrderSeeder::class);
            $this->call(ReviewSeeder::class);
            $this->call(FavoriteSeeder::class);
            $this->call(WalletSeeder::class);
        } else {
            $this->call(ProductionUserSeeder::class);
        }
        $this->call(AdminShopSeeder::class);
        $this->call(WalletSeeder::class);
        $this->command->info('Database seeded successfully');

        if (app()->environment('local')) {
            $this->userInfo();
        }
    }

    private function userInfo()
    {
        // info for root user in command line
        $this->command->line('');
        $this->command->info('Root user created:');
        $this->command->warn('- Email: root@readyecommerce.com');
        $this->command->warn('- Password: secret');
        $this->command->info('');

        //info for shop user in command line
        $this->command->info('Shop created:');
        $this->command->warn('- Email: shop@readyecommerce.com');
        $this->command->warn('- Password: secret');
    }
}
