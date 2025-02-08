<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialLink::truncate();

        $data = [
            [
                'link' => null,
                'logo' => '/assets/icons/Facebook.svg',
                'name' => 'Facebook',
            ],
            [
                'link' => 'https://www.linkedin.com/company/razinsoft',
                'logo' => '/assets/icons/LinkedIn.svg',
                'name' => 'LinkedIn',
            ],
            [
                'link' => null,
                'logo' => '/assets/icons/Instagram.svg',
                'name' => 'Instagram',
            ],
            [
                'link' => 'https://www.youtube.com/@razinsoft',
                'logo' => '/assets/icons/YouTube.svg',
                'name' => 'YouTube',
            ],
        ];

        SocialLink::insert($data);
    }
}
