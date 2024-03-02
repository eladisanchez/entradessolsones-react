<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\Booking;

class Extract extends Model
{

    use SoftDeletes;
    protected $table = 'extracts';
    protected $fillable = ['date_start', 'date_end', 'user_id', 'product_id'];
    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }

    public function setDateStartAttribute($value)
    {
        $this->attributes['date_start'] = $value ?? new Carbon('first day of this month');
    }
    public function setDateEndAttribute($value)
    {
        $this->attributes['date_end'] = $value ?? new Carbon('last day of this month');
    }

    public function getSalesAttribute()
    {
        $bookings = Booking::with('rate')->with('product')
            ->whereDate('created_at', '>=', $this->date_start)
            ->whereDate('created_at', '<=', $this->date_end)
            ->whereHas("order", function ($q) {
                $q->where('payment', 'card')->where('paid', 1);
            });
        if ($this->user_id) {
            $bookings->whereHas('product', function ($q) {
                $q->where("user_id", $this->user_id);
            });
        }
        if ($this->producte_id) {
            $bookings->where('product_id', $this->producte_id);
        }
        $bookings = $bookings->get()->groupBy(['product.title_ca', 'rate.title_ca'], $preserveKeys = true);
        $sales = [];
        foreach ($bookings as $product => $Rate):
            foreach ($Rate as $t => $bookings):
                $total = $bookings->reduce(function ($carry, $item) {
                    return $carry + $item->numEntrades * $item->preu;
                });
                $devolucio = $bookings->reduce(function ($carry, $item) {
                    if ($item->devolucio) {
                        return $carry + $item->numEntrades * $item->preu;
                    } else {
                        return $carry;
                    }
                });
                $liquidar = $total - $devolucio;
                $sales[] = [
                    'product' => $product . ' - ' . $t,
                    'tickets' => $bookings->sum('tickets'),
                    'total' => $total,
                    'refund' => $devolucio,
                    'settle' => $liquidar
                ];
            endforeach;
        endforeach;
        return $sales;
    }

    public function getTotalSalesAttribute()
    {
        $bookings = Booking::with('Rate')->with('product')
            ->whereDate('created_at', '>=', $this->date_start)
            ->whereDate('created_at', '<=', $this->date_end)
            ->whereHas("order", function ($q) {
                $q->where('payment', 'card')->where('paid', 1);
            })
            ->whereHas('product', function ($q) {
                if ($this->producte_id) {
                    $q->where("id", $this->producte_id);
                } else {
                    $q->where("user_id", $this->user_id);
                }
            })->get();
        $total = $bookings->reduce(function ($total, $item) {
            return $total + $item->numEntrades * $item->preu;
        });
        return $total;
    }

    public function getTotalRefundsAttribute()
    {
        $bookings = Booking::with('Rate')->with('product')
            ->whereDate('created_at', '>=', $this->date_start)
            ->whereDate('created_at', '<=', $this->date_end)
            ->where('refund', 1)
            ->whereHas("order", function ($q) {
                $q->where('payment', 'card')->where('paid', 1);
            })
            ->whereHas('product', function ($q) {
                if ($this->product_id) {
                    $q->where("id", $this->product_id);
                } else {
                    $q->where("user_id", $this->user_id);
                }
            })->get();
        $total = $bookings->reduce(function ($total, $item) {
            return $total + $item->numEntrades * $item->preu;
        });
        return $total;
    }

}