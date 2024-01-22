<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id', 
        'jenis_prestasi', 
        'tahun', 
        'keterangan'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
