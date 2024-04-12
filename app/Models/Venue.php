<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model {

    use SoftDeletes;

	protected $table = 'venues';
    protected $guarded = array('id');
    protected $hidden = array('created_at', 'updated_at');
    protected $casts = ['stage' => 'boolean'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class,'venue_id','id')->orderBy('order');
    }

}