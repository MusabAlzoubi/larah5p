<?php

namespace LaraH5P\Models;

use Illuminate\Database\Eloquent\Model;

class H5PLibrariesHubCache extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = true;
    
    protected $fillable = [
        'machine_name',
        'major_version',
        'minor_version',
        'patch_version',
        'h5p_major_version',
        'h5p_minor_version',
        'title',
        'summary',
        'description',
        'icon',
        'is_recommended',
        'popularity',
        'screenshots',
        'license',
        'example',
        'tutorial',
        'keywords',
        'categories',
        'owner',
        'created_at',
        'updated_at',
    ];
}