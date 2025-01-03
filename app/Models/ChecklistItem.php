<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $fillable =
        [
            // 'maintenance_id',
            'check_list',
            'description',
        ];

    protected $casts = [
        'check_list' => 'json',
    ];

    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class);
    }

}
