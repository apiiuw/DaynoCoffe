<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bill extends Model
{
    use HasFactory;

    protected $table = 'bills';

    protected $fillable = [
        'user_id',
        'date',
        'category',
        'amount',
        'due_date',
        'status',
        'description',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusAttribute($value)
    {
        return $value ? 'Lunas' : 'Belum Lunas';
    }


    protected $dates = [
        'due_date',
    ];
}
