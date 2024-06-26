<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

use App\Scopes\AscorderScope;

class Category extends Model {

    use SoftDeletes;

	protected $table = 'categories';
	protected $guarded = array('id');
	// protected $hidden = array('created_at', 'updated_at');
    // protected $appends = array('title','summary');
    // protected $attributes = [
    //     'title' => '',
    // ];
    // public $language;


    protected static function boot() 
    {
        parent::boot();
        static::addGlobalScope(new AscorderScope);
    }

    protected static function booted()
    {
        static::creating(function ($category) {
            $category->order = Category::max('order') + 1;
        });
    }

    // public function __construct()
    // {
    //     $this->language = LaravelLocalization::setLocale();
    // }


	// public function getTitleAttribute()
    // {
    //     return $this->{'title_'.$this->language} ?? $this->{'title_'.config('app.locale')};
    // }
    
    // public function getSummaryAttribute()
    // {
    //     return $this->{'summary_'.$this->language} ?? $this->{'summary_'.config('app.locale')};
    // }


    public function products($target=NULL)
    {
        return $this->hasMany(Product::class)->orderBy('order');
    }


}