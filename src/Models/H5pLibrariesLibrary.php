<?php

namespace LaraH5P\Models;

use Illuminate\Database\Eloquent\Model;

class H5PLibrariesLibrary extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    
    protected $fillable = [
        'library_id',
        'required_library_id',
        'dependency_type',
    ];
}