<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'masa_kerja',
        'jobdesk',
    ];
    protected $casts = [
        'masa_kerja' => 'integer',
    ];

    public function votes()
    {
    return $this->hasMany(Vote::class);
    }

    public function comments()
    {
    return $this->hasMany(Comment::class);
    }

}
