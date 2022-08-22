<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table = 'tasks';
    protected $fillable = [
        'content',
        'description',
        'status',
        'end_time',
        'idUser', 'idSprint', 'idTag'
    ];

    public function info()
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'description' => $this->description,
            'status' => $this->status,
            'end_time' => $this->end_time,
            'idTag' => $this->idTag
        ];
    }

    public function user()
    {
        return $this->belongsToMany('App\Models\User', 'task_users', 'idTask', 'idUser');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'idTask', 'id');
    }

    public function sprint()
    {
        return $this->belongsTo(Sprint::class, 'idSprint', 'id');
    }
}
