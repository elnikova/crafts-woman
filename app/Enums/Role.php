<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Master = 'master';
    case Buyer = 'buyer';
}
