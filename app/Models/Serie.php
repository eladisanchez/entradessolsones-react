<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\AscorderScope;

class Serie extends Model
{
    use HasFactory;

    protected $guarded = array('id');
    protected $appends = array('title','summary');

    public $language;
    protected static function boot() 
    {
        parent::boot();
        static::addGlobalScope(new AscorderScope);
    }

    public function __construct()
    {
        $this->language = LaravelLocalization::setLocale();
    }

    public function getTitleAttribute()
    {
        return $this->{'title_'.$this->language} ?? $this->{'title_'.config('app.locale')};
    }

    public function products($target=NULL)
    {
        return $this->hasMany(Product::class)->orderBy('order');
    }

}
