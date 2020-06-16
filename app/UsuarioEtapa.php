<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class UsuarioEtapa extends Model 
{
  public $timestamps = false;

   protected $table = 'usuario_etapa';

   protected $fillable = [
      'intIdUsua',
      'intIdEtapa',

      'varCodiUsua',
      'acti_usua',
      'acti_hora',
   
     
  ];
}
