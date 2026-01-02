<?php

namespace Bale\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BaleMenu extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'bale_id',
        'name',
        'slug',
        'icon',
        'url',
        'order',
        'is_active',
    ];

    /**
     * Relasi ke Bale.
     */
    public function bale()
    {
        return $this->belongsTo(BaleList::class, 'bale_id');
    }
}
