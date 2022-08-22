<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable = [
        'name',
        'description'
    ];

    public function info()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at
        ];
    }

    public function user()
    {
        return $this->belongsToMany('App\Models\User', 'project_users', 'idProject', 'idUser');
    }

    public function sprints()
    {
        return $this->hasMany('App\Models\Sprint', 'idProject', 'id');
    }
}
