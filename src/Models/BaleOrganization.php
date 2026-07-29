<?php

namespace Bale\Cms\Models;

use App\Models\User;
use Bale\Core\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaleOrganization extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime:d M Y',
        'updated_at' => 'datetime:d M Y',
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
