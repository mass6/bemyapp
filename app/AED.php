<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AED extends Model
{
    protected $table = 'aeds';

    public function hours()
    {
        return $this->hasMany(OpenHours::class, 'aed_id', 'aed_id');
    }
}
