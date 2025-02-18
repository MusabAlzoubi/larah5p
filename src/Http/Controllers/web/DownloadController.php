<?php

namespace LaraH5P\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use LaraH5P\Events\H5pEvent;
use LaraH5P\Models\H5PContent;

class DownloadController extends Controller
{
    public function __invoke(Request $request, $id)
    {
        $h5p = App::make('LaraH5P');
        $core = $h5p::$core;
        $interface = $h5p::$interface;

        $content = H5PContent::findOrFail($id);
        $content['filtered'] = '';
        $params = $core->filterParameters($content);

        event(new H5pEvent('download', null, $content->id, $content->title, $content->library->name, $content->library->majorVersion, $content->library->minorVersion));

        return response()->download($interface->_download_file, $content->title . '.h5p', [
            'Content-Type'  => 'application/zip',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
        ]);
    }
}
