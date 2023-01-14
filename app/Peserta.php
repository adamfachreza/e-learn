<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peserta extends Model
{
    use SoftDeletes;

    protected $table = 'pesertas';
    protected $primaryKey = 'id';
    protected $fillable = ['nama','email','password','token','status'];
    protected $dates = ['created_at','updated_at','deleted_at'];
}
