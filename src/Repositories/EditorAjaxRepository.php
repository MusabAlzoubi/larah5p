<?php

/*
 *
 * @Project        LaraH5P Editor Ajax Repository
 * @Copyright      Musab  Alzoubi
 * @Created        2024-02-18
 * @Filename       EditorAjaxRepository.php
 * @Description    Repository class implementing H5PEditorAjaxInterface
 *
 */

namespace LaraH5P\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use H5PEditorAjaxInterface;
use LaraH5P\Models\H5pLibrariesHubCache;

class EditorAjaxRepository implements H5PEditorAjaxInterface
{
    public function getAuthorsRecentlyUsedLibraries()
    {
        return DB::select("SELECT hl4.id, hl4.name AS machine_name, hl4.major_version, hl4.minor_version, hl4.patch_version, hl4.restricted, hl4.has_icon FROM h5p_libraries hl4 GROUP BY hl4.name, hl4.major_version, hl4.minor_version");
    }

    public function getContentTypeCache($machineName = null)
    {
        $query = H5pLibrariesHubCache::select();
        return $machineName ? $query->where('machine_name', $machineName)->pluck('id', 'is_recommended') : $query->get();
    }

    public function getLatestLibraryVersions()
    {
        return DB::table('h5p_events')
            ->select(['library_name', DB::raw('MAX(created_at) AS max_created_at')])
            ->where('type', 'content')
            ->where('sub_type', 'create')
            ->where('user_id', Auth::id())
            ->groupBy('library_name')
            ->orderBy('max_created_at', 'DESC')
            ->pluck('library_name');
    }

    public function validateEditorToken($token)
    {
        return true;
    }

    public function getTranslations($libraries, $language_code)
    {
        return [];
    }
}
