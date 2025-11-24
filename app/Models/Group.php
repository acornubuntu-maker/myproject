<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'description', 'created_by'];

    public function links()
    {
        return $this->belongsToMany(Link::class, 'group_link');
    }
}