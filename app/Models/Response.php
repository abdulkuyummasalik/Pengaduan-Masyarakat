<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    // protected $fillable = ['report_id', 'response_status', 'staff_id'];
    protected $guarded = [];

    protected $casts = [
        'response_content' => 'array', // Cast otomatis ke array
    ];

    // Relasi ke report (many-to-one)
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    // Relasi ke user (many-to-one, staff as foreign key)
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    // Relasi ke response_progress (one-to-one)
    public function responseProgress()
    {
        return $this->hasOne(ResponseProgress::class);
    }
}
