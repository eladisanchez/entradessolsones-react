<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Session;
use App\Scopes\AscorderScope;
use App\Scopes\TranslatedScope;
use Auth;
use App\Models\Booking;

class Product extends Model {

    use SoftDeletes;

	protected $table = 'products';
    protected $guarded = array('id');
    protected $hidden = array('created_at', 'updated_at');
    protected $appends = array('title','description','summary','price','pricezone');
    public $language;


    protected static function boot() {

        parent::boot();
        static::addGlobalScope(new AscorderScope);
        static::addGlobalScope(new TranslatedScope);
        

    }

    public function __construct()
    {
        $this->language = LaravelLocalization::setLocale();
    }


    // Get Product by URL
    public function scopeUrl($query,$url)
    {
        return $query->where('name',$url)->first();
    }

    // Filtra per target
    public function scopeOfTarget($query,$target)
    {
        return $query->where('target',$target);
    }



    public function getTitleAttribute()
    {
        return $this->{'title_'.$this->language} ?? $this->{'title_'.config('app.locale')};
    }


    public function getDescriptionAttribute()
    {
        return $this->{'description_'.$this->language} ?? $this->{'description_'.config('app.locale')};
    }
    public function getScheduleAttribute()
    {
        return $this->{'schedule_'.$this->language} ?? $this->{'schedule_'.config('app.locale')};
    }
    public function getSummaryAttribute()
    {
        return $this->{'summary_'.$this->language} ?? $this->{'summary_'.config('app.locale')};
    }


	public function rates()
    {
        return $this->belongsToMany(\App\Models\Rate::class,'product_rate')->orderBy('order')->withPivot('price','pricezone');
    }


    public function getPriceAttribute($value)
    {
        
        if (isset($this->pivot->preu))
        {
            if ($this->pivot->preu >0)
            {
                // Preu amb codi de descompte
                if ( Session::has('coupon.p'.$this->id.'_t'.$this->pivot->rate_id) )
                {
                    $preuDescompte = $this->pivot->preu * (1 - Session::get('coupon.p'.$this->id.'_t'.$this->pivot->rate_id)/100 );
                    return number_format($preuDescompte,2,',','.').' €';
                }
                // Preu normal
                else
                {
                    return number_format($this->pivot->price,2,',','.').' €';
                }
            }
            // Gratis
            return trans('textos.gratis');
            
        }

        return '';


    }

    public function getPricezoneAttribute($value)
    {
        return $this->pivot?$this->pivot->pricezone:null;
    }
  

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class,'category_id');
    }


    // Si és un pack
    public function packs()
    {
        return $this->belongsToMany(\App\Models\Product::class, 'products_packs', 'product_id', 'pack_id');
    }
    public function packProducts()
    {
        return $this->belongsToMany(\App\Models\Product::class, 'products_packs', 'pack_id', 'product_id');
    }


    // Si és una variant
    public function variants()
    {
        return $this->hasMany(Product::class)->where('parent_id', 0);
    }
    public function parent()
    {
        return $this->belongsTo(\App\Models\Product::class,'parent_id');
    }

    public function tickets()
    {

        $datetime = new \DateTime('today');
        return $this->hasMany(Ticket::class)->where('day','>=',$datetime)->whereNull('cancelled');
        if(Auth::user()&&(Auth::user()->hasRole('admin')||Auth::user()->hasRole('entity')))
            return $this->hasMany(Ticket::class);
        else
            return $this->hasMany(Ticket::class)->where('day','>',$datetime);
        
    }


    public function venue()
    {
        return $this->belongsTo(\App\Models\Venue::class)->where('id','!=',0)->withTrashed();
    }

    public function coupons()
    {
        return $this->hasMany(\App\Models\Coupon::class)->where('rate_id',$this->rate->id);
    }



    public function availableDays()
    {
        $datetime = new \DateTime('today');
        return Ticket::where('product_id',$this->id)->where('day','>=',$datetime)->whereNull('cancelled')->groupBy('day')->pluck('day');
    }



    public function ticketsDay($day,$hour=NULL)
    {

        $ref = $this;
        if($this->parent_id>0) { $ref = $this->parent; }

        if ($hour) {
            return $ref->hasMany(Ticket::class)->where('day',$day)->where('hour',$hour)->whereNull('cancelled')->first();
        }

        return $ref->hasMany(Ticket::class)->where('day',$day)->whereNull('cancelled')->get();

        
    }


    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function bookingsByPaymentMethod($payment)
    {
        $bookings = Booking::where('product_id',$this->id)->whereNull('deleted_at')->whereHas('order',function($q) use ($payment){
            $q->where('pagament',$payment)->whereNull('deleted_at');
        })->sum('numEntrades');
        return $bookings;
    }


    public function organizer()
    {
        return $this->belongsTo(\App\Models\User::class,'user_id');
    }



}