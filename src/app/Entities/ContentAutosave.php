<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ContentAutosave extends Model
{
    //
    protected $fillable = ['client_id', 'content_id', 'title', 'description', 'text', 'note'];

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id', 'id');
    }
}
