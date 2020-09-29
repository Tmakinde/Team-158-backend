<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'email', 'username','status', 'logindate', 'state_id',
    ];

    public function investors(){
        return $this->belongsTo(Investor::class);
    }

}
