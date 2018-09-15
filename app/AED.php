<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AED extends Model
{
    protected $table = 'aeds';

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function hours()
    {
        return $this->hasMany(OpenHours::class, 'aed_id', 'aed_id');
    }
}
