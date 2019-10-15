<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataKeyword extends Model
{
	protected $table = 'data_keyword';
	protected $fillable = ['keyword_id','title','url','file'];
}
