<?php


namespace App\Models;


class Item
{
    protected $fillable = [
        'name',
        'topsidelocation',
        'botsidelocation',
    ];

    public function piles()
    {
        return $this->belongsToMany('App\Models\Pile');
    }
}
