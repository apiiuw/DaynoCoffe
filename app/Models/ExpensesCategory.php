<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensesCategory extends Model
{
    use HasFactory;

    protected $table = 'expenses_category';

    protected $fillable = [
        'id',
        'category',
        'item',
        'price',
        'keterangan',
    ];
}
