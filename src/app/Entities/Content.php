<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    //
    protected $fillable = ['title', 'description', 'text', 'note'];
}
