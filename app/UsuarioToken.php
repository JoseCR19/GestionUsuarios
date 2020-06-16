<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class UsuarioToken extends Model 
{
    

    protected $table = 'oauth_clients'; //UsuarioToken
    protected $primaryKey = 'id';
    protected $fillable = [
         'user_id',
         'name',
         'secret',
         'redirect',
         'personal_access_client',
         'password_client',
         'revoked',
         'creater_at',
         'updated_at'
         
     ];
 
}
