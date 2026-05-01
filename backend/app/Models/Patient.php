<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['user_id', 'age', 'gender', 'medical_history', 'persistent_sickness', 'allergies', 'current_treatments'];

    protected $casts = [
        'medical_history' => 'encrypted',
        'persistent_sickness' => 'encrypted',
        'allergies' => 'encrypted',
        'current_treatments' => 'encrypted',
    ];

    protected $hidden = [
        'medical_history',
        'persistent_sickness',
        'allergies',
        'current_treatments',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}
