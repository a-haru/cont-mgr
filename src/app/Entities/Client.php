<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['name', 'url', 'contract_activate_at', 'contract_deactivate_at'];
}
