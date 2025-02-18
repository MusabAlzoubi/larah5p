<?php

namespace LaraH5P\Models;

use Illuminate\Database\Eloquent\Model;

class H5PTmpfile extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = true;
    
    protected $fillable = [
        'path',
        'created_at',
    ];
}
