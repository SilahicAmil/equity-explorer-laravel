<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

//    Since table name differs from model name. We can set it directly here. Pretty neat
//    Fillable is for the mass assignab le attributes. So thing we want to create or update. In this case stocks can only be bought
//    and never added/changed so not needed

    protected $table = 'stock_data';

}
