<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Bale\Cms\Livewire\Pages\Navigation\CreateNewNavigation;
use Bale\Cms\Livewire\Pages\Navigation\EditNavigation;
use Bale\Cms\Livewire\Pages\Navigation\Index as NavigationIndex;
use Bale\Cms\Livewire\Pages\Post\CreateNewPost;
use Bale\Cms\Livewire\Pages\Post\EditPost;
use Bale\Cms\Livewire\Pages\SelectPage\Index as SelectPageIndex;
use Bale\Cms\Livewire\Pages\Overview\Index;
use Bale\Cms\Livewire\Pages\Page\CreateNewPage;
use Bale\Cms\Livewire\Pages\Page\EditPage;
use Bale\Cms\Livewire\Pages\Page\Index as PageIndex;
use Bale\Cms\Livewire\Pages\Post\Index as PostIndex;
use Bale\Cms\Livewire\SharedComponents\ExitCms;
use Bale\Cms\Middleware\EnsureBaleSelected;
use Bale\Cms\Middleware\SwitchBaleConnection;

/*
 Note:
 - 'web' and 'auth' already provided by user app.
 - Include our middleware in group so SwitchBaleConnection runs after EnsureBaleSelected.
*/

Route::middleware(['web', 'auth'])->prefix('cms')->as('bale.cms.')->group(function () {
    // selection page (Livewire)
    Route::get('select-bale', SelectPageIndex::class)->name('select');

    // protected CMS pages
    Route::middleware([EnsureBaleSelected::class, SwitchBaleConnection::class])->group(function () {
        Route::get('overview', Index::class)->name('overview');

        Route::name('posts.')->group(function () {
            Route::get('posts', PostIndex::class)->name('index');
            Route::get('posts.create', CreateNewPost::class)->name('create');
            Route::get('posts.edit.{slug}', EditPost::class)->name('edit');
        });

        Route::get('exit', ExitCms::class)->name('exit');

        Route::post('/editorjs/upload', function (Request $request) {
            $request->validate([
                'image' => 'required|image|max:512', // 5KB
            ]);

            try {
                $path = $request->file('image')->store(session('bale_active_slug') . '/images', 'minio');

                // URL yang dikembalikan harus dari aplikasi, bukan MinIO
                $url = url('/cms/media/' . $path);

                return response()->json([
                    'success' => 1,
                    'file' => [
                        'url' => $url,
                    ],
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Upload failed: ' . $e->getMessage(),
                ], 500);
            }
        })->name('editorjs.upload');

        Route::post('/editorjs/fetchUrl', function (Request $request) {
            $url = $request->input('url');

            try {
                $contents = file_get_contents($url);
                $name = session('bale_active_slug') . '/images' . uniqid() . '.jpg';
                Storage::disk('minio')->put($name, $contents);

                $fileUrl = url('/cms/media/' . $name);

                return response()->json([
                    'success' => 1,
                    'file' => [
                        'url' => $fileUrl,
                    ],
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Fetch failed: ' . $e->getMessage(),
                ], 500);
            }
        });

        Route::get('/media/{path}', function ($path) {
            $disk = Storage::disk('minio');

            if (!$disk->exists($path)) {
                abort(404);
            }

            $mime = $disk->mimeType($path);
            $file = $disk->get($path);

            return Response::make($file, 200, [
                'Content-Type' => $mime,
            ]);
        })->where('path', '.*')->name('media');

        Route::get('/upload-test', function () {
            try {
                $filePath = 'images/test.txt';
                Storage::disk('minio')->put($filePath, 'Hello from Bale CMS again!');
                return '✅ Uploaded: ' . $filePath;
            } catch (\Throwable $e) {
                return '❌ Upload failed: ' . $e->getMessage();
            }
        });

        Route::name('pages.')->group(function () {
            Route::get('pages', PageIndex::class)->name('index');
            Route::get('pages.create', CreateNewPage::class)->name('create');
            Route::get('pages.edit.{slug}', EditPage::class)->name('edit');
        });

        Route::name('navigations.')->group(function () {
            Route::get('navigations', NavigationIndex::class)->name('index');
            Route::get('navigations.create', CreateNewNavigation::class)->name('create');
            Route::get('navigations.edit.{slug}', EditNavigation::class)->name('edit');
        });

        // add other CMS routes here...
    });
});

Route::get('cms/assets/{file}', function ($file) {
    $path = base_path('packages/paparee/cms/resources/assets/filepond/' . $file);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->where('file', '.*')->name('cms.assets');
