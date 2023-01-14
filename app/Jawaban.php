<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jawaban extends Model
{
    use SoftDeletes;

    protected $table = 'jawabans';
    protected $primaryKey = 'id';
    protected $fillable = ['id_peserta','id_soal','jawaban','status_jawaban','id_skor'];
    protected $dates = ['created_at','deleted_at','updated_at'];
}
