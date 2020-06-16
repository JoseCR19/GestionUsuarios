<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class AsignarEtapaProyecto extends Model 
{
    

    public $timestamps = false;

   protected $table = 'asig_etap_proy';
   protected $primaryKey = 'intIdAsigEtapProy';
   protected $fillable = [
      'intIdProy',
      'intIdTipoProducto',
      'intIdEtapa',
      'intOrden',
      'acti_usua',
      'acti_hora',
      'usua_modi',
      'hora_modi'
    
  
  ];
}
