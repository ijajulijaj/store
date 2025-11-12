<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'master_data';

    protected $fillable = [
        'outlet_code',
        'location',
        'mch_code',
        'article_no',
        'article_description',
        'stock_quantity',
        'uom',
        'eanno'
    ];
}
