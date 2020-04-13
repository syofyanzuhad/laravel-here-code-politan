<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spaces extends Model
{
    protected $guarded = [];

    public function photos() {
        return $this->hasMany(PhotoSpaces::class, 'space_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getSpaces($latitude, $longitude, $radius) {
        return $this->select('spaces.*')
                    ->selectRaw(
                        '( 6371 *
                            acos( cos( radians(?) ) *
                            cos( radians( latitude ) ) * 
                            cos( radians( longitude ) - radians(?) ) *
                            sin( radians(?) ) *
                            sin( radians( latitude ) )
                            )
                        ) AS distance', [$latitude, $longitude, $latitude]
                    )
                    ->havingRaw('distance < ?', [$radius])
                    ->orderBy('distance', 'ASC');
    }
}
