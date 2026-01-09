<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Report extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'voting' => 'array',
        'statement' => 'boolean',
        'viewers' => 'integer',
    ];

    // Relationship: Report belongs to User
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Anonymous',
            'email' => 'anonymous@example.com'
        ]);
    }

    // Relationship: Report has many responses
    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    // Relationship: Report has many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Accessor untuk mendapatkan URL gambar
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists('images/' . $this->image)) {
            return asset('storage/images/' . $this->image);
        }
        return null;
    }

    // Accessor untuk cek apakah gambar ada
    public function getHasImageAttribute()
    {
        return $this->image && Storage::disk('public')->exists('images/' . $this->image);
    }

    // Accessor untuk jumlah vote
    public function getVoteCountAttribute()
    {
        return is_array($this->voting) ? count($this->voting) : 0;
    }

    // Method untuk cek apakah user sudah vote
    public function hasVoted($userId)
    {
        $voting = is_array($this->voting) ? $this->voting : [];
        return in_array($userId, $voting);
    }
}
