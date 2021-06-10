<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'message',
        'userId',
        'GameSessionId',
    ];

    public function items()
    {
        return $this->belongsToMany('App\Models\User');
    }

}
