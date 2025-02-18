<?php

namespace LaraH5P\Models;

use Illuminate\Database\Eloquent\Model;

class H5PTag extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    
    protected $fillable = [
        'type',
        'library_name',
        'library_version',
        'num',
    ];
}
