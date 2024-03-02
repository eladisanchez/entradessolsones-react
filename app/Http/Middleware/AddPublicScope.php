<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use App\Scopes\ActiveScope;

class AddPublicScope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        Product::addGlobalScope(new ActiveScope);
        return $next($request);
        
    }
}