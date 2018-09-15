<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeploymentEvent extends Model
{
    protected $guarded = [];

    public function getEvent()
    {
        return $this->name;
    }

    public function deployment()
    {
        return $this->belongsTo(Deployment::class);
    }
}
