<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Etapa extends Model 
{
    
   public $timestamps = false;
   
   protected $table = 'etapa';
   protected $primaryKey = 'intIdEtapa';
   protected $fillable = [
      'intIdTipoEtap',
      'varValoEtapa',
      'intIdProc',
      'varDescEtap',
      'intIdTipoProducto',
      'intIdUniMedi',
      'intIdPlan',
      'boolDesp',
      'varEstaEtap',
      'boolMostMaqu',
      'boolMostSupe',
      'boolMostCont',
      'acti_usua',
      'acti_hora',
      'usua_modi',
      'hora_modi'
    
   
  ];
}
