<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class, 'customer_id');
    }

    public function generateToken(string $name)
    {
        return $this->createToken($name)->plainTextToken;
    }
}
