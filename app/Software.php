<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Software extends Model 
{
  public $timestamps = false;

   protected $table = 'software';
   protected $primaryKey = 'intIdSoft';
   protected $fillable = [
      'varNombSoft', 
      'varEstaSoft',
      'acti_usua',
      'acti_hora',
 
  ];
}
