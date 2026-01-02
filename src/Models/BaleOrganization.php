<?php

namespace Bale\Cms\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BaleOrganization extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'created_by',
    ];

    /**
     * Relasi ke Bale.
     * Satu organisasi memiliki banyak Bale.
     */
    public function bales()
    {
        return $this->hasMany(BaleList::class, 'organization_id');
    }

    /**
     * Relasi ke user pembuat organisasi.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
