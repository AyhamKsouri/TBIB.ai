<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['user_id', 'action', 'target_table', 'ip_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
