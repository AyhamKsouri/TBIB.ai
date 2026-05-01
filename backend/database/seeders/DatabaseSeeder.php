<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Departments
        $departments = [
            'Cardiology',
            'Dermatology',
            'Pediatrics',
            'General Medicine',
            'Neurology'
        ];

        foreach ($departments as $name) {
            Department::create(['name' => $name]);
        }

        // Create Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@tbib.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create a Doctor for testing
        $docUser = User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@tbib.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);

        $doctor = Doctor::create([
            'user_id' => $docUser->id,
            'specialty' => 'Cardiologie',
            'experience_years' => 15,
            'department_id' => 1,
            'phone' => '+33 1 23 45 67 89',
            'location' => 'Bâtiment A, 2ème étage',
            'work_days' => 'Lundi - Vendredi',
            'work_hours' => '09:00 - 18:00',
            'is_validated' => true,
        ]);

        // Create a Patient for testing
        $patUser = User::create([
            'name' => 'Alice Martin',
            'email' => 'alice.martin@example.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
        ]);

        $patient = Patient::create([
            'user_id' => $patUser->id,
            'age' => 30,
            'gender' => 'Femme',
            'medical_history' => 'Aucun antécédent majeur.'
        ]);

        // Add a feedback
        \App\Models\Feedback::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'comment' => 'Très bon médecin, très à l\'écoute.',
            'rating' => 5
        ]);
    }
}
