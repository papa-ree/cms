<?php

namespace Bale\Cms\Models;

use Bale\Cms\Traits\UsesTenantConnection;
use Bale\Core\Traits\LogsActivity;
use Bale\Core\Traits\LogsRoleChanges;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use LogsRoleChanges, LogsActivity, Notifiable, UsesTenantConnection;

    protected $table = 'users';

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime:d M Y',
        'updated_at' => 'datetime:d M Y',
    ];
}
