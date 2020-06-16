<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class UsuarioPrograma extends Model 
{
   public $timestamps = false;

   protected $table = 'usuario_programa';
 
   protected $fillable = [
      'IntIdUsua',
      'intIdSoft',
      'varCodiUsua',
      'intIdProg',
      'acti_usua',
      'acti_hora',
     
  ];
}
