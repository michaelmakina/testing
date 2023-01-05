<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $fillable = [
        'title',
        'description',
        'image',
        'price',
        'lat', 
        'lng',
        'owners_name',
        'owners_email',
        'owners_phone',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    // this only gets used becaus this is a json field and is not as simple as the rest
    protected $casts = [
        'location' => 'array',
    ];

}
