<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'Pending';
    case PAID = 'Paid';
}
