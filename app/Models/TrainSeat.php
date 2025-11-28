<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainSeat extends Model
{
    protected $guarded = [];

    public function wagon()
    {
        return $this->belongsTo(Wagon::class);
    }
}
