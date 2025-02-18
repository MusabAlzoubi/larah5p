<?php

namespace LaraH5P\Models;

use Illuminate\Database\Eloquent\Model;

class H5PEventLog extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $table = 'h5p_event_logs';

    protected $fillable = [
        'user_id',
        'created_at',
        'type',
        'sub_type',
        'content_id',
        'content_title',
        'library_name',
        'library_version',
    ];
}
