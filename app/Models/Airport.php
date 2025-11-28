<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;

    protected $primaryKey = 'airport_code';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];
}
