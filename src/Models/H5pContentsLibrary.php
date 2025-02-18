<?php

namespace LaraH5P\Models;

use Illuminate\Database\Eloquent\Model;

class H5PContentsLibrary extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $table = 'h5p_contents_libraries';

    protected $fillable = [
        'content_id',
        'library_id',
        'dependency_type',
        'weight',
        'drop_css',
    ];
}
