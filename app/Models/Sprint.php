<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    use HasFactory;
    protected $table = 'sprints';
    protected $fillable = [
        'title',
        'idProject'
    ];

    public function info()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'created_at' => $this->created_at
        ];
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task', 'idSprint', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'idProject', 'id');
    }
}
