<?php

namespace Bale\Cms\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Bale\Cms\Traits\UsesTenantConnection;

class User extends Authenticatable
{
    use Notifiable, HasRoles, UsesTenantConnection;

    protected $table = 'users';

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'date',
    ];
}
