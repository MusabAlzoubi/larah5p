<?php

namespace LaraH5P\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use LaraH5P\Events\H5pEvent;
use H5PEditorEndpoints;

class AjaxController extends Controller
{
    public function libraries(Request $request)
    {
        $machineName = $request->get('machineName');
        $major_version = $request->get('majorVersion');
        $minor_version = $request->get('minorVersion');

        $h5p = App::make('LaraH5P');
        $core = $h5p::$core;
        $editor = $h5p::$h5peditor;

        Log::debug('Loading library: '.$machineName.' - '.$h5p->get_language());

        if ($machineName) {
            $defaultLanguage = $editor->getLibraryLanguage($machineName, $major_version, $minor_version, $h5p->get_language());
            $editor->ajax->action(H5PEditorEndpoints::SINGLE_LIBRARY, $machineName, $major_version, $minor_version, $h5p->get_language(), '', $h5p->get_h5plibrary_url('', true), $defaultLanguage);
            event(new H5pEvent('library', null, null, null, $machineName, $major_version.'.'.$minor_version));
        } else {
            $editor->ajax->action(H5PEditorEndpoints::LIBRARIES);
        }
    }

    public function singleLibrary(Request $request)
    {
        $h5p = App::make('LaraH5P');
        $editor = $h5p::$h5peditor;
        $editor->ajax->action(H5PEditorEndpoints::SINGLE_LIBRARY, $request->get('_token'));
    }

    public function contentTypeCache(Request $request)
    {
        $h5p = App::make('LaraH5P');
        $editor = $h5p::$h5peditor;
        $editor->ajax->action(H5PEditorEndpoints::CONTENT_TYPE_CACHE, $request->get('_token'));
    }

    public function libraryInstall(Request $request)
    {
        $h5p = App::make('LaraH5P');
        $editor = $h5p::$h5peditor;
        $editor->ajax->action(H5PEditorEndpoints::LIBRARY_INSTALL, $request->get('_token'), $request->get('machineName'));
    }

    public function libraryUpload(Request $request)
    {
        $filePath = $request->file('h5p')->getPathName();
        $h5p = App::make('LaraH5P');
        $editor = $h5p::$h5peditor;
        $editor->ajax->action(H5PEditorEndpoints::LIBRARY_UPLOAD, $request->get('_token'), $filePath, $request->get('contentId'));
    }

    public function files(Request $request)
    {
        $filePath = $request->file('file');
        $h5p = App::make('LaraH5P');
        $editor = $h5p::$h5peditor;
        $editor->ajax->action(H5PEditorEndpoints::FILES, $request->get('_token'), $request->get('contentId'));
    }

    public function __invoke(Request $request)
    {
        return response()->json($request->all());
    }

    public function finish(Request $request)
    {
        return response()->json($request->all());
    }

    public function contentUserData(Request $request)
    {
        return response()->json($request->all());
    }
}
