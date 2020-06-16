<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class DetalleTags extends Model 
{
     public $timestamps = false;

   protected $table = 'deta_tags';
 
   protected $fillable = [
       'intIdTags',
       'codi_usua',
       'acti_usua',
       'acti_hora',

  ];
}
