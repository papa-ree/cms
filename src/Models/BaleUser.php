<?php

namespace Bale\Cms\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BaleUser extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [
        'id'
    ];

    public function bale()
    {
        return $this->belongsTo(BaleList::class, 'bale_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid');
    }
}
