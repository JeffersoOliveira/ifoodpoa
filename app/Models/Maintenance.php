<?php

namespace App\Models;

use App\Enums\MaintenanceStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'bike_id',
        'attendant_id',
        'maintenance_date',
        'maintenance_time',
        'description',
        'status',
        'type',
        'started_at'
    ];

    protected $casts = [
        'status' => MaintenanceStatusEnum::class,
        'started_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        //        'check_list' => 'json',
    ];


    public function attendant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'attendant_id', 'id');
    }

    public function bike(): BelongsTo
    {
        return $this->belongsTo(
            Bike::class,
            'bike_id',
            'id'
        );
    }


    public function checkList(): HasOne
    {
        return $this->hasOne(ChecklistItem::class);
    }

    public function repair(): HasOne
    {
        return $this->hasOne(Repair::class);
    }

    public static function canSchedule($date, $time): bool
    {
        return self::where('maintenance_date', $date)->where('maintenance_time', $time)->count() < 2;
    }

}
