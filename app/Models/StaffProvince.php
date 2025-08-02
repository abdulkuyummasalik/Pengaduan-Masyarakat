<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffProvince extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'province'];

    // Relasi ke user (one-to-one)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
