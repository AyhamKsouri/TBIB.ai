<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['user_id', 'specialty', 'experience_years', 'department_id', 'phone', 'location', 'work_days', 'work_hours', 'is_validated'];

    protected $casts = [
        'is_validated' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
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
