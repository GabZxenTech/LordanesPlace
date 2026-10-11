<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'price',
        'weekend_price',
        'max_guests',
        'duration',
        'description',
        'start_time',
        'end_time',
    ];

    /**
     * Price for an event on the given date: the weekend rate on Saturday and
     * Sunday when the package has one, otherwise the regular price.
     */
    public function priceForDate($date): float
    {
        if ($this->weekend_price !== null && Carbon::parse($date)->isWeekend()) {
            return (float) $this->weekend_price;
        }

        return (float) $this->price;
    }
}
