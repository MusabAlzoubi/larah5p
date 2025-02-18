<?php

/*
 *
 * @Project        LaraH5P Storage
 * @Copyright      Musab Alzoubi
 * @Created        2024-02-18
 * @Filename       LaraH5PStorage.php
 * @Description    Custom storage system for H5P content and libraries
 *
 */

namespace LaraH5P\Storages;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use LaraH5P\Models\H5PLibrary;
use H5PFileStorage;
use H5PCore;
use Exception;

class LaraH5PStorage implements H5PFileStorage
{
    private $path;

    /**
     * Constructor to initialize storage path.
     *
     * @param string $path Base location of H5P files.
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

/**
     * Saves a library to storage.
     *
     * @param array $library Library properties.
     */
    public function saveLibrary($library)
    {
        $libraryPath = $this->path . '/libraries/' . H5PCore::libraryToString($library, true);

        // Delete old library if exists
        if (File::exists($libraryPath)) {
            File::deleteDirectory($libraryPath);
        }

        // Copy new library files
        File::copyDirectory($library['uploadDirectory'], $libraryPath);
    }

    /**
     * Saves content files to storage.
     *
     * @param string $source Path to the content folder.
     * @param array $content Content properties.
     */
    public function saveContent($source, $content)
    {
        $destPath = "{$this->path}/content/{$content['id']}";

        // Remove old content if exists
        if (File::exists($destPath)) {
            File::deleteDirectory($destPath);
        }

        File::copyDirectory($source, $destPath);
    }

    /**
     * Deletes a content folder.
     *
     * @param array $content Content properties.
     */
    public function deleteContent($content)
    {
        File::deleteDirectory("{$this->path}/content/{$content['id']}");
    }

    /**
     * Clones content files to a new directory.
     *
     * @param int $id Source content ID.
     * @param int $newId Target content ID.
     */
    public function cloneContent($id, $newId)
    {
        $srcPath = "{$this->path}/content/{$id}";
        $destPath = "{$this->path}/content/{$newId}";

        if (File::exists($srcPath)) {
            File::copyDirectory($srcPath, $destPath);
        }
    }

    /**
     * Returns a temporary path for H5P content processing.
     *
     * @return string Path to temporary directory.
     */
    public function getTmpPath()
    {
        $tempDir = "{$this->path}/temp";
        File::ensureDirectoryExists($tempDir);

        return "{$tempDir}/" . uniqid('h5p-');
    }

    /**
     * Exports content to a target directory.
     *
     * @param int $id Content ID.
     * @param string $target Target directory.
     */
    public function exportContent($id, $target)
    {
        $sourcePath = "{$this->path}/content/{$id}";

        if (File::exists($sourcePath)) {
            File::copyDirectory($sourcePath, $target);
        } else {
            File::ensureDirectoryExists($target);
        }
    }

    /**
     * Exports a library to a target directory.
     *
     * @param array $library Library properties.
     * @param string $target Target directory.
     */
    public function exportLibrary($library, $target)
    {
        $libraryPath = "{$this->path}/libraries/" . H5PCore::libraryToString($library, true);

        if (File::exists($libraryPath)) {
            File::copyDirectory($libraryPath, "{$target}/" . H5PCore::libraryToString($library, true));
        }
    }

    /**
     * Saves an export file to storage.
     *
     * @param string $source Source file path.
     * @param string $filename Export filename.
     * @throws Exception If unable to save the export file.
     */
    public function saveExport($source, $filename)
    {
        $exportPath = "{$this->path}/exports";

        File::ensureDirectoryExists($exportPath);

        if (!File::copy($source, "{$exportPath}/{$filename}")) {
            throw new Exception("Unable to save H5P export file: {$filename}");
        }
    }

    /**
     * Deletes an export file.
     *
     * @param string $filename Export filename.
     */
    public function deleteExport($filename)
    {
        File::delete("{$this->path}/exports/{$filename}");
    }

    /**
     * Checks if an export file exists.
     *
     * @param string $filename Export filename.
     * @return bool True if file exists, false otherwise.
     */
    public function hasExport($filename)
    {
        return File::exists("{$this->path}/exports/{$filename}");
    }

    /**
     * Deletes a library and its files.
     *
     * @param array $library Library details.
     */
    public function deleteLibrary($library)
    {
        $libraryPath = $this->path . '/libraries/' . H5PCore::libraryToString($library, true);
        if (File::exists($libraryPath)) {
            File::deleteDirectory($libraryPath);
        }
    }

