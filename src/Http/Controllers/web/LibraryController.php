<?php

namespace LaraH5P\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use LaraH5P\Models\H5PContent;
use LaraH5P\Models\H5PLibrary;
use H5PCore;
use DB;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $h5p = App::make('LaraH5P');
        $core = $h5p::$core;
        $interface = $h5p::$interface;
        $not_cached = $interface->getNumNotFiltered();

        $libraries = H5PLibrary::paginate(10);
        $settings = $h5p::get_core([
            'libraryList' => [
                'notCached' => $not_cached,
            ],
            'containerSelector' => '#h5p-admin-container',
        ]);

        return view('h5p.library.index', compact('libraries', 'settings'));
    }

    public function show($id)
    {
        $library = H5PLibrary::findOrFail($id);
        return view('h5p.library.show', compact('library'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'h5p_file' => 'required|max:50000',
        ]);

        if ($request->hasFile('h5p_file') && $request->file('h5p_file')->isValid()) {
            $h5p = App::make('LaraH5P');
            $validator = $h5p::$validator;
            $interface = $h5p::$interface;
            
            $content = null;
            $skipContent = true;
            rename($request->file('h5p_file')->getPathName(), $interface->getUploadedH5pPath());

            if ($validator->isValidPackage($skipContent)) {
                $storage = $h5p::$storage;
                $storage->savePackage($content, null, $skipContent);
                Log::info('Library uploaded successfully.');
            }

            @unlink($interface->getUploadedH5pPath());
            return redirect()->route('h5p.library.index')->with('success', 'Library updated successfully.');
        }

        return redirect()->route('h5p.library.index')->with('error', 'Library upload failed.');
    }

    public function destroy(Request $request)
    {
        $library = H5PLibrary::findOrFail($request->get('id'));
        $h5p = App::make('LaraH5P');
        $interface = $h5p::$interface;

        $usage = $interface->getLibraryUsage($library);
        if ($usage['content'] !== 0 || $usage['libraries'] !== 0) {
            return redirect()->route('h5p.library.index')->with('error', 'Library is in use and cannot be deleted.');
        }

        $interface->deleteLibrary($library);
        return redirect()->route('h5p.library.index')->with('success', 'Library deleted successfully.');
    }

    public function clear()
    {
        $h5p = App::make('LaraH5P');
        $core = $h5p::$core;
        $contents = H5PContent::where('filtered', '')->get();

        foreach ($contents as $content) {
            $content = $core->loadContent($content->id);
            $core->filterParameters($content);
        }

        return redirect()->route('h5p.library.index')->with('success', 'Cache cleared successfully.');
    }

    public function restrict(Request $request)
    {
        $library = H5PLibrary::findOrFail($request->get('id'));
        $library->restricted = !$library->restricted;
        $library->save();
        return response()->json($library);
    }

    private function getLibrary($id)
    {
        return H5PLibrary::findOrFail($id);
    }

    private function getNotCachedSettings($num)
    {
        return [
            'num' => $num,
            'message' => 'Not all content has gotten their cache rebuilt.',
            'progress' => "$num content(s) need cache rebuilding."
        ];
    }
}
