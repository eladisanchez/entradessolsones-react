<?php

namespace App\Models\Vals;

use Illuminate\Database\Eloquent\Model;

use QrCode;


class Usuari extends Model {

	protected $table = 'vals_usuaris';
	protected $guarded = array('id');
	protected $hidden = array('created_at', 'updated_at');
	protected $with = ['qr'];
	protected $withCount = ['qr'];

	public function qrimage($count) {
		$qr = config('app.url').'/vals/qr/'.$this->codi.'/'.$count;
		$output = base64_encode(QrCode::format('png')->size(100)->generate($qr));
    	return $output;
	}

	public function getNomSencerAttribute()
	{
		return $this->name.' '.$this->cognoms;
	}

	public function qr()
	{
		return $this->hasMany('\App\Models\Vals\Qr');
	}

	public function checkActivation($count)
	{
		return $this->qr->where('count',$count)->first();
	}

	public function getComercosAttribute()
	{
		$comercos = [];
		foreach($this->qr as $qr) {
			$comercos[$qr->count] = [
				'name'=>$qr->comerc->name,
				'data'=>$qr->created_at->format('m/d/Y H:i')
			];
		}
		return $comercos;
	}

}