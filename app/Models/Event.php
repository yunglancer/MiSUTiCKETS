<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['category_id', 'venue_id', 'title', 'slug', 'description', 'image_path', 'event_date', 'is_featured', 'status'];
}
