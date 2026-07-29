<?php

namespace Bale\Cms\Models;

use App\Models\User;
use Bale\Core\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaleUser extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'created_at' => 'datetime:d M Y',
        'updated_at' => 'datetime:d M Y',
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
