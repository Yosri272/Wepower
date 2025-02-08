<?php

namespace App\Enums;

enum LegalPages: string
{
    case PRIVACYPOLICY = 'Privacy Policy';
    case TERMSANDCONDITIONS = 'Terms and Conditions';
    case RETURNANDREFUND = 'Return and Refund Policy';
    case SHIPPINGANDDELIVERY = 'Shipping and Delivery Policy';
    case ABOUTUS = 'About Us';
}
