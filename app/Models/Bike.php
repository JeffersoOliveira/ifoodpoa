<?php

namespace App\Models;

use App\Enums\BikeStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bike extends Model
{
    /** @use HasFactory<\Database\Factories\BikeFactory> */
    use HasFactory;

    protected $fillable = [
        "patrimony",
        "status",
        "series"
    ];

    protected $casts = [
        "status" => BikeStatusEnum::class,
    ];

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

}
