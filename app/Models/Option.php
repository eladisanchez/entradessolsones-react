<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model {

    public $timestamps = false;

	protected $table = 'options';
    protected $guarded = array('id');

}