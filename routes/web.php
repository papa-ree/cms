<?php

use Bale\Core\Support\Cdn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Bale\Cms\Livewire\Pages\Navigation\CreateNewNavigation;
use Bale\Cms\Livewire\Pages\Navigation\EditNavigation;
use Bale\Cms\Livewire\Pages\Navigation\Index as NavigationIndex;
use Bale\Cms\Livewire\Pages\Post\CreateNewPost;
use Bale\Cms\Livewire\Pages\Post\EditPost;
use Bale\Cms\Livewire\Pages\Overview\Index;
use Bale\Cms\Livewire\Pages\Page\CreateNewPage;
use Bale\Cms\Livewire\Pages\Page\EditPage;
use Bale\Cms\Livewire\Pages\Page\Index as PageIndex;
use Bale\Cms\Livewire\Pages\Post\Index as PostIndex;
use Bale\Cms\Livewire\Pages\Section\CreateNewSection;
use Bale\Cms\Livewire\Pages\Section\EditSection;
use Bale\Cms\Livewire\Pages\Section\Index as SectionIndex;
use Bale\Cms\Livewire\Pages\Section\Section\ExtensionSectionForm;
use Bale\Cms\Livewire\Pages\Section\SectionMetaEditor;
use Bale\Cms\Livewire\Pages\Section\Section\SearchableSectionForm;
use Bale\Cms\Livewire\Pages\Section\Section\SearchableSectionTableView;
use Bale\Cms\Livewire\Pages\Section\Section\SearchableEditKey;
use Bale\Cms\Livewire\Pages\Section\Section\SearchableCreateItem;
use Bale\Cms\Middleware\EnsureBaleSelected;
use Bale\Cms\Middleware\SwitchBaleConnection;

/*
 Note:
 - 'web' and 'auth' already provided by user app.
 - Include our middleware in group so SwitchBaleConnection runs after EnsureBaleSelected.
*/

Route::middleware(['web', 'auth'])->prefix('cms')->as('bale.cms.')->group(function () {

    // protected CMS pages
    Route::middleware([EnsureBaleSelected::class, SwitchBaleConnection::class])->group(function () {
        Route::get('overview', Index::class)->name('overview');

        Route::name('posts.')->middleware('permission:bale-post.read')->group(function () {
            Route::get('posts', PostIndex::class)->name('index');
            Route::get('posts.create', CreateNewPost::class)->name('create');
            Route::get('posts.edit.{slug}', EditPost::class)->name('edit');
        });

        Route::post('/editorjs/upload', function (Request $request) {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:512', // 512KB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => 0,
                    'message' => $validator->errors()->first('image'),
                ]);
            }

            try {
                // Upload file in images folder
                $file = $request->file('image');
                $filename = uniqid() . '.' . $file->extension();
                $path = session('bale_active_slug') . '/images/' . $filename;

                Storage::disk(app()->isProduction() ? 's3' : 'public')->put($path, $file->get());

                // Generate CDN URL
                // Format: https://cdn_url/cdn_prefix/organization_slug/images/filename
                $url = Cdn::url('images/' . $filename);

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
            $validator = Validator::make($request->all(), [
                'url' => 'required|url',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => 0,
                    'message' => $validator->errors()->first('url'),
                ]);
            }

            $url = $request->input('url');

            try {
                // Securely fetch input image from url
                $response = Http::get($url);

                if (!$response->successful()) {
                    return response()->json([
                        'success' => 0,
                        'message' => 'Failed to retrieve image from the provided URL.',
                    ]);
                }

                $contents = $response->body();
                $size = strlen($contents);

                // Validate image size (max 512KB)
                if ($size > 512 * 1024) {
                    return response()->json([
                        'success' => 0,
                        'message' => 'The image size may not be greater than 512 kilobytes.',
                    ]);
                }

                // Parse and validate Content-Type header
                $mimeType = $response->header('Content-Type');
                if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'])) {
                    return response()->json([
                        'success' => 0,
                        'message' => 'The file must be a valid image (JPEG, PNG, GIF, WEBP).',
                    ]);
                }

                $extension = 'jpg';
                if (str_contains($mimeType, 'png')) {
                    $extension = 'png';
                } elseif (str_contains($mimeType, 'gif')) {
                    $extension = 'gif';
                } elseif (str_contains($mimeType, 'webp')) {
                    $extension = 'webp';
                }

                $filename = uniqid() . '.' . $extension;
                $path = session('bale_active_slug') . '/images/' . $filename;

                Storage::disk(app()->isProduction() ? 's3' : 'public')->put($path, $contents);

                // Generate CDN URL
                $cdnUrl = Cdn::url('images/' . $filename);

                return response()->json([
                    'success' => 1,
                    'file' => [
                        'url' => $cdnUrl,
                    ],
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Fetch failed: ' . $e->getMessage(),
                ], 500);
            }
        });

        Route::name('pages.')->middleware('permission:bale-page.read')->group(function () {
            Route::get('pages', PageIndex::class)->name('index');
            Route::get('pages.create', CreateNewPage::class)->name('create');
            Route::get('pages.edit.{slug}', EditPage::class)->name('edit');
        });

        Route::name('navigations.')->middleware('permission:bale-navigation.read')->group(function () {
            Route::get('navigations', NavigationIndex::class)->name('index');
            Route::get('navigations.create.{parent?}', CreateNewNavigation::class)->name('create');
            Route::get('navigations.edit.{slug?}', EditNavigation::class)->name('edit');
        });

        Route::name('sections.')->middleware('permission:bale-section.read')->group(function () {
            Route::get('sections', SectionIndex::class)->name('index');
            Route::get('sections.create', CreateNewSection::class)->name('create'); // create new section
            Route::get('sections.meta-editor.{slug}', SectionMetaEditor::class)->name('meta-editor'); // edit section meta
            Route::get('sections.edit-keys.{slug}', SearchableEditKey::class)->name('edit-keys'); // edit field keys
            Route::get('sections.view.items.{slug}', SearchableSectionTableView::class)->name('view-searchable'); // view searchable section data
            Route::get('sections.create-item.{slug}', SearchableCreateItem::class)->name('create-searchable-item'); // create new item
            Route::get('sections.edit-item.{slug}.{itemId}', SearchableCreateItem::class)->name('edit-searchable-item'); // edit existing item
        });

        // Route::name('roles.')->middleware('permission:bale-role.read')->group(function () {
        //     Route::livewire('roles', 'cms-pages::role.index')->name('index');
        //     Route::livewire('roles.create', 'cms-pages::role.create')->name('create');
        //     Route::livewire('roles.edit.{roleId}', 'cms-pages::role.edit')->name('edit');
        // });

        // Categories Management
        Route::name('categories.')->middleware('permission:bale-category.read')->group(function () {
            Route::livewire('categories', 'cms-pages::category.index')->name('index');
            Route::livewire('categories.create', 'cms-pages::category.create')->name('create');
            Route::livewire('categories.edit.{slug}', 'cms-pages::category.create')->name('edit');
        });

        // Route::name('permissions.')->middleware('permission:bale-role.read')->group(function () {
        //     Route::livewire('permissions', 'cms-pages::permission.index')->name('index');
        // });

        Route::name('users.')->middleware('permission:bale-user.read')->group(function () {
            Route::livewire('users', 'cms-pages::user.index')->name('index');
        });

        Route::livewire('exit-cms', 'cms-shared-components::exit-cms')->name('exit-cms');

        // add other CMS routes here...
    });
});
