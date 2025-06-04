<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DompetPhoto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'photo',
        'dompet_id',
    ];
}
