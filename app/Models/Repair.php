<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{

    protected $fillable = [
        'repaired',
        'mechanic_id'
    ];

    protected $casts = [
        'repaired' => 'json',
    ];
    //

    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id', 'id');
    }
}
