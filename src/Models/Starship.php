<?php

namespace Xvrmallafre\ImportProductsSwapi\Models;

use Illuminate\Database\Eloquent\Model;

class Starships extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name',
        'model',
        'manufacturer',
        'starship_class',
        'cost_in_credits'
    ];

    public function pilots()
    {
        return $this->belongsToMany('Xvrmallafre\ImportProductsSwapi\Models\Pilot');
    }


}
