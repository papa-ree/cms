<?php

namespace Bale\Cms\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BaleList extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    /**
     * Relasi ke organisasi induk.
     */
    public function organization()
    {
        return $this->belongsTo(BaleOrganization::class, 'organization_id');
    }

    /**
     * Relasi ke user yang memiliki akses ke Bale ini.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'bale_content_management_bale_user', 'bale_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Relasi ke menu CMS milik Bale ini.
     */
    public function menus()
    {
        return $this->hasMany(BaleMenu::class, 'bale_id');
    }
}
