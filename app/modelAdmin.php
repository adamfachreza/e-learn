<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class modelAdmin extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['name','email','token','password'];
}
