<?php

/*
 *
 * @Project        LaraH5P
 * @Copyright      Musab Alzoubi
 * @Created        2024-02-18
 * @Filename       LaraH5P.php
 * @Description    Core class handling H5P functionality
 *
 */

namespace LaraH5P;

use LaraH5P\Repositories\EditorAjaxRepository;
use LaraH5P\Repositories\H5PRepository;
use LaraH5P\Storages\EditorStorage;
use LaraH5P\Storages\LaraH5PStorage;
use H5PContentValidator;
use H5PCore;
use H5peditor;
use H5PExport;
use H5PStorage;
use H5PValidator;
use Illuminate\Support\Facades\Auth;

class LaraH5P
{
    /**
     * H5P Core Components
     */
    public static $core = null;
    public static $h5peditor = null;
    public static $interface = null;
    public static $validator = null;
    public static $storage = null;
    public static $contentvalidator = null;
    public static $export = null;
    public static $settings = null;

    public function __construct()
    {
        self::$interface = new H5PRepository();
        self::$core = new H5PCore(self::$interface, self::get_h5p_storage('', true), self::get_h5p_url(), config('larah5p.language'), config('larah5p.h5p_export'));
        self::$core->aggregateAssets = config('larah5p.H5P_DISABLE_AGGREGATION');
        self::$validator = new H5PValidator(self::$interface, self::$core);
        self::$storage = new H5PStorage(self::$interface, self::$core);
        self::$contentvalidator = new H5PContentValidator(self::$interface, self::$core);
        self::$export = new H5PExport(self::$interface, self::$core);
        self::$h5peditor = new H5peditor(self::$core, new EditorStorage(), new EditorAjaxRepository());
    }

    /**
     * Convert version string to an object
     */
    public static function split_version($version)
    {
        $parts = explode('.', $version);
        if (count($parts) !== 3) {
            return false;
        }

        return (object) [
            'major' => (int) $parts[0],
            'minor' => (int) $parts[1],
            'patch' => (int) $parts[2],
        ];
    }

    /**
     * Get base URL for assets
     */
    public static function get_url($path = '')
    {
        return url('/assets/vendor' . $path);
    }

    /**
     * Get H5P storage path
     */
    public static function get_h5p_storage($path = '', $absolute = false)
    {
        return $absolute ? new LaraH5PStorage(storage_path('h5p' . $path)) : self::get_url('/h5p' . $path);
    }

    /**
     * Get LaraH5P URL
     */
    public static function get_larah5p_url($path = '')
    {
        return self::get_url('/larah5p' . $path);
    }

    /**
     * Get H5P core URL
     */
    public static function get_h5pcore_url($path = '')
    {
        return self::get_h5p_url('/h5p-core' . $path);
    }

    /**
     * Get H5P editor URL
     */
    public static function get_h5peditor_url($path = '')
    {
        return self::get_h5p_url('/h5p-editor' . $path);
    }

    /**
     * Get library URL
     */
    public static function get_h5plibrary_url($path = '', $absolute = false)
    {
        return $absolute ? storage_path('h5p/' . realpath(self::get_url('/h5p' . $path))) : self::get_url('/h5p' . $path);
    }

    /**
     * Get service URL
     */
    public static function get_service_url($path = '')
    {
        return route('h5p.index', [], false);
    }

    /**
     * Get core settings
     */
    private static function get_core_settings()
    {
        return [
            'baseUrl'            => config('larah5p.domain'),
            'url'                => self::get_h5p_storage(),
            'postUserStatistics' => (config('larah5p.h5p_track_user', true) === '1') && Auth::check(),
            'ajax'               => [
                'setFinished'     => route('h5p.ajax.finish'),
                'contentUserData' => route('h5p.ajax.content-user-data'),
            ],
            'saveFreq' => config('larah5p.h5p_save_content_state', false) ? config('larah5p.h5p_save_content_frequency', 30) : false,
            'siteUrl'  => config('larah5p.domain'),
            'l10n'     => [
                'H5P' => trans('larah5p.h5p'),
            ],
            'hubIsEnabled' => config('larah5p.h5p_hub_is_enabled'),
        ];
    }

    /**
     * Get core files
     */
    private static function get_core_files($settings = [])
    {
        $settings['loadedJs'] = [];
        $settings['loadedCss'] = [];

        $settings['core'] = [
            'styles'  => [],
            'scripts' => [],
        ];

        foreach (H5PCore::$styles as $style) {
            $settings['core']['styles'][] = self::get_h5pcore_url('/' . $style);
        }
        foreach (H5PCore::$scripts as $script) {
            $settings['core']['scripts'][] = self::get_h5pcore_url('/' . $script);
        }

        return $settings;
    }

    /**
     * Get language setting
     */
    public static function get_language()
    {
        return config('larah5p.language');
    }

    /**
     * Get H5P embed code
     */
    public function get_embed($content, $settings, $no_cache = false)
    {
        $embed = H5PCore::determineEmbedType($content['embedType'], $content['library']['embedTypes']);
        $cid = 'cid-' . $content['id'];

        if (!isset($settings['contents'][$cid])) {
            $settings['contents'][$cid] = self::get_content_settings($content);
        }

        if ($embed === 'div') {
            return [
                'settings' => $settings,
                'embed'    => '<div class="h5p-content" data-content-id="' . $content['id'] . '"></div>',
            ];
        } else {
            return [
                'settings' => $settings,
                'embed'    => '<iframe class="h5p-iframe" data-content-id="' . $content['id'] . '" style="height:1px" src="about:blank" frameborder="0" scrolling="no"></iframe>',
            ];
        }
    }
}
