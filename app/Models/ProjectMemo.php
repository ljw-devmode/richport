<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMemo extends Model
{
    protected $table = 'project_memo';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
        'idx',
        'name',
        'memo',
        'created_at'
    ];
}


