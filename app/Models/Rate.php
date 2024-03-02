<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Producte;
use LaravelLocalization;
use Session;

class Rate extends Model
{

    use SoftDeletes;

    protected $table = 'rates';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'name'];
    protected $appends = ['title', 'description'];


    public function product()
    {
        return $this->belongsToMany('App\Models\Product', 'product_rate')->withPivot('price', 'pricezone');
    }


    public function getTitleAttribute()
    {
        if (LaravelLocalization::setLocale())
            if ($this->{'title_' . LaravelLocalization::setLocale()}) {
                return $this->{'title_' . LaravelLocalization::setLocale()};
            } else {
                return $this->{'title_ca'};
            } else
            return $this->{'title_ca'};
    }


    public function getDescriptionAttribute()
    {
        if (LaravelLocalization::setLocale()) {
            return $this->{'description_' . LaravelLocalization::setLocale()};
        } else {
            return $this->description_ca;
        }
    }


    public function getPriceAttribute($value)
    {

        if ($this->pivot->price > 0) {
            // Preu amb codi de descompte
            if (Session::has('codi.p' . $this->pivot->producte_id . '_t' . $this->id)) {
                $priceDescompte = $this->pivot->price * (1 - Session::get('codi.descompte') / 100);
                return number_format($priceDescompte, 2, ',', '.') . ' €';
            }
            // Preu normal
            else {
                return number_format($this->pivot->price, 2, ',', '.') . ' €';
            }
        } else {
            // Gratis
            return trans('textos.gratis');
        }

    }

    public function getPreuvalueAttribute($value)
    {

        if ($this->pivot->price > 0) {
            // Preu amb codi de descompte
            if (Session::has('codi.p' . $this->pivot->producte_id . '_t' . $this->id)) {
                $priceDescompte = $this->pivot->price * (1 - Session::get('codi.descompte') / 100);
                return $priceDescompte;
            }
            // Preu normal
            else {
                return $this->pivot->price;
            }
        } else {
            // Gratis
            return trans('textos.gratis');
        }
    }


    public function getPricezoneAttribute($value)
    {

        if ($this->pivot->pricezone) {
            return $this->pivot->pricezone;
        } else {
            return $this->pivot->price . ',' . $this->pivot->price . ',' . $this->pivot->price . ',' . $this->pivot->price;
        }

    }

    public function pricelocalitat($zona)
    {

        $i = $zona - 1;

        if ($this->pivot->pricezone) {
            $prices = explode(',', $this->pricezone);
            return $prices[$i];
        } else {
            return $this->pricevalue;
        }

    }


}