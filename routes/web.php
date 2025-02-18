<?php

use Illuminate\Support\Facades\Route;
use LaraH5P\Http\Controllers\web\H5pController;
use LaraH5P\Http\Controllers\web\LibraryController;
use LaraH5P\Http\Controllers\web\AjaxController;
use LaraH5P\Http\Controllers\web\EmbedController;
use LaraH5P\Http\Controllers\web\DownloadController;

Route::group(['middleware' => ['web']], function () {
    if (config('larah5p.use_router') == 'EDITOR' || config('larah5p.use_router') == 'ALL') {
        Route::resource('h5p', H5pController::class);

        Route::group(['middleware' => ['auth']], function () {
            Route::get('library', [LibraryController::class, 'index'])->name('h5p.library.index');
            Route::get('library/show/{id}', [LibraryController::class, 'show'])->name('h5p.library.show');
            Route::post('library/store', [LibraryController::class, 'store'])->name('h5p.library.store');
            Route::delete('library/destroy', [LibraryController::class, 'destroy'])->name('h5p.library.destroy');
            Route::get('library/restrict', [LibraryController::class, 'restrict'])->name('h5p.library.restrict');
            Route::post('library/clear', [LibraryController::class, 'clear'])->name('h5p.library.clear');
        });

        // AJAX routes
        Route::match(['GET', 'POST'], 'ajax/libraries', [AjaxController::class, 'libraries'])->name('h5p.ajax.libraries');
        Route::get('ajax', [AjaxController::class, '__invoke'])->name('h5p.ajax');
        Route::get('ajax/libraries', [AjaxController::class, 'libraries'])->name('h5p.ajax.libraries');
        Route::get('ajax/single-libraries', [AjaxController::class, 'singleLibrary'])->name('h5p.ajax.single-libraries');
        Route::post('ajax/content-type-cache', [AjaxController::class, 'contentTypeCache'])->name('h5p.ajax.content-type-cache');
        Route::post('ajax/library-install', [AjaxController::class, 'libraryInstall'])->name('h5p.ajax.library-install');
        Route::post('ajax/library-upload', [AjaxController::class, 'libraryUpload'])->name('h5p.ajax.library-upload');
        Route::post('ajax/rebuild-cache', [AjaxController::class, 'rebuildCache'])->name('h5p.ajax.rebuild-cache');
        Route::post('ajax/files', [AjaxController::class, 'files'])->name('h5p.ajax.files');
        Route::get('ajax/finish', [AjaxController::class, 'finish'])->name('h5p.ajax.finish');
        Route::post('ajax/content-user-data', [AjaxController::class, 'contentUserData'])->name('h5p.ajax.content-user-data');
    }

    // Export and Embed routes
    Route::get('h5p/embed/{id}', [EmbedController::class, '__invoke'])->name('h5p.embed');
    Route::get('h5p/export/{id}', [DownloadController::class, '__invoke'])->name('h5p.export');
});
