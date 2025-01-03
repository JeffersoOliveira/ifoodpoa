<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'system'
    ];

    // protected $guarded = [
    //     'password'
    // ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            // 'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'user_role',
            'user_id',
            'role_id'
        )->withPivot([
                    'user_id',
                    'role_id',
                ]);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class, 'attendant_id');
    }

    public function repaired(): HasMany
    {
        return $this->hasMany(Maintenance::class, 'mechanic_id');
    }


    /*
     * ACL
     */
    public function hasAnyRoles($roles): bool
    {
        if (is_object($roles)) {
            return !!$roles->intersect($this->roles)->count();
        }
        return $this->roles->contains('name', $roles);
    }

    public function hasPermission(Permission $permission): bool
    {
        return $this->hasAnyRoles($permission->roles);
    }

}
