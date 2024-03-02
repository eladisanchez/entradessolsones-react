<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model {

	protected $table = 'refunds';
	protected $guarded = array('id');
	protected $hidden = array('updated_at');
    protected $with = array('order');
    protected $dates = [
        'day_cancelled',
        'day_new'
    ];

    public function comanda()
    {
        return $this->belongsTo('App\Comanda');
    }

    public function reserves()
    {
        return $this->comanda->bookings->where('devolucio',1);
    }

    public function producte()
    {
        return $this->belongsTo('App\Producte');
    }


}