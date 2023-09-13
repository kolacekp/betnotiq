<?php

namespace App\Models\Enums;

class RoleMapper
{
    public static function map(Roles $role)
    {
        switch ($role){
            case Roles::User:
                return "Tipér";
            case Roles::Admin:
                return "Admin";
            case Roles::Manager:
                return "Manager";
        }
    }
}
