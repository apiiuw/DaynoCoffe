<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpansesCategory extends Model
{
    use HasFactory;

    protected $table = 'expanses_category';

    protected $fillable = [
        'category',
        'item',
        'nominal',
        'keterangan',
    ];
}
