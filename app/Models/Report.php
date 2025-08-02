<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'user_id', 'description', 'type', 'province', 'regency', 'subdistrict',
    //     'village', 'voting', 'viewers', 'image', 'statement'
    // ];

    protected $guarded = [''];
    // (many-to-one)
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    // (one-to-many)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
