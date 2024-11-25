<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class debt extends Model
{
    use HasFactory;

    protected $table = 'debts';

    protected $fillable = [        
        'user_id',
        'debt_type',
        'date',
        'amount',
        'due_date',
        'category',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
