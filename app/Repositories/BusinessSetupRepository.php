<?php

namespace App\Repositories;

use Abedin\Boiler\Repositories\Repository;
use App\Models\BusinessSetup;

class BusinessSetupRepository extends Repository
{
    public static function model()
    {
        return BusinessSetup::class;
    }
}
