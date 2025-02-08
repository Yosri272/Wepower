<?php

namespace App\Enums;

enum DiscountType: string
{
    case AMOUNT = 'Amount';
    case PERCENTAGE = 'Percentage';
}
