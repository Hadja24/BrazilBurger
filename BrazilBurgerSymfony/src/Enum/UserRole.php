<?php

namespace App\Enum;

enum UserRole: string
{
    case CUSTOMER = 'CUSTOMER';
    case MANAGER = 'MANAGER';
    case DELIVERY_GUY = 'DELIVERY_GUY';
}