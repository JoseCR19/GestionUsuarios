<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class DetalleNotificacion extends Model 
{
     public $timestamps = false;

   protected $table = 'deta_noti';
 
   protected $fillable = [
       'intIdNoti',
       'varUsuaNoti',
       'dateNoti',
       'intIdEsta',
        'acti_usua',
       'acti_hora',
       'hora_leid'
       
  ];
}
