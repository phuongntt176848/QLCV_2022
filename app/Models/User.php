<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'google_id',
        'password',
        'active',
        'title',
        'phone',
        'brithday',
        'location'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'email' => $this->email,
            'phone' => $this->phone,
            'location' => $this->location,
            'brithday' => $this->brithday,
            'avatar' => $this->avatar,
            'name' => $this->name
        ];
    }

    public function team()
    {
        return $this->belongsToMany('App\Models\Team', 'team_users', 'idUser', 'idTeam');
    }

    public function project()
    {
        return $this->belongsToMany('App\Models\Project', 'project_users', 'idUser', 'idProject');
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Models\Task','task_users', 'idUser', 'idTask');
    }
}
