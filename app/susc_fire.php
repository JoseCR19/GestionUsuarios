<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class susc_fire extends Model {

    public $timestamps = false;
    protected $table = 'susc_fire';
    protected $primaryKey = 'intIdFire';
    protected $fillable = [
        'susc_usua'
    ];
}
