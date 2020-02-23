<?php

namespace Xvrmallafre\ImportProductsSwapi\Models;

use Illuminate\Database\Eloquent\Model;

class Starship extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name',
        'model',
        'manufacturer',
        'starship_class',
        'cost_in_credits',
        'url',
    ];

    public function pilots()
    {
        return $this->belongsToMany('Xvrmallafre\ImportProductsSwapi\Models\Pilot');
    }


}
