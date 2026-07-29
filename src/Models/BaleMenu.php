<?php

namespace Bale\Cms\Models;

use Bale\Core\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaleMenu extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $fillable = [
        'bale_id',
        'name',
        'slug',
        'icon',
        'url',
        'order',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime:d M Y',
        'updated_at' => 'datetime:d M Y',
    ];

    /**
     * Relasi ke Bale.
     */
    public function bale()
    {
        return $this->belongsTo(BaleList::class, 'bale_id');
    }
}