    /**
     * Caches JavaScript and CSS assets.
     *
     * @param array $files List of assets to cache.
     * @param string $key Unique hash for the cached asset.
     */
    public function cacheAssets(&$files, $key)
    {
        foreach ($files as $type => $assets) {
            if (empty($assets)) {
                continue;
            }

            $content = '';
            foreach ($assets as $asset) {
                $assetPath = $this->path . $asset->path;
                if (File::exists($assetPath)) {
                    $assetContent = File::get($assetPath);
                    $content .= ($type === 'scripts') ? $assetContent . ";\n" : $assetContent . "\n";
                }
            }

            $cachedPath = "{$this->path}/cachedassets";
            File::ensureDirectoryExists($cachedPath);

            $ext = ($type === 'scripts' ? 'js' : 'css');
            $outputFile = "{$cachedPath}/{$key}.{$ext}";
            File::put($outputFile, $content);

            $files[$type] = [(object) ['path' => "/cachedassets/{$key}.{$ext}", 'version' => '']];
        }
    }

    /**
     * Retrieves cached assets.
     *
     * @param string $key Unique hash for the cached asset.
     * @return array|null Cached assets or null if not found.
     */
    public function getCachedAssets($key)
    {
        $files = [];
        foreach (['js', 'css'] as $ext) {
            $filePath = "{$this->path}/cachedassets/{$key}.{$ext}";
            if (File::exists($filePath)) {
                $files[$ext === 'js' ? 'scripts' : 'styles'] = [(object) ['path' => "/cachedassets/{$key}.{$ext}", 'version' => '']];
            }
        }
        return !empty($files) ? $files : null;
    }

    /**
     * Deletes cached assets.
     *
     * @param array $keys List of asset keys to delete.
     */
    public function deleteCachedAssets($keys)
    {
        foreach ($keys as $key) {
            foreach (['js', 'css'] as $ext) {
                File::delete("{$this->path}/cachedassets/{$key}.{$ext}");
            }
        }
    }

    /**
     * Reads and returns the content of a file.
     *
     * @param string $filePath File path.
     * @return string|false File content or false if not found.
     */
    public function getContent($filePath)
    {
        return File::exists($filePath) ? File::get($filePath) : false;
    }

    /**
     * Saves an uploaded file.
     *
     * @param object $file File object.
     * @param int $contentId Content ID.
     */
    public function saveFile($file, $contentId)
    {
        $path = empty($contentId) ? "{$this->path}/editor" : "{$this->path}/content/{$contentId}";
        $path .= '/' . $file->getType() . 's';
        File::ensureDirectoryExists($path);

        $filePath = "{$path}/{$file->getName()}";
        File::put($filePath, $file->getData());

        return $file;
    }

    /**
     * Clones a single content file from one content ID to another.
     *
     * @param string $file File name.
     * @param int|string $fromId Source content ID (or 'editor').
     * @param int $toId Target content ID.
     */
    public function cloneContentFile($file, $fromId, $toId)
    {
        $sourcePath = ($fromId === 'editor')
            ? "{$this->path}/editor/{$file}"
            : "{$this->path}/content/{$fromId}/{$file}";

        $targetDir = "{$this->path}/content/{$toId}/" . dirname($file);
        $targetPath = "{$targetDir}/" . basename($file);

        if (File::exists($sourcePath)) {
            File::ensureDirectoryExists($targetDir);
            File::copy($sourcePath, $targetPath);
        }
    }

    /**
     * Moves content directory to a new location.
     *
     * @param string $source Source directory.
     * @param int|null $contentId Content ID.
     */
    public function moveContentDirectory($source, $contentId = null)
    {
        $target = empty($contentId) ? "{$this->path}/editor" : "{$this->path}/content/{$contentId}";
        File::copyDirectory($source, $target);
    }

    /**
     * Retrieves a content file path.
     *
     * @param string $file File name.
     * @param int $contentId Content ID.
     * @return string|null File path or null if not found.
     */
    public function getContentFile($file, $contentId)
    {
        $path = "{$this->path}/content/{$contentId}/{$file}";
        return File::exists($path) ? $path : null;
    }

    /**
     * Removes a content file.
     *
     * @param string $file File name.
     * @param int $contentId Content ID.
     */
    public function removeContentFile($file, $contentId)
    {
        File::delete("{$this->path}/content/{$contentId}/{$file}");
    }

    /**
     * Checks if the storage has write access.
     *
     * @return bool
     */
    public function hasWriteAccess()
    {
        return File::isWritable($this->path);
    }

    /**
     * Determines if a library has pre-save scripts.
     */
    public function hasPresave($libraryName, $developmentPath = null)
    {
        return false;
    }

    /**
     * Retrieves upgrade scripts for a library.
     */
    public function getUpgradeScript($machineName, $majorVersion, $minorVersion)
    {
        return null;
    }

    /**
     * Saves a file from a ZIP archive.
     */
    public function saveFileFromZip($path, $file, $stream)
    {
        return File::put("{$this->path}/{$path}/{$file}", stream_get_contents($stream));
    }
}
