<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\AscorderScope;

class Serie extends Model
{
    use HasFactory;

    protected $guarded = array('id');

    public $language;
    protected static function boot() 
    {
        parent::boot();
        static::addGlobalScope(new AscorderScope);
    }

    public function products($target=NULL)
    {
        return $this->hasMany(Product::class)->orderBy('order');
    }

}
