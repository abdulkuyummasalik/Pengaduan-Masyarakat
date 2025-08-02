<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['report_id', 'user_id', 'comment'];

    // Relasi ke report (many-to-one)
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    // Relasi ke user (many-to-one)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
