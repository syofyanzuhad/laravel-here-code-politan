<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhotoSpaces extends Model
{
    protected $guarded = [];

    public function spaces() {
        return $this->belongsTo(Spaces::class, 'spaces_id', 'id');
    }
}
