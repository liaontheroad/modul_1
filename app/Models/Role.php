<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles'; // Connects to the 'roles' table
    public $timestamps = false; // We didn't create created_at/updated_at columns in the SQL
    
    protected $fillable = ['name'];

    // RELATIONSHIP: One Role has many Users
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    protected function validator(array $data)
{
    return Validator::make($data, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'role_id' => ['required'], // <--- ADD THIS
    ]);
}

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => (int) $data['role_id'], // <--- ADD THIS
        ]);
    }
}