<?php

namespace App\Models\Enums;

enum Roles: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case User = 'user';
}
