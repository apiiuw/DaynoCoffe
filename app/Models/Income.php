<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $table = 'incomes';

    protected $fillable = [
        'user_id',
        'id_incomes',
        'date',
        'price',
        'quantity',
        'total_price',
        'category',
        'amount',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
