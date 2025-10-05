<?php

namespace App\Enums;

enum State: string
{
    case UPDATE = 'update';
    case CREATE = 'create';
    case SHOW = 'show';
    case LISTDATA = 'listdata';
    case KOMENTAR = 'komentar';
}
