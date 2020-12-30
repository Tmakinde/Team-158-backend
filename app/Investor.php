<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Investor extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'first_name', 'last_name', 'email', 'username','status', 'logindate', 'state_id',
    ];

    public function farmers(){
        return $this->hasMany(Farmer::class, 'investors_id');
    }

    public function getJWTIdentifier(){
        return $this->getkey();
    }

    public function getJWTCustomClaims(){
        return [];
    }
    
}
