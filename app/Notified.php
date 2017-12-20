<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notified extends Model
{
    //
    protected $table = 'notified';
    
    public $timestamps = false;

    const CREATED_AT = 'notified';
}
