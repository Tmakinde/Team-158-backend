<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'email', 'username','status', 'logindate', 'state_id',
    ];
}
