<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Scan extends Model {

	protected $table = 'scans';
	protected $guarded = array('id');

	public function reserva()
	{
		return $this->belongsTo('App\Reserva','reserva_id');
	}

}