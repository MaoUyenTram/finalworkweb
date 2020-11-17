<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Item extends Model
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
