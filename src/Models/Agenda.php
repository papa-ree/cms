<?php

namespace Bale\Cms\Models;

use Bale\Cms\Services\AgendaRecurrenceService;
use Bale\Cms\Traits\UsesTenantConnection;
use Bale\Core\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasUuids;
    use LogsActivity;
    use UsesTenantConnection;

    /**
     * Setiap tenant memiliki tabel 'agendas'.
     */
    protected $table = 'agendas';

    /**
     * Kolom yang dikecualikan dari mass assignment.
     */
    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'datetime:d M Y H:i',
        'end_date' => 'datetime:d M Y H:i',
        'all_day' => 'boolean',
        'is_recurring' => 'boolean',
        'is_visible' => 'boolean',
        'created_at' => 'datetime:d M Y',
        'updated_at' => 'datetime:d M Y',
    ];

    /**
     * Ringkasan aturan ulang untuk ditampilkan di tabel.
     * Delegasi ke AgendaRecurrenceService; satu sumber kebenaran RRULE.
     */
    protected function recurrenceSummary(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->is_recurring && $this->rrule
                ? app(AgendaRecurrenceService::class)->describe($this->rrule, $this->start_date)
                : __('Sekali jalan'),
        );
    }
}
