<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Get the formatted latitude with a degree symbol and direction.
     *
     * @return string Formatted latitude
     */
    public function getFormattedLatitudeAttribute(): string
    {
        $lat = abs($this->latitude);
        $direction = $this->latitude >= 0 ? 'N' : 'S';
        return number_format($lat, 4) . '° ' . $direction;
    }

    /**
     * Get the formatted longitude with a degree symbol and direction.
     *
     * @return string Formatted longitude
     */
    public function getFormattedLongitudeAttribute(): string
    {
        $lng = abs($this->longitude);
        $direction = $this->longitude >= 0 ? 'E' : 'W';
        return number_format($lng, 4) . '° ' . $direction;
    }

}
