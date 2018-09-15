<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpenHours extends Model
{
    protected $table = 'aed_open';

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aed()
    {
        return $this->belongsTo(AED::class, 'aed_id', 'aed_id');
    }
}
