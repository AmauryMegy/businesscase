<?php

namespace App\Enumeration;

enum RoleList: string
{
    case ROLE_USER = 'ROLE_USER';
    case ROLE_STATS = 'ROLE_STATS';
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_BANNED = 'ROLE_BANNED';
}
