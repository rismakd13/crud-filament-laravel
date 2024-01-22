<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
    'nim', 
    'nama', 
    'fakultas', 
    'jenis_kelamin',
    'email',
    'kelas',
    'foto'
    ];
    
    public function prestasi(){
        return $this->hasMany(Prestasi::class);
    }

    public function post(){
        return $this->hasMany(Post::class);
}
}
