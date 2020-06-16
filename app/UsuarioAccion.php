<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class UsuarioAccion extends Model 
{
     public $timestamps = false;

   protected $table = 'usuario_accion';
 
   protected $fillable = [
      'intIdUsua',
       'varCodiUsua',
       'intIdProg',
       'intIdBoton',
       'acti_usua',
       'acti_hora'
       
  ];
}
