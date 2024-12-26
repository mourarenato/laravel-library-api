<?php

namespace App\Domain\Entities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'id',
        'email',
        'password',
        'name',
    ];
    protected array $sensitiveAttributes = [
        'email',
        'password',
    ];

    protected static function booted(): void
    {
        static::updating(function ($user) {
            $user->encryptAttributes();
        });

        static::creating(function ($user) {
            $user->encryptAttributes();
        });
    }

    public function encryptAttributes(): void
    {
        foreach ($this->sensitiveAttributes as $attribute) {
            if (isset($this->attributes[$attribute]) && $attribute == 'password') {
                $this->attributes[$attribute] = bcrypt($this->attributes[$attribute]);
            }
        }
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
