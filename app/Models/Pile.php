<?php


namespace App\Models;


class Pile
{

    protected $fillable = [
        'name',
        'private',
        'visibility',
    ];

    public function items()
    {
        return $this->belongsToMany('App\Models\item');
    }
}
