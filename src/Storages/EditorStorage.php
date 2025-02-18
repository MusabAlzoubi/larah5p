<?php

/*
 *
 * @Project        LaraH5P Editor Storage
 * @Copyright      Musab Alzoubi
 * @Created        2024-02-18
 * @Filename       EditorStorage.php
 * @Description    Custom file storage system for H5P editor and temporary files
 *
 */

namespace LaraH5P\Storages;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use LaraH5P\Models\H5PLibrary;
use LaraH5P\Models\H5PTmpFile;
use H5peditorStorage;
use H5PCore;

class EditorStorage implements H5peditorStorage
{
    /**
     * Modify library files before serving them to the editor.
     *
     * @param array $files
     * @param array $libraries
     */
    public function alterLibraryFiles(&$files, $libraries)
    {
        $h5p = App::make('LaraH5P');
        $h5p->alter_assets($files, $libraries, 'editor');
    }

    /**
     * Retrieve available language translations for a library.
     *
     * @param string $machineName
     * @param int $majorVersion
     * @param int $minorVersion
     */
    public function getAvailableLanguages($machineName, $majorVersion, $minorVersion)
    {
        // Functionality not implemented yet
    }

    /**
     * Retrieve the translation for a specific library and language.
     *
     * @param string $machineName
     * @param int $majorVersion
     * @param int $minorVersion
     * @param string $language
     * @return string|null
     */
    public function getLanguage($machineName, $majorVersion, $minorVersion, $language)
    {
        $result = DB::table('h5p_libraries_languages')
            ->join('h5p_libraries', 'h5p_libraries.id', '=', 'h5p_libraries_languages.library_id')
            ->where('h5p_libraries.name', $machineName)
            ->where('h5p_libraries.major_version', $majorVersion)
            ->where('h5p_libraries.minor_version', $minorVersion)
            ->where('h5p_libraries_languages.language_code', $language)
            ->select('h5p_libraries_languages.translation')
            ->first();

        return $result ? $result->translation : null;
    }

    /**
     * Retrieve H5P libraries for the editor.
     *
     * @param array|null $libraries
     * @return array
     */
    public function getLibraries($libraries = null)
    {
        $return = [];

        if ($libraries !== null) {
            foreach ($libraries as $library) {
                $details = H5PLibrary::where('name', $library->name)
                    ->where('major_version', $library->majorVersion)
                    ->where('minor_version', $library->minorVersion)
                    ->whereNotNull('semantics')
                    ->first();

                if ($details) {
                    $library->tutorialUrl = $details->tutorial_url;
                    $library->title = $details->title;
                    $library->runnable = $details->runnable;
                    $library->restricted = $details->restricted === '1' ? true : false;
                    $return[] = $library;
                }
            }
        } else {
            $libraries_result = H5PLibrary::where('runnable', 1)
                ->whereNotNull('semantics')
                ->select([
                    'name',
                    'title',
                    'major_version AS majorVersion',
                    'minor_version AS minorVersion',
                    'patch_version AS patchVersion',
                    'restricted',
                    'tutorial_url',
                ])
                ->orderBy('name', 'ASC')
                ->get();

            foreach ($libraries_result as $library) {
                foreach ($return as $key => $existingLibrary) {
                    if ($library->name === $existingLibrary->name) {
                        if (($library->majorVersion === $existingLibrary->majorVersion &&
                            $library->minorVersion > $existingLibrary->minorVersion) ||
                            ($library->majorVersion > $existingLibrary->majorVersion)) {
                            $existingLibrary->isOld = true;
                        } else {
                            $library->isOld = true;
                        }
                    }
                }
                $library->restricted = $library->restricted === '1' ? true : false;
                $return[] = $library;
            }
        }

        return $return;
    }

    /**
     * Remove a temporary file.
     *
     * @param string $fileId
     */
    public function keepFile($fileId)
    {
        DB::table('h5p_tmpfiles')->where('path', $fileId)->delete();
    }

    /**
     * Mark a file for cleanup.
     *
     * @param object $file
     * @param int $content_id
     */
    public static function markFileForCleanup($file, $content_id)
    {
        $h5p = App::make('LaraH5P');
        $path = $h5p->get_h5p_storage();
        $path .= empty($content_id) ? '/editor' : '/content/' . $content_id;
        $path .= '/' . $file->getType() . 's';
        $path .= '/' . $file->getName();

        H5PTmpFile::create(['path' => $path, 'created_at' => now()]);
    }

    /**
     * Remove temporarily saved files.
     *
     * @param string $filePath
     */
    public static function removeTemporarilySavedFiles($filePath)
    {
        if (is_dir($filePath)) {
            H5PCore::deleteFileTree($filePath);
        } elseif (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * Save a file temporarily.
     *
     * @param string $data
     * @param bool $move_file
     * @return object
     */
    public static function saveFileTemporarily($data, $move_file)
    {
        $h5p = App::make('LaraH5P');
        $path = $h5p::$interface->getUploadedH5pPath();

        if ($move_file) {
            rename($data, $path);
        } else {
            file_put_contents($path, $data);
        }

        return (object) ['dir' => dirname($path), 'fileName' => basename($path)];
    }
}
