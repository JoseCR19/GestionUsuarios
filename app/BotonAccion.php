<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class BotonAccion extends Model 
{
    
 public $timestamps = false;
   protected $table = 'boton_accion';
 
   protected $fillable = [
      'intIdBoton',
       'intIdProg',
        'intIdSoft',
       'varDescBoto',
       'acti_usua',
       'acti_hora'
       
  ];
}
