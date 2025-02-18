<?php

namespace LaraH5P\Models;

use Illuminate\Database\Eloquent\Model;

class H5PResult extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = true;
    
    protected $fillable = [
        'content_id',
        'user_id',
        'score',
        'max_score',
        'opened',
        'finished',
        'time',
    ];
}
