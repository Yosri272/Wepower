<?php

namespace App\Enums;

enum Roles: string
{
    case ROOT = 'root';
    case ADMIN = 'admin';
    case SHOP = 'shop';
    case CUSTOMER = 'customer';
    case VISITOR = 'visitor';
    case DRIVER = 'driver';
}
