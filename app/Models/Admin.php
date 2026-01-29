<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // ← 이걸 상속해야 함
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
	use Notifiable;
	
    protected $table = 'admin';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'idx',
    	'id',
        'pw',
        'name',
        'hp',
        'email',
    	'level',
        'created_at',
        'created_by'
    ];
    
    public function getAuthPassword()
    {
    	return $this->pw;
    }

}