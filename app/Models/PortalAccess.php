<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class PortalAccess extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'portal_access';

    // 👇 Tell Laravel the primary key is 'user_id', not the default 'id'
    protected $primaryKey = 'user_id';

    // 👇 Disable default timestamp columns (created_at, updated_at)
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'user_type',
        'name',
        'email',
        'password',
        'phone_no',
        'outlet_code',
        'gender',
        'auth_token',
        'device_token',
        'reset_code',
        'status',
        'created_date',
        'modify_date',
    ];

    /**
     * The attributes that should be hidden for arrays or JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'created_date' => 'datetime',
            'modify_date' => 'datetime',
        ];
    }
}
