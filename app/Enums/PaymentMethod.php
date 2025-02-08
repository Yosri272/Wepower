<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'Cash Payment';
    case ONLINE = 'Online Payment';
    case STRIPE = 'Stripe';
    case PAYPAL = 'PayPal';
    case RAZORPAY = 'Razorpay';
    case PAYSTACK = 'PayStack';
    case AAMARPAY = 'Amarpay';
    case BKASH = 'Bkash';
    case PAYTABS = 'PayTabs';
}
