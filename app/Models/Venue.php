<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
   protected $fillable = ['name', 'city', 'address', 'capacity'];

   public function venue()
   {
       return $this->hasMany(Event::class);
   }
   public function zones()
    {
        return $this->hasMany(VenueZone::class);
    }
}

