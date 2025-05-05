<?php

namespace App\Enums;

enum RuleContext: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case PASSWORD_CHANGE = 'password_change';
   
    
}