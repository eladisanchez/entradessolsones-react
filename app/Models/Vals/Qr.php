<?php

namespace App\Models\Vals;

use Illuminate\Database\Eloquent\Model;

class Qr extends Model {

	protected $table = 'vals_qr';
	protected $guarded = array('id');
	protected $hidden = array('created_at', 'updated_at');

	public function usuari()
	{
		return $this->belongsTo('\App\Models\Vals\Usuari','usuari_id');
	}

	public function comerc()
	{
		return $this->belongsTo('\App\Models\Vals\Comerc','comerc_id');
	}

}