<?php

namespace Xvrmallafre\ImportProductsSwapi\Models;

use Illuminate\Database\Eloquent\Model;

class Pilot extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name',
        'gender',
        'url',
    ];

    public function starships()
    {
        return $this->belongsToMany('Xvrmallafre\ImportProductsSwapi\Models\Starship');
    }
}
