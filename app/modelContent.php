<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class modelContent extends Model
{
    use SoftDeletes;
    protected $table = 'contents';
    protected $primaryKey = 'id';
    protected $fillable = ['id','judul','keterangan','link_thumbnail','link_video','status','view'];
    protected $dates = ['created_at','updated_at','deleted_at'];

}
