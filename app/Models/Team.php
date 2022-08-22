<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $table = 'teams';
    protected $fillable = [
        'name',
        'img',
        'bg_img'
    ];

    public function user()
    {
        return $this->belongsToMany('App\Models\User', 'team_users', 'idTeam', 'idUser');
    }
}
