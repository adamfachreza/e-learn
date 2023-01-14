<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skor extends Model
{
    use SoftDeletes;

    protected $table = 'skors';
    protected $primaryKey = 'id';
    protected $fillable = ['id_peserta','skor','status'];
    protected $dates = ['created_at','updated_at','deleted_at'];
}
