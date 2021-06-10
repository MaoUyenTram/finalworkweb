<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Pile extends Model
{

    protected $fillable = [
        'name',
        'private',
        'visibility',
        'image',
    ];

    public function items()
    {
        return $this->belongsToMany('App\Models\item');
    }
}
