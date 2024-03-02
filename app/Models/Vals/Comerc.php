<?php

namespace App\Models\Vals;

use Illuminate\Database\Eloquent\Model;

class Comerc extends Model {

	protected $table = 'vals_comercos';
	protected $guarded = array('id');
	protected $withCount = ['qr'];

	public function qr()
	{
		return $this->HasMany('\App\Models\Vals\Qr','comerc_id');
	}

}