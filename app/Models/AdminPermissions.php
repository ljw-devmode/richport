<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminPermissions extends Model
{
    protected $table = 'admin_permissions';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'admin_idx',        // admin.idx
        'permission_key',   // 접근 권한 (config > permission.php)
    ];
}