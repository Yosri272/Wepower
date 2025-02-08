<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'Pending';
    case CONFIRM = 'Confirm';
    case PROCESSING = 'Processing';
    case PICKUP = 'Pickup';
    case ON_THE_WAY = 'On The Way';
    case DELIVERED = 'Delivered';
    case CANCELLED = 'Cancelled';
}
