<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Usuario extends Model 
{
    

   protected $table = 'usuario';
   protected $primaryKey = 'intIdUsua';
   protected $fillable = [
      'varNumeDni',
      'varNombUsua',
      'varApelUsua',
      'varCodiUsua',
      'varClavUsua',
      'varEstaUsua',
      'varCorrUsua',
      'varTelfUsua',
      'acti_usua',
      'acti_hora',
      'client_id',
      'intIdUsuaToke',
      'varSecrUsua',
      'updated_at',
      'created_at',
      'usua_modi',
      'hora_modi',
  ];
}
