<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Soal extends Model
{
    use SoftDeletes;

    protected $table = 'soals';
    protected $primaryKey = 'id';
    protected $fillable = ['pertanyaan','opsi1','opsi2','opsi3','opsi4','jawaban','status'];
    protected $dates = ['created_at','updated_at','deleted_at'];
}
