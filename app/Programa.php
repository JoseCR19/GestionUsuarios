<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Programa extends Model 
{
  public $timestamps = false;

   protected $table = 'programas';
   protected $primaryKey = 'intIdProg';
   protected $fillable = [
      'varNombProg',
      'intIdSoft',
      'varCodiProg',
      'varPadrProg',
      'intTamaProg',
      'varClicProg',
      'varEstaProg',
      'varRutaProg',
      'varIconProg',
      'acti_usua',
      'acti_hora',
      'varPublProg',
      'usua_modi',
      'hora_modi'
     
  ];
}
