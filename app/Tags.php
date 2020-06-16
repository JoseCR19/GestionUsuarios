<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Tags extends Model 
{
     public $timestamps = false;

   protected $table = 'tags_noti';
   protected $primaryKey = 'intIdTags';
 
   protected $fillable = [
       'varDescTags',
       'varPropTags',
       'acti_usua',
       'acti_hora',
       'varPublTags'
       
       
  ];
}
