<?php

/*
 *
 * @Project        LaraH5P
 * @Copyright      Musab Alzoubi
 * @Created        2024-02-18
 * @Filename       H5PEvent.php
 * @Description    Event handler for logging H5P interactions
 *
 */

namespace LaraH5P\Events;

use LaraH5P\Models\H5PEventLog;
use H5PEventBase;
use Illuminate\Support\Facades\Auth;

class H5PEvent extends H5PEventBase
{
    private $user;

    /**
     * Initializes the event with type, subtype, and associated content details.
     */
    public function __construct($type, $sub_type = null, $content_id = null, $content_title = null, $library_name = null, $library_version = null)
    {
        $this->user = Auth::id();
        parent::__construct($type, $sub_type, $content_id, $content_title, $library_name, $library_version);
    }

    /**
     * Store the event in the database.
     */
    protected function save()
    {
        $data = $this->getDataArray();
        $data['user_id'] = Auth::id();
        return H5PEventLog::create($data);
    }

    /**
     * Count number of events (currently disabled).
     */
    protected function saveStats()
    {
        return true;
    }
}
