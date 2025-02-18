<?php

namespace LaraH5P\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use LaraH5P\Events\H5pEvent;
use LaraH5P\Models\H5PContent;

class EmbedController extends Controller
{
    public function __invoke(Request $request, $id)
    {
        $h5p = App::make('LaraH5P');
        $core = $h5p::$core;
        $settings = $h5p::get_editor();
        $content = H5PContent::findOrFail($id);
        
        $embed = $h5p->get_embed($content, $settings);
        $embed_code = $embed['embed'];
        $settings = $embed['settings'];

        event(new H5pEvent('content', null, $content->id, $content->title, $content->library->name, $content->library->majorVersion, $content->library->minorVersion));

        return view('h5p.content.embed', compact('settings', 'embed_code'));
    }
}
