<?php

namespace LaraH5P\Models;

use Illuminate\Database\Eloquent\Model;

class H5PLibrariesLanguage extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    
    protected $fillable = [
        'library_id',
        'language_code',
        'translation',
    ];
}