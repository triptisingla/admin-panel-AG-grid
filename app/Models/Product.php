<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasFactory;
    use MediaAlly;
    use SoftDeletes;
    protected $fillable=[
        'name',
        'detail',
        'category',
        'quantity',
        'display',
        'productimage',
        'price',
        'user_id',
        'product_id',
    ];

    public function user(){
       return $this->belongsTo('App\Models\User','user_id');

    } 
}
