<?php

/*
 *
 * @Project        LaraH5P
 * @Copyright      Musab Mufid Abdel Majeed Alzoubi
 * @Created        2024-02-18
 * @Filename       larah5p.php
 * @Description    Configuration file for the LaraH5P package
 *
 */

return [
    // وضع التطوير H5P
    'H5P_DEV' => env('H5P_DEV', false),
    
    // اللغة الافتراضية للمحتوى التفاعلي
    'language' => env('H5P_LANGUAGE', 'en'),
    
    // النطاق الأساسي للموقع
    'domain' => env('APP_URL', 'http://localhost'),
    
    // مسار التخزين العام للأصول
    'h5p_public_path' => env('H5P_PUBLIC_PATH', '/vendor/larah5p'),
    
    // إعدادات المسارات داخل Laravel
    'slug' => env('H5P_SLUG', 'larah5p'),
    'views' => env('H5P_VIEWS_PATH', 'h5p'), // مسار المشاهدات
    'layout' => env('H5P_LAYOUT_PATH', 'h5p.layouts.h5p'), // قالب التخطيط الأساسي
    'use_router' => env('H5P_USE_ROUTER', 'ALL'), // خيارات التوجيه: ALL, EXPORT, EDITOR

    // تعطيل التجميع إذا لزم الأمر
    'H5P_DISABLE_AGGREGATION' => env('H5P_DISABLE_AGGREGATION', false),

    // إعدادات عرض المحتوى
    'h5p_show_display_option' => env('H5P_SHOW_DISPLAY_OPTION', true),
    'h5p_frame' => env('H5P_FRAME', true),
    'h5p_export' => env('H5P_EXPORT', true),
    'h5p_embed' => env('H5P_EMBED', true),
    'h5p_copyright' => env('H5P_COPYRIGHT', false),
    'h5p_icon' => env('H5P_ICON', true),
    'h5p_track_user' => env('H5P_TRACK_USER', false),
    'h5p_ext_communication' => env('H5P_EXT_COMMUNICATION', true),
    'h5p_save_content_state' => env('H5P_SAVE_CONTENT_STATE', false),
    'h5p_save_content_frequency' => env('H5P_SAVE_CONTENT_FREQUENCY', 30),
    
    // إعدادات مفتاح الموقع
    'h5p_site_key' => [
        'h5p_h5p_site_uuid' => env('H5P_SITE_UUID', false),
    ],

    // تحديث ذاكرة التخزين المؤقت لنوع المحتوى
    'h5p_content_type_cache_updated_at' => env('H5P_CACHE_UPDATED_AT', 0),

    // تمكين أو تعطيل متطلبات H5P
    'h5p_check_h5p_requirements' => env('H5P_CHECK_REQUIREMENTS', false),

    // تمكين أو تعطيل H5P Hub
    'h5p_hub_is_enabled' => env('H5P_HUB_ENABLED', true),

    // إصدار H5P
    'h5p_version' => env('H5P_VERSION', '1.25.0'),
];