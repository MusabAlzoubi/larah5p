<?php

namespace LaraH5P\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use LaraH5P\Models\H5PContent;
use LaraH5P\Events\H5pEvent;
use H5pCore;
use Illuminate\Support\Facades\App;

class H5PController extends Controller
{
    public function index(Request $request)
    {
        $contents = H5PContent::query()
            ->orderByDesc('id')
            ->when($request->query('sf') === 'title', function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->query('s').'%');
            })
            ->when($request->query('sf') === 'creator', function ($query) use ($request) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->query('s').'%');
                });
            })
            ->paginate(10);

        return view('h5p.content.index', compact('contents', 'request'));
    }

    public function create()
    {
        $h5p = App::make('LaraH5P');
        $core = $h5p::$core;
        $settings = $h5p::get_editor();
        event(new H5pEvent('content', 'new'));
        return view('h5p.content.create', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:250',
            'action' => 'required',
        ]);

        $h5p = App::make('LaraH5P');
        $core = $h5p::$core;
        $editor = $h5p::$h5peditor;
        
        $content = [
            'disable'    => H5PCore::DISABLE_NONE,
            'user_id'    => Auth::id(),
            'title'      => $request->title,
            'embed_type' => 'div',
            'filtered'   => '',
            'slug'       => config('larah5p.slug'),
        ];
        
        $content['id'] = $core->saveContent($content);
        
        event(new H5pEvent('content', 'create', $content['id'], $content['title']));
        
        return redirect()->route('h5p.index')->with('success', 'Content created successfully!');
    }

    public function show($id)
    {
        $content = H5PContent::findOrFail($id);
        return view('h5p.content.show', compact('content'));
    }

    public function destroy($id)
    {
        $content = H5PContent::findOrFail($id);
        $content->delete();
        return redirect()->route('h5p.index')->with('success', 'Content deleted successfully!');
    }
}
