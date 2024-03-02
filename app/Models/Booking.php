<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Scan;
use App\Models\Rate;
use App\Models\Order;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Booking extends Model {

	use SoftDeletes;

	protected $table = 'bookings';
	protected $guarded = ['id','product_id'];
	protected $hidden = ['created_at', 'updated_at','uniqid'];
	protected $appends = ['hour'];
	protected $dates = [
        'day'
	];

	public function getHourAttribute($value)
	{
		if($value)
		return Carbon::createFromFormat('H:i:s', $value)->format('H:i');
	}

	public function getDates()
	{
		return array('day');
	}

	public function product()
	{
		// return $this->belongsTo(Product::class,'product_id')->withTrashed();
		return $this->belongsTo(Product::class,'product_id');
	}

	public function scans()
	{
		return $this->hasMany(Scan::class);
	}

	public function rate()
	{
		return $this->belongsTo(Rate::class);
	}

	public function scopeProductDayHour($query,$id,$day,$hour)
	{
		$query->where('product_id',$id)
		->where('day',$day)
		->where('hour',$hour);
	}

	public function order()
	{
		return $this->belongsTo(Order::class)->withTrashed();
	}

	// public function getSeatAttribute()
	// {
	// 	return $this->seat;
	// }

	public function getSeatRowAttribute()
	{
		
		return \App\Helpers\Common::seat($this->seat);
		
	}

	public function smallSeatAttribute($seient) {
		return $seient->f.' / '.$seient->s;
	}


	public function qrcode($count) {
		$ch = substr(\Hash::make($count.$this->uniqid),-2,2);
		$qr = base64_encode($ch.'_'.$this->uniqid.'_'.$count.'_'.$this->id);
		return $qr;
	}

	public function qrimage($count) {
		$qr = $this->qrcode($count);
		$output = base64_encode(QrCode::format('png')->size(100)->generate($qr));
    return $output;
	}

}
