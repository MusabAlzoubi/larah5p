<?php

namespace LaraH5P\Models;

use Illuminate\Database\Eloquent\Model;

class H5PContentsTag extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $table = 'h5p_contents_tags';

    protected $fillable = [
        'content_id',
        'tag_id',
    ];
}
